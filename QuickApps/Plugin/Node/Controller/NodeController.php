<?php
/**
 * Node Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Node.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class NodeController extends NodeAppController {
	public $name = 'Node';
	public $uses = array('Node.Node');

	public function beforeFilter() {
		parent::beforeFilter();

		if (QuickApps::is('view.node')) {
			$this->Security->disabledFields[] = 'recaptcha_challenge_field';
			$this->Security->disabledFields[] = 'recaptcha_response_field';
		}
	}

/**
 * Redirect to default controller (Contents)
 *
 */
	public function admin_index() {
		$this->redirect("/admin/node/contents");
	}

/**
 * Site FrontPage.
 * "Default front page" URL will be displayed if this option has been set in configuration panel.
 * Otherwise, promoted nodes are captured and default front page is rendered,
 * how it is displayed depends on active FrontEnd Theme.
 *
 */
	public function index() {
		$fp = Configure::read('Variable.site_frontpage');
		$front_page = '';

		if (!empty($fp)) {
			$front_page = $this->requestAction($fp, array('return'));
		}

		if (empty($front_page)) {
			// USE Node.roles_cache
			$this->Node->unbindModel(array('hasAndBelongsToMany' => array('Role')));
			$this->Node->unbindComments();

			$this->paginate = array(
				'limit' => Configure::read('Variable.default_nodes_main'),
				'order' => array(
					'Node.sticky' => 'DESC',
					'Node.created' => 'DESC'
				)
			);

			$conditions = array(
				'Node.status' => 1,
				'Node.promote' => 1,
				'NodeType.status' => 1,
				'OR' => array(
					array('Node.roles_cache = ' => null),
					array('Node.roles_cache = ' => '')
				),
				'Node.language' => array('', Configure::read('Variable.language.code'))
			);

			$userRoles = QuickApps::userRoles();

			foreach ($userRoles as $role_id) {
				$conditions['OR'][] = array('Node.roles_cache LIKE' => "%|{$role_id}|%");
			}

			if ($this->QuickApps->is('user.admin')) {
				// admin => no role restrictions
				unset($conditions['OR']);
			}

			$this->Layout['node'] = $this->paginate('Node', $conditions);
			$this->Layout['feed'] = '/search/promote:1%20language:*';
			$this->Layout['feed'] .= Configure::read('Variable.language.code') ? ',' . Configure::read('Variable.language.code') : '';
			$this->Layout['feed'] .= '/feed:rss';
		}

		$this->Layout['display'] = 'list';

		$this->set('front_page', $front_page);
	}

/**
 * Node rendering by given node-slug.
 * Error 404 will be rendered if:
 *  - Node does not exists
 *  - User has no access to it
 *  - User's language is different to node's languages
 *
 * @param string $type Node type of the node
 * @param string $slug Slug of the Node to render
 */
	public function details($type, $slug) {
		$result = Cache::read("node_{$slug}_" . Configure::read('Variable.language.code'));

		if (!$result) {
			// USE Node.roles_cache
			$this->Node->unbindModel(array('hasAndBelongsToMany' => array('Role')));
			$this->Node->unbindComments();
			$this->Node->Behaviors->attach('Field.Fieldable',
				array(
					'belongsTo' => 'NodeType-{Node.node_type_id}'
				)
			);

			$conditions = array(
				'Node.node_type_id' => $type,
				'Node.slug' => $slug,
				'Node.status' => 1,
				'NodeType.status' => 1,
				'OR' => array(
					array('Node.roles_cache = ' => null),
					array('Node.roles_cache = ' => '')
				),
				'Node.language' => array('', Configure::read('Variable.language.code'))
			);

			$userRoles = QuickApps::userRoles();

			foreach ($userRoles as $role_id) {
				$conditions['OR'][] = array('Node.roles_cache LIKE' => "%|{$role_id}|%");
			}

			if ($this->QuickApps->is('user.admin')) {
				// admin-> no role restrictions
				unset($conditions['OR']);
			}

			$this->Node->recursive = 2;
			$result = $this->Node->find('first', array('conditions' => $conditions));

			// try to to find translation
			if (!$result) {
				$hasTranslation = $this->Node->find('first',
					array(
						'recursive' => -1,
						'conditions' => array(
							'Node.status' => 1,
							'Node.translation_of' => $slug,
							'Node.language' => Configure::read('Variable.language.code')
						)
					)
				);

				if ($hasTranslation) {
					$this->redirect("/{$type}/{$hasTranslation['Node']['slug']}.html");
				}

				$isTranslationOf = $this->Node->find('first',
					array(
						'recursive' => -1,
						'conditions' => array(
							'slug' => $slug,
							'status' => 1,
							'NOT' => array(
								'Node.translation_of' => null
							)
						)
					)
				);

				if ($isTranslationOf) {
					$this->redirect("/{$type}/{$isTranslationOf['Node']['translation_of']}.html");
				}
			}

			if (isset($result['Node']['cache']) && !empty($result['Node']['cache'])) {
				// in seconds
				Cache::config('node_cache', array('engine' => 'File', 'duration' => $result['Node']['cache']));
				Cache::write("node_{$slug}_" . Configure::read('Variable.language.code'), $result, 'node_cache');
			}
		}

		if (!$result) {
			throw new NotFoundException(__t('Page not found'));
		}

		if (isset($result['Node']['description']) && !empty($result['Node']['description'])) {
			$this->Layout['meta']['description'] = $result['Node']['description'];
		}

		$this->loadModel('Comment.Comment');

		// comment reply
		if (isset($this->data['Comment']) && $result['Node']['comment'] == 2) {
			$data = $this->data;
			$data['Comment']['node_id'] = $result['Node']['id'];

			if ($this->Comment->save($data)) {
				if (!$this->Comment->status) {
					$this->flashMsg(__t('Your comment has been queued for review by site administrators and will be published after approval.'), 'alert', 'comment-form');
				} else {
					$this->flashMsg(__t('Your comment has been posted.'), 'success', 'comment-form');
				}

				$this->redirect($this->referer());
			} else {
			   $this->flashMsg(__t('Comment could not be saved. Please try again.'), 'error', 'comment-form');
			}
		}

		$this->paginate = array(
			'Comment' => array(
				'order' => array('Comment.created' => 'DESC'),
				'limit' => $result['NodeType']['comments_per_page']
			)
		);
		$comments = $this->paginate('Comment',
			array(
				'Comment.node_id' => $result['Node']['id'],
				'Comment.status' => 1
			)
		);
		$result['Comment'] = $comments;
		$this->Layout['display'] = 'full';
		$this->Layout['node'] = $result;
	}

/**
 * Search engine.
 * Process search form-POST criteria and convert it to a nice-well-formatted url query.
 * If no form-POST criteria is given then query criteria is spected.
 * Optionally it can render results as formatted feed (ajax, xml or rss) by passing the `feed`
 * named parameter.
 *
 * ### Example
 *
 *    http://www.example.com/search/term:jazz limit:10 order:Node.created,asc
 *
 * The above criteria will return the first ten nodes tagged as ´jazz´, results are ordered ascending
 * by creation date.
 *
 *    http://www.example.com/search/term:jazz limit:10 order:Node.created,asc/feed:rss
 *
 * Same as before, but serving as RSS. You may also use `xml` or `ajax`.
 *
 * ### Default expressions
 *
 *	-	limit (int): Limit result. e.g.: `limit:5`
 *	-	order (string): Field to order by and direction, multiple orders must by separated by `|`.
 *		e.g.: `Node.field,asc|Node.field2,desc`
 *	-	term (string): terms ID separed by comma. e.g.: `term:my-term1,my-term2`
 *	-	vocabulary (string): vocabularies ID separed by comma. e.g.: `vocabulary:voc-1,voc2`
 *	-	promote (boolean): Set to 1 to display promoted nodes only. 0 or unset otherwise. e.g.: `promote:1`
 *	-	language (string): Languages codes separed by comma. The wildcard `*` means any language.
 *		e.g.: `language:eng,fre,spa` or `language:*`
 *	-	type (string): Node type ID separated by comma. e.g.: `type:article,page`
 *	-	created (string): Filter by creation date range. e.g.: `created:[1976-03-06T23:59:59.999Z TO *]`
 *	-	modified (string): Filter by modified date range. e.g.: `modified:[1976-03-06T23:59:59.999Z TO NOW]`
 *	-	author: The author of the nodes. e.g.: author:1,demo@mail.com,26
 *
 * @param string $criteria Well formatted filter criteria. If no criteria is pass POST criteria is spected.
 */
	public function search($criteria = false) {
		$scope = array();
		$keys = array(
			'type' => null,
			'term' => null,
			'language' => null,
			'or' => null,
			'negative' => null,
			'phrase' => null,
			'limit' => null,
			'created' => null,
			'modified' => null
		);

		$this->Node->unbindModel(
			array(
				'hasAndBelongsToMany' => array('Role'), // USE Node.roles_cache
				'hasMany' => array('Comment')
			)
		);

		if ($criteria) {
			$criteria = rawurldecode($criteria);
			$data['Search']['criteria'] = $criteria; // hold untouch criteria query
			$this->data = $data;

			$this->set('criteria', $data['Search']['criteria']);

			// limit
			if ($limit = $this->__search_expression_extract($criteria, 'limit')) {
				$criteria = str_replace("limit:{$limit}", '', $criteria);
				$limit = intval($limit);
				$limit = $limit <= 0 ? Configure::read('Variable.default_nodes_main') : $limit;
			} else {
				$limit = Configure::read('Variable.default_nodes_main');
			}

			// order
			if ($orders = $this->__search_expression_extract($criteria, 'order')) {
				$criteria = str_replace("order:{$orders}", '', $criteria);
				$orders = trim($orders);
				$orders = explode('|', $orders);
				$order = array();

				foreach ($orders as $o) {
					list($field, $direction) = explode(',', $o);
					$field = trim($field);
					$direction = strtoupper(trim($direction));

					if (!empty($field)) {
						$direction = !in_array($direction, array('ASC', 'DESC')) ? 'ASC' : $direction;
						$order[$field] = $direction;
					}
				}

				if (empty($order)) {
					$order = array(
						'Node.sticky' => 'DESC',
						'Node.created' => 'DESC'
					);
				}
			} else {
				$order = array(
					'Node.sticky' => 'DESC',
					'Node.created' => 'DESC'
				);
			}

			// promote
			if ($promote = $this->__search_expression_extract($criteria, 'promote')) {
				$criteria = str_replace("promote:{$promote}", '', $criteria);
				$scope['Node.promote'] = intval($promote);
			}

			// type
			if ($type = $this->__search_expression_extract($criteria, 'type')) {
				$criteria = str_replace("type:{$type}", '', $criteria);
				$scope['Node.node_type_id'] = explode(',', $type);
			}

			// vocabulary
			if ($vocabulary = $this->__search_expression_extract($criteria, 'vocabulary')) {
				$criteria = str_replace("vocabulary:{$vocabulary}", '', $criteria);
				$vSlugs = explode(',', $vocabulary);
				$vSlugs = Hash::filter($vSlugs);

				if (!empty($vSlugs)) {
					$Vocabulary = ClassRegistry::init('Taxonomy.Vocabulary');

					$Vocabulary->bindModel(
						array(
							'hasMany' => array(
								'Term' => array(
									'className' => 'Taxonomy.Term',
									'foreignKey' => 'vocabulary_id',
									'fields' => array('Term.id', 'Term.slug')
								)
							)
						)
					);

					$vocabularies = ClassRegistry::init('Taxonomy.Vocabulary')->find('all',
						array(
							'conditions' => array(
								'Vocabulary.slug' => $vSlugs
							)
						)
					);

					$vocabulary_terms = Hash::extract($vocabularies, '{n}.Term.slug');
				}
			}

			// term
			if (($terms = $this->__search_expression_extract($criteria, 'term')) || isset($vocabulary_terms)) {
				$criteria = str_replace("term:{$terms}", '', $criteria);
				$terms = explode(',', $terms);

				if (isset($vocabulary_terms)) {
					$terms = array_merge($terms, $vocabulary_terms);
				}

				foreach ($terms as $term) {
					$term = trim($term);

					if (empty($term)) {
						continue;
					}

					$scope['OR'][] = array('Node.terms_cache LIKE' => "%:{$term}|%");
					$scope['OR'][] = array('Node.terms_cache LIKE' => "%:{$term}");
				}
			}

			// language
			if ($language = $this->__search_expression_extract($criteria, 'language')) {
				$criteria = str_replace("language:{$language}", '', $criteria);
				$scope['Node.language'] = explode(',', strtolower($language));

				if (in_array('*', $scope['Node.language'])) {
					$scope['Node.language'][] = '';
					unset($scope['Node.language'][array_search('*', $scope['Node.language'])]);
				}
			} else {
				$scope['Node.language'] = array(null, '', Configure::read('Variable.language.code'));
			}

			// created && modified
			foreach (array('created', 'modified') as $type) {
				if ($key = $this->__search_expression_extract($criteria, $type)) {
					App::uses('CakeTime', 'Utility');

					$criteria = str_replace("{$type}:{$key}", '', $criteria);
					$from = $to = false;

					if (preg_match('/^\[(.*)\]/i', $key)) {
						$key = trim($key);
						$key = preg_replace('/\[|\]/', '', $key);

						if (strpos($key, 'TO') !== false) {
							list($from, $to) = array_map('trim', explode('TO', $key));
							$from = $from == '*' ? false : CakeTime::fromString($from);
							$to = $to == '*' ? false : CakeTime::fromString($to);
						} else {
							$equals = $key;
						}

						if (isset($equals) && is_numeric($equals)) {
							$scope["Node.{$type}"] = $equals;
						} elseif ($from && !$to) {
							$scope["Node.{$type} >="] = $from;
						} elseif (!$from && $to) {
							$scope["Node.{$type} <="] = $to;
						} elseif ($from && $to) {
							$scope["Node.{$type} BETWEEN ? AND ?"] = array($from, $to);
						}
					}
				}
			}

			// author
			if ($author = $this->__search_expression_extract($criteria, 'author')) {
				$criteria = str_replace("author:{$author}", '', $criteria);
				$author = array_map('trim', explode(',', $author));

				for ($i = 0; $i < count($author); $i++) {
					if (intval($author[$i]) <= 0) {
						if ($user_id = ClassRegistry::init('User.User')->findByEmail($author[$i], array('id'))) {
							$author[$i] = $user_id['User']['id'];
						} else {
							unset($author[$i]);
						}
					}
				}

				if (!empty($author)) {
					$author = array_unique($author);
					$scope['AND']['OR'][] = array('Node.created_by' => $author);
				}
			}

			preg_match_all('/(^| )\-[a-z0-9]+/i', $criteria, $negative);
			if (isset($negative[0])) {
				$criteria = str_replace(implode('', $negative[0]), '', $criteria);
				$criteria = trim(preg_replace('/ {2,}/', ' ',  $criteria));

				foreach ($negative[0] as $n) {
					$n = trim(str_replace('-', '', $n));

					if (empty($n)) {
						continue;
					}

					$scope['NOT'][] = array('Node.:: LIKE' => "%{$n}%");
				}
			}

			preg_match('/\"(.+)\"/i', $criteria, $phrase);
			if (isset($phrase[1])) {
				$criteria = str_replace($phrase[0], '', $criteria);
				$criteria = trim(preg_replace('/ {2,}/', ' ',  $criteria));
				$phrase = trim($phrase[1]);
				$scope['AND'][] = array('Node.:: LIKE' => "%{$phrase}%");
			}

			$criteria = explode('OR', trim($criteria));

			foreach ($criteria as $or) {
				$or = trim($or);

				if (empty($or)) {
					continue;
				}

				$scope['AND']['OR'][] = array('Node.:: LIKE' => "%{$or}%");
			}

			// pass scoping params to modules
			$this->hook('node_search_criteria_alter', $d = array('scope' => $scope, 'criteria' => $criteria));
			extract($d);
		} elseif (isset($this->data['Search'])) {
			// node types
			if (isset($this->data['Search']['type']) && !empty($this->data['Search']['type'])) {
				$keys['type'] = $this->__search_expression($keys['type'], 'type', implode(',', $this->data['Search']['type']));
			}

			// taxonomy terms
			if (isset($this->data['Search']['term']) && is_array($this->data['Search']['term']) && !empty($this->data['Search']['term'])) {
				$keys['term'] = $this->__search_expression($keys['term'], 'term', implode(',', $this->data['Search']['term']));
			}

			// node language
			if (isset($this->data['Search']['language']) && is_array($this->data['Search']['language'])) {
				$languages = array_filter($this->data['Search']['language']);
				if (count($languages)) {
					$keys['language'] = $this->__search_expression($keys['language'], 'language', implode(',', $languages));
				}
			}

			if (isset($this->data['Search']['or']) && trim($this->data['Search']['or']) != '') {
				if (preg_match_all('/ ("[^"]+"|[^" ]+)/i', ' ' . $this->data['Search']['or'], $matches)) {
					$keys['or'] = ' ' . implode(' OR ', $matches[1]);
				}
			}

			if (isset($this->data['Search']['negative']) && trim($this->data['Search']['negative']) != '') {
				if (preg_match_all('/ ("[^"]+"|[^" ]+)/i', ' ' . $this->data['Search']['negative'], $matches)) {
					$keys['negative'] = ' -' . implode(' -', $matches[1]);
				}
			}

			if (isset($this->data['Search']['phrase']) && trim($this->data['Search']['phrase']) != '') {
				$keys['phrase'] = ' "' . str_replace('"', ' ', $this->data['Search']['phrase']) . '"';
			}

			if (isset($this->data['Search']['limit']) && trim($this->data['Search']['limit']) != '') {
				$keys['limit'] = intval($this->data['Search']['limit']);
			}

			$keys = Hash::filter($keys);

			// pass search keys to modules
			$this->hook('node_search_post_alter', $d = array('keys' => $keys, 'post' => $this->data));
			extract($d);

			if (!empty($keys)) {
				$keys = preg_replace('/ {2,}/', ' ',  implode(' ', $keys));
				$this->redirect('/search/' . rawurlencode(trim($keys)));
			}
		} else {
			$this->redirect('/');
		}

		$languages = array();

		foreach (Configure::read('Variable.languages') as $l) {
			$languages[$l['Language']['code']] = $l['Language']['native'];
		}

		$this->set('nodeTypes',
			$this->Node->NodeType->find('list',
				array(
					'conditions' => array(
						'NodeType.status' => 1
					)
				)
			)
		);

		// prepare content
		if (!empty($scope)) {
			$scope['Node.status'] = 1;
			$scope['AND']['AND']['OR'][] = array('Node.roles_cache LIKE' => null);
			$scope['AND']['AND']['OR'][] = array('Node.roles_cache LIKE' => '');

			foreach (QuickApps::userRoles() as $role) {
				$scope['AND']['AND']['OR'][] =  array('Node.roles_cache LIKE' => "%|{$role}|%");
			}

			$this->paginate = array(
				'limit' => $limit,
				'order' => $order
			);
			$this->Layout['node'] = $this->paginate('Node', $scope);

			if (!empty($this->Layout['node'])) {
				$this->Layout['feed'] = "/{$this->request->url}/feed:rss";
			}
		} else {
			$this->Layout['node'] = array();
		}

		if ($this->request->is('requested')) {
			return $this->Layout['node'];
		}

		if (isset($this->request->params['named']['feed'])) {
			switch ($this->request->params['named']['feed']) {
				case 'rss':
					$this->layoutPath = 'rss';
					$this->helpers[] = 'Rss';
					$this->helpers[] = 'Text';
					$this->Layout['display'] = 'rss';

					$this->response->type('xml');
				break;

				case 'ajax':
					case 'xml':
						$this->viewClass = $this->request->params['named']['feed'] == 'ajax' ? 'Json' : 'Xml';
						$this->Layout['viewMode'] = $this->request->params['named']['feed'];

						if ($this->request->params['named']['feed'] == 'xml') {
							foreach ($this->Layout['node'] as $key => $node) {
								$this->Layout['node']["node-{$node['Node']['id']}"] = $node;

								unset($this->Layout['node'][$key]);
							}
						}

						$this->set('nodes', array('nodes' => $this->Layout['node']));
						$this->set('_serialize', 'nodes');
				break;
			}
		} else {
			$this->Layout['display'] = 'list';
		}

		$this->set('languages', $languages);
		$this->set('scope', $scope);
	}

/**
 * Adds a search option to a search expression.
 *
 * They take the form option:value, and are added to the ordinary
 * keywords in the search expression.
 *
 * @param $expression The search expression to add to.
 * @param $option The name of the option to add to the search expression.
 * @param $value
 *	The value to add for the option. If present, it will replace any previous
 *	value added for the option. Cannot contain any spaces or | characters, as
 *	these are used as delimiters. If you want to add a blank value $option: to
 *	the search expression, pass in an empty string or a string that is composed
 *	of only spaces. To clear a previously-stored option without adding a
 *	replacement, pass in NULL for $value or omit.
 *
 * @return
 *	$expression, with any previous value for this option removed, and a new
 *	$option:$value pair added if $value was provided.
 */
	private function __search_expression($expression, $option, $value = null) {
		$expression = trim(preg_replace('/(^| )' . $option . ':[^ ]*/i', '', $expression));

		if (isset($value)) {
			$expression .= ' ' . $option . ':' . trim($value);
		}

		return $expression;
	}

/**
 * Extracts a search option from a search expression.
 *
 * They take the form option:value, and
 * are added to the ordinary keywords in the search expression.
 *
 * @param $expression The search expression to extract from.
 * @param $option The name of the option to retrieve from the search expression.
 * @return
 *	The value previously stored in the search expression for option $option,
 *	if any. Trailing spaces in values will not be included.
 */
	private function __search_expression_extract($expression, $option) {
		// look for date ranges: "[xxxx< TO yyyy>]"
		if (preg_match('/(^| )' . $option . ':\[(.*)\]( |$)/i', $expression, $matches)) {
			return '[' . trim($matches[2]) . ']';
		}

		// look for basic expressions: "exp:value". Where value is any char except white spaces " "
		if (preg_match('/(^| )' . $option . ':([^ ]*)( |$)/i', $expression, $matches)) {
			return trim($matches[2]);
		}
	}
}