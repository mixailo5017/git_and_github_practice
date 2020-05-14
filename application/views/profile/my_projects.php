<div id="content" class="clearfix">
	<div id="col4">
		<ul id="profile_nav">
			<li><a href='/profile/account_settings'><?php echo lang('ProfileInformation');?></a></li>
			<!-- ExpertAdverts Start-->
			<?php if($usertype == '8')
			{?>
				<li><a href="/profile/edit_seats"><?php echo lang('EditSeats');?></a></li>
				<li><a href="/profile/edit_case_studies"><?php echo lang('EditCaseStudies');?></a></li>
				<li><a href="javascript:void(0);"><?php echo lang('StorePurchaseHistory');?></a></li>
				<li><a href="javascript:void(0);"><?php echo lang('LicenseInformation');?></a></li>
			<?php
			}
			else
			{
			?>
			<li class="here"><a href="/profile/my_projects"><?php echo lang('MyProjects');?></a></li>
			<?php
			}
			?>
			<!-- ExpertAdverts End-->
			<li><a href="/profile/account_settings_email"><?php echo lang('EmailPassword');?></a></li>
		</ul>
	</div><!-- end #col4 -->
	
	<div id="col5">
		<h1 class="col_top gradient"><?php echo lang('ProfileInformation');?></h1>
		<div class="profile_links">
			<div id="form_submit">
				<a href="/expertise/<?php echo $users['uid'];?>" class="light_gray"><?php echo lang('ViewMyProfile'); ?></a>
			</div>
		</div>
	
		<div id="profile_tabs">
			<ul>
				<li><a href="#project-involvement"><?php echo lang('ProjectInvolvement'); ?></a></li>
			</ul>
			<div id="project-involvement" class="col5_tab project_form">
			
				<div class="clearfix">
				
					<p><strong><?php echo lang('MyProjectInvolvement');?></strong></p>
	
					
					<?php
					
					if($project['totalproj']> 0)
					{
						foreach($project['proj'] as $projkey=>$projval)
						{?>
						
						<div class="project clearfix">
						
							<img alt="<?php echo $projval['projectname'];?>" class="left img_border" width="48px" src="<?php echo project_image($projval["projectphoto"], 50); ?>" height="50">
		
							<p class="left" style="width: 50%;"><strong><?php echo $projval['projectname']; ?></strong></p>
							
							<span class="right">
								<a class="open_project" href="/projects/edit/<?php echo $projval['slug']; ?>"><?php echo lang('EditProject');?></a>
								<a class="view" href="/projects/<?php echo $projval['slug']; ?>"><?php echo lang('ViewProject');?></a>
							</span>
						
							
						</div><!-- .project -->
						
						<?php } 
					}
					else
					{
						?>
						<div class="clear">&nbsp;</div>
						<div align="center"><?php echo lang('noProjectTODisplay');?>.</div>
						<div class="clear">&nbsp;</div>
						<?php
					}	
					?>
					
				
		<!-- EDIT HERE -->
				<!-- the inline padding below won't be needed if this sits in tabs -->
					<h2><?php echo lang('ProjectsoutsideofViP');?></h2>
					<div class="clearfix matrix_dropdown project_executives">
					
			<?php					
					if(count($projectlink)> 0)
					{
						$cntLink = 1;
						foreach($projectlink as $projlinkkey=>$projlinkval)
						{?>

						<ul id="load_executive_form">
							<li class="" id="row_id_<?php echo $cntLink; ?>">
							
							<?php
									if($projlinkval['projectlink'] != '')
									{
										if(strstr($projlinkval['projectlink'],'https'))
										{
											$toplink = str_replace('https://','',$projlinkval['projectlink']);
											$link = 'https://'.$toplink;
										}
	
										else if(strstr($projlinkval['projectlink'],'http') || strstr($projlinkval['projectlink'],'www'))
										{
											$toplink = str_replace('http://','',$projlinkval['projectlink']);
											$link = 'http://'.$toplink;
										}
										else
										{
											$link = 'http://'.$projlinkval['projectlink'];
										}
									}
									
							
							?>
								<div class="view clearfix">
									<span class="left"><strong><?php echo $projlinkval['projectname'];?></strong>
										<a href="<?php echo $link;?>" target="_blank" class="external"><?php echo $projlinkval['projectlink'];?></a>
									</span>
									

									<a class="right delete" href="#profile/delete_projlink/<?php echo $projlinkval['linkid']?>"><?php lang('Delete');?></a>
									<a class="right edit" id="edit_executive_<?php echo $cntLink; ?>" href="javascript:void(0);"  onclick="rowtoggle(this.id);"><?php echo lang('Edit');?></a>

								</div>
								<div class="edit">
								<?php echo form_open('profile/update_project_link/'.$projlinkval['linkid'],array('id'=>'proj_link_form','name'=>'proj_link_form','method'=>'post','class'=>'ajax_form'));?>	
								<?php 
								
									$opt['proj_link_form'] = array(
											'lbl_projectname' => array(
													'class' => 'left_label'
													),
											'project_name'	=> array(
													'name' 		=> 'project_name',
													'id' 		=> 'project_name',
													'value'		=> $projlinkval['projectname'],
													),
											'lbl_projectlink' => array(
													'class' => 'left_label'
													),
											'project_link'	=> array(
													'name' 		=> 'project_link',
													'id' 		=> 'project_link',
													'value'		=> $projlinkval['projectlink'],
													)
								    );
		
								?>
								<?php echo form_hidden("hdn_project_link_id",$projlinkval["linkid"]); ?>
								
								<?php echo form_label(lang('NameOfProject').':', '', $opt['proj_link_form']['lbl_projectname']);?>
								<div class="fld">
									<?php echo form_input($opt['proj_link_form']['project_name']);?>
									<div id="err_project_name" class="errormsg"></div>
								</div>
								<br>
		
								<?php echo form_label(lang('ProjectLink').':', '', $opt['proj_link_form']['lbl_projectlink']);?>
								<div class="fld">
									<?php echo form_input($opt['proj_link_form']['project_link']);?>
									<div id="err_project_link" class="errormsg"></div>
								</div>
								<br>
		
								<?php echo form_submit('link_submit', lang('Update'),'class = "light_green btn_lml"');?>
								<input type="reset" name="" value="<?php echo lang('Close');?>" class="light_red btn_sml"  />
								
								<?php echo form_close(); ?>
										
							</div>
								
							</li>
						</ul>
						
						<?php 
						}
					}
				?>
						
						<ul>
							<li>
								<div class="view">
									<a id="addnewOutsideProject" class="edit project_row_add" href="javascript:void(0);" onclick="rowtoggle(this.id);">+ <?php echo lang('AddOutsideProject');?></a>
								</div>

								<div class="edit add_new">
								<?php echo form_open('profile/add_project_link/',array('id'=>'uproj_link_form','name'=>'uproj_link_form','method'=>'post','class'=>'ajax_form'));?>	
								<?php 
									$opt['uproj_link_form'] = array(
											'lbl_projectname' => array(
													'class' => 'left_label'
													),
											'project_name'	=> array(
													'name' 		=> 'project_name',
													'id' 		=> 'project_name',
													),
											'lbl_projectlink' => array(
													'class' => 'left_label'
													),
											'project_link'	=> array(
													'name' 		=> 'project_link',
													'id' 		=> 'project_link',
													)
								    );
		
								?>
								
								<?php echo form_label(lang('NameOfProject').':', '', $opt['uproj_link_form']['lbl_projectname']);?>
								<div class="fld">
									<?php echo form_input($opt['uproj_link_form']['project_name']);?>
									<div id="err_project_name" class="errormsg"></div>
								</div>
								<br>
		
								<?php echo form_label(lang('ProjectLink').':', '', $opt['uproj_link_form']['lbl_projectlink']);?>
								<div class="fld">
									<?php echo form_input($opt['uproj_link_form']['project_link']);?>
									<div id="err_project_link" class="errormsg"></div>
								</div>
								<br>
		
								<?php echo form_submit('link_submit', lang('AddthisProject'),'class = "light_green"');?>
								<input type="reset" name="" value="<?php echo lang('Close');?>" class="light_red btn_sml"  />	
								
								<?php echo form_close(); ?>
								
								</div>
								
							</li>
						</ul>

					</div>
					
					
				</div>
				<!-- END EDIT -->
					
					
				</div>
	
			</div>
	
		</div><!-- end #tabs -->
	
	
	
	<div aria-labelledby="ui-dialog-title-dialog-message" class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-draggable ui-resizable" role="dialog" style="display: none; z-index: 1002; outline: 0px none; position: absolute; height: auto; width: 300px; top: 1050px; left: 558px;" tabindex="-1">
		<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
			<span id="ui-dialog-title-dialog-message" class="ui-dialog-title">Message</span>
			<a class="ui-dialog-titlebar-close ui-corner-all" href=javascript:void(0); role="button">
				<span class="ui-icon ui-icon-closethick">close</span>
			</a>
		</div>
		<div id="dialog-message" class="ui-dialog-content ui-widget-content" scrollleft="0" scrolltop="0" style="width: auto; min-height: 12.8px; height: auto;">
			<?php echo lang('successupdated');?></div>
		<div class="ui-resizable-handle ui-resizable-n"></div>
		<div class="ui-resizable-handle ui-resizable-e"></div>
		<div class="ui-resizable-handle ui-resizable-s"></div>
		<div class="ui-resizable-handle ui-resizable-w"></div>
		<div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se ui-icon-grip-diagonal-se" style="z-index: 1001;"></div>
		<div class="ui-resizable-handle ui-resizable-sw" style="z-index: 1002;"></div>
		<div class="ui-resizable-handle ui-resizable-ne" style="z-index: 1003;"></div>
		<div class="ui-resizable-handle ui-resizable-nw" style="z-index: 1004;"></div>
		<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
			<div class="ui-dialog-buttonset">
				<button aria-disabled="false" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" type="button">
				<span class="ui-button-text"><?php echo lang('Ok');?></span></button>
			</div>
		</div>
	</div>
	
	</div><!-- end #col5 -->
</div><!-- end #content -->
