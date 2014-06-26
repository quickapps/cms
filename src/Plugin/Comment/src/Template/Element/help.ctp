<h2>About</h2>
<p>
	The Comment plugin allows users to comment on site content, set commenting defaults and permissions, and moderate comments.
</p>

<h2>Uses</h2>
<dl>
	<dt>Default and custom settings</dt>
	<dd>
		Each <?php echo $this->Html->link('content type', ['plugin' => 'node', 'controller' => 'types', 'prefix' => 'admin']); ?> can have its own default comment settings configured as:
		<em>Open</em> to allow new comments and show existing ones, <em>Closed</em> to prevent new comments and do not show existing ones, or <em>Read only</em> to show existing comments but prevent new ones.
		These defaults will apply to all new content created (changes to the settings on existing content must be done manually).
		Other comment settings can also be customized per content type, and can be overridden for any given item of content.
	</dd>

	<dt>Comment approval</dt>
	<dd>
		All comments are placed in the <?php echo $this->Html->link('Unapproved comments', ['plugin' => 'comment', 'controller' => 'manage', 'action' => 'unpublished', 'prefix' => 'admin']); ?> queue,
		until a user who has the proper permissions will publishes or deletes them.
		Published comments can be bulk managed on the <?php echo $this->Html->link('Published comments', ['plugin' => 'comment', 'controller' => 'manage', 'action' => 'published', 'prefix' => 'admin']); ?> administration page.
	</dd>
</dl>