<?php
    if (Configure::read('Variable.url_language_prefix')) {
        $url = $this->request->url;
        $url = $url[0] !== '/' ? "/{$url}" : $url;
        $url = !preg_match('/^\/[a-z]{3}\//', $url) ? '/' . Configure::read('Config.language') . $url : $url;
    }
?>

<ul id="lang-selector">
    <?php foreach (Configure::read('Variable.languages') as $key => $lang ): ?>
    <?php $selected = Configure::read('Variable.language.code') == $lang['Language']['code'] ? 'selected' : ''; ?>
    <?php
        $flag = '';

        if ($block['Block']['settings']['flags'] && !empty($lang['Language']['icon'])) {
            if (strpos($lang['Language']['icon'], '://') !== false) {
                $icon = $lang['Language']['icon'];
            } else {
                $icon = "/locale/img/flags/{$lang['Language']['icon']}";
            }

            $flag = $this->Html->image($icon, array('class' => 'flag-icon'));
        }

        $name = $block['Block']['settings']['name'] ? "<span>{$lang['Language']['native']}</span>" : '';
    ?>
    <li class="<?php echo "{$lang['Language']['code']} {$selected}"; ?>">
        <?php
            if (Configure::read('Variable.url_language_prefix')) {
                $switch_url = $this->request->base . str_replace_once(Configure::read('Config.language') . '/' , "{$lang['Language']['code']}/", $url);
            } else {
                $switch_url = str_replace_once('/' . Configure::read('Config.language') . '/' , '', $this->here) . "?lang=" . $lang['Language']['code'];
            }
        ?>
        <a href="<?php echo $switch_url; ?>">
            <?php echo $flag; ?>
            <?php echo $name; ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>