<?php if ($this->params['plugin'] == 'user' && $this->params['controller'] == 'user'): ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo Configure::read('Variable.language.code'); ?>" version="XHTML+RDFa 1.0" dir="<?php echo Configure::read('Variable.language.direction'); ?>">
    <head>
        <title><?php echo $this->Layout->title(); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <?php echo $this->Html->css('reset.css'); ?>
        <style>
            html { background:#282828; color:#fff; font-family:arial; }
            .text input, .password input { border:1px solid #4c4c4c; background:#3B3B3B; padding:8px; color:#D8D8D8; margin-bottom:8px; }
            .submit input { margin-top:20px; float:left; background:#466C19; color:#fff; border:1px solid #6A8946; padding:5px;
                border-radius: 5px;
                -ms-border-radius: 5px;
                -moz-border-radius: 5px;
                -webkit-border-radius: 5px;
                -khtml-border-radius: 5px;
                cursor:pointer;
            }
            label { float:left; display:block; width:100px;}
            form#UserAdminLoginForm { display:block; width:400px; margin:100px auto;}
            .password { margin-top:15px;}
            h2 { display:none; }
            #sessionFlash { display:none; }
        </style>
        <?php echo $this->Layout->header(); ?>
    </head>
    <body>
        <?php if ($sessionFlash = $this->Layout->sessionFlash()): ?>
        <div id="sessionFlash">
            <?php echo $sessionFlash; ?>
        </div>
        <?php endif; ?>

        <div id="content" class="clearfix">
            <?php echo $this->Layout->content(); ?>
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
                    <?php echo $this->Html->link(__d('theme_admin_default', 'Log outt'), '/user/logout'); ?>
                    <?php echo $this->Html->link(__d('ThemeAdminDefault', 'View site'), '/',  array('target' => '_blank')); ?>
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