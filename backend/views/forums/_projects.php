<div id="project_list_form" class="clearfix">
    <div class="contenttitle2">
        <h3>Project List</h3>
    </div>
    <br/>

    <?php
    echo form_open(current_url() . '/#projects', array('id' => 'forum_projects_form'));
    echo form_hidden('update', 'projects');
    ?>

    <div style="height:500px; overflow-x:auto; width:50%" id="div_general_photo_form" class="clearfix">
        <?php
        foreach($projects as $project) {
        ?>
            <div style="height: 27px;overflow: hidden;">
        <?php
            echo form_checkbox(array(
                'name' => 'projects[]',
                'id' => 'project_' . $project['pid'],
                'value' => $project['pid'],
                'checked' => $project['selected']
            ));
            echo form_label($project['projectname'], 'project_' . $project['pid'], array('style' => 'float:right;width:90%;','class' => 'lblmulticheck'));
        ?>
            </div>
        <?php
        }
        ?>
    </div>
    <br/>

    <div>
        <?php echo form_submit('submit', 'Update Project List', 'class="light_green no_margin_left"'); ?>
    </div>
    <?php echo form_close(); ?>
</div>