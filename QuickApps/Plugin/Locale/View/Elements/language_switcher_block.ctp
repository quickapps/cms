<?php
	$render_type = @$block['Block']['settings']['list_type'] != 'select' ? 'html' : 'select';

	if (Configure::read('Variable.url_language_prefix')) {
		$url = $this->request->url;
		$url = $url[0] !== '/' ? "/{$url}" : $url;
		$url = !preg_match('/^\/[a-z]{3}\//', $url) ? '/' . Configure::read('Config.language') . $url : $url;
	}
?>

<?php if ($render_type == 'html'): ?>
<ul id="lang-selector">
	<?php foreach (Configure::read('Variable.languages') as $key => $lang): ?>
	<?php $isActive = Configure::read('Variable.language.code') == $lang['Language']['code'] ? 'active' : ''; ?>
	<?php
		$flag = '';

		if ($block['Block']['settings']['flags'] && !empty($lang['Language']['icon'])) {
			if (strpos($lang['Language']['icon'], '://') !== false) {
				$icon = $lang['Language']['icon'];
			} else {
				$icon = "/locale/img/flags/{$lang['Language']['icon']}";
			}

			$flag = $this->Html->image($icon, array('class' => 'flag-icon'));
		}

		$name = $block['Block']['settings']['name'] ? "<span>{$lang['Language']['native']}</span>" : '';
	?>
	<li class="<?php echo "{$lang['Language']['code']} {$isActive}"; ?>">
		<?php
			if (Configure::read('Variable.url_language_prefix')) {
				$switch_url = $this->request->base . QuickApps::str_replace_once(Configure::read('Config.language') . '/' , "{$lang['Language']['code']}/", $url);
			} else {
				$switch_url = QuickApps::str_replace_once('/' . Configure::read('Config.language') . '/' , '', $this->here) . "?lang=" . $lang['Language']['code'];
			}
		?>
		<a href="<?php echo $switch_url; ?>">
			<?php echo $flag; ?>
			<?php echo $name; ?>
		</a>
	</li>
	<?php endforeach; ?>
</ul>
<?php else: ?>
<div id="lang-selector">
	<?php
		$options = array();

		foreach (Configure::read('Variable.languages') as $key => $lang) {
			$options[$lang['Language']['code']] = $lang['Language']['native'];
		}

		echo $this->Form->input('Language.switcher',
			array(
				'type' => 'select',
				'options' => $options,
				'label' => __t('Language'),
				'value' => Configure::read('Config.language'),
				'onchange' => 'change_language(this.value);'
			)
		);
	?>

	<script>
		<?php
			if (Configure::read('Variable.url_language_prefix')) {
				$switch_url = $this->request->base . QuickApps::str_replace_once(Configure::read('Config.language') . '/' , "@CODE@/", $url);
			} else {
				$switch_url = QuickApps::str_replace_once('/' . Configure::read('Config.language') . '/' , '', $this->here) . '?lang=@CODE@';
			}
		?>

		function change_language(code) {
			var redirect_url = '<?php echo $switch_url; ?>';

			$(location).attr('href', redirect_url.replace('@CODE@', code));
		}
	</script>
</div>
<?php endif; ?>