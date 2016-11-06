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

<h2><?= $block->title; ?></h2>
<ul class="qa-recent-content">
    <?php foreach ($contents as $content): ?>
        <li>
            <?= $this->Html->link($content->title, $content->url, ['title' => $content->description]); ?>
        </li>
    <?php endforeach; ?>
</ul>
