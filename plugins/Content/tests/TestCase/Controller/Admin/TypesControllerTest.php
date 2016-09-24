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
namespace Content\Test\TestCase\Controller\Admin;

use Cake\ORM\TableRegistry;
use CMS\TestSuite\IntegrationTestCase;

/**
 * TypesControllerTest class.
 */
class TypesControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures.
     *
     * @var array
     */
    public $fixtures = [
        //'app.acos',
        'app.block_regions',
        'app.blocks',
        'app.blocks_roles',
        'app.comments',
        'app.content_revisions',
        'app.contents',
        'app.contents_roles',
        'app.content_type_permissions',
        'app.content_types',
        'app.eav_attributes',
        'app.eav_values',
        'app.entities_terms',
        'app.field_instances',
        'app.languages',
        'app.menu_links',
        'app.menus',
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
     * test index action.
     *
     * @return void
     */
    public function testIndex()
    {
        $this->get('/admin/content/types');
        $this->assertResponseSuccess();
    }

    /**
     * test edit action.
     *
     * @return void
     */
    public function testEdit()
    {
        $this->post('/admin/content/types/edit/article', [
            'name' => 'Modified Article',
            'title_label' => 'Article Title',
            'defaults' => [
                'comment_status' => 0,
                'language' => '' // any
            ]
        ]);

        $type = TableRegistry::get('Content.ContentTypes')
            ->find()
            ->where(['name' => 'Modified Article'])
            ->limit(1)
            ->first();

        $this->assertResponseSuccess();
        $this->assertNotEmpty($type);
    }

    /**
     * test that slug (machine name) cannot be changed once created.
     *
     * @return void
     */
    public function testEditSlugNotAllowed()
    {
        $this->post('/admin/content/types/edit/article', [
            'name' => 'Modified Article',
            'slug' => 'modified-slug',
            'title_label' => 'Article Title',
            'defaults' => [
                'comment_status' => 0,
                'language' => '' // any
            ]
        ]);

        $type = TableRegistry::get('Content.ContentTypes')
            ->find()
            ->where(['slug' => 'modified-slug'])
            ->limit(1)
            ->first();

        $this->assertResponseSuccess();
        $this->assertEmpty($type);
    }

    /**
     * test add action.
     *
     * @return void
     */
    public function testAdd()
    {
        $this->post('/admin/content/types/add', [
            'name' => 'Forum post',
            'slug' => 'forum-post',
            'title_label' => 'Topic',
            'description' => 'Forum post type test',
            'defaults' => [
                'status' => 1, // Mark as published
                'promote' => 0, // No promote
                'sticky' => 0, // No sticky
                'comment_status' => 0, // Comments closed
                'language' => '' // Any language
            ]
        ]);

        $type = TableRegistry::get('Content.ContentTypes')
            ->find()
            ->where(['slug' => 'forum-post'])
            ->limit(1)
            ->first();

        $this->assertResponseSuccess();
        $this->assertNotEmpty($type);
    }
}
