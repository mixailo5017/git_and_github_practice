<?php $segment = $this->uri->segment(1) ?>

<header class="m-navbar">
    <div class="container">
        <nav class="m-nav logo">
            <ul>
            <?php if (Auth::check() && (! App::is_down_for_maintenence() || App::is_ip_allowed_when_down())) { ?>
                <li>
                    <span class="iicon-menu m-menu-btn"></span>
                </li>
            <?php } ?>
                <li>
                    <a href="/"><span><img src="/images/new/logo.png" /></span></a>
                </li>
            </ul>       
        </nav>
        <?php if (Auth::check() && (! App::is_down_for_maintenence() || App::is_ip_allowed_when_down())) { ?>
        <nav class="m-nav nav-main">
            <ul>
                <li class="<?php echo $segment == 'mygvip' ? 'active' : '' ?>">
                    <a href="/mygvip"><span><?php echo lang('myVip') ?></span></a>
                </li>
                <li class="<?php echo $segment == 'map' ? 'active' : '' ?>">
                    <a href="/map"><span><?php echo lang('Map') ?></span></a>
                </li>
                <li class="<?php echo $segment == 'projects' ? 'active' : '' ?>">
                    <a href="/projects"><span><?php echo lang('projects') ?></span></a>
                </li>
                <li class="<?php echo $segment == 'forums' ? 'active' : '' ?>">
                    <a href="/forums"><span><?php echo lang('forums') ?></span></a>
                </li>
                <li class="<?php echo $segment == 'expertise' ? 'active' : '' ?>">
                    <a href="/expertise"><span><?php echo lang('expertise') ?></span></a>
                </li>
                <li class="<?php echo $segment == 'companies' ? 'active' : '' ?>">
                    <a href="/companies"><span><?php echo lang('Lightning') ?></span></a>
                </li>
                <li>
                    <a href="http://store.globalvipprojects.com" target="_blank"><span><?php echo lang('store') ?></span></a>
                </li>
            </ul>
        </nav>
        <?php } ?>

        <?php if (! App::is_down_for_maintenence() || App::is_ip_allowed_when_down()) { ?>
        <nav class="m-nav m-right">
            <ul>
                <?php if (Auth::check()) { ?>
                <li class="user-profile">
                    <a href="#">
                        <?php $src = expert_image(sess_var('userphoto'), 55) ?>
                        <img class="user-thumb" src="<?php echo $src ?>" alt="User's photo">
                        <div class="user-arrow">
                            <span class="iicon-circular-arrow"></span>
                        </div>
                    </a>
                    <ul class="user-menu">
                        <?php
                            $usertype = sess_var('usertype');
                            $project_involvement = $usertype == MEMBER_TYPE_EXPERT_ADVERT ? lang('ViewCurrentProjects') : lang('UpdateMyProjects');
                        ?>
                        <?php if ($usertype != MEMBER_TYPE_EXPERT_ADVERT) { ?>
                            <li><a href="/projects/create"><span><?php echo lang('CreateProject') ?></span></a></li>
                        <?php } ?>

                        <li><a href="/profile/account_settings#project-involvement"><span><?php echo $project_involvement ?></span></a></li>
                        <li><a href="/profile/account_settings_email"><span><?php echo lang('AccountSettings') ?></span></a></li>
                        <li><a href="/profile/account_settings"><span><?php echo lang('EditProfile') ?></span></a></li>
                        <li><a href="/expertise/<?php echo Auth::id() ?>"><span><?php echo lang('ViewPublicProfile') ?></span></a></li>
                        <li class="separator"></li>
                        <li class="v-mobile <?php echo $segment == 'help' ? 'active' : '' ?>">
                            <a href="/help"><span><?php echo lang('Help') ?></span></a>
                        </li>
                        <li><a href="/profile/logout"><span><?php echo lang('Logout') ?></span></a></li>
                    </ul>
                </li>
                <?php } else { ?>
                    <?php if ($segment != 'login') { ?>
                        <li><a href="/login"><span>Sign In</span></a></li>
                    <?php } ?>
                    <?php if (! in_array($segment, array('', 'signup', 'password'))) { ?>
                        <li class="join"><a href="/signup"><span>Join for Free</span></a></li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </nav>
        <?php } // is_down_for_maintenance ?>

        <?php if (Auth::check() && (! App::is_down_for_maintenence() || App::is_ip_allowed_when_down())) { ?>
        <nav class="m-nav m-right">
            <ul>
                <li class="m-dropdown m-language">
                    <span><img src="" class="active-language"></span>
                    <ul class="dropdown-menu">
                        <li class="<?php echo App::language() == 'english' ? 'active' : '' ?>">
                            <a title="English" href="javascript:void(0);" onClick="changeLanguage('english');">
                                <span><img alt="english lang flag" src="<?php echo SITE_IMAGE_PATH ?>us.png">English</span>
                            </a>
                        </li>
                        <li class="<?php echo App::language() == 'french' ? 'active' : '' ?>">
                            <a title="Français" href="javascript:void(0);" onClick="changeLanguage('french');">
                                <span><img alt="french lang flag" src="<?php echo SITE_IMAGE_PATH ?>fr.png">Français</span>
                            </a>
                        </li>
                        <li class="<?php echo App::language() == 'spanish' ? 'active' : '' ?>">
                            <a title="Español" href="javascript:void(0);" onClick="changeLanguage('spanish');">
                                <span><img alt="spanish lang flag" src="<?php echo SITE_IMAGE_PATH ?>es.png">Español</span>
                            </a>
                        </li>
                        <li class="<?php echo App::language() == 'portuguese' ? 'active' : '' ?>">
                            <a title="Português" href="javascript:void(0);" onClick="changeLanguage('portuguese');">
                                <span><img alt="portuguese lang flag" src="<?php echo SITE_IMAGE_PATH ?>br.png">Português</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <nav class="m-nav m-right v-desktop">
            <ul>
                <li class="<?php echo $segment == 'help' ? 'active' : '' ?>">
                    <a href="/help"><span><?php echo lang('Help') ?></span></a>
                </li>
            </ul>
        </nav>
        <?php } ?>
    </div>
</header>