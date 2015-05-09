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
namespace Taxonomy\Widget;

use Block\Model\Entity\Block;
use Block\Widget;
use Cake\Cache\Cache;
use QuickApps\View\View;

/**
 * Renders a simple language menu for switching site language.
 *
 */
class CategoriesWidget extends Widget
{

    /**
     * {@inheritDoc}
     */
    public function render(Block $block, View $view)
    {
        return $view->element('Taxonomy.categories_widget_render', compact('block'));
    }

    /**
     * {@inheritDoc}
     */
    public function settings(Block $block, View $view)
    {
        return $view->element('Taxonomy.categories_widget_settings', compact('block'));
    }

    /**
     * {@inheritDoc}
     */
    public function afterSave(Block $block)
    {
        Cache::clear(false, 'terms_count');
    }
}
