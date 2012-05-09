<?php echo $this->Html->script('nivo-slider/jquery.nivo.slider.pack.js'); ?>
<?php echo $this->Html->css('nivo-slider.css'); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#slider').nivoSlider({effect: 'fade'});
	});
</script>

<div id="slider-wrapper">
	<div id="slider" class="nivoSlider">
		<?php
			$folder = WWW_ROOT . 'files' . DS . Configure::read('Modules.ThemeDefault.settings.slider_folder') . DS;
			$url_folder = 'files/' . Configure::read('Modules.ThemeDefault.settings.slider_folder') . '/';
			$folder = str_replace('/', DS, $folder);
			$folder = preg_replace('/\/{2,}/i', DS, $folder);
			$images = explode("\n", $this->Layout->hooktags($block['Block']['settings']['slider_order']));
			$i = 1;
			$captions = array();

			foreach ($images as $image) {
				if (empty($image)) {
					continue;
				}

				$image = explode('|', $image); // 0: file_name, 1: url, 2: description
				$image[0] = @trim($image[0]);

				if (file_exists($folder . $image[0])) {
					$url = '/' . $url_folder . $image[0];
					$url = preg_replace('/\/{2,}/i', '/', $url);

					if (isset($image[1]) && !empty($image[1])  && isset($image[2]) && !empty($image[2])) {
						echo "<a href=\"{$image[1]}\">" . $this->Html->image($url, array('title' => "#sliderCaption{$i}")) . "</a>";

						$captions[] = "<div id=\"sliderCaption{$i}\" class=\"nivo-html-caption\">" . __t($image[2]) . "</div>";
					} elseif (isset($image[1]) && !empty($image[1])) {
						echo "<a href=\"{$image[1]}\">" . $this->Html->image($url, array('title' => '')) . "</a>";
					} elseif (isset($image[2]) && !empty($image[2])) {
						echo $this->Html->image($url, array('title' => "#sliderCaption{$i}"));

						$captions[] = "<div id=\"sliderCaption{$i}\" class=\"nivo-html-caption\">" . __t($image[2]) . "</div>";
					} else {
						echo $this->Html->image($url, array('title' => ''));
					}

					echo "\n";
				}

				$i++;
			}
		?>
	</div>
	<?php echo implode("\n", $captions); ?>
</div>