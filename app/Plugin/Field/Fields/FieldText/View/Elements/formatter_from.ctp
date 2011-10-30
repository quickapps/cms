<!-- Text Formatter Form -->
<?php
    $actualType = @$this->data['Field']['settings']['display'][$view_mode]['type'];

    echo $this->Form->input("Field.settings.display.{$view_mode}.type",
        array(
            'label' => false,
            'type' => 'select',
            'options' => array(
                'full' => __t('Full'),
                'plain' => __t('Plain'),
                'trimmed' => __t('Trimmed')
            ),
            'empty' => false,
            'escape' => false,
            'onChange' => "if (this.value == 'trimmed') { $('#trimmed').show(); }else{ $('#trimmed').hide(); };"
        )
    );
?>

<div id="trimmed" style="<?php echo $actualType !== 'trimmed' ? 'display:none;' : ''; ?>">
    <?php
        echo $this->Form->input("Field.settings.display.{$view_mode}.trim_length",
            array(
                'type' => 'text',
                'label' => __d('field_text', "Trim length or read-more-cutter")
            )
        );
    ?>

    <ul>
        <li><em><?php echo __d('field_text', 'Numeric value will convert content to plain text and then trim it to the especified number of chars. i.e.: 400'); ?></em></li>
        <li><em><?php echo __d('field_text', 'String value will cut the content in two by the specified string, the first part will be displayed. i.e.: &lt;!-- readmore --&gt;'); ?></em></li>
    </ul>
</div>