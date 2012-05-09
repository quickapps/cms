<h3>About</h3>
<p>
	The Field module allows custom data fields to be defined for <em>entity</em> types
	(entities include content items, comments, user accounts or any <b>Model</b> in general).
	The Field module takes care of storing, loading, editing, and rendering field data.
	Most users will not interact with the Field module directly, but will instead use a <em>Field GUI</em>.
	Module developers can use the Field API to make new entity types "fieldable" and thus allow fields to be attached to them.
</p>

<h3>Uses</h3>
<dl>
	<dt>Enabling field types</dt>
	<dd>
		The Field module provides the infrastructure for fields and field attachment;
		Field types and inputs are provided by additional modules.
		QuickApps CMS includes the following field type modules:
		Date, File, List, Text and Terms.
		Additional fields may be provided by other modules.
	</dd>

	<dt>Managing field data storage</dt>
	<dd>
		Developers of field modules can either use the default <em>QuickApps storage table</em> to store data for their fields,
		or a contributed or custom storage system developed using <em>QuickApps Field API</em>.
	</dd>
</dl>