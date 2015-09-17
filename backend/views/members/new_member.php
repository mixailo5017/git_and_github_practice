<div class="centercontent">
    
    <div class="pageheader notab">
            <h1 class="pagetitle"><?php echo $headertitle; ?></h1>
            <span class="pagedesc">&nbsp;</span>
            
        </div><!--pageheader-->
  
        
        <div id="contentwrapper" class="contentwrapper">
        
        
					<div class="contenttitle2">
                        <h3>Register New Member</h3>
                    </div><!--contenttitle-->
                 
                 	<?php echo form_open('/members/add_member',array('id'=>'add_member_form','class'=>'stdform stdform2','method'=>'POST','name'=>'add_member_form'));?>
                 	
                 	<?php 
                 	$opt = array(
						'lbl_firstname' => array(
				              'class' => 'left_label'
			            	),
		            	'member_first_name' => array(
			              'name'        => 'member_first_name',
			              'value'       => set_value("member_first_name"),
			              'class'		=> 'smallinput'
			            ),
			            'lbl_lastname' => array(
				              'class' => 'left_label'
			            	),
		            	'member_last_name' => array(
			              'name'        => 'member_last_name',
			              'value'       => set_value("member_last_name"),
			              'class'		=> 'smallinput'
			            ),
			            'lbl_email' => array(
				              'class' => 'left_label'
			            	),
		            	'email' => array(
			              'name'        => 'email',
			              'value'       => set_value("email"),
			              'class'		=> 'smallinput'
			            ),
			            'lbl_organization' => array(
				              'class' => 'left_label'
			            	),
		            	'member_organization' => array(
			              'name'        => 'member_organization',
			              'value'       => set_value("organization"),
			              'autocomplete' => "off",
			              'class'		=> 'smallinput'
			            ),
			            'lbl_password' => array(
				              'class' => 'left_label'
			            	),
		            	'register_password' => array(
			              'id'        => 'register_password',
			              'name'        => 'register_password',
			              'value'       => set_value("register_password"),
			              'autocomplete' => "off",
			              'class'		=> 'smallinput'
			            ),
			            'lbl_confirm' => array(
				              'class' => 'left_label'
			            	),
		            	'lbl_membertype' => array(
				              'class' => 'left_label'
			            	),
		            	'password_confirm' => array(
			              'id'        => 'password_confirm',
			              'name'        => 'password_confirm',
			              'value'       => set_value("password_confirm"),
			              'class'		=> 'smallinput'
			            )
			        );
			   ?>	
			   
                 	
                 		<p style="border-top:1px #dddddd solid;">
                        	<?php echo form_label('First Name:', 'member_first_name', $opt['lbl_firstname']);?>
                            <span class="field"><?php echo form_input($opt['member_first_name']);?></span>
                        </p>
                        
                        <p>
                        	<?php echo form_label('Last Name:', 'member_last_name', $opt['lbl_lastname']);?>
                            <span class="field"><?php echo form_input($opt['member_last_name']);?></span>
                        </p>
                        
                        <p>
                        	<?php echo form_label('Email:', 'email', $opt['lbl_email']);?>
							<span class="field">
								<?php echo form_input($opt['email']);?><br />
								<?php echo form_error('email'); ?>
							</span>
                        </p>
                        
                        <p>
                        	<?php echo form_label('Organization:', 'member_organization', $opt['lbl_organization']);?>
							<span class="field">
                            	<?php echo form_input($opt['member_organization']);?><br />
                            	<?php echo form_error('member_organization'); ?>
                            </span> 
                        </p>
                        
                        
                        <p>
                        	<?php echo form_label('Member Group:', 'member_type', $opt['lbl_membertype']);?>
							<span class="field">
                            	<?php
	                            	$group_attr = "class='radius3' id='member_group'";	
			                		$group_options = membergrouplist_Add();
                					echo form_custom_dropdown("member_group",$group_options,'',$group_attr,array(),array());
                            	?>
                            	<br />
                            	<?php echo form_error('member_membertype'); ?>
                            </span> 
                        </p>
                        
                        <p>
                        	<?php echo form_label('Password:', 'register_password', $opt['lbl_password']);?>
                            <span class="field">
                            	<?php echo form_password($opt['register_password']);?><br />
                            	<?php echo form_error('register_password'); ?>
                            </span> 
                        </p>
                        
                        <p style="display:none">
                            <span class="field">
                            <?php echo form_checkbox(array("name"=>"member_conference","id"=>"member_conference","value"=>"no","checked"=>"checked")); ?>Attending the 4th Annual North America Leadership Forum
                            <br /></span>
                        </p>
                        
                        
                        <p class="stdformbutton">
                        	<?php echo form_submit('submit', 'Register Memeber','class = "submit radius2"');?>
                        	<input type="reset" class="reset radius2" value="Reset Button" />
                        </p>
                    
                    <?php echo form_close();?>
					
                    <br />
                    
        	
        	
        </div><!--contentwrapper-->
        
	</div>