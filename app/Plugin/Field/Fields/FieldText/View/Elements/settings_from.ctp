<!-- Text Settings Form -->
<?php
    echo $this->Form->input("Field.settings.type",
        array(
            'type' => 'select',
            'options' => array(
                'text' => __d('field_text', 'Text field'), 
                'textarea' => __d('field_text', 'Long text')
            ),
            'label' => __d('field_text', 'Type of content')
        )
    );
?>

<?php  
    echo $this->Form->input("Field.settings.text_processing",
        array(
            'type' => 'select',
            'options' => array(
                'plain' => __d('field_text', 'Plain text'), 
                'full' => __d('field_text', 'Full HTML'), 
                'filtered' => __d('field_text', 'Filtered HTML'), 
                'markdown' => __d('field_text', 'Markdown')
            ),
            'label' => __d('field_text', 'Text processing')
        )
    );
?>
<blockquote class="text-processing-desc">
    <ul>
        <li>
            <b><?php echo __d('field_text', 'Plain text'); ?>:</b>
            <ul>
                <li><?php echo __d('field_text', 'No HTML tags allowed.'); ?></li>
                <li><?php echo __d('field_text', 'Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
                <li><?php echo __d('field_text', 'Lines and paragraphs break automatically.'); ?></li>
            </ul>
        </li>
    
        <li>
            <b><?php echo __d('field_text', 'Full HTML'); ?>:</b>
            <ul>
                <li><?php echo __d('field_text', 'Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
            </ul>
        </li>
        
        <li>
            <b><?php echo __d('field_text', 'Filtered HTML'); ?>:</b>
            <ul>
                <li><?php echo __d('field_text', 'Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
                <li><?php echo __d('field_text', 'Allowed HTML tags: &lt;a&gt; &lt;em&gt; &lt;strong&gt; &lt;cite&gt; &lt;blockquote&gt; &lt;code&gt; &lt;ul&gt; &lt;ol&gt; &lt;li&gt; &lt;dl&gt; &lt;dt&gt; &lt;dd&gt;'); ?></li>
                <li><?php echo __d('field_text', 'Lines and paragraphs break automatically.'); ?></li>
            </ul>
        </li>

        <li>
            <b><?php echo __d('field_text', 'Markdown'); ?>:</b>
            <ul>
                <li><?php echo __d('field_text', '<a href="%s" target="_blank">Markdown</a> text format allowed only.', 'http://wikipedia.org/wiki/Markdown'); ?></li>
            </ul>
        </li>
    </ul>
</blockquote>

<style>
    blockquote.text-processing-desc { -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; }
    blockquote.text-processing-desc ul {list-style:circle; margin-top:5px;}
    blockquote.text-processing-desc li { margin: 10px 0 0 15px;}
</style>

<?php
    echo $this->Form->input("Field.settings.max_len",
        array(
            'type' => 'text',
            'label' => __d('field_text', 'Max lenght')
        )
    );
?>
<em><?php echo __d('field_text', "This is only used if your Type of content is a `Text field`. This will limit the subscriber to typing X number of characters in your textbox."); ?></em>

<?php
    echo $this->Form->input("Field.settings.validation_rule",
        array(
            'type' => 'text',
            'label' => __d('field_text', 'Validation rule')
        )
    );
?>
<em><?php echo __d('field_text', 'Enter your custom regular expression. i.e.: "/^[a-z0-9]{3,}$/i" (Only letters and integers, min 3 characters)'); ?></em>

<?php
    echo $this->Form->input("Field.settings.validation_message",
        array(
            'type' => 'text',
            'label' => __d('field_text', 'Validation message')
        )
    );
?>
<em><?php echo __d('field_text', 'This is only used if `Validation rule` has been set.'); ?></em>