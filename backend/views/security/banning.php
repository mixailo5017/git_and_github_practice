<div class="centercontent">
    
    <div class="pageheader notab">
            <h1 class="pagetitle"><?php echo $headertitle; ?></h1>
            <span class="pagedesc">&nbsp;</span>
            
        </div><!--pageheader-->
  
        
        <div id="contentwrapper" class="contentwrapper">
			<div class="contenttitle2">
                <h3>Preferences</h3>
            </div><!--contenttitle-->
            <?php if($status) { ?>
	   		<div class="notibar msgsuccess">
                <a class="close"></a>
                <p>User Banning Preferences updated successfully!</p>
            </div>
	   		<?php } ?>
            <?php echo form_open('/security/banning',array('id'=>'update_banning_form','class'=>'stdform stdform2','method'=>'POST','name'=>'update_banning_form'));?>
            <?php 
             	$opt = array(
					'banned_ips' => array(
		              'name'        => 'banned_ips',
		              'id'			=> 'banned_ips',
		              'value'       => $security["bannedips"],
		              'class'		=> 'longinput',
		              'cols'		=> '80',
		              'rows'		=> '5'
		            ),
		            'banned_emails' => array(
		              'name'        => 'banned_emails',
		              'id'			=> 'banned_emails',
		              'value'       => $security["bannedemails"],
		              'class'		=> 'longinput',
		              'cols'		=> '80',
		              'rows'		=> '5'
		            )

		        );
		   ?>
		   		<p style="border-top:1px solid #dddddd ">
            		<?php echo form_label('Banned IP Address <small><font color="red">Place each IP address on a separate line</font><br>Use wildcards for partial IP addresses. Example: 123.345.*</small>', '');?>
                    <span class="field" style="margin-bottom:0px;">
                    	<?php echo form_textarea($opt["banned_ips"]); ?>
                    </span>
                </p>
                
                <p>
                	<?php echo form_label('Banned Email Address <small><font color="red">Place each email address on a separate line</font><br>Use wildcards for partial email addresses. Example: *@domain.com</small>', '');?>
                    <span class="field" style="margin-bottom:0px;">
                    	<?php echo form_textarea($opt["banned_emails"]); ?>
                    </span>
                </p>
                
                
                <p class="stdformbutton">
                	<button class="submit radius2">Update</button>
                </p>
                <?php echo form_hidden("update","Update"); ?>
            <?php echo form_close(); ?>
            
        </div><!--contentwrapper-->
        
	</div>