<?php
/**
 * Default template for HTML e-mails.
 *
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
	<head>
		<title><?php echo $this->Layout->title(); ?></title>
	</head>

	<body>
		<?php echo $this->Layout->hooktags($this->Layout->content()); ?>
		<p><?php echo __t('This email was sent using <a href="http://www.quickappscms.org/">QuickApps CMS v%s</a>', Configure::read('Variable.qa_version')); ?></p>
	</body>
</html>