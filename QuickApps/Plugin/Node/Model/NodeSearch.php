<?php
/**
 * Node Type Model
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Node.Model
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class NodeSearch extends NodeAppModel {
	public $name = 'NodeSearch';
	public $useTable = "nodes_searches";
	public $primaryKey = 'id';
}