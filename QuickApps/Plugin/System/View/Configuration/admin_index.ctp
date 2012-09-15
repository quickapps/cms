<?php
	$this->Layout->script("
		function addRemoteAddr() {
				var ips = $.grep($('#VariableSiteMaintenanceIp').attr('value').split(','), function(n, i) { return (n != ''); });
				var remote_ip = '" . env('REMOTE_ADDR') . "';

				if ($.inArray(remote_ip, ips) < 0) {
					ips.push(remote_ip);
					$('#VariableSiteMaintenanceIp').attr('value', ips.join(','));
				}
		}
	", 'inline');
?>

<?php echo $this->Form->create('Variable', array('url' => '/admin/system/configuration')); ?>
	<!-- Settings -->
	<?php echo $this->Html->useTag('fieldsetstart', __t('Site information')); ?>
		<?php echo $this->Html->useTag('fieldsetstart', __t('Site details')); ?>
			<?php echo $this->Form->input('Variable.site_name', array('required' => 'required', 'type' => 'text', 'label' => __t('Site name *'))); ?>

			<?php echo $this->Form->input('Variable.site_slogan', array('type' => 'text', 'label' => __t('Slogan'))); ?>
			<em><?php echo __t("How this is used depends on your site's theme."); ?></em>

			<?php echo $this->Form->input('Variable.site_description', array('type' => 'textarea', 'label' => __t('Description'), 'rows' => 2)); ?>
			<em><?php echo __t("A brief description about your site, this will be used as default meta-description in layout."); ?></em>

			<?php echo $this->Form->input('Variable.site_mail', array('required' => 'required', 'type' => 'email', 'label' => __t('E-mail address *'))); ?>
			<em><?php echo __t("The From address in automated e-mails sent during registration and new password requests, and other notifications. (Use an address ending in your site's domain to help prevent this e-mail being flagged as spam.)"); ?></em>

			<?php echo $this->Form->input('Variable.site_online', array('type' => 'select', 'options' => array(1 => __t('No'), 0 => __t('Yes')), 'label' => __t('Site under maintenance'))); ?>

			<?php
				echo $this->Form->input('Variable.site_maintenance_ip',
					array(
						'type' => 'text',
						'label' => __t('Maintenance IP'), 
						'after' => '&nbsp;' . $this->Html->link(__t('Add my IP'), '#', array('onclick' => 'addRemoteAddr(); return false;'))
				));
			?>
			<em><?php echo __t('IP addresses allowed to access the Front Office even if the site is disabled. Use a comma to separate them (e.g., 42.24.4.2,127.0.0.1,99.98.97.96)'); ?></em>

			<?php echo $this->Form->input('Variable.site_maintenance_message', array('type' => 'textarea', 'label' => __t('Maintenance message'))); ?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<?php echo $this->Html->useTag('fieldsetstart', __t('Front page')); ?>
		<?php echo $this->Form->input('Variable.default_nodes_main', array('type' => 'select', 'options' => Hash::combine(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10), '{n}', '{n}'), 'label' => __t('Number of posts on front page'))); ?>
		<em><?php echo __t("The maximum number of posts displayed on overview pages such as the front page."); ?></em>

		<?php echo $this->Form->input('Variable.site_frontpage', array('between' => Router::url('/', true), 'type' => 'text', 'label' => __t('Default front page'))); ?>
		<em><?php echo __t("Optionally, specify a relative URL to display as the front page. Leave blank to display the default content feed"); ?></em>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<?php echo $this->Html->useTag('fieldsetstart', __t('Regional settings')); ?>
		<?php App::import('Lib', 'Locale.QALocale'); ?>
		<?php echo $this->Form->input('Variable.default_language', array('type' => 'select', 'options' => $languages, 'label' => __t('Default language'))); ?>

		<?php echo $this->Form->input('Variable.date_default_timezone', array('type' => 'select', 'options' => QALocale::timeZones(), 'label' => __t('Default time zone'))); ?>

		<?php echo $this->Form->input('Variable.url_language_prefix', array('type' => 'checkbox', 'options' => array(0 => __t('No'), 1 => __t('Yes')), 'label' => __t('URL path prefix'))); ?>
		<em><?php echo __t('URLs like http://www.example.com/fre/about set language to French (fre). <b>Warning: Changing this setting may break incoming URLs. Use with caution on a production site.</b>'); ?></em>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<?php echo $this->Html->useTag('fieldsetstart', __t('Users settings')); ?>
		<?php echo $this->Html->useTag('fieldsetstart', __t('User Login')); ?>
			<?php
				echo $this->Form->input('Variable.user_login_attempts',
					array(
						'type' => 'select',
						'options' => array(
							2 => '2',
							3 => '3',
							4 => '4',
							5 => '5',
							10 => '10'
						),
						'empty' => true,
						'before' => __t('After') . '&nbsp;',
						'after' => '&nbsp;' . __t('failed login attempts') . '&nbsp;',
						'label' => false
					)
				);

				echo $this->Form->input('Variable.user_login_attempts_time',
					array(
						'type' => 'select',
						'options' => array(
							60 * 1 => '1',
							60 * 2 => '2',
							60 * 3 => '3',
							60 * 4 => '4',
							60 * 5 => '5',
							60 * 10 => '10',
							60 * 20 => '20',
							60 * 30 => '30',
							60 * 40 => '40',
							60 * 50 => '50',
							60 * 30 => '60'
						),
						'empty' => true,
						'before' => __t('Block visitor for') . '&nbsp;',
						'after' => '&nbsp;' . __t('minutes'),
						'label' => false
					)
				);
			?>
			<em><?php echo __t('Leave empty any of the parameters for no login-blocking feature.'); ?></em>
		<?php echo $this->Html->useTag('fieldsetend'); ?><!-- /login -->

		<?php echo $this->Html->useTag('fieldsetstart', __t('User Avatar')); ?>
			<?php
				echo $this->Form->input('Variable.user_use_gravatar',
					array(
						'type' => 'checkbox',
						'label' => __t("Use <a href='http://www.gravatar.com'>Gravatar</a>"),
						'checked' => Configure::read('Variable.user_use_gravatar'),
						'onclick' => "
							$('#gravatar-options').toggle();
							$('#default_avatar-options').toggle();
						"
					)
				);
			?>

			<div id="gravatar-options" style="<?php echo !Configure::read('Variable.user_use_gravatar') ? 'display:none;' : ''; ?>">
				<?php echo $this->Form->input('Variable.user_gravatar_size', array('type' => 'text', 'label' => __t('Avatar width'))); ?>
				<em><?php echo __t('By default, images are presented at 80px by 80px if no size parameter is supplied.'); ?></em>

				<?php echo $this->Form->input('Variable.user_gravatar_default', array('type' => 'text', 'label' => __t('Default avatar URL'))); ?>
				<em><?php echo __t('URL of picture to display for users with an email not matching a Gravatar image.'); ?></em>

				<?php
					echo $this->Form->input('Variable.user_gravatar_force_default',
						array(
							'type' => 'select',
							'label' => __t('Force default'),
							'options' => array(
								'n' => __t('No'),
								'y' => __t('Yes')
							)
						)
					);
				?>
				<em><?php echo __t('Force the default image to always load.'); ?></em>

				<?php
					echo $this->Form->input('Variable.user_gravatar_rating',
						array(
							'type' => 'select',
							'label' => __t('Rating'),
							'options' => array(
								'g' => 'G',
								'pg' => 'PG',
								'r' => 'R',
								'x' => 'X'
							),
							'empty' => __t('-- None --')
						)
					);
				?>
				<em><?php echo __t("If the user's email does not have an image meeting the requested rating level, then the default image is returned (or the specified default)"); ?></em>
				<p>
					<ul>
						<li><b>G:</b> <?php echo __t('suitable for display on all websites with any audience type.'); ?></li>
						<li><b>PG:</b> <?php echo __t('may contain rude gestures, provocatively dressed individuals, the lesser swear words, or mild violence.'); ?></li>
						<li><b>R:</b> <?php echo __t('may contain such things as harsh profanity, intense violence, nudity, or hard drug use.'); ?></li>
						<li><b>X:</b> <?php echo __t('may contain hardcore sexual imagery or extremely disturbing violence.'); ?></li>
					</ul>
				</p>
			</div>

			<div id="default_avatar-options" style="<?php echo !Configure::read('Variable.user_use_gravatar') ? '' : 'display:none;'; ?>">
				<?php echo $this->Form->input('Variable.user_default_avatar', array('type' => 'text', 'label' => __t('Default avatar'))); ?>
				<em><?php echo __t("URL of picture to display for users with no custom picture selected or anonymous users."); ?></em>
			</div>
		<?php echo $this->Html->useTag('fieldsetend'); ?><!-- /avatar -->
	<?php echo $this->Html->useTag('fieldsetend'); ?><!-- /users -->
	<?php
		$moduleSettingsLinks = array();

		foreach (Configure::read('Modules') as $name => $data) {
			$isTheme = strpos($name, 'Theme') === 0;

			if (!$isTheme && file_exists($data['path'] . 'View' . DS . 'Elements' . DS . 'settings.ctp')) {
				$moduleSettingsLinks[] =
					$this->Html->link($data['yaml']['name'], '/admin/system/modules/settings/' . $name) .
					"<p><em>" . __d($name, $data['yaml']['description']) . "</em></p>";
			}
		}

		if (!empty($moduleSettingsLinks)):
	?>
		<?php echo $this->Html->useTag('fieldsetstart', __t('Other module settings')); ?>
			<?php echo $this->Html->nestedList($moduleSettingsLinks, array('id' => 'other-module-settings-list')); ?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php endif; ?>

	<!-- Submit -->
	<?php echo $this->Form->input(__t('Save all'), array('type' => 'submit')); ?>
<?php echo $this->Form->end(); ?>