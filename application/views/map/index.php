<div id="content" class="clearfix">
    <div id="col9" class="center_col white_box new_map show_loading show_projects">

        <div class="map_filter clearfix">
            <form id="map_search">
                <div class="form_row">
                    <div class="select_wrap input_group">
                        <span class="show_me">Show:</span>
                        <div class="form_control">
                            <?php
                            $members_options = show_members_dropdown();
                            $keys = array_keys($members_options);
                            echo form_dropdown("content_type", $members_options, array(array_shift($keys)), 'id="content_type" class="toggle_experts"');
                            $keys = null;
                            ?>
                        </div>
                    </div>

                    <div class="select_wrap input_group toggle_projects stage">
                        <span class="word">Stage:</span>

                        <div class="form_control">
                            <label class="access" for="f4">Stage:</label>
                            <?php echo form_dropdown("project_stage", stages_dropdown(), '', 'class="toggle_projects"') ?>
                        </div>
                    </div>

                    <div class="select_wrap input_group toggle_experts discipline">
                        <span class="word">In:</span>

                        <div class="form_control">
                            <label class="access" for="f4">In:</label>
                            <?php
                            $expert_discipline_options = discipline_dropdown();
                            array_shift($expert_discipline_options);
                            $list = array('' => lang('AnyDiscipline')) + $expert_discipline_options;
                            echo form_dropdown('expert_discipline', $list, '', 'class="toggle_experts"');
                            ?>
                        </div>
                    </div>

                    <div class="select_wrap input_group sector">
                        <span class="word">Sector:</span>

                        <div class="form_control">
                            <label class="access" for="f3">Sectors</label>
                            <select id="f3" name="sector">
                                <option value="">All Sectors</option>
                                <?php echo map_sector_options() ?>
                            </select>
                        </div>
                    </div>

                    <div class="select_wrap input_group toggle_projects budget">
                        <span class="word">Value:</span>

                        <div class="form_control">
                            <label class="access" for="f6">Budget</label>
                            <?php echo form_dropdown('budget', budget_dropdown(), '', 'class="toggle_projects" id="budget"') ?>
                        </div>
                    </div>

                </div>
            </form>
        </div>
        <!-- end .project_filter -->

        <div id="map_wrapper" class="inner clearfix">

            <div class="result_info_top">
                <p>Showing 1 - 12 of 48 Projects</p>

                <div class="buttons clearfix">
                    &nbsp;<a href="javascript:void(0);" style="color:black;"><strong>1</strong></a>
                    &nbsp;<a href="#">2</a>
                    &nbsp;<a href="#">3</a>
                    &nbsp;<a href="#">Next &gt;</a>&nbsp;
                </div>
                <!-- end .buttons -->
            </div>
            <!-- end .result_info_top -->

            <div class="map_options">
                <div class="error_msg">
                    <p><strong><?php echo lang('no_results_1'); ?></strong></p>

                    <p><?php echo lang('no_results_2'); ?></p>
                </div>

                <div class="loader">
                    <img src="/images/ajax-loader.gif" style="padding:50px 95px;"/>
                </div>

                <div class="projects" id="map_projects"></div>

                <div class="experts" id="map_experts"></div>
            </div>

            <div id="p_e_map" class="p_e_map">

            </div>
        </div>

    </div>
    <!-- end .inner -->
</div><!-- end #col9 -->
</div><!-- end #content -->

<div id="dialog-message"></div>

<audio id="lightning_sound" src="images/lightning.mp3" preload="auto"></audio>
