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
namespace Taxonomy\Utility;

use Cake\ORM\TableRegistry;
use Field\Model\Entity\Field;
use QuickApps\Event\HooktagAwareTrait;

/**
 * Taxonomy utility class.
 *
 * Utility methods used by TaxonomyField Handler.
 */
class TaxonomyToolbox
{

    use HooktagAwareTrait;

/**
 * Holds an instance of this class.
 *
 * @var \Taxonomy\Utility\TaxonomyToolbox
 */
    protected static $_instance = null;

/**
 * Returns an instance of this class.
 *
 * Useful when we need to use some of the trait methods.
 *
 * @return \Taxonomy\Utility\TaxonomyToolbox
 */
    public static function getInstance()
    {
        if (!static::$_instance) {
            static::$_instance = new TaxonomyToolbox();
        }

        return static::$_instance;
    }

/**
 * Formats the given field.
 *
 * @param \Field\Model\Entity\Field $field The field being rendered
 * @param \Cake\View\View $view Instance of View, used to access HtmlHelper
 * @return string
 */
    public static function formatter(Field $field, $view)
    {
        $out = [];
        $instance = static::getInstance();
        $glue = ' ';
        $terms = TableRegistry::get('Taxonomy.Terms')
            ->find()
            ->where(['id IN' => (array)$field->raw])
            ->all();

        if (!empty($field->view_mode_settings['link_template'])) {
            $templatesBefore = $view->Html->templates();
            $view->Html->templates(['link' => $field->view_mode_settings['link_template']]);
        }

        foreach ($terms as $term) {
            if ($field->view_mode_settings['hooktags']) {
                $term->set('name', $instance->hooktags($term->name));
            }

            if ($field->view_mode_settings['formatter'] === 'link_localized') {
                $glue = ' ';
                $out[] = $view->Html->link(__($term->name), "/find/term:{$term->slug}", ['class' => 'label label-primary']);
            } elseif ($field->view_mode_settings['formatter'] === 'plain_localized') {
                $glue = ', ';
                $out[] = __($term->name);
            } else {
                $glue = ', ';
                $out[] = $term->name;
            }
        }

        if (isset($templatesBefore)) {
            $view->Html->templates($templatesBefore);
        }

        return implode($glue, $out);
    }
}
