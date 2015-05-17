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
namespace User\Widget;

use Block\Model\Entity\Block;
use Block\Widget;
use CMS\View\View;

/**
 * Renders a simple language menu for switching site language.
 *
 */
class UserMenuWidget extends Widget
{

    /**
     * {@inheritDoc}
     */
    public function render(Block $block, View $view)
    {
        return $view->element('User.user_menu_widget_render', compact('block'));
    }
}
