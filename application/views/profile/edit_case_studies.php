<div class="clearfix" id="content">

	<div id="col4">			
		<ul id="profile_nav">
			<li><a href="/profile/account_settings"><?php echo lang('ProfileInformation');?></a></li>
			<?php if($usertype == '8')
			{?>
				<li><a href="/profile/edit_seats"><?php echo lang('EditSeats');?></a></li>
				<li class="here"><a href="/profile/edit_case_studies"><?php echo lang('EditCaseStudies');?></a></li>
				<li><a href="javascript:void(0);"><?php echo lang('StorePurchaseHistory');?></a></li>
				<li><a href="javascript:void(0);"><?php echo lang('LicenseInformation');?></a></li>
			<?php
			}
			?>
			<li><a href="/profile/account_settings_email"><?php echo lang('EmailPassword');?></a></li>
		</ul>
	</div><!-- end #col4 -->

	<div id="col5" class="edit_case_studies">
		<h1 class="col_top gradient"><?php echo lang('EditCaseStudies');?></h1>
	
		<div class="profile_links">
			<div id="form_submit">
				<a href="/expertise/<?php echo $users['uid'];?>" class="light_gray"><?php echo lang('ViewMyProfile'); ?></a>
			</div>
		</div>
	
		<div class="inner">
				<p><?php echo lang('selectCaseStudies');?></p>
				
				<?php 
				//echo count($case_studies);
				$available_case_studies = (MAX_CASE_STUDIES - count($case_studies));
				$total_case_studies = count($case_studies);
				$j = 0;
				if(count($case_studies) > 0)
				{
					foreach($case_studies as $c => $cstudies)
					{?>
						<div class="edit_portlet">
                            <img alt=<?php echo lang("CaseStudy");?> src="<?php echo expert_image($cstudies['filename'], 88); ?>">

							<div class="content">
								<h2><?php echo $cstudies['name'];?></h2>
								<p><?php
									if(strlen($cstudies['description']) > 240)
									{
										echo substr(strip_tags($cstudies['description']),0,240).'...';
									}
									else
									{
										 echo strip_tags($cstudies['description']);
									}								
								?></p>
							</div>
							<div class="edit_buttons">
							<?php $deletelink = '/profile/delete_case_studies/'.$cstudies['casestudyid']; ?>
								<a href="javascript:void(0);" class="edit_button first" id="case_delete_<?php echo $j;?>" onclick="delete_case_studies('<?php echo $deletelink;?>',this);"><?php echo lang('Delete');?></a>
								<a href="javascript:void(0);" class="edit_button" id="case_studies_<?php echo $j;?>" onclick="edit_case_studies(this);"><?php echo lang('Edit');?></a>
								<a class="edit_button last" href="view_case_studies/<?php echo $cstudies['uid'];?>/<?php echo $cstudies['casestudyid'];?>"><?php echo lang('View');?></a>
							</div>
						</div>
									
						<div class="edit_portlet add_case_study" id="add_portlet" style="display:none;">
								<?php echo form_open_multipart('profile/update_case_study/'.$j,array("id"=>"update_case_study_form_".$j,"name"=>"update_case_study_form_".$j,'method'=>'post','class'=>'ajax_form')); ?>

                                <img alt=<?php echo lang("CaseStudy");?> src="<?php echo expert_image($cstudies['filename'], 88); ?>">

								<div class="file_upload" id="file_upload_<?php echo $j;?>">
									<?php  echo form_upload(array('name' => 'photo_filename','id' => 'comment_'.$j));?>
                                 </div>
								<div class='hiddenFields'>
										<?php echo form_hidden("RET",current_url()); ?>
										<?php echo form_hidden("hdn_casestudyid",$cstudies['casestudyid']); ?>
										<?php echo form_hidden("photo_filenam_hidden",$cstudies['filename']); ?>
								</div>
								
								<div class="edit_case_study_form">
								<?php echo form_hidden_custom("hdn_caseno",$j,FALSE,"id='hdn_caseno'"); ?>
									<div>
										<label><?php echo lang('Name');?>:</label>
										<?php echo form_input(array('type'=>'text','id'=>'case_name_'.$j,'name'=>'case_name_'.$j,'placeholder'=>lang('Name'),'value'=>$cstudies['name'])); ?>
										<div class="errormsg"></div>
									</div>
									
									<div>
										<label class="tinymce"><?php echo lang('Description');?>:</label>
										<?php echo form_textarea(array('type'=>'text', 'data-width'=>'612', 'class'=>'tinymce','id'=>'case_description_'.$j,'name'=>'case_description_'.$j,'value'=>$cstudies['description'])); ?>
										<div class="errormsg"></div>
									</div>
									
									<div>
										<div class="status">
											<span class="label"><?php echo lang('Status');?>:</span>
                                            <?php if($cstudies['status'] == 1)
											{$open = TRUE;$draft = FALSE;}
											else
											{$open = FALSE;$draft = TRUE;}
											?>
											<label><?php echo form_radio(array('name'=>'case_status_'.$j,'class'=>'styled'), '1', $open); ?> <span class="text"><?php echo lang('Open');?></span></label>
											<label><?php echo form_radio(array('name'=>'case_status_'.$j,'class'=>'styled'), '0', $draft); ?> <span class="text"><?php echo lang('Draft');?></span></label>
											<div class="errormsg"></div>								
										</div>
										<div class="form_buttons">
											<?php echo form_submit(array('name'	=> 'case_submit','value' => lang('Save'),'class' => 'light_green'));  ?>
											<?php echo form_button(array('name'	=> 'case_cancel','value' => 'Cancel','class' => 'light_gray','onclick'=>'cancle_case_studies(this)'),lang('Cancel'));  ?>
										</div>
									</div>
								</div>
								<?php echo form_close(); ?>
							</div>
			

					<?php 
					$j++;
					}
				} 
				?>
				<?php 
				for ($i = $j; $i < MAX_CASE_STUDIES; $i++)
				{?>
				<div class="edit_portlet empty" id="edit_portlet">
					<img alt="<?php echo lang("CaseStudy");?>" src="<?php echo expert_image('', 88); ?>">
					<div class="content">
						<p><?php echo lang('Emptycasestudy');?></p>
					</div>
					<div class="add_study">
						<a href="javascript:void(0);" style="padding:7px 14px" class="light_green btn_casestudy" id="case_studies_<?php echo $j;?>" onclick="edit_case_studies(this);"><?php echo lang('AddCaseStudy');?></a>
					</div>
				</div>

				<div class="edit_portlet add_case_study" id="add_portlet" style="display:none;">
					<?php echo form_open_multipart('profile/update_case_study/'.$j,array("id"=>"update_case_study_form_".$j,"name"=>"update_case_study_form_".$j,'method'=>'post','class'=>'ajax_form')); ?>
					
					<img alt=<?php echo lang("CaseStudy");?> src="<?php echo expert_image('', 88); ?>">

					<div class="file_upload" id="file_upload_<?php echo $j;?>">
						<?php  echo form_upload(array('name' => 'photo_filename','id' => 'comment_'.$j));?>
                         <div class="errormsg" id="err_photo_filename"></div>					
					</div>
					<div class='hiddenFields'>
							<?php echo form_hidden("RET",current_url()); ?>
							<?php echo form_hidden("hdn_casestudyid",''); ?>
							<?php echo form_hidden("photo_filenam_hidden",''); ?>
					</div>
					
					<div class="edit_case_study_form">
					<?php echo form_hidden_custom("hdn_caseno",$j,FALSE,"id='hdn_caseno'"); ?>
						<div>
							<label><?php echo lang('Name').':';?></label>
							<?php echo form_input(array('type'=>'text','id'=>'case_name_'.$j,'name'=>'case_name_'.$j,'placeholder'=>lang('Name'))); ?>
							<div class="errormsg"></div>
						</div>
						
						<div>
							<label class="tinymce"><?php echo lang('Description').':';?></label>
							<?php echo form_textarea(array('type'=>'text','data-width'=>'612', 'class'=>'tinymce','id'=>'case_description_'.$j,'name'=>'case_description_'.$j)); ?>
							<div class="errormsg"></div>
						</div>
						
						<div>
							<div class="status">
								<span class="label"><?php echo lang('Status').':';?></span>
								<label><?php echo form_radio(array('name'=>'case_status_'.$j,'class'=>'styled'), '1', TRUE); ?> <span class="text"><?php echo lang('Open');?></span></label>
								<label><?php echo form_radio(array('name'=>'case_status_'.$j,'class'=>'styled'), '0', FALSE); ?> <span class="text"><?php echo lang('Draft');?></span></label>
								<div class="errormsg"></div>								
							</div>
							<div class="form_buttons">
								<?php echo form_submit(array('name'	=> 'case_submit','value' => lang('Save'),'class' => 'light_green'));  ?>
								<?php echo form_button(array('name'	=> 'case_cancel','value' => 'Cancel','class' => 'light_gray','onclick'=>'cancle_case_studies(this)'),lang('Cancel'));  ?>
							</div>
						</div>
					</div>
					<?php echo form_close(); ?>
				</div>
				<?php $j++; } ?>
		</div><!-- end .inner -->

<div aria-labelledby="ui-dialog-title-dialog-message" class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-draggable ui-resizable" role="dialog" style="display: none; z-index: 1002; outline: 0px none; position: absolute; height: auto; width: 300px; top: 1050px; left: 558px;" tabindex="-1">
		<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
			<span id="ui-dialog-title-dialog-message" class="ui-dialog-title"><?php echo lang('Message');?></span>
			<a class="ui-dialog-titlebar-close ui-corner-all" href=javascript:void(0); role="button">
				<span class="ui-icon ui-icon-closethick"><?php echo lang('close');?></span>
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
	</div>