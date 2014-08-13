<p class="details">
	<ul>
		<?php if (!empty($composer['homepage'])): ?>
		<li><strong><?php echo __d('system', 'Homepage'); ?>:</strong> <?php echo $this->Html->link($composer['homepage'], $composer['homepage']); ?></li>
		<?php endif; ?>

		<?php if (!empty($composer['support']['issues'])): ?>
		<li><strong><?php echo __d('system', 'Issues'); ?>:</strong> <?php echo $this->Html->link($composer['support']['issues'], $composer['support']['issues']); ?></li>
		<?php endif; ?>

		<?php if (!empty($composer['support']['forum'])): ?>
		<li><strong><?php echo __d('system', 'Forum'); ?>:</strong> <?php echo $this->Html->link($composer['support']['forum'], $composer['support']['forum']); ?></li>
		<?php endif; ?>

		<?php if (!empty($composer['support']['wiki'])): ?>
		<li><strong><?php echo __d('system', 'Wiki'); ?>:</strong> <?php echo $this->Html->link($composer['support']['wiki'], $composer['support']['wiki']); ?></li>
		<?php endif; ?>

		<?php if (!empty($composer['support']['irc'])): ?>
		<li><strong><?php echo __d('system', 'IRC'); ?>:</strong> <?php echo $this->Html->link($composer['support']['irc'], $composer['support']['irc']); ?></li>
		<?php endif; ?>

		<?php if (!empty($composer['support']['source'])): ?>
		<li><strong><?php echo __d('system', 'Source'); ?>:</strong> <?php echo $this->Html->link($composer['support']['source'], $composer['support']['source']); ?></li>
		<?php endif; ?>

		<?php if (!empty($composer['authors'])): ?>
		<li>
			<strong><?php echo __d('system', 'Authors'); ?>:</strong>

			<ul>
				<?php foreach ($composer['authors'] as $author): ?>
					<li>
						<?php if (!empty($author['homepage'])): ?>
							<?php echo $this->Html->link($author['name'], $author['homepage']); ?>
						<?php else: ?>
							<?php echo $author['name']; ?>
						<?php endif; ?>

						<?php if (!empty($author['email'])): ?>
							&lt;<?php echo $this->Html->link($author['email'], "mailto:{$author['email']}"); ?>&gt;
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>

		</li>
		<?php endif; ?>

	</ul>
</p>

<hr />

<div class="clearfix package-links">
	<p>
		<?php
			$trans = [
				'require' => __d('system', 'Requires'),
				'devRequire' => __d('system', 'Requires (Dev)'),
				'suggest' => __d('system', 'Suggests'),
				'provide' => __d('system', 'Provides'),
				'conflict' => __d('system', 'Conflicts'),
				'replace' => __d('system', 'Replaces'),
			];
		?>

		<?php foreach (["require", "devRequire", "suggest", "provide", "conflict", "replace"] as $type): ?>
			<div class="<?php echo $type; ?>">
				<p>
					<strong><?php echo $trans[$type]; ?></strong>

					<?php if (!empty($composer[$type])): ?>
					<ul>
						<?php foreach ($composer[$type] as $package => $version): ?>
							<li><?php echo $package; ?>: <?php echo $version; ?></li>
						<?php endforeach; ?>
					</ul>
					<?php else: ?>
						<?php echo __d('system', 'None'); ?>
					<?php endif; ?>
				</p>
			</div>
		<?php endforeach; ?>
	</p>
</div>

<?php if (!empty($composer['keywords'])): ?>
<p>
	<hr />

	<div class="clearfix text-left package-tags">
		<?php foreach($composer['keywords'] as $tag): ?>
			<?php echo $this->Html->link($tag, 'https://packagist.org/search/?q=' . $tag, ['class' => 'label label-default', 'target' => '_blank']); ?>
		<?php endforeach; ?>
	</div>
</p>
<?php endif; ?>