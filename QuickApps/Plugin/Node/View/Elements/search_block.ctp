<?php
/**
 * Block
 *
 * @package QuickApps.Plugin.Node.View.Elements
 * @author Christopher Castro
 */
?>

<?php
	$prefix = '';

	if (isset($block['Block']['settings']['url_prefix']) && !empty($block['Block']['settings']['url_prefix'])) {
		$prefix = trim($block['Block']['settings']['url_prefix']) . ' ';
	}

	echo $this->Form->create('Search',
		array(
			'url' => '/search/',
			'onSubmit' => "QuickApps.doSearch(); return false;"
		)
	);
?>
	<?php echo $this->Form->input('criteria', array('required' => 'required', 'type' => 'text', 'label' => __t('Keywords'))); ?>
	<?php echo $this->Form->submit(__t('Search')); ?>
<?php echo $this->Form->end(); ?>

<script type="text/javascript">
	QuickApps.doSearch = function () {
		$(location).attr('href',
			QuickApps.settings.base_url + 'search/<?php echo $prefix; ?>' + decodeURIComponent($('#SearchCriteria').val())
		);
	};

	QuickApps.__searchCriteria = '<?php echo @$criteria; ?>';

	$(document).ready(function () {
		$('#SearchCriteria').focus(function () {
			if ($(this).val() == QuickApps.__searchCriteria) {
				$(this).val('');
			}
		});

		$('#SearchCriteria').blur(function () {
			if ($.trim($(this).val()) == '') {
				$(this).val(QuickApps.__searchCriteria);
			}
		});
	});
</script>