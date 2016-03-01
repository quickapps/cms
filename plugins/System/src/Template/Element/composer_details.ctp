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

<p class="details">
    <ul>
        <?php if (!empty($composer['homepage'])): ?>
        <li><strong><?= __d('system', 'Homepage'); ?>:</strong> <?= $this->Html->link($composer['homepage'], $composer['homepage'], ['target' => '_blank']); ?></li>
        <?php endif; ?>

        <?php if (!empty($composer['support']['issues'])): ?>
        <li><strong><?= __d('system', 'Issues'); ?>:</strong> <?= $this->Html->link($composer['support']['issues'], $composer['support']['issues']); ?></li>
        <?php endif; ?>

        <?php if (!empty($composer['support']['forum'])): ?>
        <li><strong><?= __d('system', 'Forum'); ?>:</strong> <?= $this->Html->link($composer['support']['forum'], $composer['support']['forum']); ?></li>
        <?php endif; ?>

        <?php if (!empty($composer['support']['wiki'])): ?>
        <li><strong><?= __d('system', 'Wiki'); ?>:</strong> <?= $this->Html->link($composer['support']['wiki'], $composer['support']['wiki']); ?></li>
        <?php endif; ?>

        <?php if (!empty($composer['support']['irc'])): ?>
        <li><strong><?= __d('system', 'IRC'); ?>:</strong> <?= $this->Html->link($composer['support']['irc'], $composer['support']['irc']); ?></li>
        <?php endif; ?>

        <?php if (!empty($composer['support']['source'])): ?>
        <li><strong><?= __d('system', 'Source'); ?>:</strong> <?= $this->Html->link($composer['support']['source'], $composer['support']['source']); ?></li>
        <?php endif; ?>

        <?php if (!empty($composer['authors'])): ?>
        <li>
            <strong><?= __d('system', 'Authors'); ?>:</strong>

            <ul>
                <?php foreach ($composer['authors'] as $author): ?>
                <li>
                    <?php if (!empty($author['homepage'])): ?>
                        <?= $this->Html->link($author['name'], $author['homepage']); ?>
                    <?php else: ?>
                        <?= $author['name']; ?>
                    <?php endif; ?>

                    <?php if (!empty($author['email'])): ?>
                        &lt;<?= $this->Html->link($author['email'], "mailto:{$author['email']}"); ?>&gt;
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>

        </li>
        <?php endif; ?>

        <?php if (!empty($composer['extra']['regions'])): ?>
        <li>
            <strong><?= __d('system', 'Theme Regions'); ?>:</strong>

            <ul>
                <?php foreach ($composer['extra']['regions'] as $machineName => $region): ?>
                <li><?= $region; ?> (<?= $machineName; ?>)</li>
                <?php endforeach; ?>
            </ul>
        </li>
        <?php endif; ?>
    </ul>
</p>

<hr />

<div class="clearfix package-links">
    <p>
        <?php
            $trans = [
                'require' => __d('system', 'Requires'),
                'devRequire' => __d('system', 'Requires (Dev)'),
                'suggest' => __d('system', 'Suggests'),
                'provide' => __d('system', 'Provides'),
                'conflict' => __d('system', 'Conflicts'),
                'replace' => __d('system', 'Replaces'),
            ];
        ?>

        <?php foreach (["require", "devRequire", "suggest", "provide", "conflict", "replace"] as $type): ?>
            <div class="<?= $type; ?>">
                <p>
                    <strong><?= $trans[$type]; ?></strong>

                    <?php if (!empty($composer[$type])): ?>
                    <ul>
                        <?php foreach ($composer[$type] as $package => $version): ?>
                            <li><?= $package; ?>: <?= $version; ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                        <?= __d('system', 'None'); ?>
                    <?php endif; ?>
                </p>
            </div>
        <?php endforeach; ?>
    </p>
</div>

<?php if (!empty($composer['keywords'])): ?>
<p>
    <hr />

    <div class="clearfix text-left package-tags">
        <?php foreach($composer['keywords'] as $tag): ?>
            <?= $this->Html->link($tag, 'https://packagist.org/search/?q=' . $tag, ['class' => 'label label-default', 'target' => '_blank']); ?>
        <?php endforeach; ?>
    </div>
</p>
<?php endif; ?>