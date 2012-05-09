<h3>About</h3>
<p>
	The Node module manages the creation, editing, deletion, settings, and display of the main site content.
	Content items managed by the Node module are typically displayed as pages on your site, and include a title,
	some meta-data (author, creation time, content type, etc.), and optional fields containing text or other data
	(fields are managed by the <a href="<?php echo $this->Html->url('/admin/system/help/module/Field'); ?>">Field module</a>).
</p>

<h3>Uses</h3>
<dl>
	<dt>Creating content</dt>
	<dd>
		When new content is created, the Node module records basic information about the content, including the author, date of creation, and the <a href="<?php echo $this->Html->url('/admin/node/types'); ?>">Content type</a>.
		It also manages the <em>publishing options</em>, which define whether or not the content is published, promoted to the front page of the site, and/or sticky at the top of content lists.
		Default settings can be configured for each <a href="<?php echo $this->Html->url('/admin/node/types'); ?>">type of content</a> on your site.
	</dd>

	<dt>Creating custom content types</dt>
	<dd>
		The Node module gives users with the proper permission the ability to
		<a href="<?php echo $this->Html->url('/admin/node/types/add/'); ?>">create new content types</a>
		in addition to the default ones already configured.
		Creating custom content types allows you the flexibility to add <a href="<?php echo $this->Html->url('/admin/system/help/module/Field'); ?>">fields</a> and configure default settings that suit the differing needs of various site content.
	</dd>

	<dt>Administering content</dt>
	<dd>
		The <a href="<?php echo $this->Html->url('/admin/node/contents'); ?>">Content administration page</a> allows you to review and bulk manage your site content.
	</dd>
</dl>