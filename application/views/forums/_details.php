<?php
    if ($details['banner']) {
        $banner = safe_image(FORUM_IMAGE_PATH, $details['banner'], FORUM_NO_IMAGE_PATH . 'placeholder_forum_banner.png', array(
            'max' => 600,
            'rounded_corners' => null,
            'crop' => false,
            'allow_scale_larger' => false));
?>
        <div class="banner_image" style="width:600px">
            <img src="<?php echo $banner ?>" class="uploaded_img" alt="Forum's banner">
        </div>
<?php
    }
?>

<!-- MAIN MAP (PROJECTS, EXPERTS)-->
<div>
    <div class="map_filter clearfix">
        <form id="map_search">
            <div class="form_row">
                <div class="select_wrap input_group">
                    <span class="show_me">Show me:</span>
                    <div class="form_control">
                        <?php
                        $members_options = show_members_dropdown();
                        $keys = array_keys($members_options);
                        echo form_dropdown("content_type", $members_options, array(array_shift($keys)), 'id="content_type" class="toggle_experts"');
                        $keys = null;
                        ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="p_e_map" class="p_e_map" style="width: 598px; height: 450px; margin-bottom: 20px;"></div>
</div>

<div class="forum-actions" style="text-align: center;">
    <?php // TODO: Revisit ans escape data-name content ?>
    <?php if ($details['register_url'] && $details['registration_type'] == FORUM_REGISTER_OFFSITE) { ?>
        <a href="<?php echo $details['register_url'] ?>"
           target="_blank"
           class="button light_gray attend"
           data-id="<?php echo $details['id'] ?>"
           data-name="<?php echo $details['title'] ?>"><?php echo lang('ForumRegister') ?></a>
    <?php } ?>
    <?php if ($details['registration_type'] == FORUM_REGISTER_ON_GVIP) { ?>
        <?php echo form_open(
          'api/experts/' . sess_var('uid') . '/forums/' . $details['id'],
          [
            'id'=>'attend_forum_form',
            'name'=>'attend_forum_form',
            'class'=>'ajax_form'
          ]
          ) ?>
          <?php echo form_submit('submit_attend_forum', lang('ForumRegister'), 'class="light_green button light_gray attend"');?>
        </form>
    <?php } ?>
    <?php if ($details['meeting_url']) { ?>
        <a href="<?php echo $details['meeting_url'] ?>"
           target="_blank"
           class="button light_gray book"
           style="margin-left:10px;" ><?php echo lang('ForumBookMeeting') ?></a>
    <?php } ?>
</div>
<br/>

<!-- VENUE MAP -->
<?php //if ($details['venue_lat'] && $details['venue_lng']) { ?>
<!--    <div class="map_box clearfix">-->
<!--        <div id="venue_map" style="margin-left:15px;margin-top:10px;width:568px;height:144px;"></div>-->
<!---->
<!--        <div class="clearfix">-->
<!--            <p class="left coord">-->
<!--                <span class="geo"></span>-->
<!--                <span class="address">-->
<!--                    --><?php
//                    echo  implode(' | ', array_filter(array(anchor($details['venue_url'], $details['venue']), $details['venue_address'])));
//                    ?>
<!--                </span>-->
<!--            </p>-->
<!--        </div>-->
<!--    </div>-->
<!--    <script>-->
<!--        --><?php
//        echo "var lat = {$details['venue_lat']};";
//        echo "var lng = {$details['venue_lng']};";
//        ?>
<!--        var map = L.map('venue_map').setView([lat, lng], 16);-->
<!---->
<!--        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {-->
<!--            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'-->
<!--        }).addTo(map);-->
<!--        var marker = L.marker([lat, lng]).addTo(map);-->
<!--    </script>-->
<?php //} ?>

<!-- FORUM DESCRIPTION -->
<?php if (! empty($details['content'])) { ?>
<div class="white_box forum_attendance">
    <?php echo $details['content'];?>
</div>
<?php } ?>

