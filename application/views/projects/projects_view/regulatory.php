                <h2><?php echo lang('Regulatory')   ?></h2>
                <div id="tabs-7" class="col2_tab">
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
                                            <a class="frontfiles_link" href="<?php echo PROJECT_IMAGE_PATH . $regulatory['file'];?>" target="_blank">
                                                <img src="/images/icons/<?php echo filetypeIcon($regulatory['file']);?>" alt=<?php echo lang('file')?> title=<?php echo lang('file')?>>
                                            </a>
                                        <?php } else { echo "No File"; } ?> </td>
                                    <td><?php if ($regulatory['description']!= '') { echo $regulatory['description'];} else { echo "N/A";} ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } ?>
                </div>