<h3>About</h3>
<p>
	The Locale module allows your QuickApps site to be presented in languages other than the default English, and to be multilingual.
	The Locale module works by maintaining a database of translations, and examining text as it is about to be displayed. When a translation of the text is available in the language to be displayed, the translation is displayed rather than the original text.
	When a translation is unavailable, the original text is displayed.
</p>

<h3>Uses</h3>
<dl>
	<dt>Translating interface text</dt>
	<dd>
		Translations of text in the QuickApps interface may be provided by:
		<ul style="list-style:circle;">
			<li>
				Translating within your site, using the Locale module's integrated
				<a href="<?php echo $this->Html->url('/admin/locale/translations'); ?>">translation interface</a>.

				<p>
					<dt>Fuzzy Entries</dt>
					<dd>
						Each time QuickApps CMS fails when it tries to translate a text of your site, the text is marked as <b>fuzzy</b>.
						Fuzzy entries are suggested translatable entries. You can export and import the list of entries as .pot packages. 
					</dd>
				</p>
			</li>

			<li>
				<a href="<?php echo $this->Html->url('/admin/locale/packages'); ?>">Importing</a> files from a set of existing translations,
				known as a translation package files in the Gettext Portable Object (<em>.po</em>) format.
			</li>

			<li>
				If an existing translation package does not meet your needs, the Gettext Portable Object (<em>.po</em>) files
				within a package may be modified, or new <em>.po</em> files may be created, using a desktop Gettext editor.
			</li>
		</ul>
	</dd>

	<dt>Configuring a multilingual site</dt>
	<dd>
		Language negotiation allows your site to automatically change language based on path used for each request.
		Users may (optionally) select their preferred language on their <em>My account</em> page, and your site can be configured to
		honor a web browser's preferred language settings.
	</dd>
</dl>