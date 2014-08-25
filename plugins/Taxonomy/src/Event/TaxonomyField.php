<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Taxonomy\Event;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Field\Core\FieldHandler;

/**
 * Text Field Handler.
 *
 * This field allows to store text information, such as textboxes, textareas, etc.
 */
class TaxonomyField extends FieldHandler {

/**
 * Return a list of implemented events.
 *
 * @return array
 */
	public function implementedEvents() {
		$events = parent::implementedEvents();
		$events['SearchableBehavior.operatorTerm'] = 'operatorTerm';
		return $events;
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options Additional array of options
 * @return string HTML representation of this field
 */
	public function entityDisplay(Event $event, $field, $options = []) {
		$View = $event->subject;
		return $View->element('Taxonomy.taxonomy_field_display', compact('field', 'options'));
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return string HTML containing from elements
 */
	public function entityEdit(Event $event, $field, $options = []) {
		$View = $event->subject;
		$terms = [];

		if ($field->metadata->settings['vocabulary']) {
			$TermsTable = TableRegistry::get('Taxonomy.Terms');
			$TermsTable->addBehavior('Tree', ['scope' => ['vocabulary_id' => $field->metadata->settings['vocabulary']]]);
			$terms = $TermsTable->find('treeList');
		}

		return $View->element('Taxonomy.taxonomy_field_edit', compact('field', 'options', 'terms'));
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $entity The entity to which field is attached to
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return bool
 */
	public function entityBeforeSave(Event $event, $entity, $field, $options) {
		if (!$field->metadata->settings['vocabulary']) {
			return true;
		}

		$TermsTable = TableRegistry::get('Taxonomy.Terms');
		if ($field->metadata->settings['type'] === 'autocomplete') {
			$termIds = explode(',', (string)$options['_post']);
			$TermsTable->addBehavior('Tree', [
				'scope' => [
					'vocabulary_id' => $field->metadata->settings['vocabulary']
				]
			]);

			// any non-integer value represents a new term to be registered
			foreach ($termIds as $i => $idOrName) {
				if (!intval($idOrName)) {
					$alreadyExists = $TermsTable
						->find()
						->where(['name' => $idOrName])
						->first();
					if ($alreadyExists) {
						$termIds[$i] = $alreadyExists->id;
					} else {
						$termEntity = $TermsTable->newEntity([
							'name' => $idOrName,
							'vocabulary_id' => $field->metadata->settings['vocabulary'],
						]);
						if ($TermsTable->save($termEntity)) {
							$termIds[$i] = $termEntity->id;
						} else {
							unset($termIds[$i]);
						}
					}
				}
			}
			$field->set('extra', array_unique($termIds));
		} else {
			// single value given (radio)
			if (!is_array($options['_post'])) {
				$options['_post'] = [$options['_post']];
			}
			$field->set('extra', array_unique($options['_post']));
		}

		$termsNames = $TermsTable
			->find()
			->select(['name'])
			->where(['id IN' => $field->extra])
			->all()
			->extract('name')
			->toArray();

		$field->set('value', implode(' ', $termsNames));
		return true;
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $entity The entity to which field is attached to
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return void
 */
	public function entityAfterSave(Event $event, $entity, $field, $options) {
		$pk = $event->subject->primaryKey();

		if ($entity->has($pk)) {
			$TermsCache = TableRegistry::get('Taxonomy.EntitiesTerms');
			$table_alias = Inflector::underscore($event->subject->alias());
			$extra = !is_array($field->extra) ? [$field->extra] : $field->extra;
			$TermsCache->deleteAll([
				'entity_id' => $entity->get($pk),
				'table_alias' => $table_alias,
				'field_instance_id' => $field->metadata->field_instance_id,
			]);

			foreach ($extra as $term_id) {
				$cacheEntity = $TermsCache->newEntity([
					'entity_id' => $entity->get($pk),
					'term_id' => $term_id,
					'table_alias' => $table_alias,
					'field_instance_id' => $field->metadata->field_instance_id,
				]);
				$TermsCache->save($cacheEntity);
			}
		}
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $entity The entity to which field is attached to
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @param \Cake\Validation\Validator $validator
 * @return bool False will halt the save process
 */
	public function entityBeforeValidate(Event $event, $entity, $field, $options, $validator) {
		if ($field->metadata->required) {
			$validator->allowEmpty(":{$field->name}", false, __d('taxonomy', 'Field required.'));
		} else {
			$validator->allowEmpty(":{$field->name}", true);
		}

		if (intval($field->metadata->settings['max_values']) > 0) {
			if (!empty($field->metadata->settings['error_message'])) {
				$limitErrorMessage = $field->metadata->settings['error_message'];
			} else {
				$limitErrorMessage = __d('taxonomy', 'You can select {0,number} values as maximum.', $field->metadata->settings['max_values']);
			}

			$validator
				->add(":{$field->name}", 'validateLimit', [
					'rule' => function ($value, $context) use ($field) {
						if (!is_array($value)) {
							$value = explode(',', (string)$value);
						}
						return count($value) <= $field->metadata->settings['max_values'];
					},
					'message' => $limitErrorMessage,
				]);
		}

		return true;
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $entity The entity to which field is attached to
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @param \Cake\Validation\Validator $validator
 * @return bool False will halt the save process
 */
	public function entityAfterValidate(Event $event, $entity, $field, $options, $validator) {
		return true;
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $entity The entity to which field is attached to
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return bool False will halt the delete process
 */
	public function entityBeforeDelete(Event $event, $entity, $field, $options) {
		return true;
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $entity The entity to which field is attached to
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return void
 */
	public function entityAfterDelete(Event $event, $entity, $field, $options) {
		return;
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event
 * @return array
 */
	public function instanceInfo(Event $event) {
		return [
			'name' => __d('taxonomy', 'Term Reference'),
			'description' => __d('field', 'Defines terms list based on taxonomy vocabularies.'),
			'hidden' => false,
		];
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return string HTML form elements for the settings page
 */
	public function instanceSettingsForm(Event $event, $instance, $options = []) {
		$View = $event->subject;
		$vocabularies = TableRegistry::get('Taxonomy.Vocabularies')->find('list');
		return $View->element('Taxonomy.taxonomy_field_settings_form', compact('instance', 'options', 'vocabularies'));
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return array
 */
	public function instanceSettingsDefaults(Event $event, $instance, $options = []) {
		return [
			'vocabulary' => null,
			'type' => 'checkbox', // checkbox, select, tagging
			'max_values' => 0, // 0: unlimited
			'error_message' => null,
		];
	}	

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return string HTML form elements for the settings page
 */
	public function instanceViewModeForm(Event $event, $instance, $options = []) {
		$View = $event->subject;
		return $View->element('Taxonomy.taxonomy_field_view_mode_form', compact('instance', 'options'));
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return array
 */
	public function instanceViewModeDefaults(Event $event, $instance, $options = []) {
		switch ($options['viewMode']) {
			default:
				return [
					'label_visibility' => 'above',
					'hooktags' => false,
					'hidden' => false,
					'formatter' => 'plain',
					'link_template' => '<a href="{{url}}"{{attrs}}>{{content}}</a>',
				];
		}
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return bool False will halt the attach process
 */
	public function instanceBeforeAttach(Event $event, $instance, $options = []) {
		return true;
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @return void
 */
	public function instanceAfterAttach(Event $event, $instance, $options = []) {
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return bool False will halt the detach process
 */
	public function instanceBeforeDetach(Event $event, $instance, $options = []) {
		return true;
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return void
 */
	public function instanceAfterDetach(Event $event, $instance, $options = []) {
	}

/**
 * Handles the "term:" search operator. Which filters all entities matching
 * a given collection of terms.
 * 
 *     term:cat,dog,bird,...,term-slug
 *
 * You can provide up to 10 terms as maximum.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Query $query The query being modified
 * @param string $value Operator value. e.g. `cat,dog,bird`
 * @param bool $negate Whether this operator was negated using `-`. e.g. "-term:dog"
 * @param string $orAnd Possible values are "or" & "and"
 * @return \Cake\ORM\Query Scoped query
 */
	public function operatorTerm(Event $event, $query, $value, $negate, $orAnd) {
		$slugs = explode(',', $value);
		$slugs = array_slice($slugs, 0, 10);

		if ($slugs) {
			$IN = $negate ? 'NOT IN' : 'IN';
			$table = $event->subject;
			$pk = $table->primaryKey();
			$tableAlias = $table->alias();
			$termsIds = TableRegistry::get('Taxonomy.Terms')
				->find()
				->select(['id'])
				->where(['Terms.slug IN' => $slugs])
				->all()
				->extract('id')
				->toArray();
			$termsIds = empty($termsIds) ? [0] : $termsIds;
			$subQuery = TableRegistry::get('Taxonomy.EntitiesTerms')
					->find()
					->select(['entity_id'])
					->where(['term_id IN' => $termsIds, 'table_alias' => $tableAlias]);

			if ($orAnd === 'or') {
				$query->orWhere(["{$tableAlias}.{$pk} {$IN}" => $subQuery]);
			} elseif ($orAnd === 'and') {
				$query->andWhere(["{$tableAlias}.{$pk} {$IN}" => $subQuery]);
			} else {
				$query->where(["{$tableAlias}.{$pk} {$IN}" => $subQuery]);
			}
		}

		return $query;
	}

}
