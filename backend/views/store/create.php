<div class="centercontent">
    <div class="pageheader notab">
        <h1 class="pagetitle">Add New Store Item</h1>
        <span class="pagedesc">&nbsp;</span>
    </div>

    <div id="contentwrapper" class="contentwrapper">
        <div class="contenttitle2">
            <h3>Details</h3>
        </div>

        <?php echo form_open_multipart(current_url(), array('id' => "new_store_item_form")); ?>
        <div id="div_general_photo_form" style="width:675px;">
            <div class="field">
                <?php echo form_label('Title:', 'title_label', array('class' => 'left_label')); ?>
                <div class="fld">
                    <?php echo form_input(array(
                        'id'	=> 'title',
                        'value' => set_value('title'),
                        'name'	=> 'title',
                        'placeholder' => 'Strategic Partnership'
                    )); ?>
                    <div class="errormsg" id="err_title"><?php echo form_error('title'); ?></div>
                </div>
            </div>

            <div class="field">
                <?php echo form_label('URL:', 'url_label', array('class' => 'left_label')); ?>
                <div class="fld">
                    <?php echo form_input(array(
                        'id'	=> 'url',
                        'value' => set_value('url'),
                        'name'	=> 'url',
                        'placeholder' => 'http://store.gvip.io/product/strategic-partnership/'
                    )); ?>
                    <div class="errormsg" id="err_url"><?php echo form_error('url'); ?></div>
                </div>
            </div>
        </div>

        <br/>

        <div>
            <?php
            echo form_submit('submit', 'Add New Item', 'class="light_green no_margin_left"');
            echo form_button('cancel', 'Cancel', 'class="light_gray no_margin_left" style="margin-left:10px;" onclick="window.location.href=\'/admin.php/store\'"');
            ?>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>