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
 * Default layout for error pages.
 *
 * This layout is used when a `404` or `500` error is raised.
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->head(['bootstrap' => true]); ?>
    </head>
    <body class="error">
        <div class="container">
            <?= $this->fetch('content'); ?>
        </div>
    </body>
</html>