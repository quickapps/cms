<ul id="lang-selector">
    <?php foreach (Configure::read('Variable.languages') as $key => $lang ): ?>
    <?php $selected = Configure::read('Variable.language.code') == $lang['Language']['code'] ? 'selected' : ''; ?>
    <?php
        $flag = $block['Block']['settings']['flags'] && !empty($lang['Language']['icon']) ?
            $this->Html->image(
                ( strpos($lang['Language']['icon'], '://') !== false ? $lang['Language']['icon'] : "/locale/img/flags/{$lang['Language']['icon']}" ),
                array('class' => 'flag-icon'))
            : '';
    ?>
    <?php $name = $block['Block']['settings']['name'] ? "<span>{$lang['Language']['native']}</span>" : ''; ?>
    <li class="<?php echo "{$lang['Language']['code']} {$selected}"; ?>">
        <a href="<?php echo $this->here . "?lang=" . $lang['Language']['code']; ?>">
            <?php echo $flag; ?>
            <?php echo $name; ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>