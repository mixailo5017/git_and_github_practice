<div class="centercontent">
    
    <div class="pageheader notab">
            <h1 class="pagetitle"><?php echo $headertitle; ?></h1>
            <span class="pagedesc">&nbsp;</span>
            
        </div><!--pageheader-->
  
        
        <div id="contentwrapper" class="contentwrapper">
			<div class="contenttitle2">
                <h3>Experts</h3>
            </div><!--contenttitle-->
            <?php if($status === 'experts') { ?>
	   		<div class="notibar msgsuccess">
                <a class="close"></a>
                <p>Experts were successfully updated in Algolia.</p>
            </div>
	   		<?php } ?>
            <?php echo form_open('/algolia/index',array('id'=>'update_experts_form','class'=>'stdform stdform2','method'=>'POST','name'=>'update_experts_form'));?>
        
                <p class="stdformbutton">
                	<button class="submit radius2">Update Experts</button>
                </p>
                <?php echo form_hidden("update","experts"); ?>
            <?php echo form_close(); ?>

            <div class="contenttitle2">
                <h3>Projects</h3>
            </div><!--contenttitle-->
            <?php if($status === 'projects') { ?>
            <div class="notibar msgsuccess">
                <a class="close"></a>
                <p>Projects were successfully updated in Algolia.</p>
            </div>
            <?php } ?>
            <?php echo form_open('/algolia/index',array('id'=>'update_projects_form','class'=>'stdform stdform2','method'=>'POST','name'=>'update_projects_form'));?>
        
                <p class="stdformbutton">
                    <button class="submit radius2">Update Projects</button>
                </p>
                <?php echo form_hidden("update","projects"); ?>
            <?php echo form_close(); ?>
            
        </div><!--contentwrapper-->
        
	</div>