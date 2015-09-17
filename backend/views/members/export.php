<div class="centercontent">
    <div class="pageheader notab">
        <h1 class="pagetitle">Export Experts</h1>
        <span class="pagedesc">&nbsp;</span>
    </div>

    <div id="contentwrapper" class="contentwrapper">
        <?php echo form_open(current_url(), array('name' => 'export_form')); ?>

        <div id="div_general_photo_form">
            <div class="field">
                <label for="fields[]">Fields to export:</label>
                <div class="fld">
                    <?php echo form_multiselect('fields[]', $fields, set_value('fields[]', $default_fields), 'data-placeholder="Choose a fields..." class="chosen-select" tabindex="1" style="width:350px;"') ?>
                    <div class="errormsg"><?php echo form_error('fields') ?></div>
                </div>
            </div>
            <div class="field">
                <label for="format">Export format:</label>
                <div class="fld">
                    <?php echo form_dropdown('format', array('tsv' => 'Excel tab delimited (TSV)', 'csv' => 'Generic comma delimited (CSV)'), set_value('format', 'tsv'), 'class="chosen-select" style="width:350px;"') ?>
                    <div class="errormsg"><?php echo form_error('format') ?></div>
                </div>
            </div>
            <div class="field">
                <label for="new_line">New line character:</label>
                <div class="fld">
                    <?php echo form_dropdown('new_line', array('n' => 'New Line (\n)', 'rn' => 'Carriage Return + New Line (\r\n)'), set_value('new_line', 'n'), 'class="chosen-select" style="width:350px;"') ?>
                    <div class="errormsg"><?php echo form_error('new_line') ?></div>
                </div>
            </div>
            <br/>
            <div>
                <?php
                echo form_submit('export', 'Export', 'class="light_green no_margin_left"');
                echo form_button('cancel', 'Cancel', 'class="light_gray no_margin_left" style="margin-left:10px;" onclick="window.location.href=\'/admin.php/members\'"');
                ?>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    jQuery("form[name=export_form] select").chosen();
</script>