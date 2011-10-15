<?php $split = round(count($blocks_for_layout)/2); ?>
<table width="100%">
    <tr>
        <td width="50%" valign="top">
        <?php
            foreach (array_slice($blocks_for_layout, 0, $split) as $block) {
                echo $block;
            }
        ?>
        </td>

        <td width="50%" valign="top">
        <?php
            foreach (array_slice($blocks_for_layout, $split) as $block) {
                echo $block;
            }
        ?>
        </td>
    </tr>
</table>