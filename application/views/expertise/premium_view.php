<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>TheEvent Bootstrap Template - Index</title>
    <meta content="" name="descriptison">
    <meta content="" name="keywords">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/venobox/venobox.css" rel="stylesheet">
    <link href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/aos/aos.css" rel="stylesheet">


    <!-- Template Main CSS File -->
    <link href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/css/style.css" rel="stylesheet">
    <link href='https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/css/premium_view.css' rel="stylesheet">
    <!-- =======================================================
    * Template Name: TheEvent - v2.2.0
    * Template URL: https://bootstrapmade.com/theevent-conference-event-bootstrap-template/
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
</head>
<style>

</style>
<main id="main" class="main-page">
    <section id="speakers-details">
        <!-- Banner Image yet to be added -->
        <div class="container-fluid">
            <!-- ======= Header & Map  Section ======= -->
            <div class="row">

                <div data-aos='zoom-in' class="col-md-6 header custom_scrollbar color">
                    <div class="row">
                        <div class="col-md-6 ">
                            <?php $src = company_image($users['userphoto'], 500, array('width' => 500, 'fit' => 'contain')) ?>
                            <img src="<?php echo $src ?>" alt="<?php echo $users['organization'] ?>'s photo" style="height: 30em">
                        </div>
                        <div class="col-lg-5 ml-lg-auto ml-xl-0 header-container">
                            <div class="section-header">
                                <h2 class="heading-text mt-lg-0 mt-md-2"><?php echo $users['organization']; ?></h2>
                                <?php
                                $fulllocation = array();
                                if ($users['city']) {
                                    $fulllocation[] = $users['city'];
                                }
                                if ($users['state']) {
                                    $fulllocation[] = $users['state'];
                                }
                                if ($users['country']) {
                                    $fulllocation[] = $users['country'];
                                }
                                ?>
                                <?php if (count($fulllocation) > 0) { ?>
                                    <p class="location"><?php echo implode(', ', $fulllocation) ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="details row">
                        <div class="col-lg-4 social-details col-12 mx-auto ">
                            <h3>Contact</h3>
                            <div class="social">
                                <p>Phone: <?php echo $users['vcontact']; ?></p>
                                <p>Email: <?php echo $users['email']; ?></p>
                            </div>
                        </div>
                        <div class="col-lg-4 social-details col-12 mx-auto">
                            <h3>Experts</h3>
                            <div class="social">
                                <p><?php echo empty($users['discipline']) ? '&mdash;' : $users['discipline'] ?></p>
                            </div>
                        </div>
                        <div class="col-lg-4 social-details  col-12 mx-auto">
                            <h3>Sector</h3>
                            <div class="social">
                                <?php foreach ($myexpertise as $id => $sector) { ?>
                                    <p><?php echo $sector['sector'] ?>&nbsp;(<?php echo $sector['subsector'] ?>)</p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map Section -->
                <div class="col-md-6">
                    <?php $this->load->view('expertise/premium_map', $project['proj']); ?>
                </div>
                <!-- Map Section end -->

            </div>
            <!--======== Header & Map Section End ========-->


            <!--======== Basic Content ========-->
            <div class="row mt-5">
                <!-- Projects Section  -->
                <div data-aos='zoom-in' class="col-lg-3 mx-auto  mt-5  color custom_scrollbar" style="height: 50em; overflow-y: scroll;">
                    <h2 style="text-align: center"><strong>Our Projects</strong></h2>
                    <?php $this->load->view('expertise/projects_preview', $project['proj']); ?>
                </div>
                <!-- Projects Section end-->


                <!-- Experts Section -->
                <div data-aos='zoom-in' class="col-lg-4 mx-auto  mt-5 color">
                    <!-- If a scrollbar is needed,add overflow-y:scroll -->
                    <div class="our_experts custom_scrollbar color" style="height: 50em; ">
                        <h2 style="text-align: center"><strong>Our Experts</strong></h2>
                        <div class="row">
                            <?php
                            $totalassigned  = 0;
                            $j = 0;
                            $totalassigned  = count($seats['approved']);

                            if (count($seats['approved']) > 0) {
                                for ($k = 0; $k < count($seats['approved']); $k++) { ?>
                                    <div class="col-7 col-sm-6 col-md-4   mt-sm-1 mt-4  mx-auto">
                                        <a href="/expertise/<?php echo $seats['approved'][$k]['uid']; ?>">
                                            <?php
                                            $img = expert_image($seats['approved'][$k]["userphoto"], 130, array('rounded_corners' => array('all', '1')));
                                            $alt = $seats['approved'][$k]['firstname'] . ' ' . $seats['approved'][$k]['lastname'] . lang('sphoto');
                                            ?>

                                            <img alt="<?php echo $alt; ?>" style="margin:0px" src="<?php echo $img; ?>" width="130" height="130">
                                            <h3><?php echo $seats['approved'][$k]['firstname'] . ' ' . $seats['approved'][$k]['lastname']; ?></h3>
                                        </a>
                                        <?php
                                        $seat_status = $seats['approved'][$k]['title'];
                                        if ($seat_status) { ?><span class="title"><?php echo $seats['approved'][$k]['title']; ?></span>
                                        <?php } ?>
                                    </div>
                            <?php
                                }
                            } else {
                                echo '<center>' . lang('noexpAssoc') . ' ' . $users['organization'] . '.</center>';
                            }

                            ?>
                            <?php // } 
                            ?></div>
                    </div>
                </div>
                <!-- Experts Section end-->


                <!-- Promotional Materials  -->
                <!-- If a scrollbar is needed,add overflow-y:scroll -->
                <div data-aos='zoom-in' class="col-lg-3 mx-auto mt-5 custom_scrollbar color" style="height: 50em;">
                    <h2 style="text-align: center">Promotional Materials</h2>
                    <?php
                    if ((count_if_set($case_studies)) > 0) {
                        echo '<div class="portlet_list case_studies">';
                        echo '<div class="inner">';
                        echo "<ul>";
                        foreach ($case_studies as $c => $cstudies) { ?>
                            <li class="clearfix">
                                <a href="/profile/view_case_studies/<?php echo $cstudies['uid']; ?>/<?php echo $cstudies['casestudyid']; ?>">
                                    <img alt="<?php echo lang("CaseStudy"); ?>" class="left img_border" width="59" src="<?php echo expert_image($cstudies['filename'], 59); ?>" height="59">
                                    <span class="title"><?php echo $cstudies['name']; ?></span>
                                </a>
                            </li>
                    <?php
                        }
                        echo "</ul>";
                        echo '</div></div>';
                    }
                    ?>
                </div>
                <!-- Promional Materials end -->
            </div>
            <!--======== Basic Content end ========-->


            <!--======== Information Section ========-->
            <div class="row mt-5">
                <div class="details">
                    <h2 style="text-align: center">Information</h2>
                    <p><?php echo $users['mission'] ?></p>
                </div>
            </div>
            <!--======== Information Section End ========-->


            <!-- More Sections still to come -->

        </div>
    </section>

</main><!-- End #main -->
<!-- Vendor JS Files -->
<script src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/jquery/jquery.min.js"></script>
<script src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/jquery.easing/jquery.easing.min.js"></script>
<script src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/php-email-form/validate.js"></script>
<script src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/venobox/venobox.min.js"></script>
<script src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/owl.carousel/owl.carousel.min.js"></script>
<script src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/superfish/superfish.min.js"></script>
<script src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/hoverIntent/hoverIntent.js"></script>
<script src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/aos/aos.js"></script>

<!-- Template Main JS File -->
<script src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/js/main.js"></script>