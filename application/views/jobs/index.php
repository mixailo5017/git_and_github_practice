<?php
foreach($map as $key => $orgexp)
{
    $jobscreated =  $model_obj->get_jobs_created($orgexp['pid']);
    echo $jobscreated.'<br>';
}
?>
