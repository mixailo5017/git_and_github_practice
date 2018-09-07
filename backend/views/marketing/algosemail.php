<div class="centercontent">
    
    <div class="pageheader notab">
            <h1 class="pagetitle"><?php echo $headertitle; ?></h1>
            <span class="pagedesc">&nbsp;</span>
            
        </div><!--pageheader-->
  
        
        <div id="contentwrapper" class="contentwrapper">
            <div class="contenttitle2">
                <h3>Monthly Email</h3>
            </div>
            <?php if ($emailSuccess === 'true') { ?>
                <div class="notibar msgsuccess">
                    <a class="close"></a>
                    <p>An email was successfully sent to all GViP members.</p>
                </div>
            <?php } elseif ($emailSuccess === 'false') { ?>
                <div class="notibar msgerror">
                    <a class="close"></a>
                    <p>Oh no! The email didn't send properly. Check the log file for more information.</p>
                </div>
            <?php } ?>
            <div>
                <form action="/admin.php/marketing/algosemail/email_all_members">
                    <button id="email-all-members">Send email to all members</button>
                </form>
            </div>
            <div class="contenttitle2">
                <h3>Forum Recommendations</h3>
            </div>
            <div>
                <a href="/admin.php/marketing/algosemail/forums/31">View LALF16 recommendations</a>
            </div>

        </div><!--contentwrapper-->
        
	</div>