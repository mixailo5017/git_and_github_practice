<?php foreach ($feed as $update) { ?>
    <?php
        $content_html = '<strong><a href="' . $update['author_url']. '">' . $update['author_name'] . '</a></strong> ' . lang('UpdateCommented') . ': "<span>' . auto_link($update['content'], 'url', TRUE) . '</span>".';
    ?>
    <li class="update" data-id="<?php echo $update['id'] ?>" data-replies-url="/discussions/replies/<?php echo $update['id'] ?>">
        <div class="image">
            <img src="<?php echo $update['author_photo'] ?>" class="thumb" alt="<?php echo $update['author_name'] ?>'s photo" />
        </div>
        <div class="content">
            <p><?php echo $content_html ?></p>
            <span class="time"><?php echo $update['ago'] ?></span>
            <div class="number-of-comments"><a href="#"><span><?php echo $update['replies'] ?></span> <?php echo lang('UpdateComments') ?></a></div>
        </div>
        <div class="additional-comments">
            <ul class="updates"></ul>
            <div class="comment-wrapper post">
                <div class="photo">
                    <img src="<?php echo $user['photo'] ?>" class="thumb" alt="Current user's photo" />
                </div>
                <div class="comment">
                    <?php
                    echo form_open('/discussions/post/' . $discussion_id, 'name="post_update"', array(
                        'reply_to' => $update['id'],
                        'author' => $user['id'],
                    ));
                    ?>
                    <div class="field-wrapper">
                        <textarea class="post-comment" placeholder="<?php echo lang('UpdateCommentPlaceholder') ?>"></textarea>
                        <div class="errormsg"></div>
                        <input type="submit" class="light_green" value="<?php echo lang('PostUpdate') ?>">
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </li>
<?php } ?>