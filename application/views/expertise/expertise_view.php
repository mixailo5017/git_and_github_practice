<div class="clearfix" id="content">
    <div class="expert_profile" id="col6">
        <div class="expert_portlet white_box">
            <?php
            $img = expert_image($users["userphoto"], 140, array('rounded_corners' => array( 'all','2' )) );
            $name = $users['firstname'] . ' ' . $users['lastname'] . "'s photo";
            ?>
            <img src="<?php echo $img ?>" alt="<?php echo $name ?>" width="140" height="140" style="margin:0px">

            <div class="content">
                <?php /* <div class="ratings">92%</div> */ ?>
                <h1><?php echo $users['firstname']." ".$users['lastname']; ?></h1>
                <?php

                    $toprow1 = '';
                    if(isset($users['title'])&& $users['title'] != '')
                    {
                        $toprow1 .= ucfirst($users['title']);
                    }
                    if(isset($users['organization']) && $users['organization'] !='')
                    {
                        ($org_info["orgid"]!=0) ? $organizationid = $org_info["orgid"] : $organizationid = '';
                        if($organizationid)
                        {
                            if($toprow1 == '')
                            {
                                $toprow1 .='<a href="/expertise/'.$organizationid.'">'.ucfirst($users['organization']).'</a>';
                            }
                            else
                            {
                                $toprow1 .=', '.'<a href="/expertise/'.$organizationid.'">'.ucfirst($users['organization']).'</a>';
                            }
                        }
                        else
                        {
                            if($toprow1=='')
                            {
                                $toprow1 .= ucfirst($users['organization']);
                            }
                            else
                            {
                                $toprow1 .= ', '.ucfirst($users['organization']);
                            }
                        }
                    }
                    else
                    {
                        $toprow1 .= '';
                    }
                ?>

                <div class="title"><?php echo $toprow1;?></div>

                <?php
                        $fulllocation = array();

                        if($users['city']) {$fulllocation[] = $users['city'];}
                        if($users['state']){$fulllocation[] = $users['state'];}
                        if($users['country']){$fulllocation[] = $users['country'];}
                ?>
                <?php if(count($fulllocation) > 0){?>
                    <div class="more_info"><strong><?php echo lang('Location')?>: </strong><?php echo implode(', ',$fulllocation);?></div>
                <?php } ?>
                <?php if(isset($expertise['areafocus']) && $expertise['areafocus']!= ''){?>
                    <div class="more_info"><strong><?php echo lang('FocusAreas')?>: </strong><?php echo $expertise['areafocus'];?></div>
                <?php } ?>
            </div>
        </div><!-- expert_portlet -->
        
        <!-- Ratings Box. -->
        <section class="expert_profile white_box rating-block">
            <header>
                <h2><?php echo lang('Rating') ?>
                    <span class="star-rating" id="expert_rating"></span>
                    <span class="score"><?php echo $ratings['overall'] ? number_format($ratings['overall'], 1) : '' ?></span><span class="votes"><?php echo $ratings['unique_count'] ? '('.$ratings['unique_count'].')': '' ?></span>
                </h2>
                <div class="rating-meta">
                    <span class="toggle-desc"><?php echo lang('ShowMore') ?>/<?php echo lang('RateExpert') ?></span>
                    <span class="toggle icon-expand-more"></span>
                </div>
            </header>
            <div class="rating-details">
                <div class="results">
                    <p><?php echo sprintf(lang('RatingSummary'), $ratings['unique_count']) ?>:</p>
                    <dl>
                        <dt class="label"><?php echo lang('Helpful') ?></dt>
                        <dd class="star-rank" id="helpful"><span class="stars" id="helpful_rate"></span><span class="score"><?php echo $ratings['helpful'] ? number_format($ratings['helpful'], 1) : '' ?></span></dd>
                        <dt class="label"><?php echo lang('Responsive') ?></dt>
                        <dd class="star-rank" id="responsive"><span class="stars" id="responsive_rate"></span><span class="score"><?php echo $ratings['responsive'] ? number_format($ratings['responsive'], 1) : '' ?></span></dd>
                        <dt class="label"><?php echo lang('Knowledgeable') ?></dt>
                        <dd class="star-rank" id="knowledgeable"><span class="stars" id="knowledgeable_rate"></span><span class="score"><?php echo $ratings['knowledgeable'] ? number_format($ratings['knowledgeable'], 1) : '' ?></span></dd>
                    </dl>
                </div>
                <?php if (sess_var('uid') != $users['uid'] && ! $rated_by_me) { ?>
                <div class="voting">
                    <?php echo form_open(current_url() . '/rate',
                        array(
                            'name' => 'rate_expert_form',
                            'class' => 'expert-rating'
                        ),
                        array(
                            'ratings[1]' => 0,
                            'ratings[2]' => 0,
                            'ratings[3]' => 0
                        )) ?>
                        <h3><?php echo lang('RateExpert') ?></h3>
                        <dl>
                            <dt><?php echo lang('HowHelpful') ?></dt>
                            <dd><span id="helpful-vote"></span></dd>
                            <dt><?php echo lang('HowResponsive') ?></dt>
                            <dd><span id="responsive-vote"></span></dd>
                            <dt><?php echo lang('HowKnowledgeable') ?></dt>
                            <dd><span id="knowledgeable-vote"></span></dd>
                        </dl>
                        <input type="submit" class="btn std light_gray rate-expert inactive" value="<?php echo lang('AddRating') ?>" />
                    <?php echo form_close() ?>
                    <div class="voting-thankyou" style="display: none;">
                        <h3><?php echo lang('ThankYouHeader') ?></h3>
                        <p><?php echo lang('ThankYouBody') ?></p>
                    </div>
                </div>
                <div class="errormsg"></div>
                <?php } ?>
            </div>
        </section>
        <!-- End Ratings Box. -->

        <section class="expert_profile white_box">
            <header>
                <h2><?php echo lang('profile_main_profile_var',false,$users['firstname'])?></h2>
            </header>

            <?php if (! empty($expertise['summary'])) { ?>
                <h2><?php echo lang('SummaryofExpertise') ?></h2>
                <p><?php echo nl2br($expertise['summary']) ?></p>
            <?php }	?>

            <?php if (! empty($expertise['progoals'])) { ?>
                <h2><?php echo lang('ProfessionalGoals')?></h2>
                <p>
                    <?php echo nl2br($expertise['progoals']);?>
                </p>
            <?php }	?>

            <?php if (! empty($expertise['success'])) { ?>
                <h2><?php echo lang('ProfessionalSuccess')?></h2>
                <p>
                    <?php echo nl2br($expertise['success']);?>
                </p>
            <?php }	?>

            <?php if (count($myexpertise) > 0) { ?>
                <h3><?php echo lang('sector-subsectorFocus') ?></h3>
                <ul>
                <?php foreach( $myexpertise as $key_exp=>$my_exp) { ?>
                    <li><strong><?php echo $my_exp['sector'] ?></strong>&nbsp;<?php echo $my_exp['subsector'] ?></li>
                <?php } ?>
                </ul>
            <?php } ?>

            <?php if (count($education) > 0) { ?>
                <h3><?php echo lang('Education') ?></h3>
                <?php foreach($education as $key => $edu) { ?>
                    <div class="education">
                        <h4><?php echo $edu['university'];?></h4>
                        <ul>
                            <li><?php echo $edu['degree'].', '.$edu['major']." ".$edu['startyear'].' - '.$edu['gradyear'];?></li>
                        </ul>
                    </div>
                <?php } ?>
            <?php } ?>
        </section>
    </div><!-- end #col6 -->

    <div id="col7">
        <?php if ($users['uid'] != sess_var('uid')) {
            // User can't follow his or her own projects and send a message to him/her self
            echo form_open('', 'id="member_follow_form" name="follow_form"', array(
                'context' => 'expertise',
                'id' => $users['uid'],
                'action' => $isfollowing > 0 ? 'unfollow' : 'follow',
                'return_follows' => 0
            )); ?>
            <a href="#" id="submit" name="submit"
                   data-unfollow="<?php echo ($isfollowing > 0 ? lang('unfollow') : '') ?>"
                   class="button follow light_gray <?php echo ($isfollowing > 0 ? 'unfollow' : '')?>">
                <span class="follow-text"><?php echo ($isfollowing > 0 ? lang('following') : lang('follow')) ?></span>
                <!--[if IE 8]><span class="ie-8-unfollow">Unfollow</span><![endif]-->
            </a>
            <?php echo form_close(); ?>
            <a href="#" id="member_send_message" class="button mail light_gray<?php if($users['email_bouncing'] === '1') { ?> tooltip" title="<?php echo lang('EmailBounces'); } ?>"><?php echo lang('Message'); if($users['email_bouncing'] === '1') echo ' &#x26A0;'; ?></a>
        <?php } ?>


        <?php if ($project['totalproj'] > 0) { ?>
        <section class="portlet projects white_box">
            <header class="clearfix">
                <h1><?php echo lang('profile_sidebar_projects_var',false, $users['firstname'])?> </h1>
                <p><?php echo $project['totalproj'];?>&nbsp;<?php echo lang('Projects')?></p>
            </header>
            <div class="inner">
                <ul>
                <?php $prcount = 1 ?>
                <?php foreach($project['proj'] as $projkey=>$projval) { ?>
                    <li class="clearfix <?php if($prcount> 2) { echo 'hiddenproject'; } ?>" <?php if($prcount> 2) { echo 'style="display:none"'; } ?>>
                        <?php $src = project_image($projval['projectphoto'], 69); ?>
                        <div>
                            <img src="<?php echo $src ?>" alt="<?php echo $projval['projectname'] ?>'s photo" class="left img_border" width="69px">
                        </div>

                        <p class="right">
                            <a href="/projects/<?php echo $projval['slug'] ?>"><?php echo $projval['projectname'] ?></a><br>
                            <?php if (! empty($projval['subsector'])) { ?>
                                <strong><?php echo $projval['sector'] . '&nbsp;&nbsp;(' . $projval['subsector'] . ')' ?></strong><br/>
                            <?php } ?>
                        </p>
                    </li>
                    <?php $prcount++; } ?>
                </ul>
            </div><!-- end .inner -->
            <?php } ?>

            <?php if ($project['totalproj'] > 2) { ?>
                <div class="more">
                    <a href="javascript:void(0)"><?php echo lang('ShowMore')?></a>
                </div><!-- end .more -->
            <?php } ?>
        </section><!-- end .porlet -->
    </div><!-- end #col7 -->
</div> <!-- end #content -->

<div id="dialog-message"></div>

<?php $this->load->view('templates/_send_email', array(
    'to' => $users['uid'],
    'to_name' => $users['firstname'] . ' ' . $users['lastname'],
    'from' => sess_var('uid')
)) ?>
