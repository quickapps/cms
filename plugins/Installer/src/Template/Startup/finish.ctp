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
        <?= $this->Flash->render(); ?>

        <h1><?= __d('installer', 'Thanks!'); ?></h1>
        <p><?= __d('installer', "Thanks for choosing QuickApps CMS!, you can login into the administration section or go to your site's home page"); ?></p>
        <h2><?= __d('installer', 'Links of Interest'); ?></h2>

        <ul>
            <li><?= $this->Html->link(__d('installer', 'Official Site'), 'http://www.quickappscms.org'); ?></li>
            <li><?= $this->Html->link(__d('installer', 'GitHub'), 'https://github.com/QuickAppsCMS/QuickApps-CMS'); ?></li>
            <li><?= $this->Html->link(__d('installer', 'API 2.0'), 'http://api.quickappscms.org/2.0'); ?></li>
            <li><?= $this->Html->link(__d('installer', 'Issue Tracker'), 'https://github.com/QuickAppsCMS/QuickApps-CMS/issues'); ?></li>
            <li><?= $this->Html->link(__d('installer', 'Google Group'), 'https://groups.google.com/group/quickapps-cms'); ?></li>
        </ul>

        <hr />

        <p>
            <?= $this->Form->create(null, ['class' => 'pull-right']); ?>
                <?= $this->Form->submit(__d('installer', 'Visit my website'), ['name' => 'home', 'class' => 'btn btn-success']); ?>
                <?= $this->Form->submit(__d('installer', 'Administer my website'), ['name' => 'admin', 'class' => 'btn btn-primary']); ?>
            <?= $this->Form->end(); ?>
        </p>
    </div>
</div>