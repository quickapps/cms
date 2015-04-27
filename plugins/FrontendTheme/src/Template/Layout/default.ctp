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
                'append' => $this->Html->css('front-bootstrap.css'),
            ]);
        ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>

    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only"><?php echo __d('frontend_theme', 'Toggle navigation'); ?></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <?php echo $this->Html->link('QuickAppsCMS', '/', ['class' => 'navbar-brand']); ?>
                </div>
                <div class="collapse navbar-collapse">
                    <?php echo $this->region('main-menu')->render(); ?>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <?php echo $this->Html->image(user()->avatar(['s' => 20])); ?>
                                <?php echo user()->username; ?>
                                <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <?php
                                    // merges two or more rendered menus (ULs) into a single one
                                    $menus = $this->region('sub-menu');
                                    $menus = preg_replace('/\<ul[^>]*\>/i', '<li class="divider"></li>', $menus);
                                    $menus = str_replace_once('<li class="divider"></li>', '', $menus);
                                    $menus = str_ireplace('</ul>', '', $menus);
                                    echo $menus;
                                ?>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php echo $this->Flash->render(); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <?php echo $this->fetch('content'); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $this->region('right-sidebar'); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <hr />
                    <p class="text-center">
                        <em><small><?php echo __d('frontend_theme', 'Powered by QuickAppsCMS v{0}', quickapps('version')); ?></small></em>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>