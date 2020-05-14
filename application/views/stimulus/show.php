<style type="text/css">
    html, body {
        overflow: hidden;
    }
</style>

<div id="content" class="clearfix" style="width: 100%">
    <div style="text-align: center;">
        <h1 class="large page-title"><?php echo $details['title']; ?></h1>
    </div>

    <div style="padding-top: 10px">
        <div style="width:50%; display: inline-block">
            <?php $this->load->view('stimulus/_details_blue', array_merge($projects, array('id' => $details['id'])));?>
        </div><!-- end #col1 -->

        <div style="width: 50%; float: right; height: 650px; overflow: scroll">
            <div style="float:left;">
                <?php echo form_open('/stimulus', array(
                    'id' => 'projects_search_form',
                    'name' => 'search_form',
                    'method' => 'GET')); ?>
                <div class="filter_option">
                    <p><?php echo lang('Filterby').':';?></p>
                </div>

                <div class="filter_option">
                    <?php echo form_dropdown('stage', stages_dropdown('select'), $filter['stage']); //"id='project_stage'" ?>
                    <?php echo form_dropdown('sector', sector_dropdown_stim(), $filter['sector']) //id="member_sectors" ?>
                    <?php echo form_dropdown('state', state_dropdown('select'), $filter['state']); //"id='project_stage'" ?>

                    <?php if ($filter['sector'] != ''){?>
                        <?php echo form_dropdown('subsector', subsector_dropdown($filter['sector']), $filter['subsector'], 'style="width:170px;"') ?>
                    <?php }?>
                </div>

                <br>

                <div style="float: left; padding-right: 10px;">
                     <div class="filter_option">
                         <p><?php echo lang('Sort') ?>:</p>
                     </div>
                     <div class="filter_option">
                         <?php echo form_dropdown('sort_options', $sort_options, $sort) ?>
                     </div>
                </div>

                <div class="filter_option">
                    <p><?php echo lang('Search');?> :</p>
                </div>
                <div class="filter_option">
                    <?php echo form_input('searchtext', $filter['searchtext'], 'placeholder="'. lang('ProjectTextSearchTip').'"') //"id"=>"search_text" ?>
                </div>
                <div class="filter_option">
                    <?php echo form_submit('search', lang('Search'), 'class = "light_green"') ?>
                </div>
                <a href="/stimulus" style="float: right; padding-left: 10px"><?php echo 'Reset Filters';?></a>

                <input type="hidden" name="sort" value="<?php echo $sort ?>">
                <?php echo form_close(); ?>
            </div>

            <?php $this->load->view('stimulus/_projects_preview', array_merge($projects, array('id' => $details['id'])));?>
        </div><!-- end #col2 -->
    </div>


</div><!-- end #content -->

<div id="dialog-message"></div>
