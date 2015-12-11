<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Taxonomy\Field;

use Cake\Cache\Cache;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Cake\Validation\Validator;
use CMS\View\View;
use Field\Handler;
use Field\Model\Entity\Field;
use Field\Model\Entity\FieldInstance;

/**
 * Term Field Handler.
 *
 * This field allows to store terms information. Used to classify contents.
 */
class TermField extends Handler
{

    /**
     * {@inheritDoc}
     */
    public function info()
    {
        return [
            'type' => 'text',
            'name' => __d('taxonomy', 'Term Reference'),
            'description' => __d('taxonomy', 'Defines terms list based on taxonomy vocabularies.'),
            'hidden' => false,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function render(Field $field, View $view)
    {
        return $view->element('Taxonomy.taxonomy_field_display', compact('field'));
    }

    /**
     * {@inheritDoc}
     */
    public function edit(Field $field, View $view)
    {
        $terms = [];
        if ($field->metadata->settings['vocabulary']) {
            $TermsTable = TableRegistry::get('Taxonomy.Terms');
            $TermsTable->addBehavior('Tree', ['scope' => ['vocabulary_id' => $field->metadata->settings['vocabulary']]]);
            $terms = $TermsTable->find('treeList', ['spacer' => '&nbsp;&nbsp;']);
        }

        return $view->element('Taxonomy.taxonomy_field_edit', compact('field', 'terms'));
    }

    /**
     * {@inheritDoc}
     */
    public function validate(Field $field, Validator $validator)
    {
        if ($field->metadata->required) {
            $validator->notEmpty($field->name, __d('taxonomy', 'Field required.'), true);
        } else {
            $validator->allowEmpty($field->name, true);
        }

        if (intval($field->metadata->settings['max_values']) > 0) {
            if (!empty($field->metadata->settings['error_message'])) {
                $limitErrorMessage = $field->metadata->settings['error_message'];
            } else {
                $limitErrorMessage = __d('taxonomy', 'You can select {0,number} values as maximum.', $field->metadata->settings['max_values']);
            }

            $validator
            ->add($field->name, 'validateLimit', [
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
     * {@inheritDoc}
     */
    public function beforeSave(Field $field, $post)
    {
        if (!$field->metadata->settings['vocabulary']) {
            return true;
        }

        $TermsTable = TableRegistry::get('Taxonomy.Terms');
        if ($field->metadata->settings['type'] === 'autocomplete') {
            $termIds = explode(',', (string)$post);
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
            if (!is_array($post)) {
                $post = [$post];
            }
            $field->set('extra', array_unique($post));
        }

        $ids = empty($field->extra) ? [-1] : $field->extra;
        $termsNames = $TermsTable
            ->find()
            ->select(['name'])
            ->where(['id IN' => $ids])
            ->all()
            ->extract('name')
            ->toArray();

        $field->set('value', implode(' ', $termsNames));
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function afterSave(Field $field)
    {
        $entity = $field->get('metadata')->get('entity');
        $table = TableRegistry::get($entity->source());
        $pk = $table->primaryKey();

        if ($entity->has($pk)) {
            $TermsCache = TableRegistry::get('Taxonomy.EntitiesTerms');
            $tableAlias = Inflector::underscore($table->alias());
            $extra = !is_array($field->extra) ? [$field->extra] : $field->extra;
            $TermsCache->deleteAll([
                'entity_id' => $entity->get($pk),
                'table_alias' => $tableAlias,
                'field_instance_id' => $field->metadata->instance_id,
            ]);

            foreach ($extra as $termId) {
                Cache::delete("t{$termId}", 'terms_count');
                $cacheEntity = $TermsCache->newEntity([
                    'entity_id' => $entity->get($pk),
                    'term_id' => $termId,
                    'table_alias' => $tableAlias,
                    'field_instance_id' => $field->metadata->instance_id,
                ]);
                $TermsCache->save($cacheEntity);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function settings(FieldInstance $instance, View $view)
    {
        $vocabularies = TableRegistry::get('Taxonomy.Vocabularies')->find('list');
        return $view->element('Taxonomy.taxonomy_field_settings_form', compact('instance', 'vocabularies'));
    }

    /**
     * {@inheritDoc}
     */
    public function defaultSettings(FieldInstance $instance)
    {
        return [
        'vocabulary' => null,
        'type' => 'checkbox', // checkbox, select, tagging
        'max_values' => 0, // 0: unlimited
        'error_message' => null,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function viewModeSettings(FieldInstance $instance, View $view, $viewMode)
    {
        return $view->element('Taxonomy.taxonomy_field_view_mode_form', compact('instance'));
    }

    /**
     * {@inheritDoc}
     */
    public function defaultViewModeSettings(FieldInstance $instance, $viewMode)
    {
        return [
        'label_visibility' => 'above',
        'shortcodes' => false,
        'hidden' => false,
        'formatter' => 'plain',
        'link_template' => '<a href="{{url}}"{{attrs}}>{{content}}</a>',
        ];
    }
}
