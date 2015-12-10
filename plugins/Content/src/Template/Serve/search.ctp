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
<div class="row">
    <div class="col-md-12">
        <?php if (!$contents->isEmpty()): ?>
            <h1><?php echo __d('content', 'Search Results'); ?></h1>

            <?php foreach ($contents as $content): ?>
                <?php echo $this->render($content); ?>
            <?php endforeach; ?>

            <ul class="pagination">
                <?php echo $this->Paginator->options(['url' => ['_name' => 'content_search', 'criteria' => $criteria]]); ?>
                <?php echo $this->Paginator->prev(); ?>
                <?php echo $this->Paginator->numbers(); ?>
                <?php echo $this->Paginator->next(); ?>
            </ul>

            <p class="text-center help-block">
                <?php
                    echo $this->Paginator->counter(
                        __d('content', 'Page {{page}} of {{pages}}, showing {{current}} results out of {{count}} total.')
                    );
                ?>
            </p>
        <?php else: ?>
            <h2><?php echo __d('content', 'Your search yielded no results'); ?></h2>
            <ul>
                <li><?php echo __d('content', 'Check if your spelling is correct.'); ?></li>
                <li><?php echo __d('content', 'Remove quotes around phrases to search for each word individually, <code>white car</code> will often show more results than <code>"white car"</code>.'); ?></li>
                <li><?php echo __d('content', 'Consider loosening your query using <code>AND</code> & <code>OR</code>, <code>white OR car</code> will often show more results than <code>white cat</code>.'); ?></li>
            </ul>
        <?php endif; ?>
    </div>
</div>