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

<!DOCTYPE html>
<html>
    <head>
        <title>elFinder 2.0</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <?php echo $this->Html->head(['bootstrap' => false]); ?>
    </head>

    <body>
        <?php echo $this->fetch('content'); ?>
    </body>
</html>