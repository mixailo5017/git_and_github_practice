<div class="centercontent">
    <div class="pageheader notab">
        <h1 class="pagetitle">Edit Forum</h1>
        <span class="pagedesc">&nbsp;</span>
        <a class="right goback" style="margin-right:20px;" href="/admin.php/forums"><span>Back</span></a>
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
                    <li><a href="#images">Images</a></li>
                    <li><a href="#projects">Projects</a></li>
                    <li><a href="#experts">Experts</a></li>
                </ul>
                <div id="general">
                    <?php $this->load->view('forums/_general', $details); ?>
                </div>
                <div id="images">
                    <?php $this->load->view('forums/_images', compact('details', 'categories')); ?>
                </div>
                <div id="projects">
                    <?php $this->load->view('forums/_projects', $projects); ?>
                </div>
                <div id="experts">
                    <?php $this->load->view('forums/_experts', $experts); ?>
                </div>
            </div><!-- tabs -->
        </div><!-- widgetcontent -->
    </div><!-- contentwrapper -->
</div><!-- centercontent -->
