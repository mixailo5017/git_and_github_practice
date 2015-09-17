<?php foreach ($replies as $update) { ?>
    <?php
    $content_html = '<strong><a href="' . $update['author_url']. '">' . $update['author_name'] . '</a></strong> ' . lang('UpdateCommented') . ': "<span>' . auto_link($update['content'], 'url', TRUE) . '</span>".';
    ?>
    <li class="update" data-id="<?php echo $update['id'] ?>">
        <div class="image">
            <img src="<?php echo $update['author_photo'] ?>" class="thumb" alt="<?php echo $update['author_name'] ?>'s photo" />
        </div>
        <div class="content">
            <p><?php echo $content_html ?></p>
            <span class="time"><?php echo $update['ago'] ?></span>
        </div>
    </li>
<?php } ?>