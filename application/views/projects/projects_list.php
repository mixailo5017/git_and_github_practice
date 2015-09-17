<div id="content" class="clearfix">
	<div id="col5" class="center_col white_box" style="width:965px;">
		<h1 class="col_top gradient" style="height:100px;"><?php echo lang('Projects');?></h1>
		
		<div class="project_filter clearfix">
			<?php echo form_open('/projects', array(
                'id' => 'projects_search_form',
                'name' => 'search_form',
                'method' => 'GET')); ?>
			
			<div style="float:right;">	
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
                    <?php echo form_dropdown('subsector', subsector_dropdown($filter['sector']), $filter['subsector'], 'style="width:170px;"') ?>
                </div>

                <div class="filter_option">
                    <?php echo form_dropdown('country', country_dropdown(), $filter['country'], 'style="width:170px;"') //id="member_country" ?>
                </div>
            </div>
			<div style="float:right; padding-right:10px;">
					<div class="filter_option">
						<p><?php echo lang('Search');?> :</p>
					</div>
					<div class="filter_option">
						<?php echo form_input('searchtext', $filter['searchtext'], 'placeholder="'. lang('ProjectTextSearchTip').'"') //"id"=>"search_text" ?>
					</div>
					<div class="filter_option">
						<?php echo form_submit('search', lang('Search'), 'class = "light_green"') ?>
					</div>
			</div>

            <input type="hidden" name="sort" value="<?php echo $sort ?>">
            <input type="hidden" name="limit" value="<?php echo $limit ?>">

            <?php echo form_close(); ?>
			
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

            <?php echo form_paging(true, $page_from, $page_to, $total, lang('Projects'), $paging); ?>

			<?php
            $i = 0;
            if (count($projects) > 0) {
                foreach($projects as $project) { ?>
				<div class="project_listing <?php if ($i == 3) { echo 'project_listing_last'; }  ?> left">
                    <a href="/projects/<?php echo $project['slug']; ?>">
                        <?php $src = project_image($project['projectphoto'], 198, array('width' => 198)) ?>
                        <img src="<?php echo $src ?>" alt="<?php echo $project['projectname'] . "'s photo" ?>">
                    </a>
					<div style="font-size:13px;padding:8px 12px 0px 12px;"><?php echo $project['projectname'] ?></div>
					<div style="padding: 8px 12px;">
						<strong><?php echo lang('Country') ?>:</strong>&nbsp; <?php echo $project['country'] != '' ? $project['country'] : "&mdash;"; ?><br>
						<strong><?php echo lang('Sector') ?>:</strong>&nbsp;&nbsp;&nbsp; <?php echo $project['sector'] != '' ? $project['sector'] : "&mdash;"; ?><br>
						<strong><?php echo lang('Stage') ?>:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $project['stage'] != '' ? ucfirst($project['stage']) : "&mdash;"; ?><br>
						<strong><?php echo lang('Value') ?>:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo format_budget($project['totalbudget']) ?>
					</div>
				</div><!-- end .project_listing -->

				<?php $i++;	if ($i == 4) { $i = 0; }
				}
			} else { ?>
				<div>
					<div class="clear">&nbsp;</div>
                    <h3 align="center"><?php echo lang('NoProjectsfoundtodisplay')?></h3>
					<div class="clear">&nbsp;</div>
				</div>
            <?php } ?>
			
			<div id = 'display-content'></div>

            <?php echo form_paging(true, $page_from, $page_to, $total, lang('Projects'), $paging); ?>
			
		</div><!-- end .inner -->
	</div><!-- end #col5 -->
</div><!-- end #content -->

<div id="dialog-message"></div>

<script>
    var subsectors = <?php echo json_encode($all_subsectors) ?>;
</script>
