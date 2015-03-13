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
namespace System\Test\TestCase\Controller\Admin;

use Cake\Filesystem\Folder;
use Cake\TestSuite\IntegrationTestCase;

/**
 * PluginsControllerTest class.
 */
class PluginsControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures.
     *
     * @var array
     */
    public $fixtures = [
        'app.acos',
        'app.block_regions',
        'app.blocks',
        'app.blocks_roles',
        'app.comments',
        'app.entities_terms',
        'app.field_instances',
        'app.field_values',
        'app.languages',
        'app.menu_links',
        'app.menus',
        'app.node_revisions',
        'app.nodes',
        'app.nodes_roles',
        'app.node_types',
        'app.options',
        'app.permissions',
        'app.plugins',
        'app.roles',
        'app.search_datasets',
        'app.terms',
        'app.users',
        'app.users_roles',
        'app.vocabularies',
    ];
    /**
     * setUp().
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->session(mockUserSession());
        $this->_clear();
    }

    /**
     * tearDown().
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
        $this->_clear();
    }

    /**
     * Clears any previous installation.
     *
     * @return void
     */
    protected function _clear()
    {
        $folder = new Folder(TESTS . 'TestSite/plugins/NukedApp/');
        $folder->delete();
        snapshot();
    }

    /**
     * test that installation of a plugin responding "false" at beforeInstall stops
     * the entire process.
     *
     * @return void
     */
    public function testInstallStops()
    {
        $this->post('/admin/system/plugins/install', [
            'path' => normalizePath(TESTS . 'TestSite/plugins/source/NukedApp/'),
            'activate' => 0,
            'file_system' => 'Install from File System',
        ]);

        // message generated from plugin's event listener
        $this->assertResponseContains('This plugin cannot be installed as its NUKED', 'failed when installing nuked plugin');
        $this->assertResponseOk();
    }
}
