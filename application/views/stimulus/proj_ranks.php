<?php echo form_open('/stimulus/rank/37', array(
    'id' => 'projects_search_form',
    'name' => 'search_form',
    'method' => 'GET')); ?>
<div class="filter_option">
    Job Weight
    <?php echo form_input('jobweight', .1, 'placeholder="'. 'Job Weight'.'"') ?>
</div>
<div class="filter_option">
    Value Weight
    <?php echo form_input('valueweight', .05, 'placeholder="'. 'Value Weight'.'"') ?>
</div>
<div class="filter_option">
    JOV Weight
    <?php echo form_input('jovweight', .1, 'placeholder="'. 'JOV Weight'.'"') ?>
</div>
<div class="filter_option">
    Like Weight
    <?php echo form_input('likeweight', .05, 'placeholder="'. 'Like Weight'.'"') ?>
</div>
<div class="filter_option">
    PCI Weight
    <?php echo form_input('pciweight', .05, 'placeholder="'. 'PCI Weight'.'"') ?>
</div>
<div class="filter_option">
    Strategic Weight
    <?php echo form_input('strategicweight', .3, 'placeholder="'. 'Strategic Weight'.'"') ?>
</div>
<div class="filter_option">
    Economic Weight
    <?php echo form_input('economicweight', .3, 'placeholder="'. 'Economic Weight'.'"') ?>
</div>
<div class="filter_option">
    LocalBenefit Weight
    <?php echo form_input('localbenefitweight', .15, 'placeholder="'. 'LocalBenefit Weight'.'"') ?>
</div>
<div class="filter_option">
    Green Weight
    <?php echo form_input('greenweight', .15, 'placeholder="'. 'Green Weight'.'"') ?>
</div>
<div class="filter_option">
    Business Weight
    <?php echo form_input('businessweight', .1, 'placeholder="'. 'Business Weight'.'"') ?>
</div>
<div class="filter_option">
    <?php echo form_submit('submit', 'submit', 'class = "light_green"') ?>
</div>
<?php echo form_close(); ?>
<?php

//load brents csv
$file = fopen('stimProjects.csv', 'r');
$all_rows = array();
$header = fgetcsv($file);
while ($row = fgetcsv($file)) {
    $all_rows[] = array_combine($header, $row);
}
fclose($file);

//get max jobs
foreach ($projects['rows'] as $project => $p){
    $jobs = $model_obj->get_jobs_created($p['pid']);
    $jobstotal[] = $jobs;
}
//For jobs ratios
$maxjobs = max($jobstotal);



$features = array();

//Weights
$jobweight = $weights['jobweight'];
$valueweight = $weights['valueweight'];
$jovweight = $weights['jovweight'];
$likeweight = $weights['likeweight'];
$pciweight = $weights['pciweight'];
$strategicweight = $weights['strategicweight'];
$economicweight = $weights['economicweight'];
$localbenefitweight = $weights['localbenefitweight'];
$greenweight = $weights['greenweight'];
$businessweight = $weights['businessweight'];



foreach($projects['rows'] as $project => $orgexp)
{
    $pci = 0;

    $userid = $model_obj->get_uid_from_slug($orgexp['slug']);
    $slug = $orgexp['slug'];

    $fundamental = $model_obj->get_fundamental_data($slug, $userid);
    $financial =  $model_obj->get_financial_data($slug, $userid);
    $regulatory = $model_obj->get_regulatory_data($slug, $userid);
    $participants = $model_obj->get_participants_data($slug, $userid);
    $procurement = $model_obj->get_procurement_data($slug, $userid);
    $files = $model_obj->get_files_data($slug, $userid);

    $pci += $fundamental['totalfundamental'] * 7;
    $pci += $financial['totalfinancial'] * 12;
    $pci += $procurement['totalprocurement'] * 7;
    $pci += $regulatory['totalregulatory'] * 7;
    $pci += $participants['totalparticipants'] * 7;
    $pci += $files['totalfiles'] * 5;

    $likes = $model_obj->get_likes($orgexp['pid']);

    if ($model_obj->get_likes($orgexp['pid']) > 5){
        $likes = 5;
    }

    foreach ($all_rows as $rows => $row) {
        if ($row['pid'] === $orgexp['pid']) {

            $totalscore = $jobweight*($model_obj->get_jobs_created($orgexp['pid']) / $maxjobs * 5) +
                $jovweight * round((($model_obj->get_jobs_created($orgexp['pid']) / $orgexp['totalbudget'])) - .1, 2) +
                $likeweight * $model_obj->get_likes($orgexp['pid']) +
                $pciweight * $pci / 20 +
                $strategicweight * $row['strategic'] +
                $economicweight * $row['economic'] +
                $localbenefitweight * $row['localbenefit'] +
                $greenweight * $row['green'] +
                $businessweight * $row['business'];


            $features[] = array(
                'jobs' => $model_obj->get_jobs_created($orgexp['pid']) / $maxjobs * 5,
                'projectname' => $orgexp['projectname'],
                'totalbudget' => $orgexp['totalbudget'],
                'likes' => $model_obj->get_likes($orgexp['pid']),
                'jov' => round((($model_obj->get_jobs_created($orgexp['pid']) / $orgexp['totalbudget'])) - .1, 2),
                'pid' => $orgexp['pid'],
                'strategic' => $row['strategic'],
                'economic' => $row['economic'],
                'localbenefit' => $row['localbenefit'],
                'green' => $row['green'],
                'business' => $row['business'],
                'overall' => $row['overall'],
                'value' => $orgexp['totalbudget'],
                'pci' => $pci/20,
                'totalscore' => $totalscore
            );
        }
    }
}
aasort($features,"totalscore");
$features= array_reverse($features);



function aasort (&$array, $key) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
}

?>

<table>
    <tr>
        <th>Name</th>
        <th>Jov</th>
        <th style="padding-left: 10px">Jobs</th>
        <th style="padding-left: 10px">Value(M)</th>
        <th style="padding-left: 10px">Likes</th>
        <th style="padding-left: 10px">PCI</th>
        <th style="padding-left: 10px">Strategic</th>
        <th style="padding-left: 10px">Economic</th>
        <th style="padding-left: 10px">Local Benefit</th>
        <th style="padding-left: 10px">Green</th>
        <th style="padding-left: 10px">Business</th>
        <th style="padding-left: 10px">Total</th>
        <th style="padding-left: 10px">Rank</th>
    </tr>

        <?php
        $count = 1;
        foreach($features as $projects) {

            $jobratio = $projects['jobs'] / $maxjobs * 5;


            echo '<tr>';
            echo '<td>'.$projects['projectname'].'</td> ';
            echo '<td>'.$projects['jov'].'</td>';
            echo '<td style="padding-left: 10px">'.round($projects['jobs'],2).'</td>';
            echo '<td style="padding-left: 10px">'.$projects['value']/10000 .'</td>';
            echo '<td style="padding-left: 10px">'.$projects['likes'].'</td>';
            echo '<td style="padding-left: 10px">'.$projects['pci'].'</td>';
            echo '<td style="padding-left: 10px">'.$projects['strategic'].'</td>';
            echo '<td style="padding-left: 10px">'.$projects['economic'].'</td>';
            echo '<td style="padding-left: 10px">'.$projects['localbenefit'].'</td>';
            echo '<td style="padding-left: 10px">'.$projects['green'].'</td>';
            echo '<td style="padding-left: 10px">'.$projects['business'].'</td>';
            echo '<td style="padding-left: 10px">'.$projects['totalscore'].'</td>';
            echo '<td style="padding-left: 10px">'.$count.'</td>';
            echo '</tr>';

            $count ++;
        }?>
</table>

