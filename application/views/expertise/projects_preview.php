<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
<style>
 
    .default-style {
        color: white !important;
        padding: 0.15em 0.5em;
        border-radius: 15px;
    }

    .color-energy {
        background: firebrick;
    }

    .color-oil {
        background: teal;
    }

    .color-information {
        background: darkslateblue;
    }

    .color-transport {
        background: darkgreen;
    }

    .color-water {
        background: royalblue;
    }

    .color-other {
        background: lightslategrey;
    }

    .color-social {
        background: darkorchid;
    }
    .color-logistics{
        background:#c44b28;
    }
</style>
<?php
$totaljobs = 0;
$projectcount = 0;
foreach ($project['proj'] as $proj => $project2) {
    $jobscreated =  $model_obj->get_jobs_created($project2['pid']);
    $totaljobs += $jobscreated;
    $jobslist[] = $jobscreated;
}
?>

<div class="space-2 bg-light">
    <div class="container">
        <h5 style="text-align: center; padding-top: 5px">Total Jobs Created From Projects: <?php echo $totaljobs * 2; ?></h5>
        <div class="row d-flex justify-content-center">
            <?php foreach ($project['proj'] as $projkey => $project) {
                $projectcount += 1;
                if ($projectcount > 20) {
                    break;
                }
                $url = '/projects/' . $project['slug'];

                if ($project['stage'] === 'conceptual') {
                    $progressbar = '30';
                } elseif ($project['stage'] === 'feasibility') {
                    $progressbar = '30';
                } elseif ($project['stage'] === 'planning') {
                    $progressbar = '45';
                } elseif ($project['stage'] === 'procurement') {
                    $progressbar = '60';
                } elseif ($project['stage'] === 'construction') {
                    $progressbar = '75';
                } elseif ($project['stage'] === 'operation & maintenance') {
                    $progressbar = '90';
                }

                if ($project['totalbudget'] > 999) {
                    $project['totalbudget'] = $project['totalbudget'] / 1000;
                    $placeholder = 'B';
                } else {
                    $placeholder = 'M';
                }

                $jobscreated =  $model_obj->get_jobs_created($project['pid']);

            ?>


                <div class="col-lg-6 col-md-4 col-sm-6" style="padding-top: 5px; padding-right: 5px; padding-left: 5px">
                    <!-- Card -->
                    <div class="card h-100 border-0 shadow" style="overflow: hidden">
                        <!-- Card image -->
                        <div class="view ">
                            <a href="<?php echo $url ?>">
                                <img class="card-img-top rounded-top" loading="lazy" src="<?php echo project_image($project['projectphoto'], 500); ?>" alt="Card image cap">
                                <div class="mask rgba-white-slight"></div>
                            </a>
                        </div>
                        <!-- Card content -->
                        <div class="card-body d-flex flex-column container-fluid">
                            <div class="row my-3">
                                <div class="col-12">
                                    <a href="<?php echo $url; ?>" class="h3 card-title"><?php echo $project['projectname']; ?></a>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-6 mr-auto">
                                    <a class="card-text default-style color-<?php echo strtok(strtolower($project['sector']), ' '); ?> h4">
                                        <?php
                                        if ($project['sector'] == 'Information & Communication Technologies')
                                            echo 'ICT';
                                        else
                                            echo $project['sector'];
                                        ?>
                                    </a>
                                </div>
                                <div class="col-6 ml-auto">
                                    <p class="h4"><?php echo $project['country'] ?></p>
                                </div>
                            </div>
                            <div class="row mb-2 border-top mt-2">
                                <div class="col-6 my-1">
                                    <p class="mb-1 h4 font-weight-bold text-dark">Value</p>
                                </div>
                                <div class="col-6 my-1">
                                    <span class="amount h4"><?php echo $project['totalbudget'];
                                                            echo $placeholder; ?> </span>
                                </div>
                                <div class="col-6 my-1">
                                    <p class="mb-1 h4 font-weight-bold text-dark">Jobs</p>
                                </div>
                                <div class="col-6 my-1">
                                    <span class="amount h4"><?php echo $jobscreated; ?></span>
                                </div>
                                <div class="col-6 my-1">
                                    <p class="mb-1 h4 font-weight-bold text-dark">Sponsor</p>
                                </div>
                                <div class="col-6 my-1">
                                    <span class="h4 amount"><?php echo $project['sponsor']; ?></span>
                                </div>
                            </div>
                            <div style="height: 50px;" class="row align-bottom mt-auto  border-top">
                                <div style="text-transform:capitalize;" class="col-12 h3">Stage: &nbsp;<?php echo $project['stage']; ?></div>
                                <div class="col-12 mx-auto progress mb-1">
                                    <div class="progress-bar progress-bar-striped bg-success " role="progressbar" aria-valuenow="<?php echo $progressbar; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $progressbar; ?>%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Card -->
                </div>
                <!-- end col -->


            <?php
            }
            ?>
        </div>
    </div>
</div>