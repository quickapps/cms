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

<?php echo $this->Form->create('Database', ['class' => 'database-form form-vertical']); ?>
<fieldset>
    <legend><?php echo __d('installer', 'Database Connection'); ?></legend>
    <small><em><?php echo __d('installer', 'Enter connection data for your database. Note: your database must already exist before completing this step.'); ?></em></small>

    <hr />

    <div class="row">
        <div class="col-md-12">
            <?php echo $this->Flash->render(); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $this->Form->input('host', ['label' => __d('installer', 'Host'), 'value' => 'localhost', 'placeholder' => __d('installer', 'ex. localhost')]); ?>
                <em class="help-block"><?php echo __d('installer', 'ex. mysql.server.com or localhost'); ?></em>
            </div>

            <div class="form-group">
                <?php echo $this->Form->input('database', ['label' => __d('installer', 'Database Name'), 'placeholder' => __d('installer', 'ex. quickappscms_db')]); ?>
                <em class="help-block"><?php echo __d('installer', 'Database must already exist!'); ?></em>
            </div>

            <div class="form-group">
                <?php
                    echo $this->Form->input('driver', [
                        'options' => [
                            'Mysql' => 'MySQL',
                            'Postgres' => 'Postgres',
                            'Sqlite' => 'SQLite',
                            'Sqlserver' => 'SQL Server',
                        ],
                        'value' => 'Mysql',
                        'label' => __d('installer', 'Database Type'),
                        'class' => 'form-control'
                    ]);
                ?>
                <em class="help-block"><?php echo __d('installer', 'The type of database your QuickAppsCMS data will be stored in.'); ?></em>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <?php echo $this->Form->input('username', ['label' => __d('installer', 'Username')]); ?>
                <em class="help-block"><?php echo __d('installer', 'Username used to log into this database.'); ?></em>
            </div>

            <div class="form-group">
                <?php echo $this->Form->input('password', ['label' => __d('installer', 'Password')]); ?>
                <em class="help-block"><?php echo __d('installer', 'Password used to log into this database.'); ?></em>
            </div>
        </div>
    </div>

    <hr />

    <div class="row">
        <div class="col-md-12">
            <p><?php echo $this->Form->submit(__d('installer', 'Continue'), ['class' => 'submit-btn pull-right']); ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <small class="wait-msg pull-right" style="display:none;"><em><?php echo __d('installer', 'This might take a few minutes, please be patient'); ?></em></small>
        </div>
    </div>
</fieldset>
<?php echo $this->Form->end(); ?>

<script>
    $('form.database-form').on('submit', function () {
        $('.submit-btn').prop('disabled', true);
        $('.wait-msg').show();
    });
</script>
