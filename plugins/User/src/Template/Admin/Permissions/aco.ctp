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

<div class="row">
    <div class="col-md-12">
        <div class="well well-sm">
            <p><?php echo implode($path, ' / '); ?></p>

            <?php echo $this->Form->create($aco, ['onsubmit' => 'return false;', 'id' => 'permissions-form']); ?>
                <?php echo $this->Form->input('roles._ids', ['type' => 'select', 'options' => $roles, 'multiple' => 'checkbox']); ?>
                <em class="help-block">(<?php echo __d('user', 'Administrators have full access to the entire platform. No restrictions can be applied to them.'); ?>)</em>
                <a class="btn btn-success has-spinner">
                    <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
                    <?php echo __d('user', 'Save Permissions'); ?>
                </a>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<script>
    $cb = $('#roles-ids-<?php echo ROLE_ID_ADMINISTRATOR; ?>');
    $cb.hide();
    $cb.next('label').html('<s>' + $cb.next('label').html()  + '</s>');
</script>

