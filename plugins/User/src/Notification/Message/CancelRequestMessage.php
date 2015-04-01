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
namespace User\Notification\Message;

use User\Model\Entity\User;
use User\Notification\Message\BaseMessage;

/**
 * Send an informational message for canceling user account.
 *
 */
class CancelRequestMessage extends BaseMessage
{

    /**
     * {@inheritDoc}
     */
    public function send()
    {
        $this
            ->subject(plugin('User')->settings['message_cancel_request_subject'])
            ->body(plugin('User')->settings['message_cancel_request_body']);
        return parent::send();
    }
}
