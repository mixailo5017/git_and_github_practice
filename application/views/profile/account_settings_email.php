<div id="content" class="clearfix account_settings_email">

		

	<div id="col4">
		<ul id="profile_nav">
			<li><a href="/profile/account_settings"><?php echo lang('ProfileInformation');?></a></li>
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
				<li><a href="/profile/my_projects"><?php echo lang('MyProjects');?></a></li>
			<?php
			}

			?>
			<!-- ExpertAdverts End-->
			<li  class="here"><a href="/profile/account_settings_email"><?php echo lang('EmailPassword');?></a></li>
		</ul>
        	
	</div><!-- end #col4 -->

	   <div id="col5">
		<h1 class="col_top gradient"><?php echo lang('ProfileInformation');?></h1>
		<div class="profile_links">
			<div id="form_submit">
				<a href="/expertise/<?php echo $users['uid'];?>" class="light_gray"><?php echo lang('ViewMyProfile'); ?></a>
			</div>
		</div>
        
		<div class="inner">
			
			<div class="clearfix">
				
				<h4><?php echo lang('EmailSettings');?></h4>

				<label class="left_label wide" style="line-height:20px;"><?php echo lang('CurrentEmail').':';?></label>
				
				
					<p><a href="mailto:<?php echo $users['email'];?>"><?php echo $users['email'];?></a></p>
				

				<?php
					 echo form_open('profile/update_email',array('id'=>'email_settings_form','name'=>'email_settings_form','method'=>'post','class'=>'ajax_form'));?>
					
					<?php $opt['email_settings_form'] = array(
												'lbl_username' 			=> array(
														'class'			=> 'left_label wide'
													),	
												'es_username'			=> array(
														'name' 			=> 'es_username',
														'id' 			=> 'es_username',
														'placeholder'	=> lang('Enteremailaddress'),
														'autocomplete'	=> 'off'
														),
												'lbl_password' 			=> array(
														'class' 		=> 'left_label wide'
													),
												'es_password'	=> array(
														'name' 			=> 'es_password',
														'id' 			=> 'es_password',
														'placeholder'	=> lang('Enterpassword'),
														'autocomplete'	=> 'off',
														'maxlength'		=> '16'
													)
												);?>
					<div>
						<?php echo form_label(lang('NewEmail').':', 'es_username', $opt['email_settings_form']['lbl_username']);?>
						<div class="fld">
							<?php echo form_input($opt['email_settings_form']['es_username']);?>
							<div id="err_es_username" class="errormsg"></div>
						</div>
					</div>
					
					<div>
						<?php echo form_label(lang('CurrentPassword').':', 'es_password', $opt['email_settings_form']['lbl_password']);?>
						<div class="fld">
							<?php echo form_password($opt['email_settings_form']['es_password']);?>
							<div id="err_es_password" class="errormsg"></div>
						</div>
					</div>

					<?php echo form_submit('submit',  lang('Update'),'class = "light_green btn_left_label_wide"');?>
				
				<?php echo form_close();?>
				
			</div>

			<div class="clearfix">
				
				<h4><?php echo lang('PasswordSettings');?></h4>
				
				<?php
					 echo form_open('profile/update_password',array('id'=>'password_settings_form','name'=>'password_settings_form','method'=>'post','class'=>'ajax_form'));?>
					
					<?php $opt['password_settings_form'] = array(
												'lbl_currentpass'=> array(
														'class'			=> 'left_label wide'
													),	
												'ps_currentpass'		=> array(
														'name' 			=> 'ps_currentpass',
														'id' 			=> 'ps_currentpass',
														'autocomplete'	=> 'off',
														'maxlength'		=> '16'
														),
												'lbl_newpassword' 	=> array(
														'class' 		=> 'left_label wide'
													),
												'ps_newpassword'	=> array(
														'name' 			=> 'ps_newpassword',
														'id' 			=> 'ps_newpassword',
														'value' 		=> '',
														'maxlength'		=> '16'
													),
												'lbl_confpassword' 	=> array(
														'class' 		=> 'left_label wide'
													),
												'ps_confpassword'	=> array(
														'name' 			=> 'ps_confpassword',
														'id' 			=> 'ps_confpassword',
														'value' 		=> '',
														'maxlength'		=> '16'
													)

												);?>
					
					<div>
						<?php echo form_label(lang('CurrentPassword').':', 'ps_currentpass', $opt['password_settings_form']['lbl_currentpass']);?>
						<div class="fld">
							<?php echo form_password($opt['password_settings_form']['ps_currentpass']);?>
							<div id="err_ps_currentpass" class="errormsg"></div>
						</div>
					</div>
					
					<div>
						<?php echo form_label(lang('NewPassword').':', 'ps_newpassword', $opt['password_settings_form']['lbl_newpassword']);?>
						<div class="fld">
							<?php echo form_password($opt['password_settings_form']['ps_newpassword']);?>
							<div id="err_ps_newpassword" class="errormsg"></div>
						</div>
					</div>
					
					<div>
						<?php echo form_label(lang('ConfirmPassword').':', 'ps_confpassword', $opt['password_settings_form']['lbl_confpassword']);?>
						<div class="fld">
							<?php echo form_password($opt['password_settings_form']['ps_confpassword']);?>
							<div id="err_ps_confpassword" class="errormsg"></div>
						</div>
					</div>
	
					<?php echo form_submit('submit', lang('Update'),'class = "light_green btn_left_label_wide"');?>
				
				<?php echo form_close();?>

			</div>

		</div><!-- end .inner -->



	<div aria-labelledby="ui-dialog-title-dialog-message" class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-draggable ui-resizable" role="dialog" style="display: none; z-index: 1002; outline: 0px none; position: absolute; height: auto; width: 300px; top: 1050px; left: 558px;" tabindex="-1">
		<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
			<span id="ui-dialog-title-dialog-message" class="ui-dialog-title"><?php echo lang('Message');?></span>
			<a class="ui-dialog-titlebar-close ui-corner-all" href="#" role="button">
				<span class="ui-icon ui-icon-closethick"><?php echo lang('close');?></span>
			</a>
		</div>
		<div id="dialog-message" class="ui-dialog-content ui-widget-content" scrollleft="0" scrolltop="0" style="width: auto; min-height: 12.8px; height: auto;">
			<?php echo lang('updatedMessage');?></div>
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
	