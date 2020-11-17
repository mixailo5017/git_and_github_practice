<!-- MAIN MAP (PROJECTS, EXPERTS)-->
<div>
        <head>
          <meta charset='utf-8' />
          <title>Points on a map</title>
          <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
          <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v1.4.0/mapbox-gl.js'></script>
          <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v1.4.0/mapbox-gl.css' rel='stylesheet' />

        </head>
        <div id='map' style='width: 1000px; height: 650px'></div>
        <script>
        mapboxgl.accessToken = 'pk.eyJ1Ijoiam9obmJyaXNiYW5lIiwiYSI6ImNrMDN5czNjNDJhYWgzb3FkdDJxM3JtcXoifQ.o4w_VxKKH6oH1IP9sygfYg'; // replace this with your access token
        var map = new mapboxgl.Map({
          container: 'map',
          style: 'mapbox://styles/johnbrisbane/ck36dhmba5a6j1cl9ftrxq6rt', // replace this with your style URL
          center: [20.661557, 50.893748],
          zoom: 2.7
        });
    // code from the next step will go here
        </script>
</div>

<div class="forum-actions" style="text-align: center; width:1000px; padding-top:50px">
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
          <?php echo form_submit('submit_attend_forum', lang('ForumRegister'), 'class="light_green attend"');?>
        </form>
    <?php } ?>
    <?php if ($details['meeting_url']) { ?>
        <a href="<?php echo $details['meeting_url'] ?>"
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
<div class="white_box forum_attendance" style="width: 1000px">
    <?php echo $details['content'];?>
</div>
<?php } ?>
