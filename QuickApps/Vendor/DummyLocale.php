<?php
/**
 * Dummy Locale file.
 *
 * This file is used by i18n shell to parse and
 * add translations that are not in the source code.
 *
 * @author Christopher Castro <chris@quickapps.es>
 * @package QuickApps.Vendor
 * @link http://www.quickappscms.org 
 */

	// Installation
	echo __t('Welcome to QuickApps CMS');
	echo __t('Click here to install in English');

	// Management menu
	echo __t("Dashboard");
	echo __t("Structure");
	echo __t("Content");
	echo __t("Appearance");
	echo __t("Modules");
	echo __t("Users");
	echo __t("Configuration");
	echo __t("Help");

	// Miscellaneous
	echo __t("Hidden");
	echo __t("Languages");
	echo __t("View Site");
	echo __t("Log out");
	echo __t("Logout");
	echo __t("My account");
	echo __t("« Previous ");
	echo __t(" Next »");
	echo __t(" Last");
	echo __t("First ");

	// Content type descriptions
	echo __t("Configure what block content appears in your site's sidebars and other regions.");
	echo __t("Manage content types.");
	echo __t("Add new menus to your site, edit existing menus, and rename and reorganize menu links.");
	echo __t("Manage tagging, categorization, and classification of your content.");

	// Module descriptions
	echo __t("Controls the visual building blocks a page is constructed with. Blocks are boxes of content rendered into an area, or region, of a web page.");
	echo __t("Allows users to comment on and discuss published content.");
	echo __t("Field API to add fields to entities like nodes and users.");
	echo __t("Adds language handling functionality and enables the translation of the user interface to languages other than English");
	echo __t("Allows administrators to customize the site navigation menu.");
	echo __t("Allows content to be submitted to the site and displayed on pages.");
	echo __t("System Kernel.");
	echo __t("Enables the categorization of content.");
	echo __t("Manages the user registration and login system.");

	// Theme descriptions
	echo __t("Default frontend theme for QuickApps");
	echo __t("Default backend theme for QuickApps");
	echo __t("Theme in use");

	// Basic content-type titles
	echo __t("Article");
	echo __t("Basic page");

	// Basic content-type descriptions
	echo __t("Use articles for time-sensitive content like news, press releases or blog posts.");
	echo __t("Use basic pages for your static content, such as an 'About us' page.");

	// Field descriptions
	echo __t("Date picker field.");
	echo __t("Define file uploader.");
	echo __t("Defines list field types. Use with Options to create selection lists.");
	echo __t("Defines simple text field types.");
	echo __t("Defines terms list.");

	// Default theme `Configurable Style` titles
	echo __t("Main font");
	echo __t("Text");
	echo __t("Footer");
	echo __t("Links");
	echo __t("Header top");
	echo __t("Header bottom");
	echo __t("Main background");

	// Admin theme `Configurable Style` titles
	echo __t("Body");
	echo __t("Main font");
	echo __t("Text");
	echo __t("Background");
	echo __t("Links");
	echo __t("Links hover");
	echo __t("Header");
	echo __t("Main menu background");
	echo __t("Main menu text");
	echo __t("Branding background");

	// Menus descriptions
	echo __t("The <em>Main</em> menu is used on many sites to show the major sections of the site, often in a top navigation bar.");
	echo __t("The <em>Management</em> menu contains links for administrative tasks.");
	echo __t("The <em>Navigation</em> menu contains links intended for site visitors. Links are added to the <em>Navigation</em> menu automatically by some modules.");
	echo __t("The <em>User</em> menu contains links related to the user's account, as well as the 'Log out' link.");

	// Site offline message
	echo __t("We sincerely apologize for the inconvenience.<br/>Our site is currently undergoing scheduled maintenance and upgrades, but will return shortly.<br/>Thanks you for your patience.");

	// Permissions yaml: Block
	echo __t('Administer blocks');
	echo __t('Grant full access to administer blocks');

	// Permissions yaml: Comment
	echo __t('Administer comments');
	echo __t('Administer comments and comment settings');

	// Permissions yaml: Field
	echo __t('Delete fields');
	echo __t('Allow user to delete CCK fields');

	// Permissions yaml: Field.FieldFile
	echo __t('Upload & Delete files');
	echo __t('Allow user to upload new files and delete existing ones.');

	// Permissions yaml: Field.FieldImage
	echo __t('Upload & Delete images');
	echo __t('Allow user to upload new images and delete existing ones.');
	echo __t('Preview uploaded images');
	echo __t('Allow user to preview images.');

	// Permissions yaml: Locale
	echo __t('Administer languages');
	echo __t('Allow user to add, edit and delete languages');
	echo __t('Translate interface texts');
	echo __t('Allow user to administer translatable entries');
	echo __t('Administer language packages');
	echo __t('Allow user to install and modify existing language packages (.po)');

	// Permissions yaml: Menu
	echo __t('Administer menus');
	echo __t('Allow user to administer menus and menu items');

	// Permissions yaml: Node
	echo __t('Administer content types');
	echo __t('Warning: Give to trusted roles only; this permission has security implications.');
	echo __t('Administer content');
	echo __t('Warning: Give to trusted roles only; this permission has security implications.');
	echo __t("Access the site's front page");
	echo __t('View published content');
	echo __t('Search content and RSS feeds');

	// Permissions yaml: System
	echo __t('Access to dashboard');
	echo __t('Administer site configuration');
	echo __t('Warning: Give to trusted roles only; this permission has security implications.');
	echo __t('Structure Menu');
	echo __t('Allow user to access the `structure` menu item.');
	echo __t('Help Topics');
	echo __t('Allow user to consult help topics.');
	echo __t('Administer modules');
	echo __t('Administer themes');

	// Permissions yaml: Taxonomy
	echo __t('Administer vocabularies and terms');

	// Permissions yaml: Taxonomy.TaxonomyTerms
	echo __t('Keywords suggestions handler');
	echo __t('Suggest keyword');
	echo __t('Allow user to use the `Autocomplete term`');

	// Permissions yaml: User
	echo __t('User Basics');
	echo __t('Backend main module access');
	echo __t('Grant access to (/admin/user)');
	echo __t('Frontpage login form');
	echo __t('Backend login');
	echo __t('Backend login form');
	echo __t('Frontpage logout');
	echo __t('After logout user gets redirected to site frontpage');
	echo __t('Backend logout');
	echo __t('After logout user gets redirected to backend login screen');
	echo __t('Registration page');
	echo __t('Activate account');
	echo __t('Password recovery');
	echo __t('View users profile');
	echo __t('My account section');
	echo __t('Administer permissions');
	echo __t('Warning: Give to trusted roles only; this permission has security implications.');
	echo __t('Administer users');
	echo __t('Warning: Give to trusted roles only; this permission has security implications.');
	echo __t('Administer user roles');
	echo __t('Warning: Give to trusted roles only; this permission has security implications.');
	echo __t('Administer user CCK fields');
	echo __t('Warning: Give to trusted roles only; this permission has security implications.');

	// display modes
	echo __t('Default');
	echo __t('Full');
	echo __t('List');
	echo __t('RSS');
	echo __t('Print');
	echo __t('User profile');

	// FieldImage
	echo __t('Thumbnail');
	echo __t('Medium');
	echo __t('Large');