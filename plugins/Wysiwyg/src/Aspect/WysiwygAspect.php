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
namespace Wysiwyg\Aspect;

use Cake\ORM\TableRegistry;
use Cake\View\Helper\FormHelper;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Around;
use QuickApps\Aspect\Aspect;

/**
 * Main Hook Listener for Wysiwyg plugin.
 *
 */
class WysiwygAspect extends Aspect
{

    /**
     * Indicates if CKEditor's JS files were already included.
     *
     * @var bool
     */
    protected static $_scriptsLoaded = false;

    /**
     * Counts how many CK instances has been created.
     *
     * @var bool
     */
    protected static $_counter = 0;

    /**
     * Converts the given text area into a WYSIWYG editor.
     *
     * @param \Go\Aop\Intercept\MethodInvocation $invocation Invocation
     * @Around("execution(public QuickApps\View\Helper\FormHelper->textarea(*))")
     * @return bool Whether object invocation should proceed or not
     */
    public function alterTextarea(MethodInvocation $invocation)
    {
        $helper = $invocation->getThis();
        list($fieldName, $options) = $invocation->getArguments();

        if (!empty($options['class']) &&
            strpos($options['class'], 'ckeditor') !== false &&
            $helper instanceof FormHelper
        ) {
            static::$_counter++;
            $editorId = 'ck-editor-' . static::$_counter;
            $options['class'] .= ' ' . $editorId;
            $view = $this->getProperty($helper, '_View');

            if (!static::$_scriptsLoaded) {
                static::$_scriptsLoaded = true;
                $filebrowserBrowseUrl = $view->Url->build(['plugin' => 'Wysiwyg', 'controller' => 'finder']);
                $view->Html->script('Wysiwyg./ckeditor/ckeditor.js', ['block' => true]);
                $view->Html->script('Wysiwyg./ckeditor/adapters/jquery.js', ['block' => true]);
                $view->Html->scriptBlock('$(document).ready(function () {
                    CKEDITOR.editorConfig = function(config) {
                        config.filebrowserBrowseUrl = "' . $filebrowserBrowseUrl . '";
                    };
                });', ['block' => true]);
                $this->_includeLinksToNodes($view);
            }
        }

        $this->setProperty($invocation, 'arguments', [$fieldName, $options]);
        return $invocation->proceed();
    }

    /**
     * Alters CKEditor's link plugin.
     *
     * Allows to link to QuickAppsCMS's contents, adds to layout header some JS code
     * and files.
     *
     * @param \Cake\View\View $view Instance of view class
     * @return void
     */
    protected function _includeLinksToNodes($view)
    {
        $items = [];
        $nodes = TableRegistry::get('Node.Nodes')
            ->find('all', ['fieldable' => false])
            ->contain(['NodeTypes'])
            ->where(['status' => 1])
            ->order(['sticky' => 'DESC', 'modified' => 'DESC']);

        foreach ($nodes as $node) {
            $items[] = ["{$node->type}: " . h($node->title), $view->Url->build($node->url, true)];
        }

        $view->Html->scriptBlock('var linksToNodesItems = ' . json_encode($items) . ';', ['block' => true]);
        $view->Html->scriptBlock('var linksToNodesLabel = "' . __d('wysiwyg', 'Link to content') . '";', ['block' => true]);
        $view->Html->script('Wysiwyg.ckeditor.node.links.js', ['block' => true]);
    }
}
