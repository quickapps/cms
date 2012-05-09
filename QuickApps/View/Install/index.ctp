<div id="title_wrap">
	<h1 class="eng">Welcome to QuickApps CMS</h1>

	<?php foreach($languages as $locale => $msgs): ?>
		<h1 class="<?php echo $locale; ?>" style="opacity:0;"><?php echo $msgs['welcome']; ?></h1>
	<?php endforeach; ?>
</div>

<p>&nbsp;</p>

<div id="install-lang">
	<ul>
		<li class="mark">
			<a style="" class="eng" href="<?php echo $this->Html->url('/install/index/lang:eng'); ?>" title="Install in English">
				Click here to install in English
			</a>
		</li>

		<?php foreach($languages as $locale => $msgs): ?>
		<li>
			<a style="" class="<?php echo $locale; ?>" href="<?php echo $this->Html->url("/install/index/lang:{$locale}"); ?>" title="<?php echo $msgs['action']; ?>">
				<?php echo $msgs['action']; ?>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
</div>

<script type="text/javascript" charset="utf-8">
	var h1s = $('h1');
	var total = h1s.length;
	var current = 'eng';

	var all = [
		'eng', '<?php echo implode("', '", array_keys($languages)); ?>'
	];

	function change_header() {
		var r = Math.floor(Math.random()*total);
		var noob = all[r];

		if (noob != '') {
			if (noob == current) {
				window.setTimeout(change_header, 5);
			} else {
				$('h1.' + current).animate({opacity: 0}, "slow");
				$('a.' + noob).animate({color: '#ffffff'}, "slow");
				$('a.' + current).animate({color: '#999999'}, "slow");
				$('h1.' + noob).animate({opacity: 1}, "slow");
				current = noob;

				window.setTimeout(change_header, 3000)
			}
		}
	}

	$('a.' + current).css('color', '#ffffff');
	window.setTimeout(change_header, 3000);
</script>