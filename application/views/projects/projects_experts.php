<div class="clearfix" id="content">
	<div style="width:965px;" class="center_col white_box" id="col5">
		<h1 class="col_top gradient"><?php echo $title ?></h1>
		<div class="inner clearfix">
            <div style="float: right; padding-right: 10px;">
                <div class="filter_option">
                    <?php echo form_dropdown('limit_options', view_limit_options(), $limit) ?>
                </div>
                <div class="filter_option">
                    <p><?php echo lang('PerPage') ?></p>
                </div>
            </div>
            <?php echo form_open('', array('name' => 'search_form', 'method' => 'get')) ?>
                <input type="hidden" name="limit" value="<?php echo $limit ?>">
            <?php echo form_close() ?>

            <?php echo form_paging(true, $page_from, $page_to, $total_rows, lang('Experts'), $paging); ?>

            <?php $i = 0;
            foreach ($experts as $key => $expert) {
                $fullname = $expert['firstname'] . ' ' . $expert['lastname'] ?>

            <div class="project_listing <?php if ($i == 3) { echo 'project_listing_last'; }  ?> left">
                <a href="/expertise/<?php echo $expert['uid'];?>">
                    <?php $src = expert_image($expert['userphoto'], 198, array('width' => '198')) ?>
                    <div class="div_resize_img198">
                        <img src="<?php echo $src ?>" alt="<?php echo $fullname ?>'s photo">
                    </div>
                </a>

                <p>
                    <strong><?php echo $fullname ?></strong><br>
                    <?php echo $expert['title'] ?><br>
                    <?php echo $expert['organization'] ?><br>
                    <?php echo $expert['country'] ?>
                </p>
                <p>
                    <strong><?php echo lang('Sector') ?>:</strong>&nbsp;&nbsp;&nbsp;<?php  echo $expert['expert_sector']!=''?$expert['expert_sector']:"&mdash;";?><br>
                    <strong><?php echo lang('Discipline') ?>:</strong>&nbsp;&nbsp;&nbsp;<?php echo $expert['discipline']!=''?$expert['discipline']:"&mdash;";?>
                </p>
            </div>
            <?php $i++; if ($i == 4) $i = 0; } ?>

            <?php if (count($experts) == 0) { ?>
            <div>
                <div class="clear">&nbsp;</div>
                <h3 align="center"><?php lang('NoExpertiseplay') ?></h3>
                <div class="clear">&nbsp;</div>
            </div>
	        <?php } ?>

            <?php echo form_paging(false, $page_from, $page_to, $total_rows, lang('Experts'), $paging); ?>

		</div><!-- end .inner -->
	</div><!-- end #col5 -->
</div>
