<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
?>

<h2>About</h2>
<p>
	The User plugin allows users to register, log in, and log out. It also allows
	users with proper permissions to manage user roles (used to classify users)
	and permissions associated with those roles.
</p>

<h2>Uses</h2>
<dl>
	<dt>User roles and permissions</dt>
	<dd>
		<p>
			Roles are used to group and classify users; each user can be assigned
			one or more roles.By default there are three roles: anonymous user (users
			that are not logged in), authenticated user (users that are registered
			and logged in) and administrator (which has unrestricted access to whole
			the system).
		</p>

		<p>
			After creating roles, you can set permissions for each role
			on the <?php echo $this->Html->link('Permissions page', ['plugin' => 'User', 'controller' => 'permissions', 'prefix' => 'admin']); ?>.
			Granting a permission allows users who have been assigned a particular
			role to perform an action on the site, such as editing or creating
			content, administering settings for a particular plugin, or using a particular
			function of the site (such as search).
		</p>
	</dd>
</dl>