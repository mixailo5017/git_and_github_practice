                <h2><?php echo lang('Financial')    ?></h2>
                <div id="tabs-3" class="col2_tab">
                    <?php if (count($project['financial']['financial'])>0) { ?>
                        <h3><?php echo lang('FinancialStructure'); ?></h3>
                        <p><strong><?php echo lang('FinancialStructure');?> - </strong><?php if ($project['financial']['financial']['name'] != '') { echo $project['financial']['financial']['name'];} else { echo "N/A";} ?></p>
                        <?php if ($project['financial']['financial']['contactinfo'] != '') { ?>
                            <p><?php echo $project['financial']['financial']['contactinfo']; ?></p>
                        <?php } ?>
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