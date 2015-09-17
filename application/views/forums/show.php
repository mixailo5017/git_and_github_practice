<nav class="sub-nav">
    <div class="header_inner">
        <ul>
            <?php foreach ($forums_by_categories as $category) { ?>
                <li class="main">
                    <a href="#"><?php echo $category['category'] ?></a>
                    <ul>
                        <?php foreach ($category['forums'] as $forum) { ?>
                        <li><a href="/forums/<?php echo $forum['id'] ?>"><?php echo $forum['title'] ?></a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php }?>
        </ul>
    </div>
</nav>
<div id="content" class="clearfix">
    <div style="text-align: center;">
        <h1 class="large page-title"><?php echo $details['title']; ?></h1>
        <?php
        $dates = '';
        if ($details['start_date'] && $details['end_date']) {
            $dates = forum_dates($details['start_date'], $details['end_date']);
        }
        $venue = ($details['venue_url']) ? anchor($details['venue_url'], $details['venue']) : $details['venue'];
        ?>
        <p><?php echo implode(' - ', array_filter(array($dates, $venue))); ?></p>
    </div>

    <div id="col1">
        <?php $this->load->view('forums/_side_projects', array_merge($projects, array('id' => $details['id']))); ?>
    </div><!-- end #col1 -->

    <div id="col2">
        <?php $this->load->view('forums/_details', $details); ?>
    </div><!-- end #col2 -->

    <div id="col3">
        <?php $this->load->view('forums/_side_experts', array_merge($members, array('id' => $details['id']))); ?>
    </div><!-- end #col3 -->
</div><!-- end #content -->

<div id="dialog-message"></div>

<script>
    <?php //$userinfo = get_logged_userinfo(sess_var("uid")); ?>
    //var useremail = '<?php //echo $userinfo['email'];?>';
</script>