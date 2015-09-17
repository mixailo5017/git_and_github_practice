<div id="content" class="clearfix">
    <div id="col5" class="center_col white_box" style="width:965px;">
        <h1 class="col_top gradient">
            <?php echo mb_convert_case(lang('MyVipMyProjects'), MB_CASE_TITLE)?>
        </h1>

        <div class="project_filter clearfix">
            <?php echo form_open('myvip/myprojects', array('id' => 'myvip_myprojects_search_form', 'name' => 'myvip_myprojects_search_form', 'method' => 'get'));?>

            <div style="float:right;">
                <div class="filter_option">
                    <p><?php echo lang('Filterby')?>:</p>
                </div><!-- end .filter_option -->

                <div class="filter_option">
                    <?php
                    $options = array(
                        'all' => lang('MyProjectsAll'),
                        'own' => lang('MyProjectsIOwn'),
                        'follow' => lang('MyProjectsIFollow')
                    );
                    $selected = isset($filter_by['scope']) ? $filter_by['scope'] : 'all';
                    echo form_dropdown('scope', $options, $selected, 'id="scope"')
                    ?>
                </div>

                <div style="float:right; padding-right:10px;">
                    <div class="filter_option">
                        <?php echo form_submit('search', lang('Search'), 'class = "light_green"');?>
                    </div>
                </div>
                <?php echo form_close();?>

            </div>
        </div>

        <div class="inner clearfix">
            <?php
            echo form_paging(true, $page_from, $page_to, $total_rows, lang('Projects'), $paging);

            $index = 0;
            if ($total_rows > 0) {
                foreach($rows as $project) {
                    $data = array(
                        'url' => base_url() . 'projects/' . $project['slug'],
                        'image' => array(
                            'url' => project_image($project['projectphoto'], 198),
                            'alt' => $project['projectname'] . "' image"
                        ),
                        'title' => '<strong>' . $project['projectname'] . '</strong>',
                        'properties' => array(
                            array(lang('Country'), $project['country'],  1),
                            array(lang('Sector'),  $project['sector'], 1),
                            array(lang('Stage'),   ucfirst($project['stage']), 1)
                        ),
                        'last' => ($index == 3)
                    );
                    $this->load->view('templates/_list_block', $data);
                    $index = ($index == 3) ? 0 : $index + 1;
                }
            } else {
                echo form_list_empty(lang('noProjectToDisplay'));
            }
            ?>

            <div id="display-content"></div>

            <?php
            echo form_paging(false, $page_from, $page_to, $total_rows, lang('Projects'), $paging);
            ?>
        </div><!-- end .inner -->
    </div><!-- end #col5 -->
</div><!-- end #content -->

<div id="dialog-message"></div>