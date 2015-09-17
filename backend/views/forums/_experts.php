<div id="forum_expert_list_form" class="clearfix">
    <div class="contenttitle2">
        <h3>Expert List</h3>
    </div>
    <br/>

    <?php
    echo form_open(current_url() . '/#experts', array('id' => 'forum_experts_form'));
    echo form_hidden('update', 'experts');
    ?>

    <div style="height:500px; overflow-x:auto; width:50%" id="div_general_photo_form" class="clearfix">
        <?php
        foreach($experts as $expert) {
            ?>
            <div style="height: 27px;overflow: hidden;">
                <?php
                echo form_checkbox(array(
                    'name' => 'members[]',
                    'id' => 'member_' . $expert['uid'],
                    'value' => $expert['uid'],
                    'checked' => $expert['selected']
                ));
                echo form_label($expert['firstname'] . ' ' .$expert['lastname'], 'expert_' . $expert['uid'], array('style' => 'float:right;width:90%;','class' => 'lblmulticheck'));
                ?>
            </div>
        <?php
        }
        ?>
    </div>
    <br/>

    <div>
        <?php echo form_submit('submit', 'Update Expert List', 'class="light_green no_margin_left"'); ?>
    </div>
    <?php echo form_close(); ?>
</div>