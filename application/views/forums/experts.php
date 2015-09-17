<div id="content" class="clearfix">
    <div id="col5" class="center_col white_box" style="width:965px;">
        <h1 class="col_top gradient">
            <?php echo lang('ExpertAttendees')?>
        </h1>
        <div class="inner clearfix">
            <?php
            echo form_paging(true, $page_from, $page_to, $total_rows, lang('Experts'), $paging);

            $index = 0;
            if ($total_rows > 0) {
                foreach($rows as $expert) {
                    $fullname = $expert['firstname'] . ' ' . $expert['lastname'];

                    $data = array(
                        'url' => base_url() . 'expertise/' . $expert['uid'],
                        'image' => array(
                            'url' => expert_image($expert['userphoto'], 198),
                            'alt' => $fullname . ' image'
                        ),
                        'title' => '<strong>' . $fullname . '</strong><br>' . $expert['title'] . '<br>' . $expert['organization'],
                        'properties' => array(
                            array(lang('Country'), $expert['country'],  1),
                            array(lang('Sector'),  $expert['sector'], 1),
                            array(lang('Discipline'),   ucfirst($expert['discipline']), 1)
                        ),
                        'last' => ($index == 3)
                    );
                    $this->load->view('templates/_list_block', $data);
                    $index = ($index == 3) ? 0 : $index + 1;
                }
            } else {
                echo form_list_empty(lang('NoExpertisedplay'));
            }
            ?>

            <div id="display-content"></div>

            <?php
            echo form_paging(false, $page_from, $page_to, $total_rows, lang('Experts'), $paging);
            ?>
        </div><!-- end .inner -->
    </div><!-- end #col5 -->
</div><!-- end #content -->

<div id="dialog-message"></div>