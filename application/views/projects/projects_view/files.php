                <h2><?php echo lang('Files')        ?></h2>
                <div id="tabs-6" class="col2_tab">
                    <?php if ($project['files']['image_files_count'] > 0) {
                        foreach ($project['files']['files']['image_files'] as $image_file) { ?>
                            <img src="<?php echo project_image($image_file['file'], 300) ?>">
                        <?php }
                     } ?>
                    <?php if ($project['files']['other_files_count'] > 0) { ?>
                        <table width="100%">
                            <tr>
                                <th><?php echo lang('File');?>:</th>
                                <th><?php echo lang('Date');?>:</th>
                                <th><?php echo lang('Description');?>:</th>
                            </tr>

                            <?php foreach ($project['files']['files']['other_files'] as $key => $files) {
                                $filedate = new DateTime($files['dateofuploading']); ?>
                                <tr class="frontfiles_tr">
                                    <td>
                                    <?php if ($files['file']!= '' && $files['file'] != '0'){ ?>
                                        <a class="frontfiles_link" href="<?php echo PROJECT_IMAGE_PATH.$files['file'];?>" target="_blank">
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