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

/**
 * This view elements is capable of handling multiple view-modes.
 *
 * If you want to create a separated view element for each view mode
 * take a look to `ContentHook::renderContent()` method.
 */
?>

<article class="content content-<?= $content->content_type_slug; ?> viewmode-<?= $this->viewMode(); ?>">
    <header>
        <?php if ($this->viewMode() === 'full'): ?>
            <h1><?= $this->shortcodes($content->title); ?></h1>
        <?php else: ?>
            <h2><?= $this->Html->link($this->shortcodes($content->title), $content->url); ?></h2>
        <?php endif; ?>
        <p><?= __d('content', 'Published'); ?>: <time pubdate="pubdate"><?= $content->created->timeAgoInWords(); ?></time></p>
    </header>

    <?php if (!empty($content->_fields)): ?>
        <?php foreach ($content->_fields->sortByViewMode($this->viewMode()) as $field): ?>
            <?= $this->render($field); ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($this->viewMode() === 'full'): ?>
        <?= $this->Comment->render($content); ?>
    <?php endif; ?>
</article>