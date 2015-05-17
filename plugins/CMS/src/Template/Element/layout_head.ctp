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

/**
 * Provides a basic head for theme's layouts.
 *
 * @see CMS\View\Helper\HtmlHelper::head()
 */
?>

<!--
    This website is powered by QuickAppsCMS <?php echo quickapps('version'); ?>, Licensed under GNU/LGPL
    Visit the project website at http://www.quickappscms.org for more information
//-->

<?php if (isset($prepend)): ?>
    <?php echo is_array($prepend) ? implode("\n", $prepend) : $prepend; ?>
<?php endif; ?>

<title><?php echo strip_tags($this->fetch('title')); ?></title>
<?php echo $this->Html->charset(); ?>

<?php if (isset($icon) && $icon === true): ?>
    <?php echo $this->Html->meta('icon'); ?>
<?php endif; ?>

<?php echo $this->fetch('meta'); ?>
<?php echo $this->fetch('css'); ?>
<?php echo $this->fetch('script'); ?>

<?php $bootstrap = !isset($bootstrap) ? 'css' : $bootstrap; ?>
<?php $bootstrap = is_string($bootstrap) ? strtolower($bootstrap) : $bootstrap; ?>

<?php if ($bootstrap !== false): ?>
    <?php if ((is_bool($bootstrap) && $bootstrap === true) || (is_string($bootstrap) && strpos($bootstrap, 'css') !== false)): ?>
        <?php echo $this->Html->css(['Bootstrap.bootstrap.css', 'Bootstrap.bootstrap-theme.css']); ?>
    <?php endif; ?>

    <?php if ((is_bool($bootstrap) && $bootstrap === true) || (is_string($bootstrap) && strpos($bootstrap, 'js') !== false)): ?>
        <?php echo $this->Html->script('Bootstrap.bootstrap.min.js'); ?>
    <?php endif; ?>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <?php echo $this->Html->script('System.html5shiv.js'); ?>
    <?php echo $this->Html->script('System.respond.js'); ?>
    <![endif]-->
<?php endif; ?>

<?php if (isset($append)): ?>
    <?php echo is_array($append) ? implode("\n", $append) : $append; ?>
<?php endif; ?>