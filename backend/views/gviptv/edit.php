<div class="centercontent">
    <div class="pageheader notab">
        <h1 class="pagetitle">Edit Forum</h1>
        <span class="pagedesc">&nbsp;</span>
        <a class="right goback" style="margin-right:20px;" href="/admin.php/gviptv"><span>Back</span></a>
    </div><!-- pageheader -->

    <div id="contentwrapper" class="contentwrapper">
        <div class="notibar_add" style="display:none">
            <a class="close"></a>
            <p></p>
        </div>

        <div class="widgetcontent">
            <div id="tabs">
                <ul>
                    <li><a href="#general">General</a></li>
                </ul>
                <div id="general">
                    <?php $this->load->view('gviptv/_general', $details); ?>
                </div>
            </div><!-- tabs -->
        </div><!-- widgetcontent -->
    </div><!-- contentwrapper -->
</div><!-- centercontent -->
