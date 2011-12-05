<?php echo $this->Form->create('Translation'); ?>
    <!-- Settings -->
    <?php echo $this->Html->useTag('fieldsetstart', __t('Adding translatable entry')); ?>
        <?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
        <?php
            echo $this->Form->input("Translation.original",
                array(
                    'type' => 'textarea',
                    'label' => __t('Original text')
                )
            );

            $i = 0;

            foreach (Configure::read('Variable.languages') as $lang):
                $t = Set::extract("/Translation[locale={$lang['Language']['code']}]", $this->data);
                $t = Set::merge(array('Translation' => array('content' => '', 'id' => null)), @$t[0]);

                echo $this->Form->input("Translation.{$i}.content",
                    array(
                        'type' => 'textarea',
                        'value' => $t['Translation']['content'],
                        'label' => $lang['Language']['native']
                    )
                );

                echo $this->Form->input("Translation.{$i}.id",
                    array(
                        'type' => 'hidden',
                        'value' => $t['Translation']['id']
                    )
                );

                echo $this->Form->input("Translation.{$i}.model",
                    array(
                        'type' => 'hidden',
                        'value' => 'Locale.Translation'
                    )
                );

                echo $this->Form->input("Translation.{$i}.locale",
                    array(
                        'type' => 'hidden',
                        'value' => $lang['Language']['code']
                    )
                );

                $i++;
            endforeach;
        ?>
    <?php echo $this->Html->useTag('fieldsetend'); ?>

    <!-- Submit -->
    <?php echo $this->Form->input(__t('Translate'), array('type' => 'submit')); ?>
<?php echo $this->Form->end(); ?>