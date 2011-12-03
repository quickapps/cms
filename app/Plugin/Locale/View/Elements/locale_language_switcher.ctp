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
        <?php
            if (Configure::read('Variable.url_language_prefix')) {
                $switch_url = $this->request->base . '/' . str_replace_once(Configure::read('Config.language') . '/' , "{$lang['Language']['code']}/", $this->request->url);
            } else {
                $switch_url = str_replace_once('/' . Configure::read('Config.language') . '/' , '', $this->here) . "?lang=" . $lang['Language']['code'];
            }

            if ($switch_url == "{$this->request->base}/") {
                $switch_url .= "{$lang['Language']['code']}/";
            }
        ?>
        <a href="<?php echo $switch_url; ?>">
            <?php echo $flag; ?>
            <?php echo $name; ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>