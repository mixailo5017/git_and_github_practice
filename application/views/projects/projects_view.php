<?php
if ($project['projectdata']['projectphoto'] != ''){
    $pci += 10;
}
if ($project['projectdata']['website'] != ''){
    $pci += 5;
}
if ($project['projectdata']['eststart'] != '1111-11-11'){
    $pci += 5;
}
if ($project['projectdata']['estcompletion'] != '1111-11-11'){
    $pci += 5;
}
if ($project['projectdata']['sponsor'] != ''){
    $pci += 5;
}
if ($project['projectdata']['developer'] != ''){
    $pci += 12;
}
if ($project['projectdata']['financialstructure'] != ''){
    $pci += 7;
}



if ($pci < 33){
    $color = "#e5405e";
}
elseif ($pci > 33 && $pci < 66){
    $color = "#e5e619";
}
else {
    $color = "#00FF00";
}

if ($pci < 100 && ($userdata['uid'] == sess_var('uid') || in_array(sess_var('uid'), INTERNAL_USERS) ) ) { ?>
<!-- Project Completeness Index Meter -->
<div id="meter" class="profile-meter" data-value="<?php echo $pci ?>" data-max="100">
    <p>Your project profile is <strong><?php echo $pci ?>%</strong> complete. Once you reach 'green' then we will be able to assess your project.</p>
    <div class="bar">
        <div class="progress" style="background: <?php echo $color ?>"></div>
    </div>
    <div class="cta-container">
        <button><?php echo lang('DismissReminder') ?></button>
        <a href="/projects/edit/<?php echo $slug ?>"><?php echo lang('EditProject');?></a>
    </div>
</div>
<?php } ?>

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
			
			
		<!-- temporary video embed fix for certain projects -->
		<?php if($project['pid'] == 3169){ ?>
            		<section class="projectdata white_box">
            			<div style="text-align: center">
                    			<iframe width="560" height="315" src="https://www.youtube.com/embed/30RECLGlTOg" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                		</div>
            		</section>
            	<?php } ?>

			<div id="project_tabs" class="white_box">

				<?php $this->load->view('projects/projects_view/overview', $project); ?>
				<?php
					foreach ($project_sections as $section => $appears) {
						$this->load->view("projects/projects_view/$section", $project);
					}
				?>

			</div><!-- end #tabs -->

            <?php if (isset($featuredForum)): ?>
                <div class="banner_image">
                    <a id="forum-banner" href="/forums/<?= $featuredForum['id'] ?>" data-name="<?= $featuredForum['title'] ?>" data-id="<?= $featuredForum['id'] ?>">
                        <img src="<?= forum_image($featuredForum['banner'], 600, ['fit' => 'contain']) ?>" class="uploaded_img" alt="<?= $featuredForum['title'] ?>" title="Click to learn more about this upcoming event, where you can meet project executives and infrastructure decision-makers.">
                    </a>
                </div>
            <?php endif; ?>




            <?php
			// Don't show Project Feed unless internal user
			if (in_array(Auth::id(), INTERNAL_USERS)) {
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


			<div class="comments white_box pull_up_white">
				<h2> Project News Feed </h2>
				<div style="padding-left:20px; padding-right:20px; padding-bottom:20px">
					<?php
					$accessKey = 'e453e9df057848cc8c186bd0222d7060';
					$endpoint = 'https://gvipprojnews.cognitiveservices.azure.com/bing/v7.0/news/search';
					$projectname = $project['projectdata']['projectname'];
					$term = $projectname." "."project";

                    function BingNewsSearch ($url, $key, $query) {
                        // Prepare HTTP request
                        // NOTE: Use the key 'http' even if you are making an HTTPS request. See:
                        // https://php.net/manual/en/function.stream-context-create.php
                        $headers = "Ocp-Apim-Subscription-Key: $key\r\n";
                        $options = array ('http' => array (
                            'header' => $headers,
                            'method' => 'GET' ));

                        // Perform the Web request and get the JSON response
                        $context = stream_context_create($options);
                        $result = file_get_contents($url . "?q=" . urlencode($query), false, $context);

                        // Extract Bing HTTP headers
                        $headers = array();
                        foreach ($http_response_header as $k => $v) {
                            $h = explode(":", $v, 2);
                            if (isset($h[1]))
                                if (preg_match("/^BingAPIs-/", $h[0]) || preg_match("/^X-MSEdge-/", $h[0]))
                                    $headers[trim($h[0])] = trim($h[1]);
                        }

                        return array($headers, $result);
                    }

                    //print "Searching news for: " . $term . "\n";

                    list($headers, $json) = BingNewsSearch($endpoint, $accessKey, $term);

                    //print "\nRelevant Headers:\n\n";
                    foreach ($headers as $k => $v) {
                        //print $k . ": " . $v . "\n";
                    }

					    //print "\nJSON Response:\n\n";
					    $obj = json_decode($json);
                        $json = json_encode($obj, JSON_PRETTY_PRINT);
                        //printf("<pre>%s</pre>", $json);


                    if (!empty($obj->value)) {

                            for ($x = 0; $x <= 4; $x++) {

                                if (!empty($obj->value[$x]->name)){

                                    printf("
                                    <div>
                                    <a style='font-size: medium' href=\"%s\">%s</a>            
                                    <p>%s</p> 
                                    <br> 
                                    </div>
                                 
                                ", $obj->value[$x]->url, $obj->value[$x]->name, $obj->value[$x]->description);
                                }
                            }
                        }
                    else{
                        printf("
                            <div>             
                                <p>There is no news in the past 30 days</p> 
                                <br>  
                             </div>
                               ");


                    }
					?>


			    </div>
			</div>




		</div><!-- end #col2 -->

		<div id="col3" class="projects">
		<h2>This Project Has <?php echo $likes; ?> Like(s)</h2>
        	<?php if ($isliked){ ?>
            		<a href="saveLikes/<?php echo $project['projectdata']['pid']; ?>" id="submit" name="submit"
               			class="button like light_gray">Unlike Project
            		</a>
        	<?php } else { ?>
            		<a href="saveLikes/<?php echo $project['projectdata']['pid']; ?>" id="submit" name="submit"
               			class="button like light_gray">Like Project
            		</a>
        	<?php }?>
		<a href="/projects/submit/<?php echo $project['pid']?>" onclick="myFunction()" class="button discussion light_gray">Learn More about this Project</a>
            	<p id="demo"></p>
            	<script>
                	function myFunction() {
                    	var txt;
                    	if (confirm("Confirm to have a CG/LA Representative contact you")) {
                        	txt = "We will be contacting you by email shortly!";
                    	} else {
                        	txt = "";
                    	}
                    	document.getElementById("demo").innerHTML = txt;
                	}
            </script>
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
               <a href="/projects/discussions/create/<?php echo $project['pid'] ?>" class="button discussion light_gray"><?php echo lang('DiscussionNew') ?></a>
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
                    'crop' => TRUE,
                    'fit'  => ($contactperson['membertype'] == MEMBER_TYPE_EXPERT_ADVERT) ? 'contain' : null
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
			
			
			            <?php if (in_array($userdata['uid'], INTERNAL_USERS)) { ?>
                <section class="executive white_box" id="project_executive">
                    <h2><?php echo (($contactperson['membertype'] == MEMBER_TYPE_EXPERT_ADVERT) ? lang('Organization') : "CG/LA Analyst") ?></h2>

                    <div class="image">
                        <?php
                        $src = expert_image($contactperson['userphoto'], 138, array(
                            'width' => 138,
                            'rounded_corners' => array( 'all','2' ),
                            'crop' => TRUE,
                            'fit'  => ($contactperson['membertype'] == MEMBER_TYPE_EXPERT_ADVERT) ? 'contain' : null
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
					shuffle($project['organizationmatch']);
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
					<?php  }
						$l++;
						$orgCount++;
					} ?>
				</section><!-- end .portlet -->
				<?php	}	?>
		</div><!-- end #col3 -->
	</div><!-- end #content -->

	<div id="dialog-message"></div>

    <?php $this->load->view('templates/_send_email', array(
        'to' => $contactperson['uid'],
        'to_name' => $contactperson['membertype'] == MEMBER_TYPE_EXPERT_ADVERT ? $contactperson['organization'] : $contactperson['firstname'] . ' ' . $contactperson['lastname'],
        'from' => sess_var('uid')
    )) ?>

<?php if (($project['projectdata']['lat'] && $project['projectdata']['lng']) || $isAdminorOwner )  { ?>
<script>
	var mapCoords = [<?php echo $project['projectdata']['lat'],',', $project['projectdata']['lng'];?>];
	var isAdmin = <?php echo $isAdminorOwner ? 'true' : 'false'; ?>;
	var slug = '<?php echo $slug; ?>';
	var map_geom = <?php echo json_encode($map_geom); ?>;
    var projectCountry = '<?php echo $project['projectdata']['country'] ?>';
</script>
<?php } ?>
