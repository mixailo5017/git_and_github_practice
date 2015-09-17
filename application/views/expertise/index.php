<div class="clearfix" id="content">
	<div style="width:965px;" class="center_col white_box" id="col5">
		<h1 class="col_top gradient" style="height:100px;"><?php echo lang('expertise')?></h1>
		<div class="project_filter clearfix">
			<?php echo form_open('expertise/', array(
                'id' => 'expertise_search_form',
                'name' => 'search_form',
                'method' => 'get')) ?>
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
    				<?php echo form_dropdown('discipline', discipline_dropdown(), $filter['discipline'], 'style="width:170px;"') //'id="member_discipline"' ?>
				</div>
			</div>
			<div style="float:right; padding-right:10px;">
                <div class="filter_option">
                    <p><?php echo lang('Search')?>:</p>
                </div>
                <div class="filter_option">
                    <?php echo form_input('searchtext', $filter['searchtext'], 'placeholder="'. lang('ExpertTextSearchTip').'"') //'id="search_text"' ?>
                </div>
                <div class="filter_option">
                    <?php echo form_submit('search', lang('Search'),'class = "light_green"') ?>
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

            <?php echo form_paging(true, $page_from, $page_to, $filter_total, lang('Experts'), $paging); ?>
			
<?php

$i = 0;
if(count($users)>0)
{
	foreach($users as $key=> $val)
	{
	
	//ExpertAdverts Start
		if($val['membertype']== '8')
		{
			$fullname = $val['organization'];
		}
		else
		{
			$fullname = $val['firstname']." ".$val['lastname'];
	
		}
	//ExpertAdverts End

	?>
	<div class="project_listing <?php if($i==3) { echo "project_listing_last"; }  ?> left">
	
		<a href="/expertise/<?php echo $val['uid'];?>">
		<?php
            // Use helper expert_image function that deals with too large image sizes
            // by displaying a placeholder image instead of actual one for oversized images
            $src = expert_image($val["userphoto"], 198, array(
                'max' => 198,
                'rounded_corners' => array('all', '3'),
                'allow_scale_larger' => TRUE,
                'bg_color' => '#ffffff',
                'crop' => TRUE
            ));
		?>
            <div class="div_resize_img198">
                <img src="<?php echo $src; ?>" alt="<?php echo "$fullname's photo"; ?>" style="margin:0px">
            </div>
        </a>
		
		<p>
			<strong><?php echo $fullname; ?></strong>
			<br>
			<?php if ($val['membertype'] == MEMBER_TYPE_MEMBER) {
					echo $val['title'] . '<br>' . $val['organization'] . '<br>';
				}
			 	echo $val['country']; ?>
		</p>
		<p style="word-wrap:break-word">
			<strong><?php echo lang('Sector') ?>:</strong>&nbsp;<?php  echo $val['expert_sector'] ?: '&mdash;' ?>
			<br>
			<strong><?php echo lang('Discipline') ?>:</strong>&nbsp;<?php echo $val['discipline'] ?: '&mdash;';?>
			<br>
			<?php
			$rated = $val['rating_overall'] > 0 && $val['rating_count'] > 0;
			$rating_value = number_format((float) $val['rating_overall'], 1) . ' (' . $val['rating_count'] . ')';
			?>
			<strong><?php echo lang('Rating') ?>:</strong>&nbsp;<?php echo $rated ? $rating_value : '&mdash;' ?>
		</p>
	</div>
	<?php $i++; if ($i == 4) $i = 0; }
}
else
{
	?>
	<div>
		<div class="clear">&nbsp;</div>
		<h3 align="center"><?php echo lang('NoExpertiseplay')?></h3>
		<div class="clear">&nbsp;</div>
	</div>
	<?php
}
?>
            <?php echo form_paging(false, $page_from, $page_to, $filter_total, lang('Experts'), $paging); ?>

		</div><!-- end .inner -->
	</div><!-- end #col5 -->
</div>

<script>
    var subsectors = <?php echo json_encode($all_subsectors) ?>;
</script>
