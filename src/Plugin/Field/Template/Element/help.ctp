<h3>About</h3>
<p>
	The Field plugin allows custom data fields to be defined for <em>entity</em> types
	(entities include content items, comments, user accounts or any <b>table entity</b> in general).
	The Field plugin takes care of storing, loading, editing, and rendering field data.
	Most users will not interact with the Field plugin directly, but will instead use a <em>Field UI</em>.
	Plugin developers can use the Field API to make new entities "fieldable" and thus allow fields to be attached to them.
</p>

<h3>Uses</h3>
<dl>
	<dt>Enabling field types</dt>
	<dd>
		The Field plugin provides the infrastructure for fields and field attachment;
		QuickApps CMS includes the following field plugins:
		Date, File, List, Text and Terms.
		Additional fields may be provided by other plugins.
	</dd>

	<dt>Managing field data storage</dt>
	<dd>
		Developers of field plugins can either use the default <em>QuickApps storage table</em> to store data for their fields,
		or a contributed or custom storage system developed using <em>QuickApps Field API</em>.
	</dd>
</dl>