<?php
/**
 * Single comment element.
 * Rendered as part of node details page.
 *
 * @package	 QuickApps.View.Elements
 * @author	 Christopher Castro <chris@quickapps.es>
 */
?>

<div id="<?php echo "comment-{$comment['Comment']['id']}"; ?>" class="comment <?php echo $i%2 ? 'even': 'odd'; ?> <?php echo $i==1 ? 'comment-first' : ''; ?> <?php echo $i == $count ? 'comment-last' : ''; ?>">
	<div class="attribution">
		<div class="submited">
			<p class="commenter-name">
				<div class="avatar">
					<?php
						if (isset($comment['User']) && !empty($comment['User']['id'])) {
							echo $this->User->avatar($comment);
						} else {
							echo $this->User->avatar(
								array(
									'User' => array(
										'email' => $comment['Comment']['mail']
									)
								)
							);
						}
					?>
				</div>

				<?php $userURL = !empty($comment['Comment']['homepage']) ? $comment['Comment']['homepage'] : ''; ?>
				<?php $userURL = empty($userURL) && isset($comment['User']['username']) ? $this->Html->url("/user/profile/{$comment['User']['username']}") : 'javascript: return false;'; ?>
				<a href="<?php echo $userURL; ?>" class="username" rel="nofollow">
					<?php echo isset($comment['User']['username']) ? $comment['User']['username'] : $comment['Comment']['name']; ?>
				</a>
			</p>
			<p class="comment-time"><span><?php echo __t('said on %s', $this->Time->format(__t('M d, Y H:i'), $comment['Comment']['created'])); ?></span></p>
			<p class="comment-permalink"><?php echo $this->Html->link(__t('Permalink'), "/{$Layout['node']['Node']['node_type_id']}/{$Layout['node']['Node']['slug']}.html#comment-{$comment['Comment']['id']}", array('id' => "comment-{$comment['Comment']['id']}", 'class' => 'permalink')); ?></p>
		</div>
	</div>

	<div class="comment-body">
		<div class="comment-text">
			<div class="comment-actions">
				<?php
					$comment_actions = array();

					if ($Layout['node']['Node']['comment'] == 2) {
						$comment_actions[] = $this->Html->link(__t('Quote'), '#', array('class' => 'quote', 'onclick' => "quoteComment({$comment['Comment']['id']}); return false;"));
					}

					echo $this->Html->nestedList($comment_actions, array('class' => 'comment-actions-list', 'id' => "comment-actions-{$comment['Comment']['id']}"));
				?>
			</div>
			<?php if ($Layout['node']['NodeType']['comments_subject_field']): ?>
				<h3><?php echo $this->Html->link($comment['Comment']['subject'], "/{$Layout['node']['Node']['node_type_id']}/{$Layout['node']['Node']['slug']}.html#comment-{$comment['Comment']['id']}", array('class' => 'permalink')); ?></h3>
			<?php endif; ?>
			<p><?php echo $comment['Comment']['body']; ?></p>
			<p id="raw-comment-<?php echo $comment['Comment']['id']; ?>" style="display:none;"><?php echo $comment['Comment']['raw_body']; ?></p>
		</div>
	</div>
</div>