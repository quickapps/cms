<?php
/**
 * Render Node's comments list.
 *
 * @package	 QuickApps.View.Elements
 * @author	 Christopher Castro <chris@quickapps.es>
 */
?>

<?php
	$i = 1;
	$count = count($Layout['node']['Comment']);

	if ($count > 0):
?>
	<?php echo $this->Html->tag('h2', __t('Comments')); ?>
	<div id="comments-list">
		<div class="comments-pagination paginator paginator-top">
			<?php $this->Paginator->options(array('url'=> $this->passedArgs)); ?>
			<?php echo $this->Paginator->prev(__t('«'), null, null, array('class' => 'disabled')); ?>
			<?php echo $this->Paginator->numbers(array('separator' => ' ')); ?>
			<?php echo $this->Paginator->next(__t('»'), null, null, array('class' => 'disabled')); ?>
		</div>

		<?php
			foreach ($Layout['node']['Comment'] as $comment) {
				$this->Layout->hook('comment_alter', $comment);

				echo $this->element('theme_comment', compact('comment', 'i', 'count'));

				$i++;
			}
		?>

		<div class="comments-pagination paginator paginator-bottom">
			<?php $this->Paginator->options(array('url'=> $this->passedArgs)); ?>
			<?php echo $this->Paginator->prev(__t('«'), null, null, array('class' => 'disabled')); ?>
			<?php echo $this->Paginator->numbers(array('separator' => ' ')); ?>
			<?php echo $this->Paginator->next(__t('»'), null, null, array('class' => 'disabled')); ?>
		</div>
	</div>
<?php endif; ?>