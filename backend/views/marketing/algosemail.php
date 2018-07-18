<div class="centercontent">
    
    <div class="pageheader notab">
            <h1 class="pagetitle"><?php echo $headertitle; ?></h1>
            <span class="pagedesc">&nbsp;</span>
            
        </div><!--pageheader-->
  
        
        <div id="contentwrapper" class="contentwrapper">
			
            <?php foreach ($attendees as $attendee) { ?>
                <div class="contenttitle2">
                    <h3><a target="_blank" href="https://www.gvip.io/expertise/<?= $attendee['uid'] ?>"><?= $attendee['firstname'].' '.$attendee['lastname'] ?></a></h3>
                </div>
                
                <?php
                if (count($attendee['recommendations'])) {
                    foreach ($attendee['recommendations'] as $recommendation) { ?>
                        <div>
                            <div><a target="_blank" href="https://www.gvip.io/expertise/<?php echo $recommendation['uid'] ?>"><?php echo $recommendation['firstname'].' '.$recommendation['lastname'] ?> (<?php echo $recommendation['organization'] ?>)</a></div>
                        </div>
                <?php 
                    }
                }
                else { ?>
                    <div>Sorry, no recommendations available.</div>
                <?php
                }
                
            } ?>
            

            
            
        </div><!--contentwrapper-->
        
	</div>