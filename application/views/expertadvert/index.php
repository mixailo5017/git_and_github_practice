<div class="clearfix" id="content">
    <div style="width:965px;" class="center_col white_box" id="col5">
        <h1 class="col_top gradient" style="height:100px;"><?php echo lang('companies')?></h1>
        <div class="project_filter clearfix">

            <?php echo form_open('companies/', array(
                'id' => 'expertadvert_search_form',
                'name' => 'search_form',
                'method'=>'get'));?>
            <div style="float:right;">
                <div class="filter_option">
                    <p><?php echo lang('Filterby')?>:</p>
                </div>
                <div class="filter_option">
    <?php echo form_dropdown('country', country_dropdown(), $filter['country'], 'style="width:170px;"') //id="member_country" ?>
</div>
<div class="filter_option">
    <?php echo form_dropdown('sector', sector_dropdown(), $filter['sector'], 'style="width:170px;"') //id="member_sectors" ?>
</div><!-- end .filter_option -->
<div class="filter_option">
    <?php echo form_dropdown('subsector', subsector_dropdown($filter['sector']), $filter['subsector'], 'style="width:170px;"') ?>
</div>
<div class="filter_option">
    <?php echo form_dropdown('discipline', discipline_dropdown(), $filter['discipline']) //'id="member_discipline"' ?>
</div>
</div>
<div style="float:right; padding-right:10px;">
<div class="filter_option">
    <p><?php echo lang('Search')?> :</p>
</div>
<div class="filter_option">
    <?php echo form_input('searchtext', $filter['searchtext']) //'id="search_text"' ?>
</div>
<div class="filter_option">
    <?php echo form_submit('search', lang('Search'), 'class = "light_green"');?>
</div>
</div>
<input type="hidden" name="sort" value="<?php echo $sort ?>">
<input type="hidden" name="limit" value="<?php echo $limit ?>">
<?php echo form_close();?>
</div><!-- end .project_filter -->
<div class="inner clearfix">
    <div style="float: right; padding-right: 10px;">
        <div class="filter_option">
            <p><?php echo lang('Sort') ?>:</p>
        </div>
        <div class="filter_option">
            <?php echo form_dropdown('sort_options', $sort_options, $sort) ?>
        </div>
        <div class="filter_option">
            <?php echo form_dropdown('limit_options', view_limit_options(), $limit) ?>
        </div>
        <div class="filter_option">
            <p><?php echo lang('PerPage') ?></p>
        </div>
    </div>
    <?php echo form_paging(true, $page_from, $page_to, $filter_total, lang('companies'), $paging); ?>

    <?php
    $i = 0;
    if (count($users) > 0) {
        foreach ($users as $key => $val) {
    ?>
            <div class="project_listing <?php if($i==3) { echo 'project_listing_last'; } ?> left">
                <a href="/companies/<?php echo $val['uid'] ?>">
                    <?php $src = company_image($val['userphoto'], 198, array('width' => 198, 'fit' => 'contain')) ?>
                    <div class='div_resize_img198' style='width:198px;height:198px;display:table-cell;vertical-align: middle;text-align: center;'>
                        <img src="<?php echo $src ?>" alt="<?php echo $val['organization'] ?>'s photo" style="margin:0px">
                    </div>
                </a>

                <p><strong><?php echo $val['organization']; ?></strong><br>
                    <?php 	//ExpertAdverts Start
                    echo $val['country']; ?></p>
                <p style="word-wrap:break-word"><strong><?php echo lang('Sector');?>:</strong>&nbsp;&nbsp;&nbsp;<?php  echo $val['expert_sector'] != '' ? $val['expert_sector'] : "&mdash;";?>
                    <br><strong><?php echo lang('Discipline')?>:</strong>&nbsp;&nbsp;&nbsp;<?php echo $val['discipline'] != '' ? $val['discipline'] : "&mdash;";?></p>
            </div>
    <?php
            $i++;
            if ($i == 4) { $i = 0; }
        }
    } else {
    ?>
        <div>
            <div class="clear">&nbsp;</div>
            <h3 align="center"><?php echo lang('NoCompanies')?></h3>
            <div class="clear">&nbsp;</div>
            <?php
}
?>
<?php echo form_paging(false, $page_from, $page_to, $filter_total, lang('companies'), $paging); ?>
</div><!-- end .inner -->
</div><!-- end #col5 -->
</div>
<script>
var subsectors = <?php echo json_encode($all_subsectors) ?>;
</script>
