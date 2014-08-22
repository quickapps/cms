<h3>About</h3>
<p>
	The Taxonomy plugin allows you to classify the content of your website.
	To classify content, you define <em>vocabularies</em> that contain related <em>terms</em>,
	and then assign the vocabularies to content types.
</p>

<h3>Uses</h3>
<dl>
	<dt>Creating vocabularies</dt>
	<dd>
		Users with sufficient <?php echo $this->Html->link(__d('taxonomy', 'permissions'), ['plugin' => 'User', 'controller' => 'permissions']); ?>
		can create <em>vocabularies</em> and <em>terms</em> through the
		<?php echo $this->Html->link(__d('taxonomy', 'Taxonomy page'), ['plugin' => 'Taxonomy', 'controller' => 'vocabularies']); ?>.
		The page listing the terms provides an interface for controlling the order of the terms and sub-terms within a vocabulary,
		in a hierarchical fashion. A <em>controlled vocabulary</em> classifying music by genre with terms and sub-terms could look as follows:

		<ul>
			<li><em>vocabulary</em>: Music</li>
				<ul>
					<li><em>term</em>: Jazz</li>
					<ul>
						<li><em>sub-term</em>: Swing</li>
						<li><em>sub-term</em>: Fusion</li>
					</ul>
				</ul>

				<ul>
					<li><em>term</em>: Rock</li>
					<ul>
						<li><em>sub-term</em>: Country rock</li>
						<li><em>sub-term</em>: Hard rock</li>
					</ul>
				</ul>
		</ul>
	</dd>

	<dt>Assigning vocabularies to content types</dt>
	<dd>
		Before you can use a new vocabulary to classify your content, a new Taxonomy terms field must be added to a
		<?php echo $this->Html->link(__d('taxonomy', 'content type'), ['plugin' => 'Node', 'controller' => 'types']); ?> on its
		<em>fields</em> page. After choosing the terms field, on the subsequent <em>field settings</em> page you can
		choose the desired vocabulary, whether one or multiple terms can be chosen from the vocabulary, and other settings.
		The same vocabulary can be added to multiple content types by using the terms field.
	</dd>

	<dt>Classifying content</dt>
	<dd>
		After the vocabulary is assigned to the content type's Taxonomy field, you can start classifying content.
		The field with terms will appear on the content editing screen when you edit or
		<?php echo $this->Html->link(__d('taxonomy', 'add new content'), ['plugin' => 'Node', 'controller' => 'manage', 'action' => 'create']); ?>.
	</dd>

	<dt>Filtering contents by term</dt>
	<dd>
		Each taxonomy works in combination with "Node" and "Search" plugin in order to.
		For example, if the taxonomy term <em>Country Rock</em>
		has the slug <em>country-rock</em>, then you will find this list at the path <em>/find/term:country-rock</em>.
		The RSS feed will use the path <em>/rss/term:country-rock</em>.
	</dd>
</dl>