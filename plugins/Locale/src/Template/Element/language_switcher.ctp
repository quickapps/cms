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
use Cake\I18n\I18n;
use Locale\Utility\LocaleToolbox;
?>

<?php if ($block->settings['selectbox']): ?>
	<?php
		$options = [];
		foreach (quickapps('languages') as $code => $info) {
			$options[$code] = $info['name'];
		}
		echo $this->Html->script('Locale.language.switcher.js');
		echo $this->Form->input('language-switcher', [
			'type' => 'select',
			'options' => $options,
			'value' => I18n::locale(),
			'class' => 'language-switcher',
			'onchange' => 'switchLanguage(this);'
		]);
	?>
	<script type="text/javascript">
		if (typeof switchLanguage !== 'function') {
			function switchLanguage(sb) {
				var code = $(sb).val();
				if (code.length) {
					var url = '<?php echo stripLanguagePrefix($this->Url->build($this->request->url, true)); ?>?locale=' + code; 
					$(location).attr('href', url);
				}
			}
		}
	</script>
<?php else: ?>
	<?php
		$links = [];
		foreach (quickapps('languages') as $code => $info) {
			if ($block->settings['flags'] && $info['icon']) {
				$name = $this->Html->image("Locale.flags/{$info['icon']}") . ' ' . $info['name'];
			} else {
				$name = $info['name'];
			}

			$links[] = [
				'title' => $name,
				'url' => stripLanguagePrefix($this->Url->build($this->request->url, true)) . "?locale={$code}",
				'activation' => function ($request, $url) use($code) {
					return ($code == I18n::locale());
				}
			];
		}
		echo $this->Menu->render($links, ['class' => 'language-switcher']);
	?>	
<?php endif; ?>
