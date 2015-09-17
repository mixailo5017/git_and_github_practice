<div class="centercontent">
    
    <div class="pageheader notab">
            <h1 class="pagetitle"><?php echo $title; ?></h1>
            <a class="right goback" style="margin-right:20px;" href="/admin.php/dashboard"><span>Back</span></a>
            <div class="pagetitle2"><?php echo "(".$users['firstname']." ".$users['lastname'].")";?></div>
            <span class="pagedesc">&nbsp;</span>
            
        </div><!--pageheader-->
  
        
        <div id="contentwrapper" class="contentwrapper">
            	<h4>Email Settings</h4>
				<br>
				
				<?php
					 echo form_open('myaccount/update_email',array('id'=>'email_settings_form','name'=>'email_settings_form','method'=>'post','class'=>'stdform stdform2 ajax_add_form'));?>
					
					<?php $opt['email_settings_form'] = array(
												'lbl_username' 			=> array(
														'class'			=> 'left_label'
													),	
												'es_username'			=> array(
														'name' 			=> 'es_username',
														'id' 			=> 'es_username',
														'placeholder'	=> 'Enter your new email address',
														'autocomplete'	=> 'off',
														'class'		=> 'smallinput'
														),
												'lbl_password' 			=> array(
														'class' 		=> 'left_label'
													),
												'es_password'	=> array(
														'name' 			=> 'es_password',
														'id' 			=> 'es_password',
														'placeholder'	=> 'Enter your password to save changes',
														'autocomplete'	=> 'off',
														'maxlength'		=> '16',
														'class'		=> 'smallinput'
													)
												);?>
					<p style="border-top:1px #dddddd solid;">
						<label class="left_label" style="line-height:20px;">Primary email:</label>
						<span class="field" style="margin-bottom:0;"><a href="mailto:<?php echo $users['email'];?>"><?php echo $users['email'];?></a></span>
						
					</p>
					<p>
						<?php echo form_label('Update email:', 'es_username', $opt['email_settings_form']['lbl_username']);?>
						<span class="field" style="margin-bottom:0;">
							<?php echo form_input($opt['email_settings_form']['es_username']);?>
							<span class="errormsg"><?php echo form_error('es_username'); ?></span>
						</span>
						
					</p>
					
					<p>
						<?php echo form_label('Password:', 'es_password', $opt['email_settings_form']['lbl_password']);?>
						<span class="field" style="margin-bottom:0;">
							<?php echo form_password($opt['email_settings_form']['es_password']);?>
							<span class="errormsg"><?php echo form_error('es_password'); ?></span>
						</span>
					</p>

					<p class="stdformbutton">
						<?php echo form_submit('submit', 'Update Email','class = "light_green"');?>
					</p>
				
				<?php echo form_close();?>
				
			
			<br>
			<div class="clearfix">
				
				<h4>Password Settings</h4>
				<br>
				<?php
					 echo form_open('myaccount/update_password',array('id'=>'password_settings_form','name'=>'password_settings_form','method'=>'post','class'=>'stdform stdform2 ajax_add_form'));?>
					
					<?php $opt['password_settings_form'] = array(
												'lbl_currentpass'=> array(
														'class'			=> 'left_label'
													),	
												'ps_currentpass'		=> array(
														'name' 			=> 'ps_currentpass',
														'id' 			=> 'ps_currentpass',
														'autocomplete'	=> 'off',
														'maxlength'		=> '16',
														'class'			=> 'smallinput'
														),
												'lbl_newpassword' 	=> array(
														'class' 		=> 'left_label'
													),
												'ps_newpassword'	=> array(
														'name' 			=> 'ps_newpassword',
														'id' 			=> 'ps_newpassword',
														'value' 		=> '',
														'maxlength'		=> '16',
														'class'			=> 'smallinput'
													),
												'lbl_confpassword' 	=> array(
														'class' 		=> 'left_label'
													),
												'ps_confpassword'	=> array(
														'name' 			=> 'ps_confpassword',
														'id' 			=> 'ps_confpassword',
														'value' 		=> '',
														'maxlength'		=> '16',
														'class'			=> 'smallinput'
													)

												);?>
					
					<p style="border-top:1px #dddddd solid;">
						<?php echo form_label('Current:', 'ps_currentpass', $opt['password_settings_form']['lbl_currentpass']);?>
						<span class="field" style="margin-bottom:0;">
							<?php echo form_password($opt['password_settings_form']['ps_currentpass']);?>
							<span class="errormsg"></span>
						</span>
					</p>
					
					<p>
						<?php echo form_label('New:', 'ps_newpassword', $opt['password_settings_form']['lbl_newpassword']);?>
						<span class="field" style="margin-bottom:0;">
							<?php echo form_password($opt['password_settings_form']['ps_newpassword']);?>
							<span class="errormsg"></span>
						</span>
					</p>
					
					<p>
						<?php echo form_label('Retype:', 'ps_confpassword', $opt['password_settings_form']['lbl_confpassword']);?>
						<span class="field" style="margin-bottom:0;">
							<?php echo form_password($opt['password_settings_form']['ps_confpassword']);?>
							<span class="errormsg"></span>
						</span>
					</p>
	
					<p class="stdformbutton">
						<?php echo form_submit('submit', 'Update Password','class = "light_green"');?>
					</p>
				
				<?php echo form_close();?>

			</div>
			</div>

        </div><!--contentwrapper-->
        
	</div>