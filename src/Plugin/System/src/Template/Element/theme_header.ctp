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

/**
 * Provides a basic header for theme layouts.
 *
 * ### Usage:
 *
 *     echo $this->element('System.theme_header');
 */
?>
<title><?php echo $this->fetch('title'); ?></title>
<?php echo $this->Html->charset(); ?>
<?php echo $this->Html->meta('icon'); ?>
<?php echo $this->fetch('meta'); ?>
<?php echo $this->fetch('css'); ?>
<?php echo $this->fetch('script'); ?>