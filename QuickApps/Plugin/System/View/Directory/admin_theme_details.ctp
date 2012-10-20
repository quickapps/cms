<h4><?php echo $repo->yaml['info']['name']; ?></h4>
<img src="<?php echo $repo->thumbnail; ?>" align="left" class="theme_tn img-rounded" style="margin-right:20px;" />
<div>
	<p><?php echo $repo->yaml['info']['description']; ?></p>
	<?php
		$zip_url = "https://github.com/{$repo->full_name}/zipball/{$repo->master_branch}";
		$links = array(
			array(__t('Install'), '/admin/system/themes/install/' . base64_encode($zip_url), 'options' => array('confirm' => __t('Install this theme ?'))),
			array(__t('Download'), $zip_url)
		);

		echo $this->Menu->toolbar($links, array('class' => 'simple-toolbar'));
	?>
</div>

<hr />

<ul>
	<li>
		<b><?php echo __t('type'); ?>:</b>
		<?php
			if (!isset($repo->yaml['info']['admin']) ||
				(isset($repo->yaml['info']['admin']) && !$repo->yaml['info']['admin'])
			) {
				echo __t('Public theme (front office)');
			} else {
				echo __t('Private theme (back office)');
			}
		?>
	</li>

	<?php if (isset($repo->yaml['info']['author'])): ?>
	<li><b><?php echo __t('author'); ?>:</b> <?php echo htmlentities($repo->yaml['info']['author']); ?></li>
	<?php endif; ?>

	<?php if (isset($repo->yaml['info']['version'])): ?>
	<li><b><?php echo __t('version'); ?>:</b> <?php echo $repo->yaml['info']['version']; ?></li>
	<?php endif; ?>

	<?php if (isset($repo->yaml['info']['core'])): ?>
	<li><b><?php echo __t('compatibility'); ?>:</b> QuickApps CMS <?php echo $repo->yaml['info']['core']; ?></li>
	<?php endif; ?>

	<li><b><?php echo __t('watchers'); ?>:</b> <?php echo $repo->watchers; ?></li>
	<li><b><?php echo __t('branches'); ?>:</b> <?php echo implode(', ', Hash::extract($repo->branches, '{n}.name')); ?></li>

	<?php if ($repo->has_wiki): ?>
	<li><b><?php echo __t('wiki'); ?>:</b> <?php echo $this->Html->link(__t('Link'), "https://github.com/{$repo->full_name}/wiki", array('target' => '_blank')); ?></li>
	<?php endif; ?>

	<?php if ($repo->has_issues): ?>
	<li><b><?php echo __t('issue tracker'); ?>:</b> <?php echo $this->Html->link(__t('Link (%d)', $repo->open_issues), "https://github.com/{$repo->full_name}/issues", array('target' => '_blank')); ?></li>
	<?php endif; ?>

	<?php if (count($repo->tags)): ?>
	<li>Downlaods
		<ul>
			<?php foreach($repo->tags as $tag): ?>
				<li>
					<?php echo $tag->name?>:
					<?php echo $this->Html->link(__t('download'), $tag->zipball_url); ?>
					<?php echo $this->Html->link(__t('install'), "/admin/system/directory/install_module/{$tag->zipball_url}"); ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</li>
	<?php endif; ?>
</ul>

<?php if (!empty($repo->readme)): ?>
<hr />
<h4><?php echo __t('Readme'); ?></h4>
<div class="well clearfix">
	<?php echo $repo->readme; ?>
</div>
<?php endif; ?>