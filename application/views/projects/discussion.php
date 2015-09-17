<div id="content" class="clearfix">
    <div id="col2" class="projects">
        <section class="projectdata white_box">
            <?php $src = safe_image(PROJECT_NO_IMAGE_PATH, DISCUSSION_IMAGE_PLACEHOLDER, null, array('max' => '164')) ?>
            <img src="<?php echo $src ?>" alt="Discussion's photo">
            <h1><?php echo $discussion['title'] ?></h1>
            <span style="word-wrap:break-word;">
                <?php echo nl2br($discussion['description']) ?>
            </span>
        </section><!-- end .portlet -->

        <div class="comments white_box pull_up_white">
            <h2><?php echo lang('ProjectDiscussionFeedTitle') ?></h2>
            <?php
                $author_src = expert_image(sess_var('userphoto'), 43);
                $placeholder = lang('UpdateCommentPlaceholder');
            ?>
            <div class="comment-wrapper post main-post">
                <div class="photo">
                    <img src="<?php echo $author_src ?>" class="thumb" alt="" />
                </div>
                <div class="comment">
                    <?php echo form_open('/discussions/post/' . $discussion['id'], 'name="post_update"', array(
                        'author' => sess_var('uid'),
//                        'type' => ($executive['uid'] == sess_var('uid')) ? UPDATE_TYPE_STATUS : UPDATE_TYPE_COMMENT,
                    )) ?>
                    <div class="field-wrapper">
                        <textarea class="post-comment" placeholder="<?php echo $placeholder ?>"></textarea>
                        <div class="errormsg"></div>
                        <input type="submit" class="light_green" value="<?php echo lang('PostUpdate') ?>">
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>

            <ul class="feed updates">
                <!-- Populated in JS -->
            </ul>

            <div class="center">
                <?php echo form_open('/discussions/feed/' . $discussion['id'], 'name="updates_view_more"'); ?>
                <input type="submit" class="view-more button" value="<?php echo lang('LoadMoreUpdates') ?>">
                <?php echo form_close() ?>
            </div>
        </div>
    </div><!-- end #col2 -->

    <div id="col3" class="projects">
        <a href="/projects/<?php echo $project['pid'] ?>" class="button project light_gray"><?php echo lang('ProjectProfile') ?></a>
        <a href="/projects/discussions/<?php echo $project['pid'] ?>" class="button discussion light_gray"><?php echo lang('Discussions') ?></a>
        <?php if ($project['uid'] == sess_var('uid')) { ?>
<!--            <a href="/projects/discussions/edit/--><?php //echo $discussion['id'] ?><!--" class="button edit light_gray">--><?php //echo lang('DiscussionEdit') ?><!--</a>-->
        <?php } else { ?>
            <a href="#" id="project_send_message" class="button mail light_gray"><?php echo lang('Message') ?></a>
        <?php } ?>

        <?php
        $fullname = (($executive['membertype'] == MEMBER_TYPE_EXPERT_ADVERT) ? $executive['organization'] : $executive['firstname'] . ' ' . $executive['lastname']);

        // Show project executive panel only if it's not CG/LA (uid = 492)
        // We need to get rid of these magic numbers
        ?>
        <?php if ($executive['uid'] != 492) { ?>
        <section class="executive white_box" id="project_executive">
            <?php
            $fullname = (($executive['membertype'] == MEMBER_TYPE_EXPERT_ADVERT) ? $executive['organization'] : $executive['firstname'] . ' ' . $executive['lastname']);
            $src = expert_image($executive['userphoto'], 138, array(
                'width' => 138,
                'rounded_corners' => array('all', '2'),
                'crop' => TRUE
            ));
            ?>
            <h2><?php echo (($executive["membertype"] == MEMBER_TYPE_EXPERT_ADVERT) ? lang('Organization') : lang('ProjectExecutive')) ?></h2>

            <div class="image">
                <a href="/expertise/<?php $executive['uid'] ?>"></a>
                <img src="<?php echo $src ?>" alt="<?php echo $fullname ?>'s photo" style="margin:0px;">
            </div>

            <div class="executive-details">
                <h2 class="name"><a href="/expertise/<?php echo $executive['uid']; ?>"><?php echo $fullname; ?></a></h2>
                <?php $orgmemberid =  is_organization_member($executive['uid']);

                if ($executive["membertype"] != MEMBER_TYPE_EXPERT_ADVERT && isset($orgmemberid) && $orgmemberid != '' ) { ?>
                    <p><strong><?php echo $executive['title'] ?></strong></p>
                    <p><a href="/expertise/<?php echo $orgmemberid ?>"><?php echo $executive['organization'] ?></a></p>
                <?php } else if ($executive["membertype"] != MEMBER_TYPE_EXPERT_ADVERT) {?>
                    <p><strong><?php echo $executive['title'] ?></strong></p>
                    <p><?php echo $executive['organization'] ?></p>
                <?php } else { ?>
                    <p><?php echo $executive['discipline'] ?></p>
                <?php } ?>
            </div>
        </section>
        <?php } ?>

        <!-- Discussion Experts -->
        <section class="portlet white_box">
            <h4><?php echo lang('DiscussionExperts') ?></h4>
            <ul class="expert_list">
                <?php foreach ($experts as $expert)  {
                    $src = expert_image($expert['userphoto'], 39); ?>
                    <li class="clearfix" style="min-height:55px;">
                        <a href="/expertise/<?php echo $expert['id'] ?>" class="image">
                            <img src="<?php echo $src ?>" alt="<?php echo $expert['expert_name'] ?>'s photo">
                        </a>
                        <p>
                            <a href="/expertise/<?php echo $expert['id'] ?>"><?php echo $expert['expert_name'] ?></a><br>
                            <span class="title"><?php echo $expert['title'] ?></span><br>
                            <span class="title"><?php echo $expert['organization'] ?></span><br>
                        </p>
                    </li>
                <?php } ?>
                <?php if (count($experts) == 0) { ?>
                    <li class="clearfix"><?php echo lang('NoExpertiseplay') ?></li>
                <?php } ?>
            </ul>
        </section><!-- end .portlet -->
    </div><!-- end #col3 -->
</div><!-- end #content -->

<div id="dialog-message"></div>

<?php $this->load->view('templates/_send_email', array(
    'to' => $project['uid'],
    'to_name' => $fullname,
    'from' => sess_var('uid')
)) ?>
