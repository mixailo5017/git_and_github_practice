<div class="centercontent">
    
    <div class="pageheader notab">
            <h1 class="pagetitle"><?php echo $headertitle; ?></h1>
            <span class="pagedesc">&nbsp;</span>
            
        </div><!--pageheader-->
  
        
        <div id="contentwrapper" class="contentwrapper">
			<div class="contenttitle2">
                <h3>Experts</h3>
            </div><!--contenttitle-->
            <?php if($status) { ?>
	   		<div class="notibar msgsuccess">
                <a class="close"></a>
                <p>$status variable evaluates to true</p>
            </div>
	   		<?php } ?>
            <?php echo form_open('/algolia/index',array('id'=>'update_setting_form','class'=>'stdform stdform2','method'=>'POST','name'=>'update_setting_form'));?>
            

                <p><a href="experts">View All Experts</a></p>
                
                <p class="stdformbutton">
                	<button class="submit radius2">Update Experts</button>
                </p>
                <?php echo form_hidden("update","experts"); ?>
            <?php echo form_close(); ?>
            
        </div><!--contentwrapper-->
        
	</div>