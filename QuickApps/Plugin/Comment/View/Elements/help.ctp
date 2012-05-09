<h3>About</h3>
<p>
	The Comment module allows users to comment on site content, set commenting defaults and permissions, and moderate comments.
</p>

<h3>Uses</h3>
<dl>
	<dt>Default and custom settings</dt>
	<dd>
		Each <a href='<?php echo $this->Html->url('/admin/node/types'); ?>'>content type</a> can have its own default comment settings configured as:
		<em>Open</em> to allow new comments, <em>Hidden</em> to hide existing comments and prevent new comments, or <em>Closed</em> but prevent new comments and do not show existing ones.
		These defaults will apply to all new content created (changes to the settings on existing content must be done manually).
		Other comment settings can also be customized per content type, and can be overridden for any given item of content.
	</dd>

	<dt>Comment approval</dt>
	<dd>
		All comments are placed in the <a href='<?php echo $this->Html->url('/admin/comment/unpublished/'); ?>'>Unapproved comments</a> queue,
		until a user who has the proper permissions will publishes or deletes them.
		Published comments can be bulk managed on the <a href='<?php echo $this->Html->url('/admin/comment/published/'); ?>'>Published comments</a> administration page.
	</dd>
</dl>