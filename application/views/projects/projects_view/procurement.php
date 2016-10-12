                <h2><?php echo lang('Procurement')  ?></h2>
                <div id="tabs-5" class="col2_tab">
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