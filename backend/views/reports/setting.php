<div class="centercontent">
    
    <div class="pageheader notab">
            <h1 class="pagetitle"><?php echo $headertitle; ?></h1>
            <span class="pagedesc">&nbsp;</span>
            
        </div><!--pageheader-->
  
        
        <div id="contentwrapper" class="contentwrapper">
			<div class="contenttitle2">
                <h3>Setting</h3>
            </div><!--contenttitle-->
            <?php if($status) { ?>
	   		<div class="notibar msgsuccess">
                <a class="close"></a>
                <p>Google Analytics Setting updated successfully!</p>
            </div>
	   		<?php } ?>
            <?php echo form_open('/googleapi/setting',array('id'=>'update_setting_form','class'=>'stdform stdform2','method'=>'POST','name'=>'update_setting_form'));?>
            <?php 
             	$opt = array(
					'profileid' => array(
		              'name'        => 'profileid',
		              'id'			=> 'profileid',
		              'value'       => $setting["profileid"],
		              'class'		=> 'smallinput',
		            ),
		            'apikey' => array(
		              'name'        => 'api_key',
		              'id'			=> 'api_key',
		              'value'       => $setting["api_key"],
		              'class'		=> 'longinput'
		            ),
		            'clientid' => array(
		              'name'        => 'clientid',
		              'id'			=> 'clientid',
		              'value'       => $setting["clientid"],
		              'class'		=> 'longinput'
		            )

		        );
		   ?>
		   		<p style="border-top:1px solid #dddddd ">
            		<?php echo form_label('Profile ID', '');?>
                    <span class="field" style="margin-bottom:0px;">
                    	<?php echo form_input($opt["profileid"]); ?>
                    </span>
                </p>
                
                <p>
                	<?php echo form_label('API Key', '');?>
                    <span class="field" style="margin-bottom:0px;">
                    	<?php echo form_input($opt["apikey"]); ?>
                    </span>
                </p>
                
                <p>
                	<?php echo form_label('Client ID', '');?>
                    <span class="field" style="margin-bottom:0px;">
                    	<?php echo form_input($opt["clientid"]); ?>
                    </span>
                </p>

                
                
                <p class="stdformbutton">
                	<button class="submit radius2">Update</button>
                </p>
                <?php echo form_hidden("update","Update"); ?>
            <?php echo form_close(); ?>
            
        </div><!--contentwrapper-->
        
	</div>