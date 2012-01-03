<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo Configure::read('Variable.language.code'); ?>" version="XHTML+RDFa 1.0" dir="<?php echo Configure::read('Variable.language.direction'); ?>">
<head>
    <title><?php __t('Error'); ?></title>
    <?php echo $this->Html->css('/system/css/reset.css'); ?>
    <?php echo $this->Html->css('/system/css/error.css'); ?>
</head>

<body>
    <div id="error-container">
        <?php echo $content_for_layout; ?>
    </div>
</body>
</html>