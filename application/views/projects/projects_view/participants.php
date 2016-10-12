                <h2><?php echo lang('Participants') ?></h2>
                <div id="tabs-4" class="col2_tab">

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