<?php
/**
 * Renders a featured list block (e.g. for projects, experts, forums ...)
 *
 * @var string $url A link url to the entity being displayed
 * @var array $image (array('url' => '/path/to/image.jpg', 'alt' => 'alt text', 'pad' => 1))
 * @var string $title Entity's title
 * @var array $properties Entity's properties
 **/
?>
<style>
    /* line 595, sass/style.scss */
    .project_listing.featured {
        width: 925px;
        height: 406px;
        margin: 20px 31px 20px 0;
        border-radius: 3px;
    }
    /* line 596, sass/style.scss */
    .project_listing.featured img {
        margin: 5px 0 0 5px !important;
    }
    /* line 597, sass/style.scss */
    .project_listing.featured:hover {
        background: #f3f3f5;
    }
    /* line 598, sass/style.scss */
    .project_listing.featured p {
        margin-left: 12px;
        margin-right: 12px;
    }
    /* line 600, sass/style.scss */
    .project_listing_last {
        margin-right: 0 !important;
    }
</style>

<div class="project_listing featured">
    <div class="left">
        <a href="<?php echo $url; ?>">
            <img src="<?php echo $image['url']?>" alt="<?php echo $image['alt']?>" style="margin: 0px;">
        </a>
    </div>

    <div class="left">
        <div style="font-size:13px;padding:8px 12px 0px 12px;"><?php echo $title; ?></div>

        <div style="padding: 8px 12px;">
            <?php foreach ($properties as $property) { ?>
                <strong><?php echo $property[0] . ':'; ?></strong><?php echo str_repeat('&nbsp;', $property[2]); echo ($property[1] != '') ? $property[1] : '&mdash;'; echo br(); ?>
            <?php } ?>
        </div>
        <div style="padding: 8px 12px;">
            <?php
            if ($details['register_url']) {
                echo anchor("{$details['register_url']}", lang('ForumRegister'), 'class="button light_gray"');
            }
            if ($details['meeting_url']) {
                echo anchor("{$details['meeting_url']}", lang('ForumBookMeeting'), 'class="button light_gray" style="margin-left:10px;"');
            }
            ?>
        </div>
    </div>
</div><!-- end .project_listing -->
