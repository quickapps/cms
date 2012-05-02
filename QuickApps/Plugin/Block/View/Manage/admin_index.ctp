<?php echo $this->Form->create('BlockRegion'); ?>
    <?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . $themes[Configure::read('Variable.site_theme')]['info']['name'] . '</span>'); ?>
    <div class="fieldset-toggle-container" style="display:none;">
        <?php
            $theme = Configure::read('Variable.site_theme');
            $blocks_in_theme = $site_theme;
            $regions = (array)Hash::extract($blocks_in_theme, '{n}.BlockRegion');
            $region_stack = array();

            foreach ($regions as $key => $region_arrays) {
                $region_stack = array_merge($region_stack, $region_arrays);
            }

            $regions = $region_stack;
            $regions = (array)Hash::extract($regions, "{n}[theme={$theme}].region");
            $regions = array_unique($regions);

            sort($regions);

            foreach ($regions as $region):
                if (empty($region)) {
                    continue; // unasisgned
                }

                $blocks_in_region = Hash::extract($blocks_in_theme, "{n}.BlockRegion.{n}[region={$region}].block_id");

                if (empty($blocks_in_region)) {
                    continue;
                }

                $blocks_in_region = array_unique($blocks_in_region);

                foreach ($blocks_in_theme as $bkey => $block) {
                    if (!in_array($block['Block']['id'], $blocks_in_region)) {
                        continue;
                    }

                    foreach ($block['BlockRegion'] as $rkey => $BlockRegion) {
                        if ($BlockRegion['theme'] != $theme && $BlockRegion['region'] != $region) {
                            unset($blocks_in_theme[$bkey]['BlockRegion'][$rkey]);
                        }
                    }
                }

                $blocks_in_region = @Hash::sort($blocks_in_theme, '{n}.BlockRegion.{n}.ordering', 'asc');

                if (empty($blocks_in_region)) {
                    continue;
                }

                foreach ($blocks_in_region as $key => &$b) {
                    if (empty($b)) {
                        unset($blocks_in_region[$key]);

                        continue;
                    }

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
        ?>
            <h4><?php echo $themes[$theme]['regions'][$region]; ?></h4>
            <ul class="sortable">
                <?php foreach ($blocks_in_region as $block): ?>
                <li class="ui-state-default">
                    <input type="hidden" name="data[BlockRegion][<?php echo $theme; ?>][<?php echo $region; ?>][]" value="<?php echo $block['Block']['__block_region_id']; ?>" />

                    <div class="fl">
                        <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                    </div>

                    <div class="fl" style="width:60%;">
                    <?php
                        if ($block['Block']['title'] == '') {
                            if ($block['Menu']['title'] != '') {
                                echo $block['Menu']['title'];
                            } else {
                                echo "{$block['Block']['module']}_{$block['Block']['delta']}";
                            }
                        } else {
                            echo "{$block['Block']['title']}";
                        }

                        echo !empty($block['BlockCustom']['description']) ? " (<em>{$block['BlockCustom']['description']}</em>)" : '';
                    ?>
                    </div>

                    <div class="fl">
                        <?php echo $region; ?>
                    </div>

                    <div class="fr">
                        <a href="<?php echo $this->Html->url("/admin/block/manage/clone/{$block['Block']['id']}"); ?>" onClick="return confirm('<?php echo __t('Duplicate this block?'); ?>');"><?php echo __t('clone') ?></a> |
                        <a href="<?php echo $this->Html->url("/admin/block/manage/edit/{$block['Block']['id']}"); ?>"><?php echo __t('configure'); ?></a> |
                        <?php if ($block['Block']['module'] == 'Block' || $block['Block']['clone_of'] != 0) { ?>
                            <a href="<?php echo $this->Html->url("/admin/block/manage/delete/{$block['Block']['id']}"); ?>" onclick="return confirm('<?php echo __t('Delete selected block ?'); ?>');"><?php echo __t('delete'); ?></a> |
                        <?php } ?>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
    </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>


    <!-- Backend theme -->
    <?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . $themes[Configure::read('Variable.admin_theme')]['info']['name'] . '</span>'); ?>
    <div class="fieldset-toggle-container" style="display:none;">
        <?php
            $theme = Configure::read('Variable.admin_theme');
            $blocks_in_theme = $admin_theme;
            $regions = (array)Hash::extract($blocks_in_theme, '{n}.BlockRegion');
            $region_stack = array();

            foreach ($regions as $key => $region_arrays) {
                $region_stack = array_merge($region_stack, $region_arrays);
            }

            $regions = $region_stack;
            $regions = (array)Hash::extract($regions, "{n}[theme={$theme}].region");
            $regions = array_unique($regions);

            sort($regions);

            foreach ($regions as $region):
                if (empty($region)) {
                    continue; // unasisgned
                }

                $blocks_in_region = Hash::extract($blocks_in_theme, "{n}.BlockRegion.{n}[region={$region}].block_id");

                if (empty($blocks_in_region)) {
                    continue;
                }

                $blocks_in_region = array_unique($blocks_in_region);

                foreach ($blocks_in_theme as $bkey => $block) {
                    if (!in_array($block['Block']['id'], $blocks_in_region)) {
                        continue;
                    }

                    foreach ($block['BlockRegion'] as $rkey => $BlockRegion) {
                        if ($BlockRegion['theme'] != $theme && $BlockRegion['region'] != $region) {
                            unset($blocks_in_theme[$bkey]['BlockRegion'][$rkey]);
                        }
                    }
                }

                $blocks_in_region = @Hash::sort($blocks_in_theme, '{n}.BlockRegion.{n}.ordering', 'asc');

                if (empty($blocks_in_region)) {
                    continue;
                }

                foreach ($blocks_in_region as $key => &$b) {
                    if (empty($b)) {
                        unset($blocks_in_region[$key]);

                        continue;
                    }

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
        ?>
            <h4><?php echo $themes[$theme]['regions'][$region]; ?></h4>
            <ul class="sortable">
                <?php foreach ($blocks_in_region as $block): ?>
                <li class="ui-state-default">
                    <input type="hidden" name="data[BlockRegion][<?php echo $theme; ?>][<?php echo $region; ?>][]" value="<?php echo $block['Block']['__block_region_id']; ?>" />

                    <div class="fl">
                        <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                    </div>

                    <div class="fl" style="width:60%;">
                    <?php
                        if ($block['Block']['title'] == '') {
                            if ($block['Menu']['title'] != '') {
                                echo $block['Menu']['title'];
                            } else {
                                echo "{$block['Block']['module']}_{$block['Block']['delta']}";
                            }
                        } else {
                            echo "{$block['Block']['title']}";
                        }

                        echo !empty($block['BlockCustom']['description']) ? " (<em>{$block['BlockCustom']['description']}</em>)" : '';
                    ?>
                    </div>

                    <div class="fl">
                        <?php echo $region; ?>
                    </div>

                    <div class="fr">
                        <a href="<?php echo $this->Html->url("/admin/block/manage/clone/{$block['Block']['id']}"); ?>" onClick="return confirm('<?php echo __t('Duplicate this block?'); ?>');"><?php echo __t('clone') ?></a> |
                        <a href="<?php echo $this->Html->url("/admin/block/manage/edit/{$block['Block']['id']}"); ?>"><?php echo __t('configure'); ?></a> |
                        <?php if ($block['Block']['module'] == 'Block' || $block['Block']['clone_of'] != 0) { ?>
                            <a href="<?php echo $this->Html->url("/admin/block/manage/delete/{$block['Block']['id']}"); ?>" onclick="return confirm('<?php echo __t('Delete selected block ?'); ?>');"><?php echo __t('delete'); ?></a> |
                        <?php } ?>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
    <?php endforeach; ?>
    </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>

    <!-- Unassigned blocks -->
    <?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Unassigned or Disabled') . '</span>'); ?>
    <div class="fieldset-toggle-container" style="display:none;">
        <?php
            $notAssigned = array();

            foreach ($unassigned as $key => $b) {
                if (
                    strpos($b['Block']['module'], 'Theme') === 0 &&
                    !in_array($b['Block']['module'],
                        array(
                            'Theme' . Configure::read('Variable.admin_theme'),
                            'Theme' . Configure::read('Variable.site_theme')
                        )
                    )
                ) {
                    continue;
                }

                $notAssigned[] = $b;
            }
        ?>
        <ul class="not-sortable">
            <?php foreach ($notAssigned as $block): ?>
            <li class="ui-state-default">
                <div class="fl">
                    <span class="ui-icon"></span>
                </div>

                <div class="fl" style="width:60%;">
                <?php
                    if ($block['Block']['title'] == '') {
                        if ($block['Menu']['title'] != '') {
                            echo $block['Menu']['title'];
                        } else {
                            echo "{$block['Block']['module']}_{$block['Block']['delta']}";
                        }
                    } else {
                        echo "{$block['Block']['title']}";
                    }

                    echo !empty($block['BlockCustom']['description']) ? " (<em>{$block['BlockCustom']['description']}</em>)" : '';
                ?>
                </div>

                <div class="fl">
                    ---
                </div>

                <div class="fr">
                    <a href="<?php echo $this->Html->url("/admin/block/manage/clone/{$block['Block']['id']}"); ?>" onClick="return confirm('<?php echo __t('Duplicate this block?'); ?>');"><?php echo __t('clone') ?></a> |
                    <a href="<?php echo $this->Html->url("/admin/block/manage/edit/{$block['Block']['id']}"); ?>"><?php echo __t('configure'); ?></a> |
                    <?php if ($block['Block']['module'] == 'Block' || $block['Block']['clone_of'] != 0) { ?>
                        <a href="<?php echo $this->Html->url("/admin/block/manage/delete/{$block['Block']['id']}"); ?>" onclick="return confirm('<?php echo __t('Delete selected block ?'); ?>');"><?php echo __t('delete'); ?></a> |
                    <?php } ?>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>

    <?php echo $this->Form->submit(__t('Save all')); ?>
<?php echo $this->Form->end(); ?>
<script>
    $(".sortable").sortable().disableSelection();
</script>