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
namespace Taxonomy\Utility;

use Cake\Cache\Cache;
use Cake\ORM\TableRegistry;
use Cake\View\View;
use Field\Model\Entity\Field;
use QuickApps\Shortcode\ShortcodeTrait;

/**
 * Taxonomy utility class.
 *
 * Utility methods used by TaxonomyField Handler.
 */
class TaxonomyToolbox
{

    use ShortcodeTrait;

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
    public static function formatter(Field $field, View $view)
    {
        $out = [];
        $instance = static::getInstance();
        $glue = ' ';
        $terms = TableRegistry::get('Taxonomy.Terms')
            ->find()
            ->where(['id IN' => (array)$field->extra])
            ->all();

        if (!empty($field->viewModeSettings['link_template'])) {
            $templatesBefore = $view->Html->templates();
            $view->Html->templates(['link' => $field->viewModeSettings['link_template']]);
        }

        foreach ($terms as $term) {
            if ($field->viewModeSettings['shortcodes']) {
                $term->set('name', $instance->shortcodes($term->name));
            }

            if ($field->viewModeSettings['formatter'] === 'link_localized') {
                $glue = ' ';
                $out[] = $view->Html->link(__($term->name), "/find/term:{$term->slug}", ['class' => 'label label-primary']);
            } elseif ($field->viewModeSettings['formatter'] === 'plain_localized') {
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

    /**
     * Prepares the given threaded list of terms.
     *
     * @param array &$terms Threaded list of terms
     * @param \Cake\Datasource\EntityInterface $block The block
     */
    public static function termsForBlock($terms, $block)
    {
        foreach ($terms as $term) {
            $title = $term->name;

            if (!empty($block->settings['show_counters'])) {
                $count = Cache::read("t{$term->id}", 'terms_count');
                if (!$count) {
                    $count = (int)TableRegistry::get('Taxonomy.EntitiesTerms')
                        ->find()
                        ->where(['EntitiesTerms.term_id' => $term->id])
                        ->count();
                    Cache::write("t{$term->id}", $count, 'terms_count');
                }
                $title .= " ({$count})";
            }

            $term->set('title', $title);
            $term->set('url', "/find/term:{$term->slug}");

            if (!empty($term->children)) {
                $term->set('expanded', true);
                static::termsForBlock($term->children, $block);
            }
        }
    }
}
