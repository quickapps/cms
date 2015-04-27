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
<!DOCTYPE html>
<html lang="<?php echo language('code'); ?>">
    <head>
        <?php
            echo $this->Html->head([
                'bootstrap' => true,
                'append' => [
                    $this->Html->css('font-awesome.min.css'),
                    $this->Html->css('back-bootstrap.css'),
                    $this->Html->css('metisMenu.min.css'),
                    $this->Html->css('sb-admin-2.css'),
                    $this->Html->script('sb-admin-2.js'),
                    $this->Html->script('metisMenu.min.js'),
                ],
            ]);
        ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>

    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <?php echo $this->Html->link('QuickApps CMS', '/', ['class' => 'navbar-brand']); ?>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php echo $this->Html->image(user()->avatar(['s' => 20])); ?>
                        <?php echo user()->username; ?>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><?php echo $this->Html->link(__d('backend_theme', 'My account'), ['plugin' => 'User', 'controller' => 'gateway', 'action' => 'me', 'prefix' => false]); ?></li>
                        <li><?php echo $this->Html->link(__d('backend_theme', 'Visit website'), '/', ['target' => '_blank']); ?></li>
                        <li class="divider"></li>
                        <li><?php echo $this->Html->link(__d('backend_theme', 'Sign out'), '/logout'); ?></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <?php echo $this->region('main-menu')->render(); ?>
            </div>
        </div>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $this->fetch('title'); ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <?php echo $this->Breadcrumb->renderIfNotEmpty(); ?>
                    <?php echo $this->Flash->render(); ?>
                    <?php echo $this->fetch('content'); ?>
                </div>
            </div>
        </div>
    </body>
</html>