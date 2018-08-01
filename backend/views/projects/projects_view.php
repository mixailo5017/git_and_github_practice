<div id="content" class="clearfix">

		<div id="col1">

			<section class="portlet">
					<?php
					$imgurl = $project['projectdata']["projectphoto"]!=""?$project['projectdata']["projectphoto"]:"placeholder_project.jpg";
					$imgpath = $project['projectdata']["projectphoto"]!=""?PROJECT_IMAGE_PATH:PROJECT_NO_IMAGE_PATH;


					$project_photo = array(
				      'src' => $imgpath."150_150_".$imgurl,
				      'width' => '150',
				      'height' => '150',
				      'alt'		=> 'project_photo'
				    );
				    echo img($project_photo);
				?><h3><?php echo $project['projectdata']["projectname"];?></h3>
				<?php echo $project['projectdata']["description"];?>
			</section><!-- end .portlet -->

			<section class="portlet white_box" id="project_executive">
				<h1>Project Executive</h1>

				<div class="image">
				<?php

				$imgurl  = $userdata["userphoto"]!=""?$userdata["userphoto"]:"profile_image_placeholder.png";
				$imgpath = $userdata["userphoto"]!=""?USER_IMAGE_PATH:USER_NO_IMAGE_PATH;

					$image_properties = array(
					          'src' => $imgpath.'138_138_'.$imgurl,
					          'alt' => $userdata['firstname']." ".$userdata['lastname']." 's photo",
					          'style' => 'margin:0px'
					        );

					echo '<div class="div_resize_img138">'. img($image_properties).'</div>';
				?>

				</div>

				<h2 class="name"><?php echo $userdata['firstname']." ".$userdata['lastname'];?></h2>

				<p><strong><?php echo $userdata['title'];?></strong></p>

				<p><a href="#"><?php echo $userdata['organization'];?></a></p>
			</section><!-- end .portlet -->
		</div><!-- end #col1 -->

		<div id="col2" class="white_box">

			<div id="project_tabs">

			<?php

				$style_fundamental  = (($project['fundamental']['totalfundamental']-count($project['fundamental']["map_point"])) == 0) ? 'style="display:none;"' : 'style="display:block;"';
				$style_financial    = ($project['financial']['totalfinancial'] == 0) ? 'style="display:none;"' : 'style="display:block;"';
				$style_participants = ($project['participants']['totalparticipants'] == 0) ? 'style="display:none;"' : 'style="display:block;"';
				$style_procurement  = ($project['procurement']['totalprocurement'] == 0) ? 'style="display:none;"' : 'style="display:block;"';
				$style_files 		= ($project['files']['totalfiles'] == 0) ? 'style="display:none;"' : 'style="display:block;"';
			?>
				<ul>
					<li><a href="#tabs-1">Overview</a></li>
						<li><a href="#tabs-2" <?php echo $style_fundamental;?> >Fundamentals</a></li>
						<li><a href="#tabs-3" <?php echo $style_financial;?>>Financial</a></li>
						<li><a href="#tabs-4" <?php echo $style_participants;?>>Participants</a></li>
						<li><a href="#tabs-5" <?php echo $style_procurement;?>>Procurement</a></li>
						<li><a href="#tabs-6" <?php echo $style_files;?>>Files</a></li>
				</ul>


				<div id="tabs-1" class="col2_tab">

					<?php if(count($project['fundamental']["map_point"]) >0)
					{
					?>


					<div class="map_box clearfix">



					<style type="text/css">
						#project-map{
							width: 568px;
							height: 256px;
						}
						#project-map .olControlAttribution {
							bottom: .05em;
						}
						.olControlLayerSwitcher .maximizeDiv {
							width: 28px !important;
						}
						.olControlLayerSwitcher .layersDiv input {
							padding:0;
							border:none;
							margin:0;
							position:relative;
							top:2px;
							left:-4px;
						}
					</style>

					<div id="project-map"></div>

					<?php foreach($project['fundamental']["map_point"] as $key=>$val)
					{?>
					<div class="clearfix">

								<p class="left"><?php echo $val['name'];?></p>

								<p class="right coord"><span class="geo"><span class="latitude"><?php echo $val['latitude'];?></span>, <span class="longitude"><?php echo $val['longitude'];?></span></span> <a href="#">Map</a></p>

							</div>

					<?php
					}
					?>
					</div>
					<?php } ?>


					<table class="overview_table">
						<tr>
							<th>Status:</th>
							<td><?php if($project['projectdata']['conceptual_status'] != '' ){echo $project['projectdata']['conceptual_status'];} else{echo "N/A";}?></td>
							<th>Location:</th>
							<td><?php if($project['projectdata']['location']!= ''){echo $project['projectdata']['location'];} else{echo "N/A";}?></td>
						</tr>
						<tr>
							<th>Sector:</th>
							<td><?php if($project['projectdata']['sector']!= ''){echo $project['projectdata']['sector'];} else{echo "N/A";}?></td>
							<th>Sub-Sector:</th>
							<td><?php if($project['projectdata']['subsector'] != ''){echo $project['projectdata']['subsector'];} else{echo $project['projectdata']['subsector_other'];}?></td>
						</tr>
						<tr>
							<th>Financial Structure:</th>
							<td><?php if($project['projectdata']['financialstructure']!= ''){echo $project['projectdata']['financialstructure'];} else{echo "N/A";}?>
							</td>
							<th>Budget:</th>
							<td><?php if($project['projectdata']['financialstructure']!= ''){echo CURRENCY.$project['projectdata']['totalbudget'];} else{echo "N/A";}?>
								</td>
						</tr>
						<tr>
							<th>Est. Start:</th>
							<td>August 2, 2012</td>
							<th>Est. Completion:</th>
							<td>August 20, 2012</td>
						</tr>
					</table>
					<div class="project_comment_block">
						<div id="load_comment_form" style="padding-bottom:10px">
						<?php
						$totalcomment = count($project["comment"]);
						if($totalcomment >0) {
							echo heading("Comments to:",3);
							$i = 1;
							foreach($project["comment"] as $comments) {
						?>
							<div id="comment_<?php echo $comments["id"]; ?>">
							<div><div class="fld" style="width:100%"><?php echo $comments["comment"]; ?></div><div style="float:left; position:absolute; right:15px;"><a href="javascript:void(0)" onclick="delete_maxtrix_action('projects/delete_comment','<?php echo $comments["id"]; ?>','comment_<?php echo $comments["id"]; ?>')"><img src="/images/site/delete_icon.png" alt="Delete" width="15" height="15" title="Delete Comment" /></a></div></div>
							<div align="right" style="color:#AAAAAA"><?php echo DateFormat($comments["commentdate"],DATEFORMAT,TRUE); ?></div>
							<?php if($totalcomment != $i) { ?><hr style="margin:10px 0;"/> <?php } ?>
							</div>
						<?php
							$i++;
							}
						}
						?>
						</div>
						<?php if($project["isaddcomment"]) { echo form_open("projects/add_comment/".$slug."",array("id"=>"comment_form","class"=>"ajax_form")); ?>
							<div class="comment">
								<?php echo form_label("Leave a Comment/Update:","comment"); ?>
								<br>
								<?php echo form_textarea(array("class"=>"comment_box","name"=>"comment","id"=>"comment")); ?>
								<div class="errormsg" id="err_comment"></div>
								<br>

								<?php echo form_submit(array("name"=>"submit","id"=>"submit","value"=>"Submit")); ?>
							</div>
						<?php echo form_close(); } ?>
					</div>

				</div>


				<div id="tabs-2" class="col2_tab" <?php echo $style_fundamental;?>>



						<?php if(count($project['fundamental']['engineering']) >0)
						{
						?>

						<h3>Engineering</h3>

							<table width="100%">

								<tr>
									<th>Company:</th>
									<th>Role:</th>
									<th>Contact Name:</th>
									<th>Challenges:</th>
									<th>Innovations:</th>
									<th class="text_center">Schedule:</th>
								</tr>
								<?php foreach($project['fundamental']['engineering'] as $key => $engineering)
								{?>
									<tr>
										<td><?php if($engineering['company']!= ''){echo $engineering['company'];} else{echo "N/A";}?></td>
										<td><?php if($engineering['role']!= ''){echo $engineering['role'];} else{echo "N/A";}?></td>
										<td><?php if($engineering['contactname']!= ''){echo $engineering['contactname'];} else{echo "N/A";}?></td>
										<td><?php if($engineering['challenges']!= ''){echo $engineering['challenges'];} else{echo "N/A";}?></td>
										<td><?php if($engineering['innovations']!= ''){echo $engineering['innovations'];} else{echo "N/A";}?> </td>
										<td class="text_center"><?php if($engineering['schedule']!= ''){
										?>
											<a href="<?php echo PROJECT_IMAGE_PATH.$engineering['schedule'];?>" target="_blank">
												<img src="/images/icons/<?php echo filetypeIcon($engineering['schedule']);?>" alt="file" title="file">
											</a>
										<?php
											} else{ echo "No Schedule"; } ?>
										</td>
									</tr>
								<?php }?>
							</table>
						<?php } ?>

						<?php if(count($project['fundamental']['design_issue']) >0)
						{
						?>

						<h3>Design Issues</h3>

							<table width="100%">

								<tr>
									<th>Name:</th>
									<th>Description:</th>
									<th class="text_center">Attatchments:</th>
								</tr>
								<?php foreach($project['fundamental']['design_issue'] as $key => $design_issue)
								{?>
									<tr>
										<td><?php if($design_issue['title']!= ''){echo $design_issue['title'];} else{echo "N/A";}?></td>
										<td><?php if($design_issue['description']!= ''){echo $design_issue['description'];} else{echo "N/A";}?></td>

										<td class="text_center"><?php if($design_issue['attachment']!= ''){
										?>
											<a href="<?php echo PROJECT_IMAGE_PATH.$design_issue['attachment'];?>" target="_blank">
												<img src="/images/icons/<?php echo filetypeIcon($design_issue['attachment']);?>" alt="file" title="file">
											</a>
										<?php
											} else{ echo "No Attachments"; } ?>
										</td>

									</tr>
								<?php }?>

							</table>

						<?php } ?>

						<?php if(count($project['fundamental']['environment']) >0)
						{
						?>

						<h3>Environment</h3>

							<table width="100%">

								<tr>
									<th>Name:</th>
									<th>Description:</th>
									<th class="text_center">Attatchments:</th>
								</tr>

								<?php foreach($project['fundamental']['environment'] as $key => $environment)
								{?>
									<tr>
										<td><?php if($environment['title']!= ''){echo $environment['title'];} else{echo "N/A";}?></td>
										<td><?php if($environment['description']!= ''){echo $environment['description'];} else{echo "N/A";}?></td>
										<td  class="text_center">
										<?php if($environment['attachment']!= ''){
										?>
											<a href="<?php echo PROJECT_IMAGE_PATH.$environment['attachment'];?>" target="_blank">
												<img src="/images/icons/<?php echo filetypeIcon($environment['attachment']);?>" alt="file" title="file">
											</a>
										<?php } else{echo "No Attachments";}?></td>
									</tr>
								<?php }?>

							</table>

						<?php } ?>

						<?php if(count($project['fundamental']['studies']) >0)
						{
						?>

						<h3>Studies</h3>

							<table width="100%">

								<tr>
									<th>Name:</th>
									<th>Description:</th>
									<th class="text_center">Attatchments:</th>
								</tr>

								<?php foreach($project['fundamental']['studies'] as $key => $studies)
								{?>
									<tr>
										<td><?php if($studies['title']!= ''){echo $studies['title'];} else{echo "N/A";}?></td>
										<td><?php if($studies['description']!= ''){echo $studies['description'];} else{echo "N/A";}?></td>
										<td  class="text_center">
											<?php if($studies['attachment']!= ''){
											?>
												<a href="<?php echo PROJECT_IMAGE_PATH.$studies['attachment'];?>" target="_blank">
													<img src="/images/icons/<?php echo filetypeIcon($studies['attachment']);?>" alt="file" title="file">
												</a>
											<?php } else{echo "No Attachments";}?>
										</td>
									</tr>
								<?php }?>

							</table>

						<?php } ?>

						<?php if($project['projectdata']['fundamental_legal'] != '')
						{
						?>
							<h3>Legal</h3>

								<p><?php if($project['projectdata']['fundamental_legal'] != '' ){echo $project['projectdata']['fundamental_legal'];} else{echo "N/A";}?></p>
						<?php } ?>

				</div>


				<div id="tabs-3" class="col2_tab" <?php echo $style_financial;?>>


						<h3>Financial Structure</h3>
						<?php if(count($project['financial']['financial'])>0)
						{?>
							<p><strong>Financial Structure -</strong><?php if($project['financial']['financial']['name'] != '' ){echo $project['financial']['financial']['name'];} else{echo "N/A";}?></p>

							<p><?php if($project['financial']['financial']['contactinfo'] != '' ){echo $project['financial']['financial']['contactinfo'];} else{echo "N/A";}?></p>

						<?php } ?>

						<?php if(count($project['financial']['fund_sources']) >0)
						{
						?>

						<h3>Fund Sources</h3>

							<table width="100%">

								<tr>
									<th>Name:</th>
									<th>Role:</th>
									<th>Amount:</th>
									<th>Description:</th>
								</tr>

								<?php foreach($project['financial']['fund_sources'] as $key => $fund_sources)
								{?>
									<tr>
										<td><?php if($fund_sources['name']!= ''){echo $fund_sources['name'];} else{echo "N/A";}?></td>
										<td><?php if($fund_sources['role']!= ''){echo $fund_sources['role'];} else{echo "N/A";}?></td>
										<td><?php if($fund_sources['amount']!= ''){echo CURRENCY.$fund_sources['amount'];} else{echo "N/A";}?></td>
										<td><?php if($fund_sources['description']!= ''){echo $fund_sources['description'];} else{echo "N/A";}?></td>
									</tr>
								<?php }?>

							</table>

						 <?php } ?>

						<?php if(count($project['financial']['roi']) >0)
						{
						?>

						<h3>Return on Investment</h3>

							<table width="100%">

								<tr>
									<th>Name:</th>
									<th>Percent:</th>
									<th>Type:</th>
									<th>Approach:</th>
									<th>Key Study:</th>
								</tr>

								<?php foreach($project['financial']['roi'] as $key => $roi)
								{?>
									<tr>
										<td><?php if($roi['name']!= ''){echo $roi['name'];} else{echo "N/A";}?></td>
										<td><?php if($roi['percent']!= ''){echo $roi['percent']."%";} else{echo "N/A";}?></td>
										<td><?php if($roi['type']!= ''){echo $roi['type'];} else{echo "N/A";}?></td>
										<td><?php if($roi['approach']!= ''){echo $roi['approach'];} else{echo "N/A";}?></td>
										<td  class="text_center">
											<?php if($roi['keystudy']!= ''){
											?>
											<a href="<?php echo PROJECT_IMAGE_PATH.$roi['keystudy'];?>" target="_blank">
												<img src="/images/icons/<?php echo filetypeIcon($roi['keystudy']);?>" alt="file" title="file">
											</a>
											<?php } else{echo "No File";}?>
										</td>
									</tr>
								<?php }?>

							</table>
						<?php } ?>

						<?php if(count($project['financial']['critical_participants']) >0)
						{
						?>

						<h3>Critical Participants</h3>

							<table width="100%">

								<tr>
									<th>Name:</th>
									<th>Role:</th>
									<th>Description:</th>
								</tr>

								<?php foreach($project['financial']['critical_participants'] as $key => $critical_participants)
								{?>
									<tr>
										<td><?php if($critical_participants['name']!= ''){echo $critical_participants['name'];} else{echo "N/A";}?></td>
										<td><?php if($critical_participants['role']!= ''){echo $critical_participants['role'];} else{echo "N/A";}?></td>
										<td><?php if($critical_participants['description']!= ''){echo $critical_participants['description'];} else{echo "N/A";}?></td>
									</tr>
								<?php }?>

							</table>

						<?php } ?>

				</div>


				<div id="tabs-4" class="col2_tab" <?php echo $style_participants;?>>

						<?php if(count($project['participants']['public']) >0)
						{
						?>
						<h3>Public</h3>

							<table width="100%">

								<tr>
									<th>Name:</th>
									<th>Type:</th>
									<th>Description:</th>
								</tr>

								<?php foreach($project['participants']['public'] as $key => $public)
								{?>
									<tr>
										<td><?php if($public['name']!= ''){echo $public['name'];} else{echo "N/A";}?></td>
										<td><?php if($public['type']!= ''){echo $public['type'];} else{echo "N/A";}?></td>
										<td><?php if($public['description']!= ''){echo $public['description'];} else{echo "N/A";}?></td>
									</tr>
								<?php }?>

							</table>

						<?php } ?>

						<?php if(count($project['participants']['political']) >0)
						{
						?>

						<h3>Political</h3>

							<table width="100%">

								<tr>
									<th>Name:</th>
									<th>Type:</th>
									<th>Description:</th>
								</tr>

								<?php foreach($project['participants']['political'] as $key => $political)
								{?>
									<tr>
										<td><?php if($political['name']!= ''){echo $political['name'];} else{echo "N/A";}?></td>
										<td><?php if($political['type']!= ''){echo $political['type'];} else{echo "N/A";}?></td>
										<td><?php if($political['description']!= ''){echo $political['description'];} else{echo "N/A";}?></td>
									</tr>
								<?php }?>

							</table>
						<?php } ?>

						<?php if(count($project['participants']['companies']) >0)
						{
						?>

						<h3>Companies</h3>

							<table width="100%">

								<tr>
									<th>Name:</th>
									<th>Role:</th>
									<th>Description:</th>
								</tr>

								<?php foreach($project['participants']['companies'] as $key => $companies)
								{?>
									<tr>
										<td><?php if($companies['name']!= ''){echo $companies['name'];} else{echo "N/A";}?></td>
										<td><?php if($companies['type']!= ''){echo $companies['type'];} else{echo "N/A";}?></td>
										<td><?php if($companies['description']!= ''){echo $companies['description'];} else{echo "N/A";}?></td>
									</tr>
								<?php }?>

							</table>
						<?php } ?>

						<?php if(count($project['participants']['owners']) >0)
						{
						?>

						<h3>Owners</h3>

							<table width="100%">

								<tr>
									<th>Name:</th>
									<th>Type:</th>
									<th>Description:</th>
								</tr>

								<?php foreach($project['participants']['owners'] as $key => $owners)
								{?>
									<tr>
										<td><?php if($owners['name']!= ''){echo $owners['name'];} else{echo "N/A";}?></td>
										<td><?php if($owners['type']!= ''){echo $owners['type'];} else{echo "N/A";}?></td>
										<td><?php if($owners['description']!= ''){echo $owners['description'];} else{echo "N/A";}?></td>
									</tr>
								<?php }?>

							</table>

						<?php } ?>


				</div>


				<div id="tabs-5" class="col2_tab" <?php echo $style_procurement;?>>

						<?php if(count($project['procurement']['machinery']) >0)
						{
						?>

						<h3>Machinery</h3>

							<table width="100%">

								<tr>
									<th>Name:</th>
									<th>Procurement Process:</th>
									<th>Financial Information:</th>
								</tr>

								<?php foreach($project['procurement']['machinery'] as $key => $machinery)
								{?>
									<tr>
										<td><?php if($machinery['name']!= ''){echo $machinery['name'];} else{echo "N/A";}?></td>
										<td><?php if($machinery['procurementprocess']!= ''){echo $machinery['procurementprocess'];} else{echo "N/A";}?></td>
										<td><?php if($machinery['financialinfo']!= ''){echo $machinery['financialinfo'];} else{echo "N/A";}?></td>
									</tr>
								<?php }?>

							</table>

						<?php } ?>

						<?php if(count($project['procurement']['procurement_technology']) >0)
						{
						?>


						<h3>Key Technology</h3>

							<table width="100%">

								<tr>
									<th>Name:</th>
									<th>Procurement Process:</th>
									<th>Financial Information:</th>
								</tr>

								<?php foreach($project['procurement']['procurement_technology'] as $key => $procurement_technology)
								{?>
									<tr>
										<td><?php if($procurement_technology['name']!= ''){echo $procurement_technology['name'];} else{echo "N/A";}?></td>
										<td><?php if($procurement_technology['procurementprocess']!= ''){echo $procurement_technology['procurementprocess'];} else{echo "N/A";}?></td>
										<td><?php if($procurement_technology['financialinfo']!= ''){echo $procurement_technology['financialinfo'];} else{echo "N/A";}?></td>
									</tr>
								<?php }?>

							</table>
						<?php } ?>

						<?php if(count($project['procurement']['procurement_services']) >0)
						{
						?>

						<h3>Key Services</h3>

							<table width="100%">

								<tr>
									<th>Name:</th>
									<th>Type:</th>
									<th>Procurement Process:</th>
									<th>Financial Information:</th>
								</tr>


								<?php foreach($project['procurement']['procurement_services'] as $key => $procurement_services)
								{?>
									<tr>
										<td><?php if($procurement_services['name']!= ''){echo $procurement_services['name'];} else{echo "N/A";}?></td>
										<td><?php if($procurement_services['type']!= ''){echo $procurement_services['type'];} else{echo "N/A";}?></td>
										<td><?php if($procurement_services['procurementprocess']!= ''){echo $procurement_services['procurementprocess'];} else{echo "N/A";}?></td>
										<td><?php if($procurement_services['financialinfo']!= ''){echo $procurement_services['financialinfo'];} else{echo "N/A";}?></td>
									</tr>
								<?php }?>

							</table>
						<?php } ?>


				</div>


				<div id="tabs-6" class="col2_tab" <?php echo $style_files;?>>


						<?php if(count($project['files']['files']) >0)
						{
						?>

						<h3>Files</h3>

							<table width="100%">

								<tr>
									<th>File:</th>
									<th>Date:</th>
									<th>Description:</th>
								</tr>

								<?php foreach($project['files']['files'] as $key => $files)
								{
									$filedate = new DateTime($files['dateofuploading']);
								?>
									<tr>
										<td>
										<?php if($files['file']!= '' && $files['file'] != '0'){ ?>
											<a href="<?php echo PROJECT_IMAGE_PATH.$files['file'];?>" target="_blank">
												<img src="/images/icons/<?php echo filetypeIcon($files['file']);?>" alt="file" title="file">
											</a>
										<?php } else{echo "No File";}?> </td>
										<td><?php if($files['dateofuploading']!= ''){echo $filedate->format('M d,Y');} else{echo "N/A";}?></td>
										<td><?php if($files['description']!= ''){echo $files['description'];} else{echo "N/A";}?></td>
									</tr>
								<?php }?>

							</table>

						<?php } ?>

				</div>

			</div><!-- end #tabs -->

		</div><!-- end #col2 -->

		<div id="col3">


			<section class="portlet">

				<h4>Top Experts</h4>
				<ul class="expert_list">
				<?php if(count($project['topexperts']) >0)
				{
					foreach($project['topexperts'] as $key => $topexp)
					{?>
						<li class="clearfix">
							<?php

							$imgurl  = $topexp["userphoto"]!=""?$topexp["userphoto"]:"profile_image_placeholder.png";
							$imgpath = $topexp["userphoto"]!=""?USER_IMAGE_PATH:USER_NO_IMAGE_PATH;

							$user_topexperts_photo = array(
							  'src' 	=> $imgpath."39_39_".$imgurl,
							  'alt'		=> $topexp['firstname']." ".$topexp['lastname']
							  );

							echo '<div class="div_resize_img50">'. img($user_topexperts_photo).'</div>';
							?>
							<p><a href="/expertise/<?php echo $topexp['uid'];?>"><?php echo $topexp['firstname']." ".$topexp['lastname'];?></a><br>
							<span class="title"><?php echo $topexp['title'];?></span> <br>
							</p>
						</li>

					<?php }?>

				<?php }else{
					?>
					<li>No expertise found</li>
					<?php
				}
				?>
				</ul>

			</section><!-- end .portlet -->



			<section class="portlet white_box" style="min-height:200px;">
				<?php
				if($project["ad"]["totalad"] > 0) {
					$i = 0;
					foreach($project["ad"]["data"] as $ads) {
				?>
				<a href="<?php echo $ads["adurl"]; ?>" target="_blank">
					<?php
						$img_poperties = array(
							"src" => AD_IMAGE_PATH.$ads["adimage"],
							"width" => "160",
							"style" => $i==0?"margin:4px 0 10px 4px":"margin:0 0 0 4px"
						);
						echo img($img_poperties);
					?>
				</a>
				<?php $i++; } } else { ?>
				<h3 style="text-align:center;padding-top:65px;">AD AREA</h3>
				<?php } ?>
			</section><!-- end .portlet -->

		</div><!-- end #col3 -->



	</div><!-- end #content -->

	<div id="dialog-message"></div>