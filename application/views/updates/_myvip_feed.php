<?php foreach ($updates as $update) {
switch ($update['type']) {
    case UPDATE_TYPE_STATUS:
        $content_html = '<strong><a href="' . $update['author_url']. '">' . $update['author_name'] . '</a></strong> ' . lang('UpdatePosted') . ': "<span>' . auto_link($update['content'], 'url', TRUE) . '</span>".';
        break;
    case UPDATE_TYPE_PROFILE:
        $content_html = '<strong><a href="' . $update['author_url']. '">' . $update['author_name'] . '</a></strong> ' . lang('UpdateProfileChanged') . '.'; //. '.<span>' . $update['content'] . '</span>"';
        break;
    case UPDATE_TYPE_NEWPROJECT:
        $content_html = '<strong><a href="' . $update['author_url']. '">' . $update['author_name'] . '</a></strong> ' . lang('UpdateAddedNewProject') . ': <a href="' . $update['target_url'] . '"><strong>' . $update['target_name'] . '</strong></a>.';
        break;
} ?>
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