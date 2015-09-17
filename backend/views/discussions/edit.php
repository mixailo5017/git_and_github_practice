<div class="centercontent">
    <div class="pageheader notab">
        <h1 class="pagetitle">Edit Discussion</h1>
        <span class="pagedesc">&nbsp;</span>
        <a class="right goback" style="margin-right:20px;" href="/<?php echo index_page() ?>/discussions"><span>Back</span></a>
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
                    <li><a href="#experts">Experts</a></li>
                </ul>
                <div id="general">
                    <?php $this->load->view('discussions/_general', compact('discussion')) ?>
                </div>
                <div id="experts">
                    <?php $this->load->view('discussions/_experts', compact('discussion', 'experts')) ?>
                </div>
            </div><!-- tabs -->
        </div><!-- widgetcontent -->
    </div><!-- contentwrapper -->
</div><!-- centercontent -->

<script>
    jQuery("#tabs").tabs();
</script>
