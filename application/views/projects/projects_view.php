<div id="content" class="clearfix">
		<div id="col2" class="projects">
			<section class="projectdata white_box">
                <?php $src= project_image($project['projectdata']['projectphoto'], 164, array(
                    'width' => 164,
                    'crop' => TRUE
                )) ?>
                <img src="<?php echo $src ?>" alt="<?php echo $project['projectdata']['projectname'] ?>'s photo">

                <h1><?php echo $project['projectdata']['projectname'] ?></h1>

                <p><em><?php echo lang('LastUpdated') ?>: <?php echo $project['projectdata']['last_updated']->diffForHumans() ?></em></p>
                <p class="project-description">
                    <?php
					$this->load->helper('text');
                    $limited_description = word_limiter($project['projectdata']['description'], 100, '');
					echo nl2br($limited_description);
                    if (mb_strlen($limited_description) < mb_strlen($project['projectdata']['description'])) {
                    ?>
                        <span class="text-cut">…</span>
                        <button type="button" class="show"><?php echo lang('ShowMore') ?></button>
                        <span class="overflow-text">
                        	<?php echo nl2br(mb_substr($project['projectdata']['description'], mb_strlen($limited_description) + 1)) ?>
                        	<button type="button" class="hide"><?php echo lang('ShowLess') ?></button>
                        </span>
                    <?php
                    }
					?>
				</p>
			</section><!-- end .portlet -->

			<div id="project_tabs" class="white_box">
			
				<?php $this->load->view('projects/projects_view/overview', $project); ?>
				<?php 
					foreach ($project_sections as $section => $appears) {
						$this->load->view("projects/projects_view/$section", $project);
					}
				?>

			</div><!-- end #tabs -->

			<?php 
			// Don't show Project Feed on official Brazilian projects
			if ($userdata['uid'] != BRAZIL_USER_ID) {
			?>
			<div class="comments white_box pull_up_white">
				<h2><?php echo lang('ProjectUpdatesTitle') ?></h2>
                <?php
                // If it is the project owner
                if ($userdata['uid'] == sess_var('uid')) {
                    $author_src = project_image($project['projectdata']['projectphoto'], 43);
                    $placeholder = lang('UpdateStatusPlaceholder');
                } else {
                    $author_src = expert_image(sess_var('userphoto'), 43);
                    $placeholder = lang('UpdateCommentPlaceholder');
                }
                ?>
				<div class="comment-wrapper post main-post">
					<div class="photo">
						<img src="<?php echo $author_src ?>" class="thumb" alt="" />
					</div>
					<div class="comment">
                        <?php
                        echo form_open('updates/post/project/' . $project['pid'], 'name="post_update"', array(
                            'author' => sess_var('uid'),
                            'type' => ($userdata['uid'] == sess_var('uid')) ? UPDATE_TYPE_STATUS : UPDATE_TYPE_COMMENT,
                        ));
                        ?>
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
		          <?php echo form_open('/updates/project/' . $project['pid'], 'name="updates_view_more"'); ?>
		            <input type="submit" class="view-more button" value="<?php echo lang('LoadMoreUpdates') ?>">
		            <?php echo form_close() ?>
		        </div>
			</div>
			<?php } ?>
		</div><!-- end #col2 -->

		<div id="col3" class="projects">
            <?php if ($userdata['uid'] != sess_var('uid')) {
                // User can't follow his or her own projects and send a message to him/her self
                echo form_open('', 'id="project_follow_form" name="follow_form"', array(
                    'context' => 'projects',
                    'id' => $project['pid'],
                    'action' => $project['isfollowing'] > 0 ? 'unfollow' : 'follow',
                    'return_follows' => 0
                )); ?>
                    <a href="#" id="submit" name="submit"
                       data-unfollow="<?php echo ($project['isfollowing'] > 0 ? lang('unfollow') : '') ?>"
                       class="button follow light_gray <?php echo ($project['isfollowing'] > 0 ? 'unfollow' : '')?>">
                        <span class="follow-text"><?php echo ($project['isfollowing'] > 0 ? lang('following') : lang('follow')) ?></span>
                        <!--[if IE 8]><span class="ie-8-unfollow">Unfollow</span><![endif]-->
                    </a>
                <?php echo form_close(); ?>
                <?php if (!in_array($userdata['uid'], INTERNAL_USERS)) { ?>
	                <a href="#" id="project_send_message" class="button mail light_gray"><?php echo lang('Message') ?></a>
	            <?php } ?>
            <?php } ?>
            <?php if ($project['discussions_access']) { ?>
                <a href="/projects/discussions/<?php echo $project['pid'] ?>" class="button discussion light_gray"><?php echo lang('Discussions') ?></a>
            <?php } ?>
            <?php if ($userdata['uid'] == sess_var('uid')) { ?>
<!--                <a href="/projects/discussions/create/--><?php //echo $project['pid'] ?><!--" class="button discussion light_gray">--><?php //echo lang('DiscussionNew') ?><!--</a>-->
                <a href="/projects/edit/<?php echo $slug ?>" class="button edit light_gray"><?php echo lang('EditProject');?></a>
            <?php } ?>

			<?php if (!in_array($userdata['uid'], INTERNAL_USERS)) { ?>
			<section class="executive white_box" id="project_executive">
                <h2><?php echo (($contactperson['membertype'] == MEMBER_TYPE_EXPERT_ADVERT) ? lang('Organization') : lang('ProjectExecutive')) ?></h2>

				<div class="image">
                <?php
                $src = expert_image($contactperson['userphoto'], 138, array(
                    'width' => 138,
                    'rounded_corners' => array( 'all','2' ),
                    'crop' => TRUE
                ));
                $fullname = (($contactperson['membertype'] == MEMBER_TYPE_EXPERT_ADVERT) ? $contactperson['organization'] : $contactperson['firstname'] . ' ' . $contactperson['lastname']);
                ?>
                <a href="/expertise/<?php echo $contactperson["uid"] ?>">
                    <img src="<?php echo $src ?>" alt="<?php echo $fullname ?>'s photo" style="margin:0px;">
                </a>
				</div>

				<div class="executive-details">
					<h2 class="name"><a href="/expertise/<?php echo $contactperson["uid"]; ?>"><?php echo $fullname; ?></a></h2>
					<?php 
					if ($contactperson["membertype"] != MEMBER_TYPE_EXPERT_ADVERT && isset($orgmemberid) && $orgmemberid!= '' ) { ?>
						<p><strong><?php echo $contactperson['title'];?></strong></p>
						<p><a href="/expertise/<?php echo $orgmemberid; ?>"><?php echo $contactperson['organization'];?></a></p>
					<?php } else if ($contactperson["membertype"] != MEMBER_TYPE_EXPERT_ADVERT) {?>
						<p><strong><?php echo $contactperson['title'] ?></strong></p>
						<p><?php echo $contactperson['organization'] ?></p>
					<?php } else { ?>
						<p><?php echo $contactperson['discipline'] ?></p>
					<?php } ?>
				</div>
			</section>
			<?php } ?>

            <?php // Visible only to the project owner ?>
            <?php if ($userdata['uid'] == sess_var('uid')) { ?>
            <!-- Global Experts -->
			<section class="portlet white_box">
				<h4>
                    <a href="/companies/<?php echo $project['lightning'] ?>" class="lightning"><?php echo lang('GlobalExperts');?></a>
                </h4>
				<ul class="expert_list">
                    <?php
                    $topexp_count = count($project['topexperts']);
                    $topexp_total = 0;

                    foreach ($project['topexperts'] as $expert)  {
                        $fullname = $expert['firstname'] . ' ' . $expert['lastname'];
                        $src = expert_image($expert['userphoto'], 39);
                        $topexp_total = $expert['row_count'];
                    ?>
                    <li class="clearfix" style="min-height:55px;">
                        <a href="/expertise/<?php echo $expert['uid'] ?>" class="image">
                            <img src="<?php echo $src ?>" alt="<?php echo $fullname ?>'s photo">
                        </a>
                        <p>
                            <a href="/expertise/<?php echo $expert['uid'] ?>"><?php echo $fullname ?></a><br>
                            <span class="title"><?php echo $expert['title'] ?></span><br>
                            <span class="title"><?php echo $expert['organization'] ?></span><br>
                        </p>
                    </li>
                    <?php } ?>
                    <?php if ($topexp_total > $topexp_count) { ?>
                        <li class="clearfix">
                            <a href="topexperts/<?php echo $slug ?>"><?php echo lang('ViewMore') ?></a>
                        </li>
                    <?php } ?>
                    <?php if ($topexp_count == 0) { ?>
                        <li class="clearfix"><?php echo lang('NoTopExpertsfound') ?></li>
                    <?php } ?>
				</ul>
			</section><!-- end .portlet -->

            <!-- SME Service Providers -->
            <section class="portlet white_box">
				<h4><?php echo lang('SMEServiceProviders') ?></h4>
				<ul class="expert_list">
                    <?php
                    $smeexp_count = count($project['smeexperts']);
                    $smeexp_total = 0;

                    foreach ($project['smeexperts'] as $expert)  {
                    $fullname = $expert['firstname'] . ' ' . $expert['lastname'];
                    $src = expert_image($expert['userphoto'], 39);
                    $smeexp_total = $expert['row_count'];
                    ?>
                    <li class="clearfix" style="min-height:55px;">
                        <a href="/expertise/<?php echo $expert['uid'] ?>" class="image">
                            <img src="<?php echo $src ?>" alt="<?php echo $fullname ?>'s photo">
                        </a>
                        <p>
                            <a href="/expertise/<?php echo $expert['uid'] ?>"><?php echo $fullname ?></a><br>
                            <span class="title"><?php echo $expert['title'] ?></span><br>
                            <span class="title"><?php echo $expert['organization'] ?></span><br>
                        </p>
                    </li>
                    <?php } ?>
                    <?php if ($smeexp_total > $smeexp_count) { ?>
                        <li class="clearfix">
                            <a href="smeexperts/<?php echo $slug ?>"><?php echo lang('ViewMore') ?></a>
                        </li>
                    <?php } ?>
                    <?php if ($smeexp_count == 0) { ?>
                        <li class="clearfix"><?php echo lang('NoSMEExpertsfound') ?></li>
                    <?php } ?>
				</ul>
			</section>
            <?php } ?>

            <?php // Similar Projects ?>
            <?php if (! empty($project['similar_projects'])) { ?>
                <div class="portlet white_box">
                    <h4><?php echo strtoupper(lang('SimilarProjects')) ?></h4>
                    <?php foreach ($project['similar_projects'] as $similar_project) { ?>
                        <article class="m_project">
                            <div class="image">
                                <div class="image_wrap">
                                    <a href="<?php echo '/projects/' . $similar_project['id'] ?>">
                                        <img src="<?php echo project_image($similar_project['projectphoto']) ?>" alt="<?php echo $similar_project['projectname'] . "'s photo" ?>">
                                    </a>
                                </div>
                                <span class="ps_<?php echo project_stage_class($similar_project['stage']) ?>"></span>
                                <span class="price"><?php echo format_budget($similar_project['totalbudget']) ?></span>
                            </div>
                            <div class="content">
                                <h3 class="the_title"><a href="<?php echo '/projects/' . $similar_project['id'] ?>"><?php echo $similar_project['projectname'] ?></a></h3>
                                <span class="type <?php echo project_sector_class($similar_project['sector']) ?>"><?php echo ucfirst($similar_project['sector']) ?></span>
                            </div>
                        </article>
                    <?php } ?>
                </div>
            <?php } ?>

			<?php
				$l = 0;
				if(count($project['organizationmatch']) >0)
				{
				?>
				<section class="portlet white_box expert-orgs">
					<h4><?php echo lang('ExpertOrganizations');?></h4>
				<?php
					$orgCount = 0;
					foreach($project['organizationmatch'] as $key => $orgexp)
					{
						if($orgexp['uid'] == $userdata['uid'])
						{
							continue;
						}
						if($orgCount < 3)
						{
							?>
						 
							<a href="/expertise/<?php echo $orgexp['uid'];?>">
                                <img alt="<?php echo $orgexp['firstname']." ".$orgexp['lastname']; ?>" src="<?php echo expert_image($orgexp["userphoto"], 168, array('fit' => 'contain'));?>" >
							</a>
							
					<?php }
						$l++;
						$orgCount++;
					} ?>
				</section><!-- end .portlet -->
				<?php	}	?>
		</div><!-- end #col3 -->
	</div><!-- end #content -->

	<div id="dialog-message"></div>

    <?php $this->load->view('templates/_send_email', array(
        'to' => $userdata['uid'],
        'to_name' => $userdata['membertype'] == MEMBER_TYPE_EXPERT_ADVERT ? $userdata['organization'] : $userdata['firstname'] . ' ' . $userdata['lastname'],
        'from' => sess_var('uid')
    )) ?>

<?php if (($project['projectdata']['lat'] && $project['projectdata']['lng']) || $isAdminorOwner )  { ?>
<script>
	var mapCoords = [<?php echo $project['projectdata']['lat'],',', $project['projectdata']['lng'];?>];
	var isAdmin = <?php echo $isAdminorOwner ? 'true' : 'false'; ?>;
	var slug = '<?php echo $slug; ?>';
	var map_geom = <?php echo json_encode($map_geom); ?>;
</script>
<?php } ?>