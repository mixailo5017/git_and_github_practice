<div class="centercontent">
    
    <div class="pageheader notab">
            <h1 class="pagetitle"><?php echo $headertitle; ?></h1>
            <span class="pagedesc">&nbsp;</span>
            
        </div><!--pageheader-->
  
        
        <div id="contentwrapper" class="contentwrapper">
			<h3>Norman Anderson</h3>
            <?php foreach ($recommendations as $recommendation) { ?>
                <div>
                    <div><a target="_blank" href="https://www.gvip.io/expertise/<?php echo $recommendation['uid'] ?>"><?php echo $recommendation['firstname'].' '.$recommendation['lastname'] ?> (<?php echo $recommendation['organization'] ?>)</div>
                </div>
            <?php } ?>

            
            
        </div><!--contentwrapper-->
        
	</div>