<?php
/**
 * Render Node's comment form.
 *
 * @package QuickApps.View.Elements
 * @author Christopher Castro <chris@quickapps.es>
 */
?>

<?php
    $collect = $this->Layout->hook('before_comments_form', $this, array('collectReturn' => true));

    echo implode(' ', (array)$collect);
?>
<div id="comment-form">
    <?php echo $this->Html->tag('h2', __d('comment', 'Add new comment')); ?>
    <div id="sessionFlash"><?php echo $this->Layout->sessionFlash(); ?></div>
    <?php echo $this->Form->create('Comment'); ?>
        <?php echo $this->Form->input('node_id', array('type' => 'hidden', 'value' => $Layout['node']['Node']['id'])); ?>
        <?php if ($this->Layout->loggedIn()): ?>
           <p><div class="input text required"><label for="CommentUserId"><?php echo __d('comment', 'Your name'); ?></label> <a href="" id="CommentUserId"><?php echo $this->Session->read('Auth.User.username'); ?></a></div></p>
        <?php else: ?> <!-- Anonymous: -->
            <?php
                switch($Layout['node']['NodeType']['comments_anonymous']):
                    #name
                    case 0:
            ?>
            <p><?php echo $this->Form->input('name', array('type' => 'text', 'label' => __d('comment', 'Your name'))); ?></p>
            <?php
                    break;

                    #name
                    #email
                    #host
                    case 1:
            ?>
            <p><?php echo $this->Form->input('name', array('type' => 'text', 'label' => __d('comment', 'Your name'))); ?></p>
            <p><?php echo $this->Form->input('email', array('type' => 'text', 'label' => __d('comment', 'Email'))); ?></p>
            <p><?php echo $this->Form->input('homepage', array('type' => 'text', 'label' => __d('comment', 'Web page'))); ?></p>
            <?php
                    break;

                    #name*
                    #email*
                    #host
                    case 2:
            ?>
            <p><?php echo $this->Form->input('name', array('type' => 'text', 'label' => __d('comment', 'Your name *'))); ?></p>
            <p><?php echo $this->Form->input('email', array('type' => 'text', 'label' => __d('comment', 'Email *'))); ?></p>
            <p><?php echo $this->Form->input('homepage', array('type' => 'text', 'label' => __d('comment', 'Web page'))); ?></p>
            <?php   break; ?>
            <?php endswitch; ?>
        <?php endif; ?>

        <?php if ($Layout['node']['NodeType']['comments_subject_field']): ?>
            <p><?php echo $this->Form->input('subject', array('type' => 'text', 'label' => __d('comment', 'Subject'))); ?></p>
        <?php endif; ?>

        <p><?php echo $this->Form->input('body', array('type' => 'textarea', 'label' => __d('comment', 'Comment *'))); ?></p>

        <p><?php echo $this->Layout->hook('comment_captcha'); ?></p>

        <div class="form-actions">
            <?php echo $this->Form->input(__d('comment', 'Save'), array('type' => 'submit', 'label' => false)); ?>
        </div>
    <?php echo $this->Form->end(); ?>
</div>
<?php
    $collect = $this->Layout->hook('after_comments_form', $this, array('collectReturn' => true));

    echo implode(' ', (array)$collect);
?>