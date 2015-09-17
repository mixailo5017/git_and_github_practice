<div class="clearfix" id="content">
    <div style="width:965px;" class="center_col white_box" id="col5">
        <h1 class="col_top gradient"><?php echo lang('ProjectDiscussions') ?></h1>
        <div class="inner clearfix">
            <div style="float: right; padding-right: 10px;">
                <div class="filter_option">
                    <?php echo form_dropdown('limit_options', view_limit_options(), $limit) ?>
                </div>
                <div class="filter_option">
                    <p><?php echo lang('PerPage') ?></p>
                </div>
            </div>
            <?php echo form_open('', array('name' => 'search_form', 'method' => 'get')) ?>
            <input type="hidden" name="limit" value="<?php echo $limit ?>">
            <?php echo form_close() ?>

            <?php echo form_paging(true, $page_from, $page_to, $total_rows, lang('Discussions'), $paging); ?>

            <?php $i = 0;
            foreach ($discussions as $key => $discussion) { ?>
                <div class="project_listing <?php if ($i == 3) { echo 'project_listing_last'; }  ?> left">
                    <a href="/projects/discussions/<?php echo $discussion['project_id'] ?>/<?php echo $discussion['id'] ?>">
                        <?php $src = safe_image(PROJECT_NO_IMAGE_PATH, DISCUSSION_IMAGE_PLACEHOLDER, null, array('max' => '198')) ?>
                        <div class="div_resize_img198">
                            <img src="<?php echo $src ?>" alt="<?php echo $discussion['title'] ?>'s photo">
                        </div>
                    </a>

                    <p>
                        <strong><?php echo $discussion['title'] ?></strong><br>
                    </p>
                    <p>
                        <strong><?php echo lang('Experts') ?>:</strong>&nbsp;&nbsp;<?php echo $discussion['expert_count'] ?><br>
                        <strong><?php echo lang('Posts') ?>:</strong>&nbsp;&nbsp;&nbsp;<?php echo $discussion['post_count'] ?><br>
                        <strong><?php echo lang('Created') ?>:</strong>&nbsp;&nbsp;<?php echo format_date($discussion['created_at'], 'm/d/Y') ?><br>
                        <strong><?php echo lang('LastActivity') ?>:</strong>&nbsp;<?php echo ! empty($discussion['last_activity_at']) ? time_ago(time(), strtotime($discussion['last_activity_at']), 'ago') : '&mdash;' ?>
                    </p>
                </div>
                <?php $i++; if ($i == 4) $i = 0; } ?>

            <?php if (count($discussions) == 0) { ?>
                <div>
                    <div class="clear">&nbsp;</div>
                    <h3 align="center"><?php echo lang('NoDiscussionsToDisplay') ?></h3>
                    <div class="clear">&nbsp;</div>
                </div>
            <?php } ?>

            <?php echo form_paging(false, $page_from, $page_to, $total_rows, lang('Discussions'), $paging); ?>

        </div><!-- end .inner -->
    </div><!-- end #col5 -->
</div>
