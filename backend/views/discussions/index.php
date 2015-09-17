<div class="centercontent tables">
    <div class="pageheader notab">
        <h1 class="pagetitle">View Discussions</h1>
        <span class="pagedesc">&nbsp;</span>
    </div><!--pageheader-->

    <div id="contentwrapper" class="contentwrapper discussion-list">
        <div class="contenttitle2">
            <h3>View All Discussions List</h3>
        </div><!--contenttitle-->
        <div class="notibar" style="display:none">
            <a class="close"></a>
            <p></p>
        </div>
        <div class="tableoptions">
<!--            <div class="filter-options feed-project">-->
                <label><span>Project:</span> <?php echo form_dropdown('project_id', $projects, '', 'id="discussion_project_filter"') ?></label>
<!--            </div>-->
        </div><!--tableoptions-->

        <table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_discussions">
            <colgroup>
                <col class="con0" style="width: 4%" />
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
                <col class="con0" />
            </colgroup>
            <thead>
                <tr>
                    <th class="head0 nosort" align="center"><span class="center">
                        <?php echo form_checkbox(array(
                            'id' => 'select_all_header',
                            'name' => 'select_all_header',
                            'class' => 'checkall'
                        )); ?></span>
                    </th>
                    <th class="head1">ID</th>
                    <th class="head0">Title</th>
                    <th class="head1">Project</th>
                    <th class="head0">Created</th>
                    <th class="head1">Experts</th>
                    <th class="head0">Posts</th>
                    <th class="head1">Last Activity</th>
                    <th class="head0">Status</th>
                    <th class="head1 nosort">Action</th>
                    <th class="head0">Project Id</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="head0 nosort" align="center"><span class="center">
                        <?php echo form_checkbox(array(
                            'id' => 'select_all_header',
                            'name' => 'select_all_header',
                            'class' => 'checkall'
                        )); ?></span>
                    </th>
                    <th class="head1">ID</th>
                    <th class="head0">Title</th>
                    <th class="head1">Project</th>
                    <th class="head0">Created</th>
                    <th class="head1">Experts</th>
                    <th class="head0">Posts</th>
                    <th class="head1">Last Activity</th>
                    <th class="head0">Status</th>
                    <th class="head1 nosort">Action</th>
                    <th class="head0">Project Id</th>
                </tr>
            </tfoot>
            <tbody>
            <?php foreach($rows as $row) { ?>
                <tr>
                    <td align="center"><span class="center"><?php echo form_checkbox(array(
                        'id' => 'select_' . $row['id'],
                        'name' =>' select_' . $row['id'],
                        'value' => $row['id']
                    )); ?></span></td>

                    <td><?php echo $row['id'] ?></td>
                    <td><a href="/<?php echo index_page() ?>/discussions/edit/<?php echo $row['id'] ?>"><?php echo $row['title'] ?></a></td>
                    <td><a href="/<?php echo index_page() ?>/projects/edit/<?php echo $row['project_slug'] ?>"><?php echo $row['project_name'] ?></a></td>
                    <td><?php echo format_date($row['created_at'], 'Y-m-d H:i:s') ?></td>
                    <td><?php echo $row['expert_count'] ?></td>
                    <td><?php echo $row['post_count'] ?></td>
                    <td><?php echo $row['last_activity_at'] ? format_date($row['last_activity_at'], 'Y-m-d H:i:s') : '' ?></td>
                    <td><?php echo empty($row['deleted_at']) ? 'Active': 'Inactive' ?></td>
                    <td>&nbsp;</td>
                    <td><?php echo $row['project_id'] ?></td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div><!--contentwrapper-->
</div>

<script>
    jQuery(".tableoptions select").chosen();
</script>