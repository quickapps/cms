<?php
/**
 * Node Controller
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Node.Controller
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class NodeController extends NodeAppController {
    public $name = 'Node';
    public $uses = array('Node.Node');

/**
 * Redirect to default controller (Contents)
 */
    public function admin_index() {
        $this->redirect("/admin/node/contents");
    }

/**
 * Site FrontPage.
 * "Default front page" URL will be displayed if this option has been set in configuration panel.
 * Otherwise, promoted nodes are captured and default front page is rendered,
 * how it is displayed depends on active FrontEnd Theme.
 */
    public function index() {
        $fp = Configure::read('Variable.site_frontpage');
        $front_page = '';

        if (!empty($fp)) {
            $front_page = $this->requestAction($fp, array('return'));
        }

        if (empty($front_page)) {
            # USE Node.roles_cache
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

            $userRoles = $this->Auth->user('role_id') ? $this->Auth->user('role_id') : array(3);

            foreach ($userRoles as $role_id) {
                $conditions['OR'][] = array('Node.roles_cache LIKE' => "%|{$role_id}|%");
            }

            if ($this->Quickapps->isAdmin()) { #admin-> no role restrictions
                unset($conditions['OR']);
            }

            $this->Layout['node'] = $this->paginate('Node', $conditions);
            $this->Layout['feed'] = '/s/promote:1 language:any';
            $this->Layout['feed'] .= Configure::read('Variable.language.code') ? ',' . Configure::read('Variable.language.code')  : '';
            $this->Layout['feed'] .= '/feed';
        }

        $this->Layout['viewMode'] = 'list';

        $this->set('front_page', $front_page);
    }

/**
 * Node rendering by given node-slug.
 * Error 404 will be rendered if:
 *  - Node does not exists
 *  - User has no access to it (Roles)
 *  - User's language is different to the node's languages
 *
 * @param string $slug Slug of the Node to render
 */
    public function details($type, $slug) {
        $result = Cache::read("node_{$slug}");

        if (!$result) {
            # USE Node.roles_cache
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

            $userRoles = $this->Auth->user('role_id') ? $this->Auth->user('role_id') : array(3);

            foreach ($userRoles as $role_id) {
                $conditions['OR'][] = array('Node.roles_cache LIKE' => "%|{$role_id}|%");
            }

            if ($this->Quickapps->isAdmin()) { #admin-> no role restrictions
                unset($conditions['OR']);
            }

            $this->Node->recursive = 2;
            $result = $this->Node->find('first', array('conditions' => $conditions));

            if (isset($result['Node']['cache']) && !empty($result['Node']['cache'])) { #in seconds
                Cache::config('node_cache', array('engine' => 'File', 'duration' => $result['Node']['cache']));
                Cache::write("node_{$slug}", $result, 'node_cache');
            }
        }

        if (!$result) {
            throw new NotFoundException(__t('Page not found'));
        }

        if (isset($result['Node']['description']) && !empty($result['Node']['description'])) {
            $this->Layout['meta']['description'] = $result['Node']['description'];
        }

        $this->loadModel('Comment.Comment');

        # comment reply
        if (isset($this->data['Comment']) && $result['Node']['comment'] == 2) {
            $data = $this->data;
            $data['Comment']['node_id'] = $result['Node']['id'];

            if ($this->Comment->save($data)) {
                if (!$this->Comment->status) {
                    $this->flashMsg(__d('comment', 'Your comment has been queued for review by site administrators and will be published after approval.'), 'alert');
                } else {
                    $this->flashMsg(__d('comment', 'Your comment has been posted.'), 'success');
                }

                $this->redirect($this->referer());
            } else {
               $this->flashMsg(__d('comment', 'Comment could not be saved. Please try again.'), 'error');
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
        $this->Layout['viewMode'] = 'full';
        $this->Layout['node'] = $result;
    }

/**
 * Search engine.
 * Process search form-POST criteria and convert it to a nice-well-formatted url query.
 * If no form-POST criteria is given then query criteria is spected.
 * Optionally it can render results as RSS feed. Theme rendering is invoked as default.
 *
 * @param string $criteria Well formatted filter criteria. If no criteria is pass POST criteria is spected.
 * @param mixed $rss set to any value (except bool FALSE) to render all results as RSS feed layout.
 */
    public function search($criteria = false, $rss = false) {
        $scope = array();
        $keys = array(
            'type' => null,
            'term' => null,
            'language' => null,
            'or' => null,
            'negative' => null,
            'phrase' => null,
            'limit' => null
        );

        $this->Node->unbindModel(
            array(
                'hasAndBelongsToMany' => array('Role'), # USE Node.roles_cache
                'hasMany' => array('Comment')
            )
        );

        if ($criteria) {
            $criteria = urldecode($criteria);
            $data['Search']['criteria'] = $criteria; // hold untouch criteria query
            $this->data = $data;

            $this->set('criteria', $data['Search']['criteria']);

            if ($limit = $this->__search_expression_extract($criteria, 'limit')) {
                $criteria = str_replace("limit:{$limit}", '', $criteria);
                $limit = intval($limit);
                $limit = $limit <= 0 ? Configure::read('Variable.default_nodes_main') : $limit;
            } else {
                $limit = Configure::read('Variable.default_nodes_main');
            }

            if ($promote = $this->__search_expression_extract($criteria, 'promote')) {
                $criteria = str_replace("promote:{$promote}", '', $criteria);
                $scope['Node.promote'] = intval($promote);
            }

            if ($type = $this->__search_expression_extract($criteria, 'type')) {
                $criteria = str_replace("type:{$type}", '', $criteria);
                $scope['Node.node_type_id'] = explode(',', $type);
            }

            if ($vocabulary = $this->__search_expression_extract($criteria, 'vocabulary')) {
                $criteria = str_replace("vocabulary:{$vocabulary}", '', $criteria);
                $vSlugs = explode(',', $vocabulary);
                $vSlugs = Set::filter($vSlugs);

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

                    $vocabulary_terms = Set::extract('/Term/slug', $vocabularies);
                }
            }

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

                    $scope['OR'][] = array('Node.terms_cache LIKE' => "%:{$term}%");
                }
            }

            if ($language = $this->__search_expression_extract($criteria, 'language')) {
                $criteria = str_replace("language:{$language}", '', $criteria);
                $scope['Node.language'] = explode(',', strtolower($language));

                if (in_array('any', $scope['Node.language'])) {
                    $scope['Node.language'][] = '';
                    unset($scope['Node.language'][array_search('any', $scope['Node.language'])]);
                }
            } else {
                $scope['Node.language'] = array(null, '', Configure::read('Variable.language.code'));
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

                    $scope['NOT']['OR'][] = array('Node.title LIKE' => "%{$n}%");
                    $scope['NOT']['OR'][] = array('Node.slug LIKE' => "%{$n}%");
                    $scope['NOT']['OR'][] = array('Node.description' => "%{$n}%");
                }
            }

            preg_match('/\"(.+)\"/i', $criteria, $phrase);
            if (isset($phrase[1])) {
                $criteria = str_replace($phrase[0], '', $criteria);
                $criteria = trim(preg_replace('/ {2,}/', ' ',  $criteria));
                $phrase = trim($phrase[1]);
                $scope['AND']['OR'][] = array('Node.title LIKE' => "%{$phrase}%");
                $scope['AND']['OR'][] = array('Node.slug LIKE' => "%{$phrase}%");
                $scope['AND']['OR'][] = array('Node.description' => "%{$phrase}%");
            }

            $criteria = explode('OR', trim($criteria));

            foreach ($criteria as $or) {
                $or = trim($or);

                if (empty($or)) {
                    continue;
                }

                $scope['AND']['OR'][] = array('Node.title LIKE' => "%{$or}%");
                $scope['AND']['OR'][] = array('Node.slug LIKE' => "%{$or}%");
                $scope['AND']['OR'][] = array('Node.description' => "%{$or}%");
            }

            # pass scoping params to modules
            $this->hook('node_search_scope_alter', $scope);

        } elseif (isset($this->data['Search'])) {
            # node types
            if (isset($this->data['Search']['type']) && !empty($this->data['Search']['type'])) {
                $keys['type'] = $this->__search_expression($keys['type'], 'type', implode(',', $this->data['Search']['type']));
            }

            # taxonomy terms
            if (isset($this->data['Search']['term']) && is_array($this->data['Search']['term']) && !empty($this->data['Search']['term'])) {
                $keys['term'] = $this->__search_expression($keys['term'], 'term', implode(',', $this->data['Search']['term']));
            }

            # node language
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

            $keys = Set::filter($keys);
            # pass search keys to modules
            $this->hook('node_search_keys_alter', $keys);

            if (!empty($keys)) {
                $keys = preg_replace('/ {2,}/', ' ',  implode(' ', $keys));
                $this->redirect('/s/' . urldecode(trim($keys)));
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

        # prepare content
        if (!empty($scope)) {
            $scope['Node.status'] = 1; # only published content!
            $this->paginate = array(
                'limit' => $limit,
                'order' => array(
                    'Node.sticky' => 'DESC',
                    'Node.created' => 'DESC'
                )
            );
            $this->Layout['node'] = $this->paginate('Node', $scope);
            $this->Layout['feed'] = "/{$this->request->url}/feed";
        } else {
            $this->Layout['node'] = array();
        }

        if ($rss) {
            $this->layoutPath = 'rss';
            $this->helpers[] = 'Rss';
            $this->helpers[] = 'Text';
            $this->Layout['viewMode'] = 'rss';
        } else {
            $this->Layout['viewMode'] = 'list';
        }

        $this->set('languages', $languages);
        $this->set('scope', $scope);
    }

/**
 * By: Drupal
 * Adds a search option to a search expression.
 *
 * They take the form option:value, and are added to the ordinary
 * keywords in the search expression.
 *
 * @param $expression
 *   The search expression to add to.
 * @param $option
 *   The name of the option to add to the search expression.
 * @param $value
 *   The value to add for the option. If present, it will replace any previous
 *   value added for the option. Cannot contain any spaces or | characters, as
 *   these are used as delimiters. If you want to add a blank value $option: to
 *   the search expression, pass in an empty string or a string that is composed
 *   of only spaces. To clear a previously-stored option without adding a
 *   replacement, pass in NULL for $value or omit.
 *
 * @return
 *   $expression, with any previous value for this option removed, and a new
 *   $option:$value pair added if $value was provided.
 */
    private function __search_expression($expression, $option, $value = null) {
        $expression = trim(preg_replace('/(^| )' . $option . ':[^ ]*/i', '', $expression));

        if (isset($value)) {
            $expression .= ' ' . $option . ':' . trim($value);
        }

        return $expression;
    }

/**
 * By: Drupal
 * Extracts a search option from a search expression.
 *
 * They take the form option:value, and
 * are added to the ordinary keywords in the search expression.
 *
 * @param $expression
 *   The search expression to extract from.
 * @param $option
 *   The name of the option to retrieve from the search expression.
 *
 * @return
 *   The value previously stored in the search expression for option $option,
 *   if any. Trailing spaces in values will not be included.
 */
    private function __search_expression_extract($expression, $option) {
        if (preg_match('/(^| )' . $option . ':([^ ]*)( |$)/i', $expression, $matches)) {
            return $matches[2];
        }
    }
}