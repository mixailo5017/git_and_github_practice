<div class="clearfix" id="content">
	<div class="column_1">
		<!-- my projects -->
		<section class="my-projects group">
            <h2 class="shadow my_vip_header h2"><?php echo lang('MyVipMyProjects') ?></h2>
            <div>
                <?php if (count($my_projects) > 0) { ?>
                <?php foreach ($my_projects as $project) { ?>
                <article class="m_project">
                    <div class="image">
                        <div class="image_wrap">
                            <a href="<?php echo '/projects/' . $project['id'] ?>" class="recommendation" data-recommendation-location="My GViP" data-recommendation-category="Project" data-recommendation-section="My Projects" data-recommendation-target-id="<?php echo $project['id'] ?>" data-recommendation-target-name="<?php echo $project['projectname'] ?>">
                                <img src="<?php echo project_image($project['projectphoto']) ?>" alt="<?php echo $project['projectname'] . "'s photo" ?>">
                            </a>
                        </div>
                        <span class="ps_<?php echo project_stage_class($project['stage']) ?>"></span>
                        <span class="price"><?php echo format_budget($project['totalbudget']) ?></span>
                    </div>
                    <div class="content">
                        <h3 class="the_title"><a href="<?php echo '/projects/' . $project['id'] ?>" class="recommendation" data-recommendation-location="My GViP" data-recommendation-category="Project" data-recommendation-section="My Projects" data-recommendation-target-id="<?php echo $project['id'] ?>" data-recommendation-target-name="<?php echo $project['projectname'] ?>"><?php echo $project['projectname'] ?></a></h3>
                        <span class="type <?php echo project_sector_class($project['sector']) ?>"><?php echo ucfirst($project['sector']) ?></span>
                </div>
                <?php } else { ?>
                    <p class="not_found">
                        <?php echo lang('MyVipMyProjectsNotFound'); ?>
                    </p>
                <?php } ?>
            </div>
        </section>

        <!-- My Experts -->
        <section class="similar-experts group">
            <h2 class="shadow my_vip_header h2"><?php echo lang('MyVipMyExperts') ?></h2>
            <div>
                <ul class="reset">
                    <?php foreach($my_experts as $expert) { ?>
                        <li class="m_person">
                            <a href="/expertise/<?php echo $expert['uid'] ?>" class="image recommendation" data-recommendation-location="My GViP" data-recommendation-category="Expert" data-recommendation-section="My Experts" data-recommendation-target-id="<?php echo $expert['uid'] ?>" data-recommendation-target-name="<?php echo $expert['fullname'] ?>">
                                <img src="<?php echo expert_image($expert['userphoto']) ?>" alt="<?php echo $expert['fullname'] ?>'s photo">
                            </a>
                            <p class="content">
                                <a href="/expertise/<?php echo $expert['uid'] ?>" class="recommendation" data-recommendation-location="My GViP" data-recommendation-category="Expert" data-recommendation-section="My Experts" data-recommendation-target-id="<?php echo $expert['uid'] ?>" data-recommendation-target-name="<?php echo $expert['fullname'] ?>"><?php echo $expert['fullname'] ?></a>
                                <span class="title"><?php echo $expert['title'] ?></span>
                                <span class="title"><?php echo $expert['organization'] ?></span>
                            </p>
                        </li>
                    <?php } ?>
                    <?php if (empty($my_experts)) { ?>
                        <li class="not_found m_person"><?php echo lang('MyVipMyExpertsNotFound') ?></li>
                    <?php } ?>
                </ul>
                <?php if (! empty($my_experts)) { ?>
                <div class="more_link">
                    <a href="/mygvip/myexperts"><?php echo lang('ViewMore') ?></a>
                </div>
                <?php } ?>
                <div class="more_link">
                    <a href="/mygvip/myfollowers"><?php echo lang('ViewMyFollowers') ?></a>
                </div>
            </div>
        </section>

        <!-- My Discussions -->
        <?php if (! empty($my_discussions)) { ?>
        <section class="similar-experts group">
            <h2 class="shadow my_vip_header h2"><?php echo mb_convert_case(lang('MyVipMyDiscussions'), MB_CASE_UPPER) ?></h2>
            <div>
                <ul class="reset">
                    <?php foreach($my_discussions as $discussion) { ?>
                        <li class="m_person">
                            <a href="/projects/discussions/<?php echo $discussion['project_id'] ?>/<?php echo $discussion['id'] ?>" class="image recommendation" data-recommendation-location="My GViP" data-recommendation-category="Discussion" data-recommendation-section="My Discussions" data-recommendation-target-id="<?php echo $discussion['id'] ?>" data-recommendation-target-name="<?php echo $discussion['title'] ?>">
                                <img src="<?php echo safe_image(USER_NO_IMAGE_PATH, DISCUSSION_IMAGE_PLACEHOLDER, null, array('max' => 50)) ?>" alt="Discussion's photo">
                            </a>
                            <p class="content">
                                <a href="/projects/discussions/<?php echo $discussion['project_id'] ?>/<?php echo $discussion['id'] ?>" class="recommendation" data-recommendation-location="My GViP" data-recommendation-category="Discussion" data-recommendation-section="My Discussions" data-recommendation-target-id="<?php echo $discussion['id'] ?>" data-recommendation-target-name="<?php echo $discussion['title'] ?>"><?php echo $discussion['title'] ?></a>
                            </p>
                        </li>
                    <?php } ?>
                </ul>
                <div class="more_link">
                    <a href="/mygvip/mydiscussions"><?php echo lang('ViewMore') ?></a>
                </div>
            </div>
        </section>
        <?php } ?>

        <!-- gvip store -->
        <!--  If store items not found don't show the whole section -->
        <?php if (count($store_items) > 0) { ?>
		<section class="gvip-store group">
            <h2 class="shadow my_vip_header h2"><?php echo lang('MyVipGvipStore') ?></h2>
            <div>
                <ul class="m_store reset">
                    <?php foreach($store_items as $item) { ?>
                    <li class="item">
                        <a href="<?php echo $item['url'] ?>" class="recommendation" data-recommendation-location="My GViP" data-recommendation-category="Product" data-recommendation-section="GViP Store" data-recommendation-target-id="<?php echo $item['url'] ?>" data-recommendation-target-name="<?php echo $item['title'] ?>">
                            <img src="<?php echo store_item_image($item['photo'], 50) ?>" alt="Store item's photo">
                            <span><?php echo $item['title'] ?></span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
		</section>
        <?php } ?>

        <!-- similar projects -->
        <section class="similar-projects group">
            <h2 class="shadow my_vip_header h2"><?php echo lang('MyVipSimilarProjects') ?></h2>
            <div>
                <?php if (count($similar_projects) == 0) { ?>
                    <p class="not_found">
                        <?php echo lang('MyVipSimilarProjectsNotFound'); ?>
                    </p>
                <?php } ?>
                <?php foreach ($similar_projects as $project) { ?>
                    <article class="m_project">
                        <div class="image">
                            <div class="image_wrap">
                                <a href="<?php echo '/projects/' . $project['id'] ?>" class="recommendation" data-recommendation-location="My GViP" data-recommendation-category="Project" data-recommendation-section="Similar Projects" data-recommendation-target-id="<?php echo $project['id'] ?>" data-recommendation-target-name="<?php echo $project['projectname'] ?>">
                                    <img src="<?php echo project_image($project['projectphoto']) ?>" alt="<?php echo $project['projectname'] . "'s photo" ?>">
                                </a>
                            </div>
                            <span class="ps_<?php echo project_stage_class($project['stage']) ?>"></span>
                            <span class="price"><?php echo format_budget($project['totalbudget']) ?></span>
                        </div>
                        <div class="content">
                            <h3 class="the_title"><a href="<?php echo '/projects/' . $project['id'] ?>" class="recommendation" data-recommendation-location="My GViP" data-recommendation-category="Project" data-recommendation-section="Similar Projects" data-recommendation-target-id="<?php echo $project['id'] ?>" data-recommendation-target-name="<?php echo $project['projectname'] ?>"><?php echo $project['projectname'] ?></a></h3>
                            <span class="type <?php echo project_sector_class($project['sector']) ?>"><?php echo ucfirst($project['sector']) ?></span>
                        </div>
                    </article>
                <?php } ?>
            </div>
        </section>

        <!-- your picks -->
        <section class="similar-experts group">
            <h2 class="shadow my_vip_header h2"><?php echo lang('MyVipKeyExecutives') ?></h2>
            <div>
                <ul class="reset">
                    <?php if (count($key_executives) == 0) { ?>
                        <li class="not_found">
                            <?php echo lang('MyVipKeyExecutivesNotFound'); ?>
                        </li>
                    <?php } ?>

                    <?php foreach($key_executives as $expert) { ?>
                    <?php $fullname = $expert['firstname'] . ' ' . $expert['lastname'] ?>
                    <li class="m_person">
                        <a href="/expertise/<?php echo $expert['uid'] ?>" class="image recommendation" data-recommendation-location="My GViP" data-recommendation-category="Expert" data-recommendation-section="Key Executives" data-recommendation-target-id="<?php echo $expert['uid'] ?>" data-recommendation-target-name="<?php echo $fullname ?>">
                            <img src="<?php echo expert_image($expert['userphoto']) ?>" alt="<?php echo $fullname ?>'s photo">
                        </a>
                        <p class="content">
                            <a href="/expertise/<?php echo $expert['uid'] ?>" class="recommendation" data-recommendation-location="My GViP" data-recommendation-category="Expert" data-recommendation-section="Key Executives" data-recommendation-target-id="<?php echo $expert['uid'] ?>" data-recommendation-target-name="<?php echo $fullname ?>"><?php echo $fullname ?></a>
                            <span class="title"><?php echo $expert['title'] ?></span>
                            <span class="title"><?php echo $expert['organization'] ?></span>
                        </p>
                    </li>
                <?php } ?>
                </ul>
            </div>
        </section>

        <!-- New Experts -->
        <section class="similar-experts group">
            <h2 class="shadow my_vip_header h2"><?php echo lang('MyVipNewExperts') ?></h2>
            <div>
                <ul class="reset">
                    <?php foreach($new_experts as $expert) { ?>
                        <?php $fullname = $expert['firstname'] . ' ' . $expert['lastname'] ?>
                        <li class="m_person">
                            <a href="/expertise/<?php echo $expert['uid'] ?>" class="image recommendation" data-recommendation-location="My GViP" data-recommendation-category="Expert" data-recommendation-section="New Experts" data-recommendation-target-id="<?php echo $expert['uid'] ?>" data-recommendation-target-name="<?php echo $fullname ?>">
                                <img src="<?php echo expert_image($expert['userphoto']) ?>" alt="<?php echo $fullname ?>'s photo">
                            </a>
                            <p class="content">
                                <a href="/expertise/<?php echo $expert['uid'] ?>" class="recommendation" data-recommendation-location="My GViP" data-recommendation-category="Expert" data-recommendation-section="New Experts" data-recommendation-target-id="<?php echo $expert['uid'] ?>" data-recommendation-target-name="<?php echo $fullname ?>"><?php echo $fullname ?></a>
                                <span class="title"><?php echo $expert['title'] ?></span>
                                <span class="title"><?php echo $expert['organization'] ?></span>
                            </p>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </section>
	</div>

	<div class="column_2">
		<!-- map -->
		<div class="map">
            <div class="map_filter my_vip">
                <form id="map_search">
                    <div class="form_row">
                        <!-- <div class="input_group pe_toggle"> -->
                        <div class="select_wrap input_group">
<!--                            <span class="show_me">Show:</span>-->
                            <div class="form_control">
                                <?php
                                $members_options = show_members_dropdown2();
                                $keys = array_keys($members_options);
                                echo form_dropdown("content_type", $members_options, array(array_shift($keys)), 'id="content_type" class="toggle_experts"');
                                $keys = null;
                                ?>
                            </div>
                        </div>

                        <div class="select_wrap input_group stage toggle_projects">
<!--                            <span class="word">Stage:</span>-->
                            <div class="form_control">
<!--                                <label class="access" for="f4">Stage:</label>-->
                                <?php
                                $project_stage_options = stages_dropdown();
                                echo form_dropdown("project_stage",$project_stage_options,'','class="toggle_projects"');
                                ?>
                            </div>
                        </div>

                        <div class="select_wrap input_group discipline toggle_experts">
<!--                            <span class="word">In:</span>-->
                            <div class="form_control">
<!--                                <label class="access" for="f4">In:</label>-->
                                <?php
                                $expert_discipline_options =  discipline_dropdown();
                                array_shift($expert_discipline_options);
                                $list = array('' => lang('AnyDiscipline')) + $expert_discipline_options;
                                echo form_dropdown("expert_discipline",$list,'','class="toggle_experts"');
                                ?>
                            </div>
                        </div>

                        <div class="select_wrap input_group sector">
<!--                            <span class="word">Sector:</span>-->
                            <div class="form_control">
<!--                                <label class="access" for="f3">Sectors</label>-->
                                <select id="f3" name="sector">
                                    <option value="">All Sectors</option>
                                    <?php echo map_sector_options() ?>
                                </select>
                            </div>
                        </div>

                        <div class="select_wrap input_group toggle_projects budget">
<!--                            <span class="word">Value:</span>-->
                            <div class="form_control">
<!--                                <label class="access" for="f6">Budget</label>-->
                                <?php
                                $budget_dropdown_options = budget_dropdown();
                                echo form_dropdown('budget',$budget_dropdown_options,'','class="toggle_projects" id="budget"');
                                ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div><!-- end filter -->
		</div>
        <div class="dn" id="map_projects"></div>
        <div class="dn" id="map_experts"></div>
        <div id="map_wrapper" class="my_vip">
            <div id="p_e_map" class="p_e_map" style="width:100%; height:450px;"></div>
        </div>

		<!-- news feed -->
		<section class="comments">
            <h2 class="shadow my_vip_header h2"><?php echo lang('MyVipUpdatesTitle') ?></h2>

            <ul class="feed updates">
                <!-- populated from JS -->
            </ul>
            <div class="center">
                <?php echo form_open('updates/myvip', 'name="updates_view_more"'); ?>
                <input type="submit" class="view-more button" value="<?php echo lang('LoadMoreUpdates') ?>">
                <?php echo form_close() ?>
            </div>
		</section>
	</div>

	<div class="column_3">

	</div>
</div>
