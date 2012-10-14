<h4><?php echo $repo->yaml['name']; ?></h4>
<p><?php echo $repo->yaml['description']; ?></p>

<div class="clearfix">
	<?php
		$zip_url = "https://github.com/{$repo->full_name}/zipball/{$repo->master_branch}";
		$links = array(
			array(__t('Install'), '/admin/system/modules/install/' . base64_encode($zip_url), 'options' => array('confirm' => __t('Install this module ?'))),
			array(__t('Download'), $zip_url)
		);

		echo $this->Menu->toolbar($links, array('class' => 'simple-toolbar'));
?>
</div>

<hr />

<ul>
	<li><b><?php echo __t('project repository'); ?>:</b> <?php echo $this->Html->link(__t('Visit'), $repo->html_url, array('target' => '_blank')); ?></li>

	<?php if (isset($repo->yaml['author'])): ?>
	<li><b><?php echo __t('author'); ?>:</b> <?php echo htmlentities($repo->yaml['author']); ?></li>
	<?php endif; ?>

	<?php if (isset($repo->yaml['category'])): ?>
	<li><b><?php echo __t('category'); ?>:</b> <?php echo $repo->yaml['category']; ?></li>
	<?php endif; ?>

	<?php if (isset($repo->yaml['version'])): ?>
	<li><b><?php echo __t('version'); ?>:</b> <?php echo $repo->yaml['version']; ?></li>
	<?php endif; ?>

	<?php if (isset($repo->yaml['core'])): ?>
	<li><b><?php echo __t('compatibility'); ?>:</b> QuickApps CMS <?php echo $repo->yaml['core']; ?></li>
	<?php endif; ?>

	<?php if (isset($repo->homepage)): ?>
	<li><b><?php echo __t('home page'); ?>:</b> <?php echo $this->Html->link($repo->homepage, $repo->homepage, array('target' => '_blank')); ?></li>
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
	<li><b><?php echo __t('Available downloads'); ?>:</b>
		<ul>
			<?php foreach($repo->tags as $tag): ?>
				<li>
					<?php echo $tag->name?>:
					<?php echo $this->Html->link(__t('download'), $tag->zipball_url); ?>
					<?php echo $this->Html->link(__t('install'), '/admin/system/modules/install/' . base64_encode("https://github.com/{$repo->full_name}/zipball/{$tag->zipball_url}")); ?>
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