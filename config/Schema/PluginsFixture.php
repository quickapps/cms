<?php
class PluginsFixture {

	public $fields = array (
  '_constraints' => 
  array (
    'primary' => 
    array (
      'type' => 'primary',
      'columns' => 
      array (
        0 => 'name',
      ),
      'length' => 
      array (
      ),
    ),
  ),
  'name' => 
  array (
    'type' => 'string',
    'length' => 80,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'package' => 
  array (
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => NULL,
    'comment' => 'composer package. e.g. user_name/plugin_name',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'settings' => 
  array (
    'type' => 'text',
    'length' => NULL,
    'null' => false,
    'default' => NULL,
    'comment' => 'serialized array of options',
    'precision' => NULL,
  ),
  'status' => 
  array (
    'type' => 'boolean',
    'length' => NULL,
    'null' => false,
    'default' => '0',
    'comment' => '',
    'precision' => NULL,
  ),
  'ordering' => 
  array (
    'type' => 'integer',
    'length' => 3,
    'unsigned' => false,
    'null' => false,
    'default' => '0',
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
);

	public $records = array (
  0 => 
  array (
    'name' => 'BackendTheme',
    'package' => 'quickapps-theme/backend-theme',
    'settings' => '',
    'status' => true,
    'ordering' => 0,
  ),
  1 => 
  array (
    'name' => 'Block',
    'package' => 'quickapps-plugin/block',
    'settings' => '',
    'status' => true,
    'ordering' => 1,
  ),
  2 => 
  array (
    'name' => 'Comment',
    'package' => 'quickapps-plugin/comment',
    'settings' => 'a:15:{s:12:"auto_approve";s:1:"0";s:15:"allow_anonymous";s:1:"1";s:14:"anonymous_name";s:1:"1";s:23:"anonymous_name_required";s:1:"1";s:15:"anonymous_email";s:1:"1";s:24:"anonymous_email_required";s:1:"1";s:13:"anonymous_web";s:1:"1";s:22:"anonymous_web_required";s:1:"0";s:15:"text_processing";s:5:"plain";s:8:"use_ayah";s:1:"1";s:18:"ayah_publisher_key";s:40:"a5613704624c0c213e3a51a3202dd22c1fc5f652";s:16:"ayah_scoring_key";s:40:"1bfe675e8061d1e83fc56090dbef62d4cc2e4912";s:11:"use_akismet";s:1:"0";s:11:"akismet_key";s:1:"s";s:14:"akismet_action";s:6:"delete";}',
    'status' => true,
    'ordering' => 2,
  ),
  3 => 
  array (
    'name' => 'Field',
    'package' => 'quickapps-plugin/field',
    'settings' => '',
    'status' => true,
    'ordering' => 3,
  ),
  4 => 
  array (
    'name' => 'FrontendTheme',
    'package' => 'quickapps-theme/frontend-theme',
    'settings' => '',
    'status' => true,
    'ordering' => 4,
  ),
  5 => 
  array (
    'name' => 'Installer',
    'package' => 'quickapps-plugin/installer',
    'settings' => '',
    'status' => true,
    'ordering' => 5,
  ), 
  6 =>
  array (
    'name' => 'Jquery',
    'package' => 'quickapps-plugin/jquery',
    'settings' => '',
    'status' => true,
    'ordering' => 6,
  ),
  7 => 
  array (
    'name' => 'Locale',
    'package' => 'quickapps-plugin/locale',
    'settings' => '',
    'status' => true,
    'ordering' => 7,
  ),
  8 => 
  array (
    'name' => 'Menu',
    'package' => 'quickapps-plugin/menu',
    'settings' => '',
    'status' => true,
    'ordering' => 8,
  ),
  9 => 
  array (
    'name' => 'Node',
    'package' => 'quickapps-plugin/node',
    'settings' => '',
    'status' => true,
    'ordering' => 9,
  ),
  10 => 
  array (
    'name' => 'Search',
    'package' => 'quickapps-plugin/search',
    'settings' => '',
    'status' => true,
    'ordering' => 10,
  ),
  11 => 
  array (
    'name' => 'System',
    'package' => 'quickapps-plugin/system',
    'settings' => '',
    'status' => true,
    'ordering' => 11,
  ),
  12 => 
  array (
    'name' => 'Taxonomy',
    'package' => 'quickapps-plugin/taxonomy',
    'settings' => '',
    'status' => true,
    'ordering' => 12,
  ),
  13 => 
  array (
    'name' => 'User',
    'package' => 'quickapps-plugin/user',
    'settings' => 'a:15:{s:23:"message_welcome_subject";s:46:"Account details for [user:name] at [site:name]";s:20:"message_welcome_body";s:450:"[user:name],

Thank you for registering at [site:name]. You may now log in by clicking this link or copying and pasting it to your browser:

[user:one-time-login-url]

This link can only be used once to log in and will lead you to a page where you can set your password.

After setting your password, you will be able to log in at [site:login-url] in the future using:

username: [user:name]
password: Your password

--  [site:name] team";s:18:"message_activation";s:1:"1";s:26:"message_activation_subject";s:57:"Account details for [user:name] at [site:name] (approved)";s:23:"message_activation_body";s:461:"[user:name],

Your account at [site:name] has been activated.

You may now log in by clicking this link or copying and pasting it into your browser:

[user:one-time-login-url]

This link can only be used once to log in and will lead you to a page where you can set your password.

After setting your password, you will be able to log in at [site:login-url] in the future using:

username: [user:name]
password: Your password

--  [site:name] team";s:15:"message_blocked";s:1:"1";s:23:"message_blocked_subject";s:56:"Account details for [user:name] at [site:name] (blocked)";s:20:"message_blocked_body";s:85:"[user:name],

Your account on [site:name] has been blocked.

--  [site:name] team";s:33:"message_password_recovery_subject";s:61:"Password recovery instructions for [user:name] at [site:name]";s:30:"message_password_recovery_body";s:340:"[user:name],

A request to reset the password for your account has been made at [site:name].

You may now log in by clicking this link or copying and pasting it to your browser:

[user:one-time-login-url]

This link can only be used once to log in and will lead you to a page where you can set your password.

--  [site:name] team";s:30:"message_cancel_request_subject";s:59:"Account cancellation request for [user:name] at [site:name]";s:27:"message_cancel_request_body";s:300:"[user:name],

A request to cancel your account has been made at [site:name].

You may now cancel your account on [site:url] by clicking this link or copying and pasting it into your browser:

[user:cancel-url]

NOTE: The cancellation of your account is not reversible.

--  [site:name] team";s:16:"message_canceled";s:1:"1";s:24:"message_canceled_subject";s:57:"Account details for [user:name] at [site:name] (canceled)";s:21:"message_canceled_body";s:86:"[user:name],

Your account on [site:name] has been canceled.

--  [site:name] team";}',
    'status' => true,
    'ordering' => 13,
  ),
  14 => 
  array (
    'name' => 'Wysiwyg',
    'package' => 'quickapps-plugin/wysiwyg',
    'settings' => '',
    'status' => true,
    'ordering' => 14,
  ),
);

}

