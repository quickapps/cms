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

use CMS\TestSuite\IntegrationTestCase;

/**
 * ManageControllerTest class.
 */
class ManageControllerTest extends IntegrationTestCase
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
        $this->get('/admin/content/manage');
        $this->assertResponseOk();
    }

    /**
     * test create action.
     *
     * @return void
     */
    public function testCreate()
    {
        $this->get('/admin/content/manage/create');
        $this->assertResponseOk();
    }

    /**
     * test add action.
     *
     * @return void
     */
    public function testAdd()
    {
        $this->post('/admin/content/manage/add/article', [
            'title' => 'Test Article',
            'description' => 'this is a test content',
            'status' => 1,
            'comment_status' => 1,

            // custom fields
            'article-introduction' => 'Intro text',
            'article-body' => 'Article body',
            'article-category' => [],
        ]);
        $content = $this->_controller
            ->Contents
            ->find()
            ->where(['title' => 'Test Article'])
            ->limit(1)
            ->first();
        $this->assertNotEmpty($content);
    }

    /**
     * test edit action.
     *
     * @return void
     */
    public function testEdit()
    {
        $this->get('/admin/content/manage/edit/1');
        $this->assertResponseOk();

        $this->post('/admin/content/manage/edit/1', [
            'title' => 'Modified Article',
            'description' => 'this content was modified',
            'status' => 1,
            'comment_status' => 1,

            // custom fields
            'article-introduction' => 'Intro text',
            'article-body' => 'Article body',
        ]);

        $content = $this->_controller
            ->Contents
            ->find()
            ->where(['title' => 'Modified Article'])
            ->limit(1)
            ->first();
        $this->assertNotEmpty($content);
    }

    /**
     * test translate action.
     *
     * @return void
     */
    public function testTranslate()
    {
        foreach ([1, 2] as $id) {
            \Cake\ORM\TableRegistry::get('Content.Contents')->updateAll(['language' => 'en_US'], ['id' => $id]);
            $newTitle = "Translated content #{$id}";
            $this->post("/admin/content/manage/translate/{$id}", [
                'title' => $newTitle,
                'language' => 'es_ES',
            ]);
            $translation = $this->_controller
                ->Contents
                ->find()
                ->where(['title' => $newTitle])
                ->limit(1)
                ->first();
            $this->assertNotEmpty($translation);
        }
    }

    /**
     * test delete action.
     *
     * @return void
     */
    public function testDelete()
    {
        foreach ([1, 2] as $id) {
            $this->get("/admin/content/manage/delete/{$id}");
            $exists = $this->_controller
                ->Contents
                ->find()
                ->where(['id' => $id])
                ->limit(1)
                ->count();
            $this->assertEquals(0, $exists);
        }
    }
}
