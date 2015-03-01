<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    1.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace QuickApps\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;
require_once QA_CORE . '/config/Schema/PluginsSchema.php';

class PluginsFixture extends TestFixture
{

    use \PluginsSchemaTrait;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->fields = $this->fields();
        $this->records = $this->records();

        $this->records[] = [
            'name' => 'SpaceOddity',
            'package' => 'quickapps-plugins/space-oddity',
            'settings' => '',
            'status' => true,
            'ordering' => 500
        ];

        $this->records[] = [
            'name' => 'NeedsSpaceOddity',
            'package' => 'quickapps-plugins/needs-space-oddity',
            'settings' => '',
            'status' => true,
            'ordering' => 501
        ];

        parent::init();
    }
}
