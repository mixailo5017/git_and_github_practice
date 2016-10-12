				<h2><?php echo lang('Fundamentals') ?></h2>
				<div id="tabs-2" class="col2_tab">
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