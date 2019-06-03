<section class="main-content container">
    <h1 class="h1-xl"><?php echo lang('ForumBookMeeting') ?></h1>

    <div class="general">
        <div class="interior">

            <div align="center" style="height:100%;margin:auto;">
                <!-- ScheduleOnce embed START -->
                <div id="SOIDIV_NALF10-PrivateMeetings" data-so-page="NALF10-PrivateMeetings" data-style="border: 1px solid #d8d8d8; min-width: 290px; max-width: 900px;" data-psz="11"></div>
                <script type="text/javascript" src="https://cdn.scheduleonce.com/mergedjs/so.js"></script>
                <!-- ScheduleOnce embed END -->
            </div>

			<div class="clearfix" style="max-width: 796px; margin: 40px auto 10px; padding-left: 18px;">
				<p style="float: left; max-width: 525px;">Would you like to meet a forum attendee not listed above? Click the button to the right to see the full list of attendees (excluding those who have not yet joined GViP). From an attendee's profile page, click Message to introduce yourself and co-ordinate a meeting.</p>
				<a href="<?php echo '/forums/experts/' . $forumID; ?>" class="btn ctr lt-blue" style="margin-top: 15px;">View Forum Attendees</a>
			</div>
        </div>
    </div>
</section>