<h3>About</h3>
<p>
	The User module allows users to register, log in, and log out.
	It also allows users with proper permissions to manage user roles (used to classify users) and permissions associated with those roles.
</p>

<h3>Uses</h3>
<dl>
	<dt>User roles and permissions</dt>
	<dd>
		Roles are used to group and classify users; each user can be assigned one or more roles.
		By default there are three roles: anonymous user (users that are not logged in), authenticated user (users that are registered and logged in) and administrator (which has unrestricted access to whole the system).
		After creating roles, you can set permissions for each role on the <a href="<?php echo $this->Html->url('/admin/user/permissions/'); ?>">Permissions page</a>.
		Granting a permission allows users who have been assigned a particular role to perform an action on the site, such as editing or creating content, administering settings for a particular module, or using a particular function of the site (such as search).
	</dd>
</dl>