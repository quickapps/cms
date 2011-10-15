<?php
    $actions = array(
        "<a href='{url}/admin/block/manage/clone/{Block.id}{/url}' onClick=\"return confirm('" . __t('Duplicate this block?') . "');\">" . __t('clone') . "</a> | ",
        "<a href='{url}/admin/block/manage/edit/{Block.id}{/url}'>" . __t('configure') . "</a> | ",
        "{php} return (('{Block.module}' == 'block' || {Block.clone_of} != 0) ? \"<a href='{url}/admin/block/manage/delete/{Block.id}{/url}' onclick='return confirm(\\\" " . __t('Delete selected block ?') . " \\\");'>" . __t('delete') . "</a> | \" : ''); {/php}",
        "<a href='{url}/admin/block/manage/move/{Block.__block_region_id}/up{/url}'>" . __t('move up') . "</a> | ",
        "<a href='{url}/admin/block/manage/move/{Block.__block_region_id}/down{/url}'>" . __t('move down') . "</a>"
    );

    $displayFields = array(
        'columns' => array(
            __t('Block') => array(
                'value' => "{php}
                    if ('{Block.title}' == '') {
                        if ('{Menu.title}' != '') {
                            return '{Menu.title}';
                        }

                        return '{Block.module}_{Block.delta}';
                    }
                    return '{Block.title}<br/><em>&nbsp;&nbsp;{BlockCustom.description}</em>';
                {/php}",
                'tdOptions' => array('width' => '60%')
            ),
            __t('Region') => array(
                'value' => null
            ),
            __t('Actions') => array(
                'value' => implode(' ', $actions),
                'thOptions' => array('align' => 'right'),
                'tdOptions' => array('align' => 'right')
            ),
        ),
        'paginate' => false,
        'headerPosition' => 'top',
        'tableOptions' => array('width' => '100%')    # table attributes
    );
?>
<!-- Frontend theme -->
<?php echo $this->Html->useTag('fieldsetstart', '<span id="toggle-site_theme" style="cursor:pointer;">' . $themes[Configure::read('Variable.site_theme')]['info']['name'] . '</span>'); ?>
<div id="site_theme" style="display:none;">
    <?php
        $theme = Configure::read('Variable.site_theme');
        $blocks_in_theme = $site_theme;
        $regions = (array)Set::extract('{n}.BlockRegion', $blocks_in_theme);
        $region_stack = array();

        foreach ($regions as $key => $region_arrays) {
            $region_stack = array_merge($region_stack, $region_arrays);
        }

        $regions = $region_stack;
        $regions = (array)Set::extract("{n}.region/..[theme={$theme}]", $regions);
        $regions = (array)Set::extract("{n}.region", $regions);
        $regions = array_unique($regions);

        sort($regions);

        foreach ($regions as $region) {
            if (empty($region)) {
                continue; #unasisgned
            }

            $blocks_in_region = Set::extract("/BlockRegion[region={$region}]/..", $blocks_in_theme);

            if (empty($blocks_in_region)) {
                continue;
            }

            $blocks_in_region = arrayUnique($blocks_in_region);

            foreach ($blocks_in_region as $bkey => $block) {
                foreach ($block['BlockRegion'] as $rkey => $BlockRegion) {
                    if ($BlockRegion['theme'] != $theme && $BlockRegion['region'] != $region) {
                        unset($blocks_in_region[$bkey]['BlockRegion'][$rkey]);
                    }
                }
            }

            $blocks_in_region = Set::sort($blocks_in_region, '{n}.BlockRegion.{n}.ordering', 'asc');

            if (empty($blocks_in_region)) {
                continue;
            }

            foreach ($blocks_in_region as $key => &$b) {
                foreach ($b['BlockRegion'] as $key => $br) {
                    if ($br['region'] == $region && $br['theme'] == $theme) {
                        $b['Block']['__block_region_id'] = $br['id'];

                        break;
                    }
                }
            }

            foreach ($blocks_in_region as $key => $block) {
                if (!isset($block['Block']['__block_region_id'])) {
                    unset($blocks_in_region[$key]);
                }
            } 

            if (empty($blocks_in_region)) {
                continue;
            }

            $displayFields['columns'][__t('Region')]['value'] = $region;

            echo "<h4>" . $themes[$theme]['regions'][$region] . "</h4>";
            echo $this->Html->table($blocks_in_region, $displayFields);
            echo "<br/><br/><br/>";
        }
    ?>
</div>
<?php echo $this->Html->useTag('fieldsetend'); ?>

<br/>
<br/>

<!-- Backend theme -->
<?php echo $this->Html->useTag('fieldsetstart', '<span id="toggle-admin_theme" style="cursor:pointer;">' . $themes[Configure::read('Variable.admin_theme')]['info']['name'] . '</span>'); ?>
<div id="admin_theme" style="display:none;">
    <?php
        $theme = Configure::read('Variable.admin_theme');
        $blocks_in_theme = $admin_theme;
        $regions = (array)Set::extract('{n}.BlockRegion', $blocks_in_theme);
        $region_stack = array();

        foreach ($regions as $key => $region_arrays) {
            $region_stack = array_merge($region_stack, $region_arrays);
        }

        $regions = $region_stack;
        $regions = (array)Set::extract("{n}.region/..[theme={$theme}]", $regions);
        $regions = (array)Set::extract("{n}.region", $regions);
        $regions = array_unique($regions);

        sort($regions);

        foreach ($regions as $region) {
            if (empty($region)) {
                continue; #unasisgned
            }

            $blocks_in_region = Set::extract("/BlockRegion[region={$region}]/..", $blocks_in_theme);

            if (empty($blocks_in_region)) {
                continue;
            }

            $blocks_in_region = arrayUnique($blocks_in_region);

            foreach ($blocks_in_region as $bkey => $block) {
                foreach ($block['BlockRegion'] as $rkey => $BlockRegion) {
                    if ($BlockRegion['theme'] != $theme && $BlockRegion['region'] != $region) {
                        unset($blocks_in_region[$bkey]['BlockRegion'][$rkey]);
                    }
                }
            }

            $blocks_in_region = Set::sort($blocks_in_region, '{n}.BlockRegion.{n}.ordering', 'asc');

            if (empty($blocks_in_region)) {
                continue;
            }

            foreach ($blocks_in_region as $key => &$b) {
                foreach ($b['BlockRegion'] as $key => $br) {
                    if ($br['region'] == $region && $br['theme'] == $theme) {
                        $b['Block']['__block_region_id'] = $br['id'];

                        break;
                    }
                }
            }

            foreach ($blocks_in_region as $key => $block) {
                if (!isset($block['Block']['__block_region_id'])) {
                    unset($blocks_in_region[$key]);
                }
            } 

            if (empty($blocks_in_region)) {
                continue;
            }

            $displayFields['columns'][__t('Region')]['value'] = $region;

            echo "<h4>" . $themes[$theme]['regions'][$region] . "</h4>";
            echo $this->Html->table($blocks_in_region, $displayFields);
            echo "<br/><br/><br/>";
        }
    ?>
</div>
<?php echo $this->Html->useTag('fieldsetend'); ?>

<br/>
<br/>

<!-- Unassigned blocks -->
<?php echo $this->Html->useTag('fieldsetstart', '<span id="toggle-unasigned" style="cursor:pointer;">' . __t('Unassigned') . '</span>'); ?>
<div id="unasigned_theme" style="display:none;">
    <?php
        $notAssigned = array();

        foreach ($unassigned as $key => $b) {
            if (
                strpos($b['Block']['module'], 'theme_') !== false && 
                !in_array($b['Block']['module'], 
                    array(
                        'theme_' . Inflector::underscore(Configure::read('Variable.admin_theme')), 
                        'theme_' . Inflector::underscore(Configure::read('Variable.site_theme'))
                    )
                )
            ) {
                continue;
            }

            $notAssigned[] = $b;
        }

        $displayFields['columns'][__t('Region')]['value'] = '---';
        echo "<h4>" . __t('Disabled') . "</h4>" . $this->Html->table($notAssigned, $displayFields) . "<br/><br/><br/>";
    ?>
</div>
<?php echo $this->Html->useTag('fieldsetend'); ?>

<script>
    $("#toggle-admin_theme").click(function () {
        $("#admin_theme").toggle('fast', 'linear');
    });

    $("#toggle-site_theme").click(function () {
        $("#site_theme").toggle('fast', 'linear');
    });

    $("#toggle-unasigned").click(function () {
        $("#unasigned_theme").toggle('fast', 'linear');
    });
</script>