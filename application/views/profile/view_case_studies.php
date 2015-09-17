<?php
//echo "<pre>";
?><div class="clearfix" id="content">
		<div class="more_case_studies">
			<span class="current_study">
				<span class="arrow">&nbsp;</span>
				<img alt="<?php echo $currentcase['name'];?>'s photo" src="<?php echo expert_image($currentcase['filename'], 102);?>" width="102" >
			</span>
			
			
			<?php
			
			if((count($case_studies)-1) > 0)
			{
				echo'<h2>'.lang('MoreCaseStudies').'</h2>';
				echo "<ul>";
				foreach($case_studies as $c => $cstudies)
				{
					if($cstudies['casestudyid'] == $currentcase['casestudyid'])
					{
						continue;
					}	
					?>
					<li>
					<a href="/profile/view_case_studies/<?php echo $cstudies['uid'];?>/<?php echo $cstudies['casestudyid'];?>">
						<img alt="<?php echo lang("CaseStudy");?>" src="<?php echo expert_image($cstudies['filename'], 102);?>" width="102" height="102">
						<span class="title"><?php echo $cstudies['name'];?></span>
					</a>
					</li>
				<?php
				}
				echo "</ul>";
			}
			?>
		</div>
		<div class="case_study">
			<h1><?php echo $currentcase['name'];?></h1>
			<?php echo $currentcase['description'];?>
		</div><!-- case_study -->
		
		<div class="side_portlets">
			<div class="company_info">
				<div class="logo">
				<a href="/expertise/<?php echo $users['uid'];?>/">
					<?php 
						if($users['membertype']=='8') {
							$src = company_image($users['userphoto'], 228, array('crop'=>false));
						} else {
							$src = expert_image($users['userphoto'], 228, array('crop'=>false));
						}
					?>
					<img alt="<?php echo lang('Companylogo');?>" src="<?php echo $src;?>" width="228">

				</a>
				</div>
			
				<h2><?php if(strlen($users['organization']) > 0) { echo $users['organization'];} else{echo "-";} ?></h2>
				<p><?php
					if(strlen($users['mission']) > 140)
					{
						echo substr($users['mission'],0,140).'...';
					}
					else
					{
						 echo $users['mission'];
					}								
				?></p>
			</div>
			<?php 
			if(count($topexpert['approved']) > 0)
			{
				echo '<div class="portlet_list">';
				echo '<h2>'.lang('OurTopExperts').'</h2>';
				echo '<ul>';
	
			   for($k=0;$k<count($topexpert['approved']);$k++)
				{?>
					<li class="clearfix">
					<a href="/expertise/<?php echo $topexpert['approved'][$k]['uid'];?>">
						<?php 
							$alt = $topexpert['approved'][$k]['firstname'].' '.$topexpert['approved'][$k]['lastname'].lang('sphoto');
							$img = expert_image($topexpert['approved'][$k]["userphoto"], 59);
						?>
						<img src="<?php echo $img; ?>" alt="<?php echo $alt;?>" style="margin:0px" />
						<span class="title"><?php echo $topexpert['approved'][$k]['firstname'].' '.$topexpert['approved'][$k]['lastname']; ?>
						<?php
							$toprow1 = '';

							if(isset($topexpert['approved'][$k]['title'])&& $topexpert['approved'][$k]['title'] != '')
							{
								$toprow1 .= $topexpert['approved'][$k]['title']; 
							}
							elseif(isset($topexpert['approved'][$k]['organization'])&& $topexpert['approved'][$k]['organization'] !='')
							{
								($topexpert['approved'][$k]['organizationid']) ? $organizationid = $topexpert['approved'][$k]['organizationid'] : $organizationid = 'javascript:void(0);';
								if($toprow1 == "") {
									$toprow1 .= $topexpert['approved'][$k]['organization'];
								} else {
									$toprow1 .= ', '.$topexpert['approved'][$k]['organization'];
								}
							}
							else
							{
								$toprow1 .= '';
							}
						?>

							<span class="expert_title"><?php echo ucfirst($toprow1);?></span>
						</span>
					</a>
				   </li>
			<?php 
				}
				echo '</ul></div>';
			}
			?>
			<!-- portlet_list -->

			<?php if($project['totalproj'] > 0)
			{
				$prcount = 1;
			?>
			<div class="portlet_list">
				<h2><?php echo lang('CurrentProjects');?></h2>
				<div class="inner">
					<ul>
						<?php foreach($project['proj'] as $projkey=>$projval)
						{?>
							<li class="clearfix <?php if($prcount> 2) { echo "hiddenproject"; } ?>" <?php if($prcount> 2) { echo "style='display:none'"; } ?>>
								<a href="/projects/<?php echo $projval['slug']; ?>">
								<?php
									$src = project_image($projval['projectphoto'], 59);
									$alt = $projval['projectname'];
								?>
									<img src="<?php echo $src;?>" alt="<?php echo $alt;?>" class="left img_border" />
									<span class="title"><?php echo $projval['projectname'];?> <span class="location"><?php  echo $projval['location']!=''?$projval['location']:"&mdash;";?></span></span>
								</a>
							
							</li>
						<?php $prcount++; } ?>
					</ul>
				</div>
				<!-- end .inner -->
				<?php //if($project['totalproj'] > 2) { ?>
				<div class="more" style="display:none;">
					<a href="javascript:void(0)"><?php echo lang('ShowMore');?></a> 
				</div>
				<!-- end .more -->
				<?php //} ?>
			</div>
		<?php }
		?>
					
					
		</div>
</div>