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
 * Renders all comments for the given entity.
 */
?>

<section class="comments">
	<header>
		<h2><?php echo __d('comment', 'Comments'); ?></h2>
	</header>

	<?php if ($this->Comment->config('entity')->has('comments') && count($this->Comment->config('entity')->get('comments'))): ?>
		<?php foreach ($this->Comment->config('entity')->get('comments') as $comment): ?>
			<?php echo $this->render($comment); ?>
		<?php endforeach; ?>
	<?php else: ?>
		<p><?php echo __d('comment', 'There are no comments yet.'); ?></p>
	<?php endif; ?>
<section>