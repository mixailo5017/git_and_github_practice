<?php
/**
 * Renders a list view block (e.g. for projects, experts, forums ...)
 *
 * @var boolean $last Is it a last block in a row
 * @var string $url A link url to the entity being displayed
 * @var array $image (array('url' => '/path/to/image.jpg', 'alt' => 'alt text', 'pad' => 1))
 * @var string $title Entity's title
 * @var array $properties Entity's properties
 **/
?>
<div class="project_listing <?php if($last) { echo "project_listing_last"; }  ?> left">
    <a href="<?php echo $url; ?>">
        <img src="<?php echo $image['url']?>" alt="<?php echo $image['alt']?>" style="margin: 0px;">
    </a>

    <div style="font-size:13px;padding:8px 12px 0px 12px;"><?php echo $title; ?></div>

    <div style="padding: 8px 12px;">
        <?php foreach ($properties as $property) { ?>
            <strong><?php echo $property[0] . ':'; ?></strong><?php echo str_repeat('&nbsp;', $property[2]); echo ($property[1] != '') ? $property[1] : '&mdash;'; ?><br>
        <?php } ?>
    </div>
</div><!-- end .project_listing -->
