<h3>About</h3>
<p>
	The Node plugin manages the creation, editing, deletion, settings, and display of the main site content.
	Content items managed by the Node plugin are typically displayed as pages on your site, and include a title,
	some meta-data (author, creation time, content type, etc.), and optional fields containing text or other data
	(fields are managed by the <?php echo $this->Html->link('Field plugin', ['plugin' => 'system', 'controller' => 'help', 'action' => 'about', 'prefix' => 'admin', 'Field']); ?>).
</p>

<h3>Uses</h3>
<dl>
	<dt>Creating content</dt>
	<dd>
		When new content is created, the Node plugin records basic information about the content, including the author, date of creation, and the <a href="<?php echo $this->Html->url('/admin/node/types'); ?>">Content type</a>.
		It also manages the <em>publishing options</em>, which define whether or not the content is published, promoted to the front page of the site, and/or sticky at the top of content lists.
		Default settings can be configured for each <?php echo $this->Html->link('type of content', ['plugin' => 'node', 'controller' => 'types', 'prefix' => 'admin']); ?> on your site.
	</dd>

	<dt>Creating custom content types</dt>
	<dd>
		The Node plugin gives users with the proper permission the ability to
		<?php echo $this->Html->link('create new content types', ['plugin' => 'node', 'controller' => 'types', 'action' => 'add', 'prefix' => 'admin']); ?>
		in addition to the default ones already configured.
		Creating custom content types allows you the flexibility to add <?php echo $this->Html->link('fields', ['plugin' => 'system', 'controller' => 'help', 'action' => 'about', 'prefix' => 'admin', 'Field']); ?> and configure default.
		settings that suit the differing needs of various site content.
	</dd>

	<dt>Administering content</dt>
	<dd>
		The <?php echo $this->Html->link('Content administration page', ['plugin' => 'node', 'controller' => 'manage', 'prefix' => 'admin']); ?> allows you to review and bulk manage your site content.
	</dd>
</dl>