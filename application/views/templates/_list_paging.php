<?php
/**
 * Renders pagination controls (top or bootom)
 *
 * @var boolean $top
 * @var integer $from From page
 * @var integer $to To page
 * @var integer $total Total number of records
 * @var string $what What these records are (like Forums, Project, Experts etc.)
 * @var $paging CI pagging
 **/
?>
<div class="<?php echo 'result_info_' . (($top) ? 'top' : 'bottom'); ?>">
    <p>
        <?php echo lang('Showing') . ' ' . $from . ' - ' . $to . ' of ' . $total .  ' ' . $what; ?>
    </p>
    <div class="buttons clearfix">
        <?php echo $paging; ?>
    </div>
</div>
