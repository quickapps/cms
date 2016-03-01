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

<div class="elfinder">
    <?= __d('media_manager', 'Please enable JavaScript to use elFinder plugin.'); ?>
</div>

<?php $this->Html->css('MediaManager.elfinder.min.css', ['block' => true]); ?>
<?php $this->Html->css('MediaManager.theme.css', ['block' => true]); ?>
<?php $this->Html->script('MediaManager.elfinder.min.js', ['block' => true]); ?>
<?php $this->jQuery->theme(['block' => true]); ?>
<?php $this->jQuery->ui(['block' => true]); ?>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        var beeper = $(document.createElement('audio')).hide().appendTo('body')[0];

        $('div.elfinder').elfinder({
            url : '<?= $this->Url->build(['plugin' => 'MediaManager', 'controller' => 'explorer', 'action' => 'connector', 'prefix' => 'admin']); ?>',
            dateFormat: '<?= __d('media_manager', 'M d, Y h:i A'); ?>',
            fancyDateFormat: '<?= __d('media_manager', '$1 H:m:i'); ?>',
            lang: 'en',
            cookie : {
                expires: 30,
                domain: '',
                path: '/',
                secure: false,
            },
        })
        .elfinder('instance')
        .bind('rm', function(e) {
            e.stopPropagation();
            var play  = beeper.canPlayType && beeper.canPlayType('audio/wav; codecs="1"');
            play && play != '' && play != 'no' && $(beeper).html('<source src="<?= $this->Url->build('/MediaManager/sounds/rm.wav'); ?>" type="audio/wav">')[0].play()
        });
    });
</script>