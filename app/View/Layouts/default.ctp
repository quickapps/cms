<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo Configure::read('Variable.language.code'); ?>" version="XHTML+RDFa 1.0" dir="<?php echo Configure::read('Variable.language.direction'); ?>">
<head>
    <title><?php __t('Error'); ?></title>
    <?php echo $this->Html->css('reset.css'); ?>
    <?php echo $this->Html->css('error.css'); ?>
</head>

<body>
    <div id="error-container">
        <?php echo $this->Layout->content(); ?>
    </div>
</body>
</html>