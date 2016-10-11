<div id="content" class="clearfix">
		<div id="col2" class="projects">
			<section class="projectdata white_box">
                <?php $src= project_image($project['projectdata']['projectphoto'], 164, array(
                    'width' => 164,
                    'crop' => TRUE
                )) ?>
                <img src="<?php echo $src ?>" alt="<?php echo $project['projectdata']['projectname'] ?>'s photo">

                <h1><?php echo $project['projectdata']['projectname'] ?></h1>

                <p class="project-description">
                    <?php
					$this->load->helper('text');
                    $limited_description = word_limiter($project['projectdata']['description'], 100, '');
					echo nl2br($limited_description);
                    if (mb_strlen($limited_description) < mb_strlen($project['projectdata']['description'])) {
                    ?>
                        <span class="text-cut">…</span>
                        <button type="button" class="show"><?php echo lang('ShowMore') ?></button>
                        <span class="overflow-text">
                        	<?php echo nl2br(mb_substr($project['projectdata']['description'], mb_strlen($limited_description) + 1)) ?>
                        	<button type="button" class="hide"><?php echo lang('ShowLess') ?></button>
                        </span>
                    <?php
                    }
					?>
				</p>
			</section><!-- end .portlet -->

			<div id="project_tabs" class="white_box">
			<?php
            $style_fundamental  = (($project['fundamental']['totalfundamental']-count($project['fundamental']["map_point"])) == 0) ? 'style="display:none;"' : 'style="display:block;"';
            $style_financial    = ($project['financial']['totalfinancial'] == 0) ? 'style="display:none;"' : 'style="display:block;"';
            $style_regulatory   = ($project['regulatory']['totalregulatory'] == 0) ? 'style="display:none;"' : 'style="display:block;"';
            $style_participants = ($project['participants']['totalparticipants'] == 0) ? 'style="display:none;"' : 'style="display:block;"';
            $style_procurement  = ($project['procurement']['totalprocurement'] == 0) ? 'style="display:none;"' : 'style="display:block;"';
            $style_files 		= ($project['files']['totalfiles'] == 0) ? 'style="display:none;"' : 'style="display:block;"';
			?>


	            <h2><?php echo lang('Overview')    ?></h2>
				<div id="tabs-1" class="col2_tab">
					<?php if (($project['projectdata']['lat'] && $project['projectdata']['lng']) || $isAdminorOwner ) { ?>
						<div class="map_box clearfix">
							<div id="project-map"></div>

							<div class="clearfix">
								<p class="left coord"><span class="geo"></span> <span class="address"><?php if ($project['projectdata']['location']!= '') { echo $project['projectdata']['location'];} else { echo "N/A";} ?></span> <?php if ($isAdminorOwner) { ?> <a class="save_location" style="display: none;">Save</a> <?php } ?></p>
								<?php /* <p class="right coord"><span class="geo"><span class="latitude"><?php echo $val['latitude'];?></span>, <span class="longitude"><?php echo $val['longitude'];?></span></span> <a href="#"><?php echo lang("Map");?></a></p> */ ?>
							</div>
						</div>
					<?php } ?>

					<table class="overview_table">
						<tr>
							<th><?php echo lang('Stage');?>:</th>
                            <td><?php if ($project['projectdata']['stage'] != '' ){if($project['projectdata']['stage'] == "om") {echo "Operation &amp; Maintenance"; } else {echo ucfirst($project['projectdata']['stage']);}} else { echo "N/A";} ?></td>
							<th><?php echo lang('Location');?>:</th>
							<td class="city_state"><?php echo $project['prettylocation']; ?></td>
						</tr>
						<tr>
							<th><?php echo lang('Sector');?>:</th>
							<td><?php if ($project['projectdata']['sector']!= '') { echo $project['projectdata']['sector'];} else { echo "N/A";} ?></td>
							<th><?php echo lang('Sub-Sector');?>:</th>
							<td><?php if ($project['projectdata']['subsector'] != '') { echo $project['projectdata']['subsector'];} else { echo $project['projectdata']['subsector_other'];} ?></td>
						</tr>
						<tr>
							<th><?php echo lang('FinancialStructure');?>:</th>
							<td><?php if ($project['projectdata']['financialstructure']!= '') { echo $project['projectdata']['financialstructure'];} else { echo "N/A";} ?>
							</td>
							<th><?php echo lang('Budget');?>:</th>
                            <td><?php echo format_budget($project['projectdata']['totalbudget']) ?></td>
						</tr>
						<tr>
							<th><?php echo lang('EstStart');?>:</th>
							<td><?php if ($project['projectdata']['eststart']!= '' && $project['projectdata']['eststart']!= '1111-11-11'){ $startdt = new DateTime($project['projectdata']['eststart']); echo $startdt->format(DATEFORMATVIEW);} else { echo "N/A";} ?></td>
							<th><?php echo lang('EstCompletion');?>:</th>
							<td><?php if ($project['projectdata']['estcompletion']!= '' && $project['projectdata']['estcompletion'] != '1111-11-11'){$compdt = new DateTime($project['projectdata']['estcompletion']); echo $compdt->format(DATEFORMATVIEW);} else { echo "N/A";} ?></td>
						</tr>
						<tr>
							<th><?php echo lang('Developer');?>:</th>
							<td><?php if (isset($project['projectdata']['developer']) && $project['projectdata']['developer']!= ''){ echo $project['projectdata']['developer'];} else { echo "N/A";} ?></td>
							<th><?php echo lang('Sponsor');?>:</th>
							<td><?php if (isset($project['projectdata']['sponsor']) && $project['projectdata']['sponsor'] != '') { echo $project['projectdata']['sponsor'];} else { echo "N/A";} ?></td>
						</tr>
                        <tr>
                            <th><?php echo lang('Website') ?>:</th>
                            <td>
                                <?php
                                if (! empty($project['projectdata']['website'])) {
                                    $url = $project['projectdata']['website'];
                                    $parsed_url = parse_url($project['projectdata']['website']);
                                    if (is_array($parsed_url)) {
                                        $readable_url = $parsed_url['host'];
                                    } ?>
                                    <a href="<?php echo $url ?>" target="_blank" title="<?php echo $url ?>"><?php echo $readable_url ?></a>
                                <?php } else { ?>
                                    N/A
                                <?php } ?>
                            </td>
                            <th><?php echo lang('WEBScore') ?>:</th>
                            <td><?php echo isset($project['webscore']) ? $project['webscore'] : 'N/A' ?></td>
                        </tr>
					</table>
                </div>


	            <h2><?php echo lang('Procurement')  ?></h2>
				<div id="tabs-5" class="col2_tab" <?php echo $style_procurement;?>>
                    <?php if (count($project['procurement']['machinery']) >0) { ?>

                    <h3><?php echo lang('Machinery');?></h3>
                        <table width="100%">
                            <tr>
                                <th><?php echo lang('Name');?>:</th>
                                <th><?php echo lang('ProcurementProcess');?>:</th>
                                <th><?php echo lang('FinancialInformation');?>:</th>
                            </tr>

                            <?php foreach ($project['procurement']['machinery'] as $key => $machinery) { ?>
                                <tr>
                                    <td><?php if ($machinery['name']!= '') { echo $machinery['name'];} else { echo "N/A";} ?></td>
                                    <td><?php if ($machinery['procurementprocess']!= '') { echo $machinery['procurementprocess'];} else { echo "N/A";} ?></td>
                                    <td><?php if ($machinery['financialinfo']!= '') { echo $machinery['financialinfo'];} else { echo "N/A";} ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } ?>

                    <?php if (count($project['procurement']['procurement_technology']) >0) { ?>
                    <h3><?php echo lang('KeyTechnology');?></h3>
                        <table width="100%">
                            <tr>
                                <th><?php echo lang('Name');?>:</th>
                                <th><?php echo lang('ProcurementProcess');?>:</th>
                                <th><?php echo lang('FinancialInformation');?>:</th>
                            </tr>

                            <?php foreach ($project['procurement']['procurement_technology'] as $key => $procurement_technology) { ?>
                                <tr>
                                    <td><?php if ($procurement_technology['name']!= '') { echo $procurement_technology['name'];} else { echo "N/A";} ?></td>
                                    <td><?php if ($procurement_technology['procurementprocess']!= '') { echo $procurement_technology['procurementprocess'];} else { echo "N/A";} ?></td>
                                    <td><?php if ($procurement_technology['financialinfo']!= '') { echo $procurement_technology['financialinfo'];} else { echo "N/A";} ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } ?>

                    <?php if (count($project['procurement']['procurement_services']) >0) { ?>
                    <h3><?php echo lang('KeyServices');?></h3>
                        <table width="100%">
                            <tr>
                                <th><?php echo lang('Name');?>:</th>
                                <th><?php echo lang('Type');?>:</th>
                                <th><?php echo lang('ProcurementProcess');?>:</th>
                                <th><?php echo lang('FinancialInformation');?>:</th>
                            </tr>

                            <?php foreach ($project['procurement']['procurement_services'] as $key => $procurement_services) { ?>
                                <tr>
                                    <td><?php if ($procurement_services['name']!= '') { echo $procurement_services['name'];} else { echo "N/A";} ?></td>
                                    <td><?php if ($procurement_services['type']!= '') { echo $procurement_services['type'];} else { echo "N/A";} ?></td>
                                    <td><?php if ($procurement_services['procurementprocess']!= '') { echo $procurement_services['procurementprocess'];} else { echo "N/A";} ?></td>
                                    <td><?php if ($procurement_services['financialinfo']!= '') { echo $procurement_services['financialinfo'];} else { echo "N/A";} ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } ?>
				</div>


	            <h2><?php echo lang('Fundamentals') ?></h2>
	            <h2><?php echo lang('Financial')    ?></h2>
	            <h2><?php echo lang('Regulatory')   ?></h2>
	            <h2><?php echo lang('Participants') ?></h2>
	            <h2><?php echo lang('Files')        ?></h2>
				<div id="tabs-2" class="col2_tab" <?php echo $style_fundamental;?>>
                    <?php if (count($project['fundamental']['engineering']) >0) { ?>
						<h3><?php echo lang('Engineering');?></h3>
							<table width="100%">
								<tr>
									<th><?php echo lang('Company');?>:</th>
									<th><?php echo lang('Role');?>:</th>
									<th><?php echo lang('ContactName');?>:</th>
									<th><?php echo lang('Challenges');?>:</th>
									<th><?php echo lang('Innovations');?>:</th>
									<th class="text_center"><?php echo lang('Schedule');?>:</th>
								</tr>
								<?php foreach ($project['fundamental']['engineering'] as $key => $engineering) {
                                $filelink = '';
                                if (isset($engineering['schedule']) && $engineering['schedule'] !='') {
                                    $filelink = 'onclick="'.PROJECT_IMAGE_PATH.$engineering['schedule'].'"';
                                }
								?>

                                <tr class="frontfiles_tr">
                                    <td><?php if ($engineering['company']!= '') { echo $engineering['company'];} else { echo "N/A";} ?></td>
                                    <td><?php if ($engineering['role']!= '') { echo $engineering['role'];} else { echo "N/A";} ?></td>
                                    <td><?php if ($engineering['contactname']!= '') { echo $engineering['contactname'];} else { echo "N/A";} ?></td>
                                    <td><?php if ($engineering['challenges']!= '') { echo $engineering['challenges'];} else { echo "N/A";} ?></td>
                                    <td><?php if ($engineering['innovations']!= '') { echo $engineering['innovations'];} else { echo "N/A";} ?> </td>
                                    <td class="text_center">
                                        <?php if ($engineering['schedule']!= '') { ?>
                                        <a class="frontfiles_link" href="<?php echo PROJECT_IMAGE_PATH.$engineering['schedule'];?>" target="_blank">
                                            <img src="/images/icons/<?php echo filetypeIcon($engineering['schedule']);?>" alt=<?php echo lang('file')?> title=<?php echo lang('file')?>>
                                        </a>
                                    <?php
                                        } else { echo lang('NoSchedule'); } ?>
                                    </td>
                                </tr>
								<?php } ?>
							</table>
						<?php } ?>

						<?php if (count($project['fundamental']['design_issue']) >0) { ?>
						<h3><?php echo lang('DesignIssues');?></h3>
							<table width="100%">
								<tr>
									<th><?php echo lang('Name');?>:</th>
									<th><?php echo lang('Description');?>:</th>
									<th class="text_center"><?php echo lang('Attachments');?>:</th>
								</tr>
								<?php foreach ($project['fundamental']['design_issue'] as $key => $design_issue) { ?>
                                <tr class="frontfiles_tr">
                                    <td><?php if ($design_issue['title']!= '') { echo $design_issue['title'];} else { echo "N/A";} ?></td>
                                    <td><?php if ($design_issue['description']!= '') { echo $design_issue['description'];} else { echo "N/A";} ?></td>

                                    <td class="text_center"><?php if ($design_issue['attachment']!= '') { ?>
                                        <a class="frontfiles_link" href="<?php echo PROJECT_IMAGE_PATH.$design_issue['attachment'];?>">
                                            <img src="/images/icons/<?php echo filetypeIcon($design_issue['attachment']);?>" alt=<?php echo lang('file')?> title=<?php echo lang('file')?>>
                                        </a>
                                    <?php
                                        } else { echo lang('NoAttachments'); } ?>
                                    </td>
                                </tr>
								<?php } ?>
							</table>
						<?php } ?>

						<?php if (count($project['fundamental']['environment']) >0) { ?>
						<h3><?php echo lang('Environment') ?></h3>
							<table width="100%">
								<tr>
									<th><?php echo lang('Name');?>:</th>
									<th><?php echo lang('Description');?>:</th>
									<th class="text_center"><?php echo lang('Attachments');?>:</th>
								</tr>

								<?php foreach ($project['fundamental']['environment'] as $key => $environment) { ?>
									<tr class="frontfiles_tr">
										<td><?php if ($environment['title']!= '') { echo $environment['title'];} else { echo "N/A";} ?></td>
										<td><?php if ($environment['description']!= '') { echo $environment['description'];} else { echo "N/A";} ?></td>
										<td class="text_center">
										<?php if ($environment['attachment']!= '') { ?>
											<a class="frontfiles_link" href="<?php echo PROJECT_IMAGE_PATH.$environment['attachment'];?>">
												<img src="/images/icons/<?php echo filetypeIcon($environment['attachment']);?>" alt=<?php echo lang('file')?> title=<?php echo lang('file')?>>
											</a>
										<?php } else { echo lang("NoAttachments"); } ?></td>
									</tr>
								<?php } ?>
							</table>
						<?php } ?>

						<?php if (count($project['fundamental']['studies']) >0) { ?>
						<h3><?php echo lang('OtherStudies');?></h3>
							<table width="100%">
								<tr>
									<th><?php echo lang('Name');?>:</th>
									<th><?php echo lang('Description');?>:</th>
									<th class="text_center"><?php echo lang('Attachments');?>:</th>
								</tr>

								<?php foreach ($project['fundamental']['studies'] as $key => $studies) { ?>
									<tr class="frontfiles_tr">
										<td><?php if ($studies['title']!= '') { echo $studies['title']; } else { echo "N/A";} ?></td>
										<td><?php if ($studies['description']!= '') { echo $studies['description']; } else { echo "N/A";} ?></td>
										<td  class="text_center">
											<?php if ($studies['attachment']!= '') { ?>
												<a class="frontfiles_link" href="<?php echo PROJECT_IMAGE_PATH.$studies['attachment'];?>" >
													<img src="/images/icons/<?php echo filetypeIcon($studies['attachment']);?>" alt=<?php echo lang('file')?> title=<?php echo lang('file')?>>
												</a>
											<?php } else { echo lang("NoAttachments"); } ?>
										</td>
									</tr>
								<?php } ?>
							</table>
						<?php } ?>

						<?php if ($project['projectdata']['fundamental_legal'] != '') { ?>
							<h3><?php echo lang('Legal'); ?></h3>
								<p><?php if ($project['projectdata']['fundamental_legal'] != '') { echo $project['projectdata']['fundamental_legal'];} else { echo "N/A"; } ?></p>
						<?php } ?>
				</div>

				<div id="tabs-3" class="col2_tab" <?php echo $style_financial;?>>
                    <h3><?php echo lang('FinancialStructure'); ?></h3>
                    <?php if (count($project['financial']['financial'])>0) { ?>
                        <p><strong><?php echo lang('FinancialStructure');?> -</strong><?php if ($project['financial']['financial']['name'] != '') { echo $project['financial']['financial']['name'];} else { echo "N/A";} ?></p>
                        <p><?php if ($project['financial']['financial']['contactinfo'] != '') { echo $project['financial']['financial']['contactinfo']; } else { echo "N/A";} ?></p>
                    <?php } ?>

                    <?php if (count($project['financial']['fund_sources']) >0) { ?>
                    <h3><?php echo lang('FundSources');?></h3>
                        <table width="100%">
                            <tr>
                                <th><?php echo lang('Name');?>:</th>
                                <th><?php echo lang('Role');?>:</th>
                                <th><?php echo lang('Amount');?>:</th>
                                <th><?php echo lang('Description');?>:</th>
                            </tr>

                            <?php foreach ($project['financial']['fund_sources'] as $key => $fund_sources) { ?>
                                <tr>
                                    <td><?php if ($fund_sources['name']!= '') { echo $fund_sources['name'];} else { echo "N/A";} ?></td>
                                    <td><?php if ($fund_sources['role']!= '') { echo $fund_sources['role'];} else { echo "N/A";} ?></td>
                                    <td><?php if ($fund_sources['amount']!= '') { echo CURRENCY.$fund_sources['amount'];} else { echo "N/A";} ?></td>
                                    <td><?php if ($fund_sources['description']!= '') { echo $fund_sources['description'];} else { echo "N/A";} ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                     <?php } ?>

                    <?php if (count($project['financial']['roi']) >0) { ?>
                    <h3><?php echo lang('ReturnonInvestment'); ?></h3>
                        <table width="100%">
                            <tr>
                                <th><?php echo lang('Name');?>:</th>
                                <th><?php echo lang('Percent');?>:</th>
                                <th><?php echo lang('Type');?>:</th>
                                <th><?php echo lang('Approach');?>:</th>
                                <th class="text_center"><?php echo lang('KeyStudy');?>:</th>
                            </tr>

                            <?php foreach ($project['financial']['roi'] as $key => $roi) { ?>
                                <tr class="frontfiles_tr">
                                    <td><?php if ($roi['name']!= '') { echo $roi['name'];} else { echo "N/A";} ?></td>
                                    <td><?php if ($roi['percent']!= '') { echo $roi['percent']."%";} else { echo "N/A";} ?></td>
                                    <td><?php if ($roi['type']!= '') { echo $roi['type'];} else { echo "N/A";} ?></td>
                                    <td><?php if ($roi['approach']!= '') { echo $roi['approach'];} else { echo "N/A";} ?></td>
                                    <td  class="text_center">
                                        <?php if ($roi['keystudy']!= ''){
                                        ?>
                                        <a class="frontfiles_link" href="<?php echo PROJECT_IMAGE_PATH.$roi['keystudy'];?>">
                                            <img src="/images/icons/<?php echo filetypeIcon($roi['keystudy']);?>" alt=<?php echo lang('file')?> title=<?php echo lang('file')?>>
                                        </a>
                                        <?php } else { echo lang("NoFile"); } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } ?>

                    <?php if (count($project['financial']['critical_participants']) >0) { ?>
                    <h3><?php echo lang('CriticalParticipants');?></h3>
                        <table width="100%">
                            <tr>
                                <th><?php echo lang('Name');?>:</th>
                                <th><?php echo lang('Role');?>:</th>
                                <th><?php echo lang('Description');?>:</th>
                            </tr>

                            <?php foreach ($project['financial']['critical_participants'] as $key => $critical_participants) { ?>
                                <tr>
                                    <td><?php if ($critical_participants['name']!= '') { echo $critical_participants['name'];} else { echo "N/A";} ?></td>
                                    <td><?php if ($critical_participants['role']!= '') { echo $critical_participants['role'];} else { echo "N/A";} ?></td>
                                    <td><?php if ($critical_participants['description']!= '') { echo $critical_participants['description'];} else { echo "N/A";} ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } ?>
				</div>

                <!-- Regulatory -->
                <div id="tabs-7" class="col2_tab" <?php echo $style_regulatory ?>>
                    <?php if (count($project['regulatory']['regulatory']) > 0) { ?>
                        <h3><?php echo lang('Regulatory');?></h3>
                        <table width="100%">
                            <tr>
                                <th><?php echo lang('File');?>:</th>
                                <th><?php echo lang('Description');?>:</th>
                            </tr>

                            <?php foreach ($project['regulatory']['regulatory'] as $key => $regulatory) { ?>
                                <tr class="frontfiles_tr">
                                    <td>
                                        <?php if ($regulatory['file'] != '' && $regulatory['file'] != '0'){ ?>
                                            <a class="frontfiles_link" href="<?php echo PROJECT_IMAGE_PATH . $regulatory['file'];?>">
                                                <img src="/images/icons/<?php echo filetypeIcon($regulatory['file']);?>" alt=<?php echo lang('file')?> title=<?php echo lang('file')?>>
                                            </a>
                                        <?php } else { echo "No File"; } ?> </td>
                                    <td><?php if ($regulatory['description']!= '') { echo $regulatory['description'];} else { echo "N/A";} ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } ?>
                </div>

				<div id="tabs-4" class="col2_tab" <?php echo $style_participants;?>>

						<?php if (count($project['participants']['public']) >0)
						{
						?>
						<h3><?php echo lang('Public');?></h3>

							<table width="100%">

								<tr>
									<th><?php echo lang('Name');?>:</th>
									<th><?php echo lang('Type');?>:</th>
									<th><?php echo lang('Description');?>:</th>
								</tr>

								<?php foreach ($project['participants']['public'] as $key => $public)
								{?>
									<tr>
										<td><?php if ($public['name']!= '') { echo $public['name'];} else { echo "N/A";} ?></td>
										<td><?php if ($public['type']!= '') { echo $public['type'];} else { echo "N/A";} ?></td>
										<td><?php if ($public['description']!= '') { echo $public['description'];} else { echo "N/A";} ?></td>
									</tr>
								<?php } ?>

							</table>

						<?php } ?>

						<?php if (count($project['participants']['political']) >0)
						{
						?>

						<h3><?php echo lang('Political');?></h3>

							<table width="100%">

								<tr>
									<th><?php echo lang('Name');?>:</th>
									<th><?php echo lang('Type');?>:</th>
									<th><?php echo lang('Description');?>:</th>
								</tr>

								<?php foreach ($project['participants']['political'] as $key => $political)
								{?>
									<tr>
										<td><?php if ($political['name']!= '') { echo $political['name'];} else { echo "N/A";} ?></td>
										<td><?php if ($political['type']!= '') { echo $political['type'];} else { echo "N/A";} ?></td>
										<td><?php if ($political['description']!= '') { echo $political['description'];} else { echo "N/A";} ?></td>
									</tr>
								<?php } ?>

							</table>
						<?php } ?>

						<?php if (count($project['participants']['companies']) >0)
						{
						?>

						<h3><?php echo lang('Companies');?></h3>

							<table width="100%">

								<tr>
									<th><?php echo lang('Name');?>:</th>
									<th><?php echo lang('Role');?>:</th>
									<th><?php echo lang('Description');?>:</th>
								</tr>

								<?php foreach ($project['participants']['companies'] as $key => $companies)
								{?>
									<tr>
										<td><?php if ($companies['name']!= '') { echo $companies['name'];} else { echo "N/A";} ?></td>
										<td><?php if ($companies['role']!= '') { echo $companies['role'];} else { echo "N/A";} ?></td>
										<td><?php if ($companies['description']!= '') { echo $companies['description'];} else { echo "N/A";} ?></td>
									</tr>
								<?php } ?>

							</table>
						<?php } ?>

						<?php if (count($project['participants']['owners']) >0) { ?>
						<h3><?php echo lang('Owners');?></h3>
							<table width="100%">
								<tr>
									<th><?php echo lang('Name');?>:</th>
									<th><?php echo lang('Type');?>:</th>
									<th><?php echo lang('Description');?>:</th>
								</tr>

								<?php foreach ($project['participants']['owners'] as $key => $owners) { ?>
									<tr>
										<td><?php if ($owners['name']!= '') { echo $owners['name'];} else { echo "N/A";} ?></td>
										<td><?php if ($owners['type']!= '') { echo $owners['type'];} else { echo "N/A";} ?></td>
										<td><?php if ($owners['description']!= '') { echo $owners['description'];} else { echo "N/A";} ?></td>
									</tr>
								<?php } ?>

							</table>

						<?php } ?>


				</div>


				

				<div id="tabs-6" class="col2_tab" <?php echo $style_files;?>>
                    <?php if (count($project['files']['files']) >0) { ?>
                    <h3><?php echo lang('Files');?></h3>
                        <table width="100%">
                            <tr>
                                <th><?php echo lang('File');?>:</th>
                                <th><?php echo lang('Date');?>:</th>
                                <th><?php echo lang('Description');?>:</th>
                            </tr>

                            <?php foreach ($project['files']['files'] as $key => $files) {
                                $filedate = new DateTime($files['dateofuploading']); ?>
                                <tr class="frontfiles_tr">
                                    <td>
                                    <?php if ($files['file']!= '' && $files['file'] != '0'){ ?>
                                        <a class="frontfiles_link" href="<?php echo PROJECT_IMAGE_PATH.$files['file'];?>">
                                            <img src="/images/icons/<?php echo filetypeIcon($files['file']);?>" alt=<?php echo lang('file')?> title=<?php echo lang('file')?>>
                                        </a>
                                    <?php } else { echo "No File";} ?> </td>
                                    <td><?php if ($files['dateofuploading']!= '') { echo $filedate->format(DATEFORMATVIEW);} else { echo "N/A";} ?></td>
                                    <td><?php if ($files['description']!= '') { echo $files['description'];} else { echo "N/A";} ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } ?>
				</div>

			</div><!-- end #tabs -->

			<div class="comments white_box pull_up_white">
				<h2><?php echo lang('ProjectUpdatesTitle') ?></h2>
                <?php
                // If it is the project owner
                if ($userdata['uid'] == sess_var('uid')) {
                    $author_src = project_image($project['projectdata']['projectphoto'], 43);
                    $placeholder = lang('UpdateStatusPlaceholder');
                } else {
                    $author_src = expert_image(sess_var('userphoto'), 43);
                    $placeholder = lang('UpdateCommentPlaceholder');
                }
                ?>
				<div class="comment-wrapper post main-post">
					<div class="photo">
						<img src="<?php echo $author_src ?>" class="thumb" alt="" />
					</div>
					<div class="comment">
                        <?php
                        echo form_open('updates/post/project/' . $project['pid'], 'name="post_update"', array(
                            'author' => sess_var('uid'),
                            'type' => ($userdata['uid'] == sess_var('uid')) ? UPDATE_TYPE_STATUS : UPDATE_TYPE_COMMENT,
                        ));
                        ?>
						<div class="field-wrapper">
							<textarea class="post-comment" placeholder="<?php echo $placeholder ?>"></textarea>
                            <div class="errormsg"></div>
							<input type="submit" class="light_green" value="<?php echo lang('PostUpdate') ?>">
						</div>
                        <?php echo form_close() ?>
					</div>
				</div>

				<ul class="feed updates">
                    <!-- Populated in JS -->
				</ul>
        <div class="center">
          <?php echo form_open('/updates/project/' . $project['pid'], 'name="updates_view_more"'); ?>
            <input type="submit" class="view-more button" value="<?php echo lang('LoadMoreUpdates') ?>">
            <?php echo form_close() ?>
        </div>
			</div>
		</div><!-- end #col2 -->

		<div id="col3" class="projects">
            <?php if ($userdata['uid'] != sess_var('uid')) {
                // User can't follow his or her own projects and send a message to him/her self
                echo form_open('', 'id="project_follow_form" name="follow_form"', array(
                    'context' => 'projects',
                    'id' => $project['pid'],
                    'action' => $project['isfollowing'] > 0 ? 'unfollow' : 'follow',
                    'return_follows' => 0
                )); ?>
                    <a href="#" id="submit" name="submit"
                       data-unfollow="<?php echo ($project['isfollowing'] > 0 ? lang('unfollow') : '') ?>"
                       class="button follow light_gray <?php echo ($project['isfollowing'] > 0 ? 'unfollow' : '')?>">
                        <span class="follow-text"><?php echo ($project['isfollowing'] > 0 ? lang('following') : lang('follow')) ?></span>
                        <!--[if IE 8]><span class="ie-8-unfollow">Unfollow</span><![endif]-->
                    </a>
                <?php echo form_close(); ?>
                <?php if (!in_array($userdata['uid'], INTERNAL_USERS)) { ?>
	                <a href="#" id="project_send_message" class="button mail light_gray"><?php echo lang('Message') ?></a>
	            <?php } ?>
            <?php } ?>
            <?php if ($project['discussions_access']) { ?>
                <a href="/projects/discussions/<?php echo $project['pid'] ?>" class="button discussion light_gray"><?php echo lang('Discussions') ?></a>
            <?php } ?>
            <?php if ($userdata['uid'] == sess_var('uid')) { ?>
<!--                <a href="/projects/discussions/create/--><?php //echo $project['pid'] ?><!--" class="button discussion light_gray">--><?php //echo lang('DiscussionNew') ?><!--</a>-->
                <a href="/projects/edit/<?php echo $slug ?>" class="button edit light_gray"><?php echo lang('EditProject');?></a>
            <?php } ?>

			<?php if (!in_array($userdata['uid'], INTERNAL_USERS)) { ?>
			<section class="executive white_box" id="project_executive">
                <h2><?php echo (($userdata['membertype'] == MEMBER_TYPE_EXPERT_ADVERT) ? lang('Organization') : lang('ProjectExecutive')) ?></h2>

				<div class="image">
                <?php
                $src = expert_image($userdata['userphoto'], 138, array(
                    'width' => 138,
                    'rounded_corners' => array( 'all','2' ),
                    'crop' => TRUE
                ));
                $fullname = (($userdata['membertype'] == MEMBER_TYPE_EXPERT_ADVERT) ? $userdata['organization'] : $userdata['firstname'] . ' ' . $userdata['lastname']);
                ?>
                <a href="/expertise/<?php $userdata["uid"] ?>"></a>
                    <img src="<?php echo $src ?>" alt="<?php echo $fullname ?>'s photo" style="margin:0px;">
				</div>

				<div class="executive-details">
					<h2 class="name"><a href="/expertise/<?php echo $userdata["uid"]; ?>"><?php echo $fullname; ?></a></h2>
					<?php $orgmemberid =  is_organization_member($userdata['uid']);
					if ($userdata["membertype"] != MEMBER_TYPE_EXPERT_ADVERT && isset($orgmemberid) && $orgmemberid!= '' ) { ?>
						<p><strong><?php echo $userdata['title'];?></strong></p>
						<p><a href="/expertise/<?php echo $orgmemberid; ?>"><?php echo $userdata['organization'];?></a></p>
					<?php } else if ($userdata["membertype"] != MEMBER_TYPE_EXPERT_ADVERT) {?>
						<p><strong><?php echo $userdata['title'] ?></strong></p>
						<p><?php echo $userdata['organization'] ?></p>
					<?php } else { ?>
						<p><?php echo $userdata['discipline'] ?></p>
					<?php } ?>
				</div>
			</section>
			<?php } ?>

            <?php // Visible only to the project owner ?>
            <?php if ($userdata['uid'] == sess_var('uid')) { ?>
            <!-- Global Experts -->
			<section class="portlet white_box">
				<h4>
                    <a href="/companies/<?php echo $project['lightning'] ?>" class="lightning"><?php echo lang('GlobalExperts');?></a>
                </h4>
				<ul class="expert_list">
                    <?php
                    $topexp_count = count($project['topexperts']);
                    $topexp_total = 0;

                    foreach ($project['topexperts'] as $expert)  {
                        $fullname = $expert['firstname'] . ' ' . $expert['lastname'];
                        $src = expert_image($expert['userphoto'], 39);
                        $topexp_total = $expert['row_count'];
                    ?>
                    <li class="clearfix" style="min-height:55px;">
                        <a href="/expertise/<?php echo $expert['uid'] ?>" class="image">
                            <img src="<?php echo $src ?>" alt="<?php echo $fullname ?>'s photo">
                        </a>
                        <p>
                            <a href="/expertise/<?php echo $expert['uid'] ?>"><?php echo $fullname ?></a><br>
                            <span class="title"><?php echo $expert['title'] ?></span><br>
                            <span class="title"><?php echo $expert['organization'] ?></span><br>
                        </p>
                    </li>
                    <?php } ?>
                    <?php if ($topexp_total > $topexp_count) { ?>
                        <li class="clearfix">
                            <a href="topexperts/<?php echo $slug ?>"><?php echo lang('ViewMore') ?></a>
                        </li>
                    <?php } ?>
                    <?php if ($topexp_count == 0) { ?>
                        <li class="clearfix"><?php echo lang('NoTopExpertsfound') ?></li>
                    <?php } ?>
				</ul>
			</section><!-- end .portlet -->

            <!-- SME Service Providers -->
            <section class="portlet white_box">
				<h4><?php echo lang('SMEServiceProviders') ?></h4>
				<ul class="expert_list">
                    <?php
                    $smeexp_count = count($project['smeexperts']);
                    $smeexp_total = 0;

                    foreach ($project['smeexperts'] as $expert)  {
                    $fullname = $expert['firstname'] . ' ' . $expert['lastname'];
                    $src = expert_image($expert['userphoto'], 39);
                    $smeexp_total = $expert['row_count'];
                    ?>
                    <li class="clearfix" style="min-height:55px;">
                        <a href="/expertise/<?php echo $expert['uid'] ?>" class="image">
                            <img src="<?php echo $src ?>" alt="<?php echo $fullname ?>'s photo">
                        </a>
                        <p>
                            <a href="/expertise/<?php echo $expert['uid'] ?>"><?php echo $fullname ?></a><br>
                            <span class="title"><?php echo $expert['title'] ?></span><br>
                            <span class="title"><?php echo $expert['organization'] ?></span><br>
                        </p>
                    </li>
                    <?php } ?>
                    <?php if ($smeexp_total > $smeexp_count) { ?>
                        <li class="clearfix">
                            <a href="smeexperts/<?php echo $slug ?>"><?php echo lang('ViewMore') ?></a>
                        </li>
                    <?php } ?>
                    <?php if ($smeexp_count == 0) { ?>
                        <li class="clearfix"><?php echo lang('NoSMEExpertsfound') ?></li>
                    <?php } ?>
				</ul>
			</section>
            <?php } ?>

            <?php // Similar Projects ?>
            <?php if (! empty($project['similar_projects'])) { ?>
                <div class="portlet white_box">
                    <h4><?php echo strtoupper(lang('SimilarProjects')) ?></h4>
                    <?php foreach ($project['similar_projects'] as $similar_project) { ?>
                        <article class="m_project">
                            <div class="image">
                                <div class="image_wrap">
                                    <a href="<?php echo '/projects/' . $similar_project['id'] ?>">
                                        <img src="<?php echo project_image($similar_project['projectphoto']) ?>" alt="<?php echo $similar_project['projectname'] . "'s photo" ?>">
                                    </a>
                                </div>
                                <span class="ps_<?php echo project_stage_class($similar_project['stage']) ?>"></span>
                                <span class="price"><?php echo format_budget($similar_project['totalbudget']) ?></span>
                            </div>
                            <div class="content">
                                <h3 class="the_title"><a href="<?php echo '/projects/' . $similar_project['id'] ?>"><?php echo $similar_project['projectname'] ?></a></h3>
                                <span class="type <?php echo project_sector_class($similar_project['sector']) ?>"><?php echo ucfirst($similar_project['sector']) ?></span>
                            </div>
                        </article>
                    <?php } ?>
                </div>
            <?php } ?>

			<?php
				$l = 0;
				if(count($project['organizationmatch']) >0)
				{
				?>
				<section class="portlet white_box expert-orgs">
					<h4><?php echo lang('ExpertOrganizations');?></h4>
				<?php
					$orgCount = 0;
					foreach($project['organizationmatch'] as $key => $orgexp)
					{
						if($orgexp['uid'] == $userdata['uid'])
						{
							continue;
						}
						if($orgCount < 3)
						{
							?>
						 
							<a href="/expertise/<?php echo $orgexp['uid'];?>">
                                <img alt="<?php echo $orgexp['firstname']." ".$orgexp['lastname']; ?>" src="<?php echo expert_image($orgexp["userphoto"], 168, array('crop'=>false));?>" >
							</a>
							
					<?php }
						$l++;
						$orgCount++;
					} ?>
				</section><!-- end .portlet -->
				<?php	}	?>
		</div><!-- end #col3 -->
	</div><!-- end #content -->

	<div id="dialog-message"></div>

    <?php $this->load->view('templates/_send_email', array(
        'to' => $userdata['uid'],
        'to_name' => $userdata['membertype'] == MEMBER_TYPE_EXPERT_ADVERT ? $userdata['organization'] : $userdata['firstname'] . ' ' . $userdata['lastname'],
        'from' => sess_var('uid')
    )) ?>

<?php if (($project['projectdata']['lat'] && $project['projectdata']['lng']) || $isAdminorOwner )  { ?>
<script>
	var mapCoords = [<?php echo $project['projectdata']['lat'],',', $project['projectdata']['lng'];?>];
	var isAdmin = <?php echo $isAdminorOwner ? 'true' : 'false'; ?>;
	var slug = '<?php echo $slug; ?>';
	var map_geom = <?php echo json_encode($map_geom); ?>;
</script>
<?php } ?>