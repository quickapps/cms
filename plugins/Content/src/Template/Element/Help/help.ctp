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
    The Content plugin manages the creation, editing, deletion, settings, and display of the main site content.
    Content items managed by the Content plugin are typically displayed as pages on your site, and include a title,
    some meta-data (author, creation time, content type, etc.), and optional fields containing text or other data
    (fields are managed by the <?php echo $this->Html->link('Field plugin', ['plugin' => 'System', 'controller' => 'help', 'action' => 'about', 'prefix' => 'admin', 'Field']); ?>).
</p>

<h2>Uses</h2>
<dl>
    <dt>Creating content</dt>
    <dd>
        When new content is created, the Content plugin records basic information about the content, including the author, date of creation, and the <a href="<?php echo $this->Url->build('/admin/content/types'); ?>">Content type</a>.
        It also manages the <em>publishing options</em>, which define whether or not the content is published, promoted to the front page of the site, and/or sticky at the top of content lists.
        Default settings can be configured for each <?php echo $this->Html->link('type of content', ['plugin' => 'Content', 'controller' => 'types', 'prefix' => 'admin']); ?> on your site.
    </dd>

    <dt>Creating custom content types</dt>
    <dd>
        The Content plugin gives users with the proper permission the ability to
        <?php echo $this->Html->link('create new content types', ['plugin' => 'Content', 'controller' => 'types', 'action' => 'add', 'prefix' => 'admin']); ?>
        in addition to the default ones already configured.
        Creating custom content types allows you the flexibility to add <?php echo $this->Html->link('fields', ['plugin' => 'System', 'controller' => 'help', 'action' => 'about', 'prefix' => 'admin', 'Field']); ?> and configure default
        settings that suit the differing needs of various site content.
    </dd>

    <dt>Administering content</dt>
    <dd>
        The <?php echo $this->Html->link('content administration page', ['plugin' => 'Content', 'controller' => 'manage', 'prefix' => 'admin']); ?> allows you to review and bulk manage your site content.
    </dd>
</dl>