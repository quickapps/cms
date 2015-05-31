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
namespace CMS\Model\Behavior;

use Cake\Error\FatalErrorException;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\Utility\Inflector;

/**
 * Sluggable Behavior.
 *
 * Allows entities to have a unique `slug`.
 */
class SluggableBehavior extends Behavior
{

    /**
     * Flag.
     *
     * @var bool
     */
    protected $_enabled = true;

    /**
     * Default configuration.
     *
     * - `label`: Set to the field name that contains the string from where to
     *   generate the slug, or a set of field names to concatenate for generating
     *   the slug. `title` by default.
     *
     * - `slug`: Name of the field name that holds generated slugs. `slug` by default.
     *
     * - `separator`: Separator char. `-` by default. e.g.: `one-two-three`.
     *
     * - `on`: When to generate new slugs. `create`, `update` or `both` (by default).
     *
     * - `length`: Maximum length the generated slug can have. default to 200.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'label' => 'title',
        'slug' => 'slug',
        'separator' => '-',
        'on' => 'both',
        'length' => 200,
        'implementedMethods' => [
            'bindSluggable' => 'bindSluggable',
            'unbindSluggable' => 'unbindSluggable',
        ],
    ];

    /**
     * Run before a model is saved, used to set up slug for model.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Entity $entity The entity being saved
     * @param array $options Array of options for the save operation
     * @return bool True if save should proceed, false otherwise
     * @throws \Cake\Error\FatalErrorException When some of the specified columns
     *  in config's "label" is not present in the entity being saved
     */
    public function beforeSave(Event $event, $entity, $options = [])
    {
        if (!$this->_enabled) {
            return true;
        }

        $config = $this->config();
        $isNew = $entity->isNew();

        if (($isNew && in_array($config['on'], ['create', 'both'])) ||
            (!$isNew && in_array($config['on'], ['update', 'both']))
        ) {
            if (!is_array($config['label'])) {
                $config['label'] = [$config['label']];
            }

            foreach ($config['label'] as $field) {
                if (!$entity->has($field)) {
                    throw new FatalErrorException(__d('cms', 'SluggableBehavior was not able to generate a slug reason: entity\'s property "{0}" not found', $field));
                }
            }

            $label = '';

            foreach ($config['label'] as $field) {
                $val = $entity->get($field);
                $label .= !empty($val) ? " {$val}" : '';
            }

            if (!empty($label)) {
                $slug = $this->_slug($label, $entity);
                $entity->set($config['slug'], $slug);
            }
        }

        return true;
    }

    /**
     * Enables this behavior.
     *
     * @return void
     */
    public function bindSluggable()
    {
        $this->_enabled = true;
    }

    /**
     * Disables this behavior.
     *
     * @return void
     */
    public function unbindSluggable()
    {
        $this->_enabled = false;
    }

    /**
     * Generate a slug for the given string and entity.
     *
     * The generated slug is unique on the whole table.
     *
     * @param string $string string from where to generate slug
     * @param \Cake\ORM\Entity $entity The entity for which generate the slug
     * @return string Slug for given string
     */
    protected function _slug($string, $entity)
    {
        $string = $this->_mbTrim(mb_strtolower($string));
        $config = $this->config();
        $slug = Inflector::slug($string, $config['separator']);
        $pk = $this->_table->primaryKey();

        if (mb_strlen($slug) > $config['length']) {
            $slug = mb_substr($slug, 0, $config['length']);
        }

        $conditions = ["{$config['slug']} LIKE" => "{$slug}%"];
        if ($entity->has($pk)) {
            $conditions["{$pk} NOT IN"] = [$entity->{$pk}];
        }

        $same = $this->_table->find()
            ->where($conditions)
            ->all()
            ->extract($config['slug'])
            ->toArray();

        if (!empty($same)) {
            $initialSlug = $slug;
            $index = 1;

            while ($index > 0) {
                $nextSlug = "{$initialSlug}{$config['separator']}{$index}";
                if (!in_array($nextSlug, $same)) {
                    $slug = $nextSlug;
                    $index = -1;
                }

                $index++;
            }
        }

        return $slug;
    }

    /**
     * Trim singlebyte and multibyte punctuation from the start and end of a string.
     *
     * @param string $string Input string in UTF-8
     * @param string $trimChars Characters to trim off
     * @return trimmed string
     */
    protected function _mbTrim($string, $trimChars = '\s')
    {
        return preg_replace('/^[' . $trimChars . ']*(?U)(.*)[' . $trimChars . ']*$/u', '\\1', $string);
    }
}
