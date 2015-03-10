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
namespace User\Test\TestCase\Notification;

use Cake\TestSuite\TestCase;
use User\Model\Entity\User;
use User\Notification\NotificationManager;

/**
 * NotificationManagerTest class.
 *
 */
class NotificationManagerTest extends TestCase
{

    /**
     * test __callStatic() for activated().
     *
     * @return void
     */
    public function testCallStaticActivated()
    {
        $user = new User();
        $activatedMessage = NotificationManager::activated($user);
        $this->assertInstanceOf('User\Notification\Message\ActivatedMessage', $activatedMessage);
    }

    /**
     * test __callStatic() for blocked().
     *
     * @return void
     */
    public function testCallStaticBlocked()
    {
        $user = new User();
        $blockedMessage = NotificationManager::blocked($user);
        $this->assertInstanceOf('User\Notification\Message\BlockedMessage', $blockedMessage);
    }

    /**
     * test __callStatic() for canceled().
     *
     * @return void
     */
    public function testCallStaticCanceled()
    {
        $user = new User();
        $canceledMessage = NotificationManager::canceled($user);
        $this->assertInstanceOf('User\Notification\Message\CanceledMessage', $canceledMessage);
    }

    /**
     * test __callStatic() for cancelRequest().
     *
     * @return void
     */
    public function testCallStaticCancelRequest()
    {
        $user = new User();
        $cancelRequestMessage = NotificationManager::cancelRequest($user);
        $this->assertInstanceOf('User\Notification\Message\CancelRequestMessage', $cancelRequestMessage);
    }

    /**
     * test __callStatic() for passwordRequest().
     *
     * @return void
     */
    public function testCallStaticPasswordRequest()
    {
        $user = new User();
        $passwordRequestMessage = NotificationManager::passwordRequest($user);
        $this->assertInstanceOf('User\Notification\Message\PasswordRequestMessage', $passwordRequestMessage);
    }

    /**
     * test __callStatic() for welcome().
     *
     * @return void
     */
    public function testCallStaticWelcome()
    {
        $user = new User();
        $welcomeMessage = NotificationManager::welcome($user);
        $this->assertInstanceOf('User\Notification\Message\WelcomeMessage', $welcomeMessage);
    }
}
