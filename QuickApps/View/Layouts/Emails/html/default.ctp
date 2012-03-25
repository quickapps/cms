<?php
/**
 * Default Layout for HTML emails
 *
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<title><?php echo $this->Layout->title(); ?></title>
</head>
<body>
	<?php echo $this->Layout->content() ;?>

	<p>This email was sent using <a href="http://www.quickappscms.org">QuickApps CMS v<?php echo Configure::read('Variable.qa_version'); ?></a></p>
</body>
</html>