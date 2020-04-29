<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
<style>
    hr {
        border-top: 1px solid #007bff;
        width:70%;
    }

    a {color: #000;}


    .card{
        background-color: #FFFFFF;
        padding:0;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius:4px;
        box-shadow: 0 4px 5px 0 rgba(0,0,0,0.14), 0 1px 10px 0 rgba(0,0,0,0.12), 0 2px 4px -1px rgba(0,0,0,0.3);
    }


    .card:hover{
        box-shadow: 0 16px 24px 2px rgba(0,0,0,0.14), 0 6px 30px 5px rgba(0,0,0,0.12), 0 8px 10px -5px rgba(0,0,0,0.3);
        color:black;
    }

    address{
        margin-bottom: 0px;
    }




    #author a{
        color: #fff;
        text-decoration: none;

    }
</style>



<?php
if (count($rows) > 0) {
?>

    <a href="/stimulus/projects/<?php echo $id ?>" class="light_green" style="width: 100%; text-align: center"><?php echo 'Show All Projects';?></a>
    <div class="space-2 bg-light">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <?php foreach($rows as $project) {
                    $url = '/projects/' . $project['slug'];
                    if ($project['stage'] === 'conceptual'){
                        $progressbar = '30';
                    }
                    elseif ($project['stage'] === 'feasibility'){
                        $progressbar = '30';
                    }
                    elseif ($project['stage'] === 'planning'){
                        $progressbar = '45';
                    }
                    elseif ($project['stage'] === 'procurement'){
                        $progressbar = '60';
                    }
                    elseif ($project['stage'] === 'construction'){
                        $progressbar = '75';
                    }
                    elseif ($project['stage'] === 'operation & maintenance'){
                        $progressbar = '90';
                    }

                    if ($project['totalbudget'] > 1000){
                        $project['totalbudget'] = $project['totalbudget']/1000;
                        $placeholder = 'B';
                    }
                    else {
                        $placeholder = 'M';
                    }

                    ?>


                    <div class="col-lg-4 col-md-4 col-sm-4" style="padding-top: 5px; padding-right: 5px; padding-left: 5px">
                        <!-- Card -->
                        <div class="card border-0 shadow" style="overflow: hidden">
                            <!-- Card image -->
                            <div class="view ">
                                <a href="<?php echo $url ?>">
                                    <img class="card-img-top rounded-top" src="<?php echo project_image($project['projectphoto'], 500); ?>" alt="Card image cap">
                                    <div class="mask rgba-white-slight"></div>
                                </a>
                            </div>
                            <!-- Card content -->
                            <div class="card-body border rounded-bottom" style="display:inline-block; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">
                                <a class="card-text small mb-2 d-block"><?php echo $project['sector']; ?></a>
                                <!-- Title --><a href="<?php echo $url; ?>" class="h5 card-title"><?php echo $project['projectname']; ?></a>
                                <!-- Description -->
                                <p><?php echo $project['country'] ?></p>
                                <hr>
                                <ul class="list-unstyled d-flex mb-3 text-left small">
                                    <li class="pledged">
                                        <p class="mb-1 font-weight-bold text-dark">Value</p>
                                        <span class="amount"><?php echo $project['totalbudget']; echo $placeholder; ?> </span>
                                    </li>
                                    <li style="padding-left: 10%; display:inline-block; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; width: 80%" class="days">
                                        <p class="mb-1 font-weight-bold text-dark">Sponsor</p>
                                        <span class="amount"><?php echo $project['sponsor']; ?></span>
                                    </li>
                                </ul>
                                <div class="progress mb-2">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="55" aria-valuemin="91" aria-valuemax="100" style="width:<?php echo $progressbar; ?>%; text-transform:capitalize;"><?php echo $project['stage']; ?></div>
                                </div>
                                <!-- end: progress bard -->
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
    <?php
}
else {
    ?>
    <div class="clearfix" style="list-style-type:none; text-align: center; ">
       <h3> <?php echo lang('Noprojectsfound'); ?> </h3>
        <a href="/stimulus" class="light_green" style="width: 20%;"><?php echo 'Reset Filters';?></a>
    </div>
    <?php
}
?>


