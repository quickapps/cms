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
namespace Content\Widget;

use Block\Model\Entity\Block;
use Block\Widget;
use Cake\ORM\TableRegistry;
use CMS\View\View;

/**
 * Used to render a list of latest content publiched on the site.
 *
 * Aimed to be used in frontend themes.
 */
class RecentContentWidget extends Widget
{

    /**
     * {@inheritDoc}
     */
    public function render(Block $block, View $view)
    {
        $contentsTable = TableRegistry::get('Content.Contents');
        $query = $contentsTable->find('all', ['fieldable' => false]);

        if (!empty($block->settings['filter_criteria'])) {
            $query = $contentsTable->search($block->settings['filter_criteria'], $query);
        }

        $contents = $query
            ->order(['created' => 'DESC'])
            ->where(['Contents.status' => true])
            ->limit($block->settings['limit'])
            ->all();

        return $view->element('Content.Widget/recent_content_render', compact('block', 'contents'));
    }

    /**
     * {@inheritDoc}
     */
    public function settings(Block $block, View $view)
    {
        return $view->element('Content.Widget/recent_content_settings', compact('block'));
    }

    /**
     * {@inheritDoc}
     */
    public function defaultSettings(Block $block)
    {
        return [
            'filter_criteria' => '',
            'limit' => 10,
        ];
    }
}
