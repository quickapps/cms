<?php echo $this->Form->create('Translation'); ?>
    <!-- Settings -->
    <?php echo $this->Html->useTag('fieldsetstart', __t('Editing translation entry')); ?>
        <?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
        <label><?php echo __t('Original text'); ?></label>
        <em>
            <?php echo $this->data['Translation']['original']; ?>
        </em>

        <p>
            <?php
                $i = 0;
                foreach (Configure::read('Variable.languages') as $lang):
                    $t = Set::extract("/I18n[locale={$lang['Language']['code']}]", $this->data);
                    $t = Set::merge(array('I18n' => array('content' => '', 'id' => null)), @$t[0]);
                    echo $this->Form->input("I18n.{$i}.content",
                        array(
                            'type' => 'textarea',
                            'value' => $t['I18n']['content'],
                            'label' => $lang['Language']['native']
                        )
                    );

                    echo $this->Form->input("I18n.{$i}.id",
                        array(
                            'type' => 'hidden',
                            'value' => $t['I18n']['id']
                        )
                    );

                    echo $this->Form->input("I18n.{$i}.locale",
                        array(
                            'type' => 'hidden',
                            'value' => $lang['Language']['code']
                        )
                    );

                    $i++;
                endforeach;
            ?>
        </p>

        <?php echo $this->Html->useTag('fieldsetstart', __t('Usage')); ?>
            <code>
                <p><b>[t]</b><?php echo $this->data['Translation']['original']; ?><b>[/t]</b></p>
                <p><b>[t=</b><?php echo $this->data['Translation']['original']; ?><b>]</b></p>
            </code>
        <?php echo $this->Html->useTag('fieldsetend'); ?>

    <?php echo $this->Html->useTag('fieldsetend'); ?>

    <!-- Submit -->
    <?php echo $this->Form->input(__t('Translate'), array('type' => 'submit')); ?>
<?php echo $this->Form->end(); ?>