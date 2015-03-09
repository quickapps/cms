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
?>

<?php echo $this->Form->create($arrayContext, ['class' => 'form-vertical']); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $this->Form->input('site_title', ['label' => __d('system', 'Site name') . ' *']); ?>
                <em class="help-block"><?php echo __d('system', "This is used as default page's title."); ?></em>
            </div>

            <div class="form-group">
                <?php echo $this->Form->input('site_email', ['label' => __d('system', 'e-Mail address')  . ' *']); ?>
                <em class="help-block"><?php echo __d('system', "The From address in automated e-mails sent during registration and new password requests, and other notifications. (Use an address ending in your site's domain to help prevent this e-mail being flagged as spam.)"); ?></em>
            </div>

            <div class="form-group">
                <?php echo $this->Form->input('site_slogan', ['label' => __d('system', 'Slogan')]); ?>
                <em class="help-block"><?php echo __d('system', "How this is used depends on your site's theme."); ?></em>
            </div>

            <div class="form-group">
                <?php echo $this->Form->input('site_description', ['type' => 'textarea', 'label' => __d('system', 'Description'), 'rows' => 2]); ?>
                <em class="help-block"><?php echo __d('system', 'A brief description about your site, this will be used as default meta-description in layout.'); ?></em>
            </div>

            <div class="form-group">
                <?php
                    echo $this->Form->input('site_nodes_home', [
                        'type' => 'select',
                        'label' => __d('system', 'Number of contents on home page'),
                        'options' => [
                            1 => '1',
                            2 => '2',
                            3 => '3',
                            4 => '4',
                            5 => '5',
                            6 => '6',
                            7 => '7',
                            8 => '8',
                            9 => '9',
                            10 => '10',
                        ]
                    ]);
                ?>
                <em class="help-block"><?php echo __d('system', 'The maximum number of contents displayed on home page.'); ?></em>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <?php
                    echo $this->Form->input('site_maintenance', [
                        'type' => 'select',
                        'label' => __d('system', 'Site under maintenance'),
                        'options' => [
                            0 => __d('system', 'No'),
                            1 => __d('system', 'Yes'),
                        ]
                    ]);
                ?>
            </div>

            <div class="form-group">
                <?php echo $this->Form->label('site_maintenance_ip', __d('system', 'Maintenance IP')); ?>
                <div class="input-group">
                    <?php echo $this->Form->input('site_maintenance_ip', ['label' => false]); ?>
                    <span class="input-group-addon"><a href="" onclick="addRemoteAddr(); return false;"><?php echo __d('system', 'Add my IP'); ?></a></span>
                </div>
                <em class="help-block"><?php echo __d('system', 'IP addresses allowed to access the Front Office even if the site is disabled. Use a comma to separate them (e.g., 42.24.4.2,127.0.0.1,99.98.97.96)'); ?></em>
            </div>

            <div class="form-group">
                <?php echo $this->Form->input('site_maintenance_message', ['type' => 'textarea', 'label' => 'Maintenance message']); ?>
            </div>

            <div class="form-group">
                <?php echo $this->Form->input('default_language', ['type' => 'select', 'label' => __d('system', 'Default language'), 'options' => $languages]); ?>
                <em class="help-block"><?php echo __d('system', 'Default language in which website is presented to anonymous users (authenticated users may select their preferred language).'); ?></em>
            </div>

            <div class="form-group">
                <?php
                    echo $this->Form->input('url_locale_prefix', [
                        'type' => 'select',
                        'options' => [
                            '0' => __d('system', 'No'),
                            '1' => __d('system', 'Yes'),
                        ] ,
                        'label' => __d('system', 'URL locale prefix'),
                    ]);
                ?>
                <em class="help-block"><?php echo __d('system', 'URLs like http://www.example.com/en-us/about-me.html set language to English-US. <strong>Warning: Changing this setting may break incoming URLs. Use with caution on a production site.</strong>'); ?></em>
            </div>

            <div class="form-group">
                <?php echo $this->Form->submit(__d('system', 'Save Changes')); ?>
            </div>
        </div>
    </div>

    <?php if ($pluginSettings->count()): ?>
    <div class="row">
        <div class="col-md-12">
            <hr />

            <h2><?php echo __d('system', 'Other plugin settings'); ?></h2>
            

            <ul>
                <?php foreach($pluginSettings as $plugin): ?>
                <li>
                    <strong>
                        <?php
                            echo $this->Html->link($plugin->human_name, [
                                'plugin' => 'System',
                                'controller' => 'plugins',
                                'action' => 'settings',
                                $plugin->name()
                            ]);
                        ?>
                    </strong>
                    <em class="help-block"><?php echo $plugin->composer['description']; ?></em>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>
<?php echo $this->Form->end(); ?>

<script>
    function addRemoteAddr() {
        var ips = $.grep($('#site-maintenance-ip').val().split(','), function(n, i) {return (n != ''); });
        var remote_ip = '<?php echo env('REMOTE_ADDR'); ?>';

        if ($.inArray(remote_ip, ips) < 0) {
            ips.push(remote_ip);
            $('#site-maintenance-ip').val(ips.join(','));
        }
    }
</script>