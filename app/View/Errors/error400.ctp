<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake.libs.view.templates.errors
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!-- default error -->
<h2><?php echo $name; ?></h2>
<p class="error">
    <strong><?php echo __('Error'); ?>: </strong>
    <?php echo __('The requested address %s was not found. <br/> There are no translations available or you have not the sufficient permissions.', "<strong>'{$url}'</strong>"); ?>
    <p><?php echo __('<a href="%s">Go to home page</a>', Router::url('/')); ?></p>

    <script type="text/javascript">
        var GOOG_FIXURL_LANG = '<?php echo Configure::read('Config.language'); ?>';
        var GOOG_FIXURL_SITE = '<?php echo Router::url('/', true); ?>'
    </script>

    <script type="text/javascript" src="http://linkhelp.clients.google.com/tbproxy/lh/wm/fixurl.js"></script>
</p>
<?php
if (Configure::read('debug') > 0):
    echo $this->element('exception_stack_trace');
endif;
?>