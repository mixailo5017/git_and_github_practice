<div class="clearfix" id="content">
    <div class="view_organization">
        <div class="col1" style="text-align:center;">
            <div class="cell">
                <?php $src = company_image($users['userphoto'], 150, array('width' => 150, 'fit' => 'contain')) ?>
                <img src="<?php echo $src ?>" alt="<?php echo $users['organization'] ?>'s photo" style="margin:0px">
            </div>
        </div>
        <div class="col2">
            <h1><?php echo $users['organization'];?></h1>
            <?php
                $fulllocation = array();
                if ($users['city']) { $fulllocation[] = $users['city']; }
                if ($users['state']) { $fulllocation[] = $users['state']; }
                if ($users['country']) { $fulllocation[] = $users['country']; }
            ?>
            <?php if (count($fulllocation) > 0){ ?>
                <span class="location"><?php echo implode(', ', $fulllocation) ?></span>
            <?php } ?>

            <span class="phone"><?php echo $users['vcontact'];?></span>
            <a href="mailto:<?php echo $users['email'];?>" class="email"> <?php echo $users['email'];?></a>
        </div>

        <div class="col3">
            <h2><?php echo lang('expertise')?>:</h2>
            <ul>
                <li><?php  echo empty($users['discipline']) ? '&mdash;' : $users['discipline'] ?></li>
            </ul>
            <h2><?php echo lang('Sectors')?>:</h2>
            <ul>
                <?php foreach ($myexpertise as $id => $sector) { ?>
                    <li><?php echo $sector['sector'] ?>&nbsp;(<?php echo $sector['subsector'] ?>)</li>
                <?php } ?>
            </ul>
        </div>

        <div class="clear"></div>

        <div class="our_experts">
        <h2><?php echo lang('OurExperts')?></h2>
        <?php
            $totalassigned  = 0;
            $j = 0;
            $totalassigned  = count($seats['approved']);

            if (count($seats['approved']) > 0) {
               for ($k = 0; $k<count($seats['approved']); $k++) { ?>
                    <div class="expert">
                        <a href="/expertise/<?php echo $seats['approved'][$k]['uid'];?>">
                            <?php
                                $img = expert_image($seats['approved'][$k]["userphoto"],130,array('rounded_corners' => array( 'all','1' )) );
                                $alt = $seats['approved'][$k]['firstname'].' '.$seats['approved'][$k]['lastname'].lang('sphoto');
                            ?>

                            <img alt="<?php echo $alt; ?>" style="margin:0px" src="<?php echo $img; ?>" width="130" height="130">
                            <h3><?php echo $seats['approved'][$k]['firstname'].' '.$seats['approved'][$k]['lastname']; ?></h3>
                        </a>
                        <?php
                            $seat_status = $seats['approved'][$k]['title'];
                            if($seat_status){?><span class="title"><?php echo $seats['approved'][$k]['title']; ?></span>
                        <?php } ?>
                    </div>
            <?php
                }
            }
            else{
                echo '<center>'.lang('noexpAssoc').' '.$users['organization'].'.</center>';
            }

        ?>
        <?php // } ?>
        </div><!-- our_experts -->

        <div class="clear mission">
            <p><?php echo $users['mission'] ?></p>
        </div>
    </div><!-- view_organization -->

    <div class="side_portlets">

            <?php
                if((count_if_set($case_studies)) > 0)
                {
                    echo '<div class="portlet_list case_studies">';
                    echo '<div class="inner">';
                    echo '<h2>'.lang('CaseStudies').'</h2>';
                    echo "<ul>";
                    foreach($case_studies as $c => $cstudies)
                    {?>
                        <li class="clearfix">
                        <a href="/profile/view_case_studies/<?php echo $cstudies['uid'];?>/<?php echo $cstudies['casestudyid'];?>">
                            <img alt="<?php echo lang("CaseStudy"); ?>" class="left img_border" width="59" src="<?php echo expert_image($cstudies['filename'], 59);?>" height="59">
                            <span class="title"><?php echo $cstudies['name'];?></span>
                        </a>
                        </li>
                    <?php
                    }
                    echo "</ul>";
                    echo '</div></div>';
                }
            ?>

        <!-- portlet_list -->
        <div class="portlet_list">
            <h2><?php echo lang('CurrentProjects')?></h2>
            <div class="inner">
                    <ul>
                            <li class="clearfix">
                              <a href="/projects/develop-a-comprehensive-rating-methodology-international-islamic-rating-agency-iira">
                                  <img alt="Helwan Power Plant Project (II)" class="left img_border" width="59" src="/img/content_projects/9bb7a551b19ce545441ae55ffd8b206f.JPG?fit=contain&w=150&h=150&s=a04f9cc2eb31ce4a7ef78f1f6ddf2622">
                                  <span class="title">Helwan Power Plant Project (II) <span class="location"> Helwan, Cairo Governorate</span></span>
                              </a>
                            </li>
                            <li class="clearfix">
                              <a href="/projects/kuwait-international-airport-expansion">
                                  <img alt="Kuwait International Airport Expansion" class="left img_border" width="59" src="/img/content_projects/8f20a70e37524538763b973a1c703ca3.jpeg?fit=contain&w=150&h=150&s=d51b2bf1326acc4a0bbab52d3d290369">
                                  <span class="title">Kuwait International Airport Expansion <span class="location">Farwaniya, KW</span></span>
                              </a>
                            </li>
                            <li class="clearfix">
                              <a href="/projects/gebze-istanbul-motorway">
                                  <img alt="Gebze-Izmir Motorway" class="left img_border" width="59" src="/img/content_projects/19c08b43cb7b7ae11b85e30ebbfca537.jpg?fit=contain&w=150&h=150&s=4b8a998a5212a70f8ffc80760bcb2ef1">
                                  <span class="title">Gebze-Izmir Motorway <span class="location">Izmir, Izmir</span></span>
                              </a>
                            </li>
                        </ul>


                </div>

        </div>
        <!-- portlet_list -->
    </div>
</div>
