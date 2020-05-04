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
                </div>

                <div class="filter_option">
                    <?php echo form_dropdown('sector', sector_dropdown(), $filter['sector'], 'style="width:170px;"') //id="member_sectors" ?>
                </div>
                <div class="filter_option">
                    <?php echo form_dropdown('state', state_dropdown('select'), $filter['state']); //"id='project_stage'" ?>
                </div>
                <br>
                <div class="filter_option">
                    <p><?php echo lang('Search');?> :</p>
                </div>
                <div class="filter_option">
                    <?php echo form_input('searchtext', $filter['searchtext'], 'placeholder="'. lang('ProjectTextSearchTip').'"') //"id"=>"search_text" ?>
                </div>
                <div class="filter_option">
                    <?php echo form_submit('search', lang('Search'), 'class = "light_green"') ?>
                </div>
                <?php echo form_close(); ?>
            </div>
            <?php $this->load->view('stimulus/_projects_preview', array_merge($projects, array('id' => $details['id'])));?>
        </div><!-- end #col2 -->
    </div>


</div><!-- end #content -->

<div id="dialog-message"></div>
