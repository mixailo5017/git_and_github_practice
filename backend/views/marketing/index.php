<div class="centercontent">
    
    <div class="pageheader notab">
            <h1 class="pagetitle"><?php echo $headertitle; ?></h1>
            <span class="pagedesc">&nbsp;</span>
            
        </div><!--pageheader-->
  
        
        <div id="contentwrapper" class="contentwrapper">
			<?php echo form_open('/marketing/generatehtml',array('id'=>'weekly_email_form','class'=>'stdform stdform2','method'=>'POST','name'=>'weekly_email_form'));?>

            <div class="contenttitle2">
                <h3>Experts</h3>
            </div>
            
            <div class="formsection">
                <?php for($i = 0; $i<4; $i++) { ?>
                    <input name="experts[]" type="text" placeholder="e.g. https://www.gvip.io/expertise/28">
                <?php } ?>
            </div>

            <div class="contenttitle2">
                <h3>Projects</h3>
            </div>
            
            <div class="formsection">
                <?php for($i = 0; $i<4; $i++) { ?>
                    <input name="projects[]" type="text" placeholder="e.g. https://www.gvip.io/projects/gateway-program">
                <?php } ?>
            </div>

                <div class="stdformbutton">
                	<button class="submit radius2">Generate HTML for Email</button>
                </div>
            <?php echo form_close(); ?>

            
            
        </div><!--contentwrapper-->
        
	</div>