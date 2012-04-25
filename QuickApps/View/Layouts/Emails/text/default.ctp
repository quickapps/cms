<?php
/**
 * Default template for plain text e-mails.
 *
 */
?>

<?php echo $this->Layout->hooktags($this->Layout->content()); ?>
<?php echo __t('This email was sent using QuickApps CMS v%s [http://www.quickappscms.org/]', Configure::read('Variable.qa_version')); ?>