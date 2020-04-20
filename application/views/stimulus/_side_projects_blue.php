<section class="portlet">
    <ul class="expert_list">
        <?php
        if (count($rows) > 0) {
           if($total_rows > count($rows)) { ?>
              <li class="clearfix" style="padding-top:10px; padding-bottom:20px">
                  <a href="/stimulus/projects/<?php echo $id ?>" class="light_green"><?php echo 'Show All Projects';?></a>
              </li>
          <?php }
          echo heading(lang('Projects') . " ($total_rows)", 4); 

            foreach($rows as $project) {
                $url = '/projects/' . $project['slug'];
        ?>



                <li class="clearfix">
                    <a href="<?php echo $url ?>">
                        <img src="<?php echo project_image($project['projectphoto'], 80); ?>" alt="project photo" />
                    </a>
                    <p style="clear:both">
                        <a href="<?php echo $url; ?>"><?php echo $project['projectname']; ?></a>
                        <br><span class="title"><?php echo $project['sector']; ?></span>
                        <br><span class="rating"><?php echo $project['country']; ?></span>
                    </p>
                </li>
        <?php
            }
        } else {
        ?>
            <li class="clearfix" style="list-style-type:none;">
                <?php echo lang('Noprojectsfound'); ?>
            </li>
        <?php
        }
        ?>
    </ul>
</section><!-- end .portlet -->
