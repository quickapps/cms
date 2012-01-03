<?php echo $this->Form->input('Module.settings.slider_folder', array('between' => preg_replace('/\/[a-z]{3}\//', '/', $this->Html->url('/files/', true)), 'type' => 'text', 'label' => __d('theme_default', 'Image slider folder'))); ?>
<em>
    <?php echo __d('ThemeDefault', 'Recomended images size:') ?> 974x302px<br/>
</em>
<p>
    <?php echo $this->Html->script('/theme/Default/js/farbtastic.js'); ?>
    <?php echo $this->Html->css('/theme/Default/css/farbtastic.css'); ?>
    <?php echo $this->Html->useTag('fieldsetstart', __t('Color Scheme')); ?>
        <div style="width:200px; float:left;">
            <?php $color_header_top = @empty($this->data['Module']['settings']['color_header_top']) ? '#123456' : $this->data['Module']['settings']['color_header_top']; ?>
            <?php $color_header_bottom = @empty($this->data['Module']['settings']['color_header_bottom']) ? '#123456' : $this->data['Module']['settings']['color_header_bottom']; ?>
            <?php $color_links = @empty($this->data['Module']['settings']['color_links']) ? '#00b7f3' : $this->data['Module']['settings']['color_links']; ?>
            <?php $color_text = @empty($this->data['Module']['settings']['color_text']) ? '#555555' : $this->data['Module']['settings']['color_text']; ?>
            <?php $color_main_bg = @empty($this->data['Module']['settings']['color_main_bg']) ? '#ededec' : $this->data['Module']['settings']['color_main_bg']; ?>
            <?php $color_footer = @empty($this->data['Module']['settings']['color_footer']) ? '#282727' : $this->data['Module']['settings']['color_footer']; ?>

            <?php echo $this->Form->input('Module.settings.color_header_top', array('value' => $color_header_top, 'class' => 'colorwell', 'style' => 'width:50px;', 'type' => 'text', 'label' => __d('theme_default', 'Header top'))); ?>
            <?php echo $this->Form->input('Module.settings.color_header_bottom', array('value' => $color_header_bottom, 'class' => 'colorwell', 'style' => 'width:50px;', 'type' => 'text', 'label' => __d('theme_default', 'Header bottom'))); ?>
            <?php echo $this->Form->input('Module.settings.color_links', array('value' => $color_links, 'class' => 'colorwell', 'style' => 'width:50px;', 'type' => 'text', 'label' => __d('theme_default', 'Links'))); ?>
            <?php echo $this->Form->input('Module.settings.color_text', array('value' => $color_text, 'class' => 'colorwell', 'style' => 'width:50px;', 'type' => 'text', 'label' => __d('theme_default', 'Text'))); ?>
            <?php echo $this->Form->input('Module.settings.color_main_bg', array('value' => $color_main_bg, 'class' => 'colorwell', 'style' => 'width:50px;', 'type' => 'text', 'label' => __d('theme_default', 'Main Background'))); ?>
            <?php echo $this->Form->input('Module.settings.color_footer', array('value' => $color_footer, 'class' => 'colorwell', 'style' => 'width:50px;', 'type' => 'text', 'label' => __d('theme_default', 'Footer'))); ?>
        </div>
        <div id="colorpicker" style="float:left;"></div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
</p>

<script type="text/javascript">
    $(document).ready(function() {
        var f = $.farbtastic('#colorpicker');
        var selected;
        $('.colorwell')
        .each(function () { f.linkTo(this); })
        .focus(function() {
            if (selected) {
                $(selected).removeClass('colorwell-selected');
            }
            f.linkTo(this);
        });
    });
</script>