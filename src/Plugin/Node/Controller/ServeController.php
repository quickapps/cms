<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Node\Controller;

/**
 * Node serve controller.
 *
 * For handling all public node-rendering requests.
 */
class ServeController extends NodeAppController {

/**
 * Components used by this controller.
 *
 * @var array
 */
	public $components = ['Comment.CommentForm'];

/**
 * An array containing the names of helpers controllers uses.
 *
 * @var array
 */
	public $helpers = ['Time'];

/**
 * Redirects to ServeController::frontpage()
 *
 * @return void
 */
	public function index() {
		$this->redirect(['plugin' => 'node', 'controller' => 'serve', 'action' => 'frontpage']);
	}

/**
 * Site font page.
 *
 * Gets a list of all promoted nodes, so themes may render them in their
 * front-page layout.
 *
 * @return void
 */
	public function frontpage() {
		$this->loadModel('Node.Nodes');

		$nodes = $this->Nodes->find()
			->where(['promote' => 1, 'status >' => 0])
			->order(['sticky' => 'DESC', 'created' => 'DESC'])
			->all();

		$this->set('nodes', $nodes);
		$this->switchViewMode('teaser');
	}

/**
 * Node's detail page.
 *
 * @param string $node_type_slug
 * @param string $node_slug
 * @return void
 */
	public function details($node_type_slug, $node_slug) {
		$this->loadModel('Node.Nodes');

		if ($this->is('user.admin')) {
			$conditions = [
				'Nodes.slug' => $node_slug,
				'Nodes.node_type_slug' => $node_type_slug,
			];
		} else {
			$conditions = [
				'Nodes.slug' => $node_slug,
				'Nodes.node_type_slug' => $node_type_slug,
				'Nodes.status >' => 0,
				'Nodes.language IN' => ['', \Cake\Core\Configure::read('Config.language'), null]
			];
		}

		$node = $this->Nodes->find()
			->where($conditions)
			->first();

		if (!$node) {
			throw new \Cake\Error\NotFoundException(__('The requested page was not found.'));
		}

		// Post new comment logic
		if ($node->comment > 0) {
			$commentOptions = [
				'data' => ['status' => 1],
				'validate' => 'default',
			];
			$this->CommentForm->post($node, $commentOptions);
		}

		$this->set('node', $node);
		$this->switchViewMode('full');
	}

/**
 * Node search engine page.
 *
 * @param string $criteria A search criteria. e.g.: `"this phrase" -"but not this" OR -hello`
 * @return void
 */
	public function search($criteria) {
		$this->loadModel('Node.Nodes');

		try {
			$nodes = $this->Nodes
				->scopeQuery($criteria)
				->all();
		} catch (\Exception $e) {
			$nodes = [];
		}

		$this->set('nodes', $nodes);
		$this->switchViewMode('search-result');
	}

/**
 * RSS feeder.
 *
 * Similar to `ServeController::search()` but it uses
 * `rss` layout instead of default layout.
 *
 * @param string $criteria A search criteria. e.g.: `"this phrase" -"but not this" OR -hello`
 * @return void
 */
	public function rss($criteria) {
		$this->layout = 'rss';
		$this->loadModel('Node.Nodes');

		try {
			$nodes = $this->Nodes
				->scopeQuery($criteria)
				->all();
		} catch (\Exception $e) {
			$nodes = [];
		}

		$this->set('nodes', $nodes);
		$this->switchViewMode('rss');
	}

}
