<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
?>

<fieldset>
    <legend><?= __d('comment', 'Commenting Options'); ?></legend>

    <?= $this->Form->input('auto_approve', ['type' => 'checkbox', 'label' => __d('comment', 'Auto approve comments')]); ?>
    <em class="help-block"><?= __d('comment', 'Comments will automatically approved an published.'); ?></em>

    <?= $this->Form->input('allow_anonymous', ['type' => 'checkbox', 'label' => __d('comment', 'Anonymous commenting'), 'id' => 'allow-anonymous-comments', 'onclick' => 'toggleAnonymousCommentOptions();']); ?>
    <em class="help-block"><?= __d('comment', 'Anonymous users can comment.'); ?></em>

    <div class="anonymous-comments-options">
        <?= $this->Form->input('anonymous_name', ['type' => 'checkbox', 'label' => __d('comment', "Anonymous's name")]); ?>
        <em class="help-block">
            <?=
                __d('comment', 'Anonymous users {0} leave their name.',
                    $this->Form->input('anonymous_name_required', [
                        'type' => 'select',
                        'label' => false,
                        'bootstrap' => false,
                        'options' => [
                            1 => __d('comment', 'Must'),
                            0 => __d('comment', 'May'),
                        ],
                    ])
                );
            ?>
        </em>

        <?= $this->Form->input('anonymous_email', ['type' => 'checkbox', 'label' => __d('comment', "Anonymous's email")]); ?>
        <em class="help-block">
            <?=
                __d('comment', 'Anonymous users {0} leave an email address.',
                    $this->Form->input('anonymous_email_required', [
                        'type' => 'select',
                        'label' => false,
                        'bootstrap' => false,
                        'options' => [
                            1 => __d('comment', 'Must'),
                            0 => __d('comment', 'May'),
                        ]
                    ])
                );
            ?>
        </em>

        <?= $this->Form->input('anonymous_web', ['type' => 'checkbox', 'label' => __d('comment', "Anonymous's website")]); ?>
        <em class="help-block">
            <?=
                __d('comment', 'Anonymous users {0} leave a website URL.',
                    $this->Form->input('anonymous_web_required', [
                        'type' => 'select',
                        'label' => false,
                        'bootstrap' => false,
                        'options' => [
                            1 => __d('comment', 'Must'),
                            0 => __d('comment', 'May'),
                        ]
                    ])
                );
            ?>
        </em>
    </div>

    <hr />

    <?=
        $this->Form->input('text_processing', [
            'type' => 'select',
            'options' => [
                'plain' => __d('comment', 'Plain text'),
                'full' => __d('comment', 'Full HTML'),
                'filtered' => __d('comment', 'Filtered HTML'),
                'markdown' => __d('comment', 'Markdown')
            ],
            'label' => __d('comment', 'Text processing')
        ]);
    ?>
    <ul>
        <li>
            <b><?= __d('comment', 'Plain text'); ?>:</b>
            <ul>
                <li><?= __d('comment', 'No HTML tags allowed.'); ?></li>
                <li><?= __d('comment', 'Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
                <li><?= __d('comment', 'Lines and paragraphs break automatically.'); ?></li>
            </ul>
        </li>

        <li>
            <b><?= __d('comment', 'Full HTML'); ?>:</b>
            <ul>
                <li><?= __d('comment', 'All HTML tags allowed.'); ?></li>
                <li><?= __d('comment', 'Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
            </ul>
        </li>

        <li>
            <b><?= __d('comment', 'Filtered HTML'); ?>:</b>
            <ul>
                <li><?= __d('comment', 'Web page addresses and e-mail addresses turn into links automatically.'); ?></li>
                <li><?= __d('comment', 'Allowed HTML tags: &lt;a&gt; &lt;em&gt; &lt;strong&gt; &lt;cite&gt; &lt;blockquote&gt; &lt;code&gt; &lt;ul&gt; &lt;ol&gt; &lt;li&gt; &lt;dl&gt; &lt;dt&gt; &lt;dd&gt;'); ?></li>
                <li><?= __d('comment', 'Lines and paragraphs break automatically.'); ?></li>
            </ul>
        </li>

        <li>
            <b><?= __d('comment', 'Markdown'); ?>:</b>
            <ul>
                <li><?= __d('comment', '<a href="{0}" target="_blank">Markdown</a> text format allowed only.', 'http://wikipedia.org/wiki/Markdown'); ?></li>
            </ul>
        </li>
    </ul>

</fieldset>

<hr />

<fieldset>
    <legend><?= __d('comment', 'CAPTCHA Protection'); ?></legend>
    <?=
        $this->Form->input('use_captcha', [
            'id' => 'use-captcha',
            'type' => 'checkbox',
            'label' => __d('comment', 'Enable Human Verification'),
            'onclick' => 'toggleCaptchaOptions();'
        ]);
    ?>
    <div class="captcha-options">
        <?= __d('comment', 'You can configure CAPTCHA parameters by clicking <a href="{0}">this link</a>.', $this->Url->build('/admin/system/plugins/settings/Captcha')); ?>
    </div>
</fieldset>

<hr />

<fieldset>
    <legend><?= __d('comment', 'SPAM Protection'); ?></legend>

    <?= $this->Form->input('use_akismet', ['id' => 'use-akismet', 'type' => 'checkbox', 'label' => __d('comment', 'Use Akismet'), 'onclick' => 'toggleAkismetOptions();']); ?>

    <div class="akismet-options">
        <?= $this->Form->input('akismet_key', ['type' => 'text', 'label' => __d('comment', 'Akismet API Key *')]); ?>
        <em class="help-block"><?= __d('comment', 'Sign up for an Akismet <a href="{0}" target="_blank">API key here</a>.', 'http://akismet.com/'); ?></em>

        <?= $this->Form->label(__d('comment', 'On Spam detected')); ?><br />
        <?=
            $this->Form->radio('akismet_action', [
                'mark' => __d('comment', 'Mark as SPAM'),
                'delete' => __d('comment', 'Delete comment'),
            ]);
        ?>
    </div>
</fieldset>

<script>
    function toggleAkismetOptions() {
        if ($('#use-akismet').is(':checked')) {
            $('.akismet-options').show();
            $('#akismet-key').attr('required', 'required');
            $("[name='akismet_action']").attr('required', 'required');
        } else {
            $('.akismet-options').hide();
            $('#akismet-key').removeAttr('required');
            $("input:radio[name='akismet_action']").removeAttr('required');
        }
    }

    function toggleAnonymousCommentOptions() {
        if ($('#allow-anonymous-comments').is(':checked')) {
            $('.anonymous-comments-options').show();
        } else {
            $('.anonymous-comments-options').hide();
        }
    }

    function toggleCaptchaOptions() {
        if ($('#use-captcha').is(':checked')) {
            $('.captcha-options').show();
        } else {
            $('.captcha-options').hide();
        }
    }

    $(document).ready(function () {
        toggleCaptchaOptions();
        toggleAnonymousCommentOptions();
        toggleAkismetOptions();
    });
</script>

<style>
    .anonymous-comments-options div.select { display:inline; }
</style>