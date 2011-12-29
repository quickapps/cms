<?php if ($this->params['plugin'] == 'user' && $this->params['controller'] == 'user'): ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo Configure::read('Variable.language.code'); ?>" version="XHTML+RDFa 1.0" dir="<?php echo Configure::read('Variable.language.direction'); ?>">
    <head>
        <title><?php echo $this->Layout->title(); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <?php echo $this->Html->css('reset.css'); ?>
        <?php echo $this->Html->css('login.css'); ?>
        <?php echo $this->Layout->header(); ?>
        <?php echo $this->Html->script('jquery.js'); ?>
        <?php echo $this->Html->script('quickapps.js'); ?>
        <?php echo $this->Html->script('login.js'); ?>
    </head>

    <body>
        <div id="content" class="clearfix">
            <div class="left-block">
                <a href="http://cms.quickapps.es/" target="_blank"><?php echo $this->Html->image('logo.png', array('border' => 0)); ?></a>
            </div>

            <div class="right-block">
                <div class="container">
                    <div class="sessionFlash">
                    <?php if ($sessionFlash = $this->Layout->sessionFlash()): ?>
                        <?php echo $sessionFlash; ?>
                    <?php endif; ?>
                    </div>

                    <?php echo $this->Layout->content(); ?>
                </div>
            </div>
        </div>
    </body>
</html>
<?php else: ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title><?php echo $this->Layout->title(); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <?php echo $this->Layout->meta();?>
        <?php echo $this->Layout->stylesheets();?>
        <?php echo $this->Layout->javascripts();?>
        <?php echo $this->Layout->header();?>
    </head>
    <body>
        <div id="wrapper">
            <div id="toolbar-menu" class="clearfix" >
                <?php echo $this->Layout->blocks('management-menu'); ?>
                <div id="right-btns">
                    <?php echo $this->Html->link(__d('theme_admin_default', 'Log out'), '/user/logout'); ?>
                    <?php echo $this->Html->link(__d('theme_admin_default', 'View site'), '/',  array('target' => '_blank')); ?>
                </div>
            </div>

            <div id="branding" class="clearfix">
                <span class="clearfix"><?php echo $this->Layout->breadCrumb(); ?></span>
                <h1 class="page-title">
                    <em><?php echo $this->Layout->title();?></em>
                </h1>
            </div>

            <div id="page">
                <?php if (!$this->Layout->emptyRegion('toolbar')): ?>
                <div class="toolbar">
                    <?php echo $this->Layout->blocks('toolbar'); ?>
                </div>
                <?php endif; ?>

                <?php if (!$this->Layout->emptyRegion('help')): ?>
                <div class="help">
                    <?php echo $this->Layout->blocks('help'); ?>
                </div>
                <?php endif; ?>

                <?php if ($sessionFlash = $this->Layout->sessionFlash()): ?>
                <div id="sessionFlash">
                    <?php echo $sessionFlash; ?>
                </div>
                <?php endif; ?>

                <div id="content" class="clearfix">
                    <?php echo $this->Layout->content(); ?>
                </div>
            </div>

            <div id="footer">
                <?php echo $this->Layout->blocks('footer'); ?>
            </div>

        </div>
        <?php echo $this->Layout->footer(); ?>
    </body>
</html>
<?php endif; ?>

<?php
    if (Configure::read('debug') > 0) {
        echo "<!-- " . round(microtime(true) - TIME_START, 4) . "s -->";
    }
?>