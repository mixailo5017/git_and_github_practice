<div id="content" class="clearfix">
    <!--  Featured forum  -->
    <?php
    if (isset($featured)) {
        $forum_url = '/forums/' . $featured['id'];
    ?>
    <div class="center_col white_box featured-forum">
<!--        <h1 class="col_top section-title gradient">Featured Forum</h1>-->
        <span class="grid cols-2">
            <a href="<?php echo $forum_url ?>">
                <img src="<?php echo forum_image($featured['photo'], 276) ?>" class="featured-image" alt="<?php echo $featured['title'] . ' image' ?>" />
            </a>
        </span>
        <div class="grid cols-2 white-bg details">
            <h3 class="forum-title"><a href="<?php echo $forum_url ?>"><?php echo $featured['title'] ?></a></h3>
            <ul class="forum-details">
                <li><strong><?php echo lang('ForumDate') ?>: </strong> <span><?php echo forum_dates($featured['start_date'], $featured['end_date']) ?></span></li>
                <li><strong><?php echo lang('ForumVenue') ?>: </strong> <span><?php echo ($featured['venue_url']) ? anchor($featured['venue_url'], $featured['venue']) : $featured['venue'] ?></span></li>
                <li><strong><?php echo lang('ForumRegion') ?>: </strong> <span><?php echo $featured['category'] ?></span></li>
            </ul>
            <?php // TODO: Revisit ans escape data-name content ?>
            <?php if ($featured['register_url']) { ?>
                <a href="<?php echo $featured['register_url'] ?>"
                   target="_blank"
                   class="button light_gray attend"
                   data-id="<?php echo $featured['id'] ?>"
                   data-name="<?php echo $featured['title'] ?>" ><?php echo lang('ForumRegister') ?></a>
            <?php } ?>
            <?php if ($featured['meeting_url']) { ?>
                <a href="<?php echo $featured['meeting_url'] ?>"
                   target="_blank"
                   class="button light_gray book"><?php echo lang('ForumBookMeeting') ?></a>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
    <!-- End Featured Forums -->

    <!--  Paginated list view for forums  -->
    <div id="col5" class="center_col white_box">

        <h1 class="col_top gradient"><?php echo lang('forums');?></h1>

        <div class="project_filter clearfix">

            <?php echo form_open('forums/list', array('id' => 'forums_search_form', 'name' => 'forums_search_form', 'method' => 'get'));?>

            <div style="float:right;">
                <div class="filter_option">
                    <p><?php echo lang('Filterby')?>:</p>
                </div><!-- end .filter_option -->

                <div class="filter_option">
                    <?php
                    $options = array(
                        'all' => lang('ForumDateAll'),
                        'past' => lang('ForumDatePast'),
                        'upcoming' => lang('ForumDateUpcoming')
                    );
                    $selected = isset($filter_by['scope']) ? $filter_by['scope'] : 'all';
                    echo form_dropdown('scope', $options, $selected, 'id="scope"')
                    ?>
                </div>

                <div class="filter_option">
                    <?php
                    $options = array_merge(array('' => '- '. lang('ForumSelectRegion') . ' -'), $categories);
                    $selected = isset($filter_by['category']) ? $filter_by['category'] : '';
                    echo form_dropdown('category', $options, $selected, 'id="category"');
                    ?>
                </div><!-- end .filter_option -->

                <div style="float:right; padding-right:10px;">
                    <div class="filter_option">
                        <p><?php echo lang('Search')?> :</p>
                    </div>
                    <div class="filter_option">
                        <?php
                        echo form_input(array(
                            'name'  => 'search_text',
                            'id'    => 'search_text',
                            'value' => isset($filter_by['searchtext']) ? $filter_by['searchtext'] : ''
                        ));
                        ?>
                    </div>
                    <div class="filter_option">
                        <?php echo form_submit('search', lang('Search'), 'class = "light_green"');?>
                    </div>
                </div>
                <?php echo form_close();?>

            </div>
        </div>

        <div class="inner clearfix">
            <?php
            echo form_paging(true, $page_from, $page_to, $total_rows, lang('forums'), $paging);

            $index = 0;
            if ($total_rows > 0) {
                foreach($rows as $forum) {
                    $data = array(
                        'url' => base_url() . 'forums/' . $forum['id'],
                        'image' => array(
                            'url' => forum_image($forum['photo'], 198),
                            'alt' => $forum['title'] . ' image'
                        ),
                        'title' => '<strong>' . $forum['title'] . '</strong>',
                        'properties' => array(
                            array(lang('ForumDate'), forum_dates($forum['start_date'], $forum['end_date']),  1),
                            array(lang('ForumVenue'), $forum['venue'], 1),
                            array(lang('ForumRegion'), $forum['category'], 1)
                        ),
                        'last' => ($index == 3)
                    );

                    $this->load->view('templates/_list_block', $data);
                    $index = ($index == 3) ? 0 : $index + 1;
                }
            } else {
                echo form_list_empty(lang('NoForumsFound'));
//                $this->load->view('templates/_list_empty', array('what' => lang('NoForumsFound')));
            }
            ?>

            <div id="display-content"></div>

            <?php
            echo form_paging(false, $page_from, $page_to, $total_rows, lang('forums'), $paging);
            ?>
        </div><!-- end .inner -->
    </div><!-- end #col5 -->
</div><!-- end #content -->

<div id="dialog-message"></div>