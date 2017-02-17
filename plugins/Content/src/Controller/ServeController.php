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
namespace Content\Controller;

use Cake\Collection\Collection;
use Cake\Datasource\EntityInterface;
use Cake\I18n\I18n;
use Cake\Network\Exception\ForbiddenException;
use Content\Error\ContentNotFoundException;

/**
 * Content serve controller.
 *
 * For handling all public content-rendering requests.
 *
 * @property \Content\Model\Table\ContentsTable $Contents
 */
class ServeController extends AppController
{

    /**
     * Components used by this controller.
     *
     * @var array
     */
    public $components = [
        'Comment.Comment',
        'Paginator',
        'RequestHandler',
    ];

    /**
     * An array containing the names of helpers controllers uses.
     *
     * @var array
     */
    public $helpers = ['Time', 'Paginator'];

    /**
     * Paginator settings.
     *
     * Used by `search()` and `rss()`.
     *
     * @var array
     */
    public $paginate = [
        'limit' => 10,
    ];

    /**
     * Redirects to ServeController::home()
     *
     * @return void
     */
    public function index()
    {
        $this->redirect(['plugin' => 'Content', 'controller' => 'serve', 'action' => 'home']);
    }

    /**
     * Site's home page.
     *
     * Gets a list of all promoted contents, so themes may render them in their
     * front-page layout. The view-variable `contents` holds all promoted contents,
     * themes might render this contents using this variable.
     *
     * @return void
     */
    public function home()
    {
        $this->loadModel('Content.Contents');
        $contents = $this->Contents
            ->find('all')
            ->where([
                'Contents.promote' => 1,
                'Contents.status >' => 0,
                'Contents.language IN' => ['', null, I18n::locale()]
            ])
            ->order(['Contents.sticky' => 'DESC', 'Contents.created' => 'DESC'])
            ->limit((int)option('site_contents_home'));

        $this->set('contents', $contents);
        $this->viewMode('teaser');
    }

    /**
     * Content's detail page.
     *
     * @param string $contentTypeSlug Content's type-slug. e.g. `article`, `basic-page`
     * @param string $contentSlug Content's slug. e.g. `this-is-an-article`
     * @return \Cake\Network\Response|null
     * @throws \Content\Error\ContentNotFoundException When content is not found
     * @throws \Cake\Network\Exception\ForbiddenException When user can't access
     *  this content due to role restrictions
     */
    public function details($contentTypeSlug, $contentSlug)
    {
        $this->loadModel('Content.Contents');
        $conditions = [
            'Contents.slug' => $contentSlug,
            'Contents.content_type_slug' => $contentTypeSlug,
        ];

        if (!$this->request->is('userAdmin')) {
            $conditions['Contents.status >'] = 0;
        }

        $content = $this->Contents->find()
            ->where($conditions)
            ->contain(['ContentTypes', 'Roles'])
            ->first();

        if (!$content) {
            throw new ContentNotFoundException(__d('content', 'The requested page was not found.'));
        } elseif (!$content->isAccessibleByUser()) {
            throw new ForbiddenException(__d('content', 'You have not sufficient permissions to see this page.'));
        }

        if (!empty($content->language) && $content->language != I18n::locale()) {
            if ($redirect = $this->_calculateRedirection($content)) {
                $this->redirect($redirect);

                return $this->response;
            }
            throw new ContentNotFoundException(__d('content', 'The requested page was not found.'));
        }

        // Post new comment logic
        if ($content->comment_status > 0) {
            $content->set('comments', $this->Contents->find('comments', ['for' => $content->id]));
            $this->Comment->config('visibility', $content->comment_status);
            $this->Comment->post($content);
        }

        $this->set('content', $content);
        $this->viewMode('full');
    }

    /**
     * Contents search engine page.
     *
     * @param string $criteria A search criteria. e.g.: `"this phrase" -"but not this" OR -hello`
     * @return void
     */
    public function search($criteria)
    {
        $this->loadModel('Content.Contents');

        try {
            $contents = $this->Contents->search($criteria);
            if ($contents->clause('limit')) {
                $this->paginate['limit'] = $contents->clause('limit');
            }

            // TO-DO: ask search-engine for operator presence
            if (strpos($criteria, 'language:') === false) {
                $contents = $contents->where([
                    'Contents.status >' => 0,
                    'Contents.language IN' => ['', null, I18n::locale()] // any or concrete
                ]);
            }

            $contents = $this->paginate($contents);
        } catch (\Exception $e) {
            $contents = new Collection([]);
        }

        $this->set(compact('contents', 'criteria'));
        $this->viewMode('search-result');
    }

    /**
     * RSS feeder.
     *
     * Similar to `ServeController::search()` but it uses `rss` layout instead of
     * default layout.
     *
     * @param string $criteria A search criteria. e.g.: `"this phrase" -"but not this" OR -hello`
     * @return void
     */
    public function rss($criteria)
    {
        $this->loadModel('Content.Contents');

        try {
            $contents = $this->Contents
                ->search($criteria)
                ->limit(10);
        } catch (\Exception $e) {
            $contents = [];
        }

        $this->set(compact('contents', 'criteria'));
        $this->viewMode('rss');
        $this->RequestHandler->renderAs($this, 'rss');
        $this->RequestHandler->respondAs('xml');
    }

    /**
     * Calculates the URL to which visitor should be redirected according to
     * content's & visitor's language.
     *
     * @param \Cake\Datasource\EntityInterface $content Content to inspect
     * @return string Redirection URL, empty on error
     */
    protected function _calculateRedirection(EntityInterface $content)
    {
        foreach (['translation', 'parent'] as $method) {
            if ($has = $content->{$method}()) {
                return option('url_locale_prefix') ? '/' . $has->get('language') . stripLanguagePrefix($has->get('url')) : $has->get('url');
            }
        }

        return '';
    }
}
