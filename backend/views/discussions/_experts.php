<div id="discussion_expert_list_form" class="clearfix">
    <div class="contenttitle2">
        <h3>Expert List</h3>
    </div>

    <div class="notibar" style="display:none">
        <a class="close"></a>
        <p></p>
    </div>

    <div class="tableoptions">
        <!--            <div class="filter-options feed-project">-->
        <label><span>Status:</span> <?php echo form_dropdown('status', array('' => 'All', 'Allowed' => 'Allowed', 'Denied' => 'Denied'), '', 'id="discussion_members_status_filter"') ?></label>
        <!--            </div>-->
    </div><!--tableoptions-->

    <table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_discussion_members">
        <colgroup>
            <col class="con0" style="width: 4%" />
            <col class="con1" />
            <col class="con0" />
            <col class="con1" />
            <col class="con0" />
            <col class="con1" />
        </colgroup>
        <thead>
        <tr>
            <th class="head0 nosort" align="center">
                <span class="center">
                    <?php echo form_checkbox(array(
                        'id' => 'select_all_header',
                        'name' => 'select_all_header',
                        'class' => 'checkall'
                    )); ?>
                </span>
            </th>
            <th class="head1">ID</th>
            <th class="head0">Name</th>
            <th class="head1">Organization</th>
            <th class="head0">Title</th>
            <th class="head1">Status</th>
            <th class="head0 nosort">Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th class="head0 nosort" align="center">
                <span class="center">
                    <?php echo form_checkbox(array(
                        'id' => 'select_all_header',
                        'name' => 'select_all_header',
                        'class' => 'checkall'
                    )); ?>
                </span>
            </th>
            <th class="head1">ID</th>
            <th class="head0">Name</th>
            <th class="head1">Organization</th>
            <th class="head0">Title</th>
            <th class="head1">Status</th>
            <th class="head0 nosort">Action</th>
        </tr>
        </tfoot>
        <tbody>
        <?php foreach($experts as $expert) { ?>
            <tr>
                <td align="center">
                    <span class="center">
                        <?php echo form_checkbox(array(
                            'id' => 'select_' . $expert['id'],
                            'name' =>' select_' . $expert['id'],
                            'value' => $expert['id']
                        )); ?>
                    </span>
                </td>
                <td><?php echo $expert['id'] ?></td>
                <td><a href="/<?php echo index_page() ?>/myaccount/<?php echo $expert['id'] ?>"><?php echo $expert['expert_name'] ?></a></td>
                <td><?php echo $expert['organization'] ?></td>
                <td><?php echo $expert['title'] ?></td>
                <td class="status"><?php echo $expert['status'] ? 'Allowed' : 'Denied' ?></td>
                <td><?php $action = $expert['status'] ? 'deny' : 'allow' ?>
                    <a href="#" class="soft_delete"
                       data-id="<?php echo $expert['id'] ?>"
                       data-type="discussionMember"
                       data-action="<?php echo $action ?>"
                       data-url="/<?php echo index_page() ?>/discussions/members/<?php echo $discussion['id'] ?>/"
                       ><?php echo ucfirst($action) ?></a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
