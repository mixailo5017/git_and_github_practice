<div class="centercontent">
    
    <div class="pageheader notab">
            <h1 class="pagetitle"><?php echo $headertitle; ?></h1>
            <span class="pagedesc">&nbsp;</span>
            
        </div><!--pageheader-->
  
        
        <div id="contentwrapper" class="contentwrapper">
			<div class="contenttitle2">
                <h3>Throttling Configuration</h3>
            </div><!--contenttitle-->
            <?php if($status) { ?>
	   		<div class="notibar msgsuccess">
                <a class="close"></a>
                <p>Throttling Configuration updated successfully!</p>
            </div>
	   		<?php } ?>
            <?php echo form_open('/security/throttling',array('id'=>'update_throttling_form','class'=>'stdform','method'=>'POST','name'=>'update_throttling_form'));?>
            <?php 
             	$opt = array(
					'isenabledyes' => array(
		              'name'        => 'isenabled',
		              'id'			=> 'isenabledyes',
		              'value'       => '1',
		              'checked'		=> $throttling["isenabled"]==1?TRUE:FALSE
		            ),
		            'isenabledno' => array(
		              'name'        => 'isenabled',
		              'id'			=> 'isenabledno',
		              'value'       => '0',
		              'checked'		=> $throttling["isenabled"]==0?TRUE:FALSE
		            ),
		            'ipdenyyes' => array(
		              'name'        => 'noipdeny',
		              'id'			=> 'noipdenyyes',
		              'value'       => '1',
		              'checked'		=> $throttling["noipdenyenabled"]==1?TRUE:FALSE
		            ),
		            'ipdenyno' => array(
		              'name'        => 'noipdeny',
		              'id'			=> 'noipdenyno',
		              'value'       => '0',
		              'checked'		=> $throttling["noipdenyenabled"]==0?TRUE:FALSE
		            ),
		            'maxpageloads' => array(
		            	'name' => 'max_page_loads',
		            	'id'	=> 'max_page_loads',
		            	'value' => $throttling["maxpageloads"],
		            	'class'	=> 'smallinput'
		            ),
		            'timeinterval' => array(
		            	'name' => 'time_interval',
		            	'id'	=> 'time_interval',
		            	'value' => $throttling["timeinterval"],
		            	'class'	=> 'smallinput'
					),
					'lockouttime' => array(
						'name' => 'lockout_time',
						'id'	=> 'lockout_time',
		            	'value' => $throttling["lockouttime"],
		            	'class'	=> 'smallinput'
					),
					'urlredirect' => array(
						'name' => 'urlredirect',
						'id'	=> 'urlredirect',
		            	'value' => $throttling["urlredirect"],
		            	'class'	=> 'smallinput'
					),
					'custommsg' => array(
						'name' => 'custommsg',
						'id'	=> 'custommsg',
		            	'value' => $throttling["custommsg"],
		            	'class'	=> 'smallinput'
					)

		        );
		   ?>
		   		<p style="">
            		<?php echo form_label('Enable Throttling', '');?>
                    <span class="field" style="margin-bottom:0px;">
                    	<?php echo form_radio($opt["isenabledyes"]); ?> Yes &nbsp; &nbsp;
                    	<?php echo form_radio($opt["isenabledno"]); ?> No
                    </span>
                </p>
                
                <p style="min-height:45px">
                	<?php echo form_label('Deny Access if No IP Address is Present', '');?>
                    <span class="field" style="margin-bottom:0px;">
                    	<?php echo form_radio($opt["ipdenyyes"]); ?> Yes &nbsp; &nbsp;
                    	<?php echo form_radio($opt["ipdenyno"]); ?> No
                    </span>
                </p>
                
                <p style="min-height:105px">
                	<?php echo form_label('Maximum Number of Page Loads<br/> <small>The total number of times a user is allowed to load any of your web pages (within the time interval below) before being locked out.</small>',''); ?>
                	<span class="field" style="margin-bottom:0px;">
                    	<?php echo form_input($opt["maxpageloads"]); ?>
                    </span>
                </p>
                
                <p style="min-height:85px">
                	<?php echo form_label('Time Interval (in seconds)<br/> <small>The number of seconds during which the above number of page loads are allowed.</small>',''); ?>
                    <span class="field" style="margin-bottom:0px;">
                    	<?php echo form_input($opt["timeinterval"]); ?>
                    </span>
                </p>
                
                <p style="min-height:85px">
                	<?php echo form_label('Lockout Time (in seconds)<br/> <small>The length of time a user should be locked out of your site if they exceed the limits.</small>',''); ?>
                    <span class="field" style="margin-bottom:0px;">
                    	<?php echo form_input($opt["lockouttime"]); ?>
                    </span>
                </p>
                
                <p style="min-height:65px">
                	<?php echo form_label('Action to Take<br/> <small>The action that should take place if a user has exceeded the limits.</small>',''); ?>
                    <span class="field" style="margin-bottom:0px;">
                    	<?php
                    		$action_options = array(
                    			"0" => "Send 404 headers",
                    			"1" => "URL Redirect",
                    			"2" => "Show custom message"
                    		);
                    		echo form_dropdown("action",$action_options,$throttling["action"],'');
                    	?>
                    </span>
                </p>
                
                <p style="min-height:45px">
                	<?php echo form_label('URL for Redirect<br/> <small>If you chose the URL Redirect option.</small>',''); ?>
                    <span class="field" style="margin-bottom:0px;">
                    	<?php echo form_input($opt["urlredirect"]); ?>
                    </span>
                </p>
                
                <p>
                	<?php echo form_label('Custom Message<br/> <small>If you chose the Custom Message option.</small>',''); ?>
                    <span class="field" style="margin-bottom:0px;">
                    	<?php echo form_input($opt["custommsg"]); ?>
                    </span>
                </p>
                
                <p class="stdformbutton">
                	<button class="submit radius2">Update</button>
                </p>
                <?php echo form_hidden("update","Update"); ?>
            <?php echo form_close(); ?>
            
        </div><!--contentwrapper-->
        
	</div>