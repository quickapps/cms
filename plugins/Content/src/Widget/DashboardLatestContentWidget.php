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
 * Shows latest articles.
 *
 */
class DashboardLatestContentWidget extends Widget
{

    /**
     * {@inheritDoc}
     */
    public function render(Block $block, View $view)
    {
        $contents = TableRegistry::get('Content.Contents')
            ->find('all', ['fieldable' => false])
            ->order(['created' => 'DESC'])
            ->limit(10)
            ->all();
        return $view->element('Content.dashboard_latest_content', compact('block', 'contents'));
    }
}
