<section class="portlet">
    <?php if ($total_rows > 0) echo heading(lang("ExpertsAttending") . " ($total_rows)", 4); ?>

    <ul class="expert_list">
    <?php
    if (count($rows) > 0) {
        foreach($rows as $expert) {
            $url = '/expertise/' . $expert['uid'];
    ?>
            <li class="clearfix" style="margin-bottom:6px;">
                <?php
                $fullname = ucfirst($expert['firstname'] . ' ' . $expert['lastname']);
                $img = expert_image($expert['userphoto'], 39, array('rounded_corners' => array('all', '2' )));
                ?>
                <a href="<?php echo $url; ?>">
                    <img src="<?php echo $img;?>" alt="<?php echo $fullname . '\'s photo'; ?>" />
                </a>
                <p>
                    <a href="<?php echo $url; ?>"><?php echo $fullname; ?></a>
                    <br>
                    <span class="title"><?php echo $expert['title']; ?></span>
                    <br>
                    <span class="title"><?php echo $expert['organization']; ?></span>
                </p>
            </li>
        <?php
        }
        if($total_rows > count($rows)) {
        ?>
            <li class="clearfix">
                <a href="/forums/experts/<?php echo $id ?>" class="light_green"><?php echo lang('ShowAll');?></a>
            </li>
        <?php
            }
        } 
        ?>
    </ul>
</section><!-- end .portlet -->
