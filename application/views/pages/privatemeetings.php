<section class="main-content container">
    <h1 class="h1-xl"><?php echo lang('ForumBookMeeting') ?></h1>

    <div class="general">
        <div class="interior">

            <div align="center" style="height:100%;margin:auto;"><div id ="SOIDIV_global10"></div></div><script type="text/javascript">if (window.attachEvent) { window.attachEvent('onload', loadSoeJs); } else { if (window.onload){  if (typeof isSeoFunctionLoaded == 'undefined') {isSeoFunctionLoaded = false;}if(!isSeoFunctionLoaded){var curronload = window.onload; var newonload = function (evt) { curronload(evt); loadSoeJs(evt); }; window.onload = newonload; isSeoFunctionLoaded = true;}} else { window.onload = loadSoeJs; }}function loadSoeJs() { var head = document.getElementsByTagName('head').item(0); var js = document.createElement('script'); js.setAttribute('type', 'text/javascript'); js.setAttribute('src', '//static.scheduleonce.com/mergedjs/ScheduleOnceEmbed.js'); js.setAttribute('defer', 'true'); js.async = true; head.appendChild(js); }</script><script type="text/javascript">(function(){function SOEScriptLoaded(){ if(typeof soe != 'undefined') { soe.AddEventListners("//secure.scheduleonce.com/global10?thm=white&bc=006DAF&tc=FFFFFF&dt=&em=1","global10","635px","100% !important","true"); } else { setTimeout(SOEScriptLoaded,500); } } setTimeout(SOEScriptLoaded,500)})()</script>

			<div class="clearfix" style="max-width: 796px; margin: 40px auto 10px; padding-left: 18px;">
				<p style="float: left; max-width: 525px;">Would you like to meet a forum attendee not listed above? Click the button to the right to see the full list of attendees (excluding those who have not yet joined GViP). From an attendee's profile page, click Message to introduce yourself and co-ordinate a meeting.</p>
				<a href="<?php echo '/forums/experts/' . $forumID; ?>" class="btn ctr lt-blue" style="margin-top: 15px;">View Forum Attendees</a>
			</div>
        </div>
    </div>
</section>