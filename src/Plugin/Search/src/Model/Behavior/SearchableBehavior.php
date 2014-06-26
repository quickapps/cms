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
namespace Search\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

/**
 * This behavior allows entities to be searchable through
 * an auto-generated list of words.
 *
 * ## Using this Behavior
 *
 * You must indicate which fields can be indexed when attaching this behavior to your tables.
 * For example, when attaching this behavior to `Users` table:
 *
 *     $this->addBehavior('Search.Searchable', [
 *         'fields' => ['username', 'email']
 *     ]);
 *
 * You can use this behavior in combination with `Fieldable` behavior,
 * so you can also add virtual fields to entity's indexed words. To do this,
 * simply add the `_fields` keyword to the list of fields to be indexed:
 *
 *     $this->addBehavior('Search.Searchable', [
 *         'fields' => ['username', 'email', '_fields']
 *     ]);
 *
 * ## Searching Entities
 *
 * When attaching this behavior, every entity under your table gets a list of indexed words. The idea
 * is you can use this list of words to locate any entity based on a customized search-criteria.
 * A search-criteria looks as follow:
 *
 *     "this phrase" OR -"and not this one" AND "this"
 *
 * And allows you to perform complex search conditions in a human-readable way. Allows
 * you, for example, create user-friendly search-forms, or create some RSS feed just by creating a
 * nice-well formated URL using a search-criteria. e.g.: `http://example.com/rss/category:art date:>2014-01-01`
 *
 * You must use the `scopeQuery()` method to scope any query using a search-criteria. For example:
 *
 *     $criteria = '"this phrase" OR -"and not this one" AND "this"';
 *     $query = $this->Users->find();
 *     $query = $this->Users->scopeQuery($criteria, $query);
 *
 * The above will alter the given $query object according to the given criteria.
 * The second argument is optional (query object), if not provided this Behavior automatically generates
 * a find-query for you. Previous example and the one below are equivalent:
 *
 *     $criteria = '"this phrase" OR -"and not this one" AND "this"';
 *     $query = $this->Users->scopeQuery($criteria);
 *
 * ### Creating Scope Tags
 *
 * A `Scope Tag` is a search-criteria command which allows you to perform
 * very specific filter conditions over your queries. A scope-tag has two parts,
 * a `name` (underscored-lowercase) and `arguments` (letters, numbers, `<`, `>`, `[`, `]`, `,`, `-` and `_`).
 * Both parts must be separated using the `:` symbol e.g.:
 *
 *     // scope-tag name is: "author"
 *     // scope-tag arguments are: "">2014-03-01"
 *     date:>2014-03-01
 *
 * You can create custom scope-tags using the `SearchableBehavior::addScopeTag()` method.
 * For example, you might need create a custom criteria `author` which allows
 * you to search a `Node` entity by `author name`. A search-criteria using this scope-tag may
 * looks as follow:
 *
 *     // get all nodes containing `this phrase` and created by `JohnLocke`
 *     "this phrase" author:JohnLocke
 *
 * You must define in your Table a scope-method and register it into this behavior,
 * a full working example may look as follow:
 *
 *     class Nodes extends Table {
 *         public function initialize(array $config) {
 *             // attach the behavior
 *             $this->addBehavior('Search.Searchable');
 *             // register a new scope-method for handling `author:<author_name>` expressions
 *             $this->addScopeTag('author', 'scopeAuthor');
 *         }
 *         public function scopeAuthor($query, $value, $negate, $orAnd) {
 *             // $query:
 *             //     The query to alter
 *             // $value:
 *             //     The value after `author:`. e.g.: `JohnLocke`
 *             // $negate:
 *             //     TRUE if user has negated this command. e.g.: `-author:JohnLocke`.
 *             //     FALSE otherwise.
 *             // $orAnd:
 *             //     or|and|false Indicates the type of condition. e.g.: `OR author:JohnLocke`
 *             //     will set $orAnd to `or`. But, `AND author:JohnLocke` will set $orAnd to `and`.
 *             //     By default is set to FALSE. This allows you to use
 *             //     Query::andWhere() && Query::orWhere() methods.
 *         }
 *     }
 */
class SearchableBehavior extends Behavior {
/**
 * The table this behavior is attached to.
 *
 * @var Table
 */
	protected $_table;

/**
 * Behavior configuration array.
 *
 * - scopes: A list of registered scopes methods as `name` => `methodName`
 * - fields: List of entity fields where to look for words
 * - ignore_words: List of banned words
 * - on: Indicates when to extract words, `update` when entity is being updated,
 * `insert` when a new entity is inserted into table. Or `both` (by default)
 *
 * @var array
 */
	protected $_defaultConfig = [
		'scopes' => [],
		'fields' => [],
		'ignore_words' => [],
		'on' => 'both'
	];

/**
 * Constructor
 *
 * @param \Cake\ORM\Table $table The table this behavior is attached to.
 * @param array $config The config for this behavior.
 */
	public function __construct(Table $table, array $config = []) {
		$this->_table = $table;
		$this->_table->hasOne('Search.SearchDatasets', [
			'foreignKey' => 'entity_id',
			'conditions' => ['table_alias' => strtolower($this->_table->alias())],
			'dependent' => true
		]);
		parent::__construct($table, $config);
	}

/**
 * Generates a list of words after each entity is saved.
 *
 * @param \Cake\ORM\Query $query The query object
 * @param \Cake\ORM\Entity $entity Entity from where extract words
 * @return void
 */
	public function afterSave($query, $entity) {
		$config = $this->config();
		$isNew = $entity->isNew();
		$pk = $this->_table->primaryKey();
		$table_alias = strtolower($this->_table->alias());
		$Datasets = TableRegistry::get('Search.SearchDatasets');
		$words = [];

		if (
			($config['on'] === 'update' && $isNew) ||
			($config['on'] === 'insert' && !$isNew) ||
			($config['on'] !== 'both')
		) {
			continue;
		}

		if (in_array('_fields', $config['fields'])) {
			$vFields = $entity->_fields;

			if ($vFields) {
				foreach ($vFields as $vf) {
					$newWords = explode(' ', trim(strtolower($vf->value)));
					$words = array_merge($words, $newWords);
				}
			}
		}

		foreach ($config['fields'] as $f) {
			if ($entity->has($f) && $f !== '_fields') {
				$newWords = explode(' ', trim(strtolower($entity->get($f))));
				$words = array_merge($words, $newWords);
			}
		}

		foreach ($words as $k => $v) {
			if (in_array($v, $config['ignore_words']) || empty($v)) {
				unset($words[$k]);
			}
		}

		$words = ' ' . trim(strtolower(implode(' ', $words))) . ' ';
		$dataset = $Datasets->find()
			->where([
				'entity_id' => $entity->get($pk),
				'table_alias' => $table_alias
			])
			->first();

		if (!$dataset) {
			$dataset = new \Search\Model\Entity\SearchDataset([
				'entity_id' => $entity->get($pk),
				'table_alias' => $table_alias
			]);
		}

		$dataset->set('words', $words);
		$Datasets->save($dataset);
	}

/**
 * Scopes the given query object.
 *
 * It looks for search-criteria and applies them
 * over the query object. For example, given the criteria below:
 *
 *     "this phrase" -"and not this one"
 *
 * Alters the query object as follow:
 *
 *     $query->where([
 *        'indexed_words LIKE' => '%this phrase%',
 *        'indexed_words NOT LIKE' => '%and not this one%'
 *     ]);
 *
 * The `AND` & `OR` keywords are allowed to create complex conditions. For example:
 *
 *     "this phrase" OR -"and not this one" AND "this"
 *
 * Will produce something like:
 *
 *     $query->where(['indexed_words LIKE' => '%this phrase%'])
 *         ->orWhere(['indexed_words NOT LIKE' => '%and not this one%']);
 *         ->andWhere(['indexed_words LIKE' => '%this%']);
 *
 *
 * @param string $criteria A search-criteria. e.g. `"this phrase" author:username`
 * @param null|\Cake\ORM\Query $query The query to scope, or null to create one
 * @return \Cake\ORM\Query Scoped query
 */
	public function scopeQuery($criteria, $query = null) {
		$query = is_null($query) ? $this->_table->find() : $query;
		$tokens = $this->_getTokens($criteria);
		$query->contain('SearchDatasets');

		foreach ($tokens as $k => $token) {
			if (in_array(strtolower($token), ['or', 'and'])) {
				continue;
			}

			$previousToken = $k > 0 ? $tokens[$k - 1] : null;
			$orAnd = in_array($previousToken, ['or', 'and']) ? strtolower($previousToken) : null;

			if (preg_match('/(-?)(\w+)\:(.+)$/', $token, $custom)) {
				list($negate, $method, $value) = [$custom[1], $custom[2], $custom[3]];
				$negate = !empty($negate);
				$callable = $this->_getScopeCallable($method);

				if ($callable) {
					$query = $callable($query, $value, $negate, $orAnd);
				}
			} else {
				if (strpos($token, '-') === 0) {
					$token = preg_replace('/^\-/', '', $token);
					$LIKE = "NOT LIKE";
				} else {
					$LIKE = 'LIKE';
				}

				if ($orAnd === 'or') {
					$query->orWhere(["SearchDatasets.words {$LIKE}"  => "%{$token}%"]);
				} elseif ($orAnd === 'and') {
					$query->andWhere(["SearchDatasets.words {$LIKE}" => "%{$token}%"]);
				} else {
					$query->where(["SearchDatasets.words {$LIKE}"  => "%{$token}%"]);
				}
			}
		}

		return $query;
	}

/**
 * Registers a new scope method.
 *
 * @param string $name scope name. e.g. `author`
 * @param string $methodName The Table's method which will take care of this scope method
 */
	public function addScopeTag($name, $methodName) {
		$this->_config['scopes'][$name] = $methodName;
	}

/**
 * Enables a scope-tag.
 *
 * @param string $name Name of the scope to be enabled
 * @return void
 */
	public function enableScopeTag($name) {
		if (isset($this->_config['scopes'][":{$name}"])) {
			$this->_config['scopes'][$name] = $this->_config['scopes'][":{$name}"];
			unset($this->_config['scopes'][":{$name}"]);
		}
	}

/**
 * Disables a scope-tag.
 *
 * @param string $name Name of the scope to be disabled
 * @return void
 */
	public function disableScopeTag($name) {
		if (isset($this->_config['scopes'][$name])) {
			$this->_config['scopes'][":{$name}"] = $this->_config['scopes'][$name];
			unset($this->_config['scopes'][$name]);
		}
	}

/**
 * Gets the callable method for a given scope method.
 *
 * @param string $name Get the callable object for the given scope method
 * @return callable
 */
	protected function _getScopeCallable($name) {
		$config = $this->config();
		$scopes = $config['scopes'];

		if (isset($scopes[$name])) {
			$callableName = $scopes[$name];

			if (method_exists($this->_table, $callableName)) {
				return function ($query, $value, $negate, $orAnd) use($callableName) {
					return $this->_table->$callableName($query, $value, $negate, $orAnd);
				};
			}
		}

		return false;
	}

/**
 * Extract tokens from search-criteria.
 *
 * @param string $criteria A search-criteria
 * @return array List of extracted tokens
 */
	protected function _getTokens($criteria) {
		$regex = '/-?"[\pL\d\s]+"|-?[\pL\:\<\>\_\-\w\d\,\[\]]+/';
		preg_match_all($regex, $criteria, $tokens, PREG_SET_ORDER);

		foreach ($tokens as & $token) {
			$token = array_shift($token);
			$modifier = null;

			if ($token[0] === '-') {
				$modifier = $token[0];
				$token = substr($token, 1);
			}

			if ($token[0] === '"') {
				$token = trim($token, '"');
			}

			$token = $modifier . $token;
		}

		return array_unique($tokens);
	}

}
