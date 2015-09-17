<?php
/**
 * Renders Send Message dialog box
 *
 * @var int $to
 * @var int $from
 **/
?>

<div id="model_email_div" style="display:none;">
    <?php echo form_open_multipart('expertise/send_message/', array('id' => 'model_email_form', 'class' => 'ajax_form')); ?>

    <?php echo form_hidden_custom('hdn_to',   $to,   FALSE, 'id="hdn_to"'); ?>
    <?php echo form_hidden_custom('hdn_from', $from, FALSE, 'id="hdn_from"'); ?>

    <div class="top">
        <h3><?php echo ($header = empty($to_name) ? lang('SendMessage') : lang('SendMessageto') . ' ' . $to_name) ?></h3>

        <div class="fld">
            <?php echo form_input(array(
                'type' => 'text',
                'id' => 'model_esubject',
                'name' => 'model_esubject',
                'placeholder' => lang('Subject'),
                'style' => 'width:345px'
            )); ?>
            <div class="errormsg" id="err_model_esubject"><?php echo form_error('model_esubject'); ?></div>
        </div>
        <br>
        <div class="fld">
            <?php echo form_textarea(array(
                'id' => 'model_emessage',
                'name' => 'model_emessage',
                'style' => 'width:345px',
                'rows' => '8'
            ));?>
            <div class="errormsg" id="err_model_emessage"><?php echo form_error('model_emessage'); ?></div>
        </div>
        <br>
    </div>

    <?php echo form_close(); ?>
</div>
