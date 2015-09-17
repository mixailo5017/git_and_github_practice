<div class="centercontent tables">
    <div class="pageheader notab">
        <h1 class="pagetitle">View Members</h1>
        <span class="pagedesc">&nbsp;</span>
    </div><!--pageheader-->
	   
    <div id="contentwrapper" class="contentwrapper">
        <div class="contenttitle2">
            <?php echo heading("View All Members List",3); ?>
        </div><!--contenttitle-->

        <div class="notibar" style="display:none">
            <a class="close"></a>
            <p></p>
        </div>

        <div class="tableoptions">
<!--            <button class="deletebutton radius3" title="Delete Selected" name="dyntable2" id="#/admin.php/members/delete">Delete Selected</button> &nbsp;-->
            Member Group&nbsp;:
            <?php
                $group_attr = "class='radius3' id='member_group_filter'";
                $group_options = membergrouplist();
                $group_first = array('class'=>'','text'=>'All','value'=>'');
                echo form_custom_dropdown("member_group_filter", $group_options, $members["member_group"], $group_attr, array(), $group_first);
            ?>
<!--            --><?php //echo form_open('/members/members_csv', array('name' => 'export_form')); ?>
<!--                <div class="filter-options right">-->
<!--                    <label>Fields to export-->
<!--                    --><?php //echo form_multiselect('fields[]', $fields, set_value('fields[]', $default_fields), 'data-placeholder="Choose a fields..." class="chosen-select" style="width:350px;"') ?>
<!--                    </label>-->
<!--                    --><?php //echo form_submit('export', 'Export', 'class="light_green"') ?>
<!--                </div>-->
<!--            --><?php //echo form_close(); ?>

<!--            <button data-link="/admin.php/members/members_csv" class="radius3 right">Export Mailing List</button>-->
            <button data-link="/admin.php/members/export" class="radius3 right">Export</button>
        </div><!--tableoptions-->

        <table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable2">
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
            </colgroup>
            <thead>
                <tr>
                    <th class="head0 nosort" align="center"><?php echo form_checkbox(array("id"=>"select_all_header","name"=>"select_all_header","class"=>"checkall")); ?></th>
                    <th class="head1">ID</th>
                    <th class="head0">Name</th>
                    <th class="head1">Email</th>
                    <th class="head0">Joined</th>
                    <th class="head1">Group</th>
                    <th class="head0">Status</th>
                    <th class="head1">Access</th>
                    <th class="head0 nosort">Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="head0" align="center"><span class="center"><?php echo form_checkbox(array("id"=>"select_all_footer","name"=>"select_all_footer","class"=>"checkall")); ?></span></th>
                    <th class="head1">ID</th>
                    <th class="head0">Name</th>
                    <th class="head1">Email</th>
                    <th class="head0">Joined</th>
                    <th class="head1">Group</th>
                    <th class="head0">Status</th>
                    <th class="head1">Access</th>
                    <th class="head0 nosort">Action</th>
                </tr>
            </tfoot>
            <tbody>
            <?php foreach ($members['data'] as $member) {
                $uid = $member['uid'];
                $fullname = $member['membertype'] == MEMBER_TYPE_EXPERT_ADVERT ? $member['organization'] : $member['firstname'].' '.$member['lastname'];
                switch ($member['status']) {
                    case '0': $status = 'Inactive'; break;
                    case '1': $status = 'Active'; break;
                    case '2': $status = 'Pending'; break;
                }
            ?>
                <tr class="member">
                    <td align="center">
                        <span class="center"><?php echo form_checkbox(array('id' => "select_$uid", 'name' => "select_$uid", 'value' => $uid)) ?></span>
                    </td>
                    <td><?php echo $uid ?></td>
                    <td><a href="/<?php echo index_page(); ?>/myaccount/<?php echo $uid ?>"><?php echo $fullname ?></a></td>
                    <td><a href="mailto:<?php echo $member['email'] ?>"><?php echo $member['email'] ?></a></td>
                    <td><?php echo DateFormat($member['registerdate'], DATEFORMAT) ?></td>
                    <td><?php echo $member['typename'] ?></td>
                    <td class="status">
<!--                        --><?php //if ($member['status'] == '0') { ?>
<!--                        <a href="/--><?php //echo index_page(); ?><!--/members/approve/--><?php //echo $uid ?><!--">Approve</a> | <a href="/--><?php //echo index_page(); ?><!--/members/deny/--><?php //echo $uid ?><!--">Deny</a>-->
<!--                        --><?php //} else { echo $status; } ?>
                        <?php echo $status ?>
                    </td>
                    <td class="access">
                        <?php if ($member['membertype'] == MEMBER_TYPE_MEMBER || $member['membertype'] == MEMBER_TYPE_EXPERT_ADVERT) { ?>
                        <a href="/login/admin/<?php echo $uid ?>" target="_blank" style="<?php echo $member['status'] == STATUS_INACTIVE ? 'display: none;' : '' ?>">Access</a>
                       <?php } ?>
                    </td>
                    <td class="action">
<!--                        --><?php //if (isset($member['membertype']) && $member['membertype'] != '1'){?>
<!--                            <a class="delete" href="#" name="--><?php //echo $uid ?><!--" id="#/admin.php/members/delete">Delete</a>-->
<!--                        --><?php //} ?>
                        <?php if ($uid != sess_var('admin_uid')) {
                            $action =  $member['status'] == STATUS_INACTIVE ? 'restore' : 'delete' ?>
                            <a class="soft_delete" data-type="member" data-url="/admin.php/members/" data-action="<?php echo $action ?>" data-id="<?php echo $uid ?>" href="#"><?php echo ucfirst($action) ?></a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div><!--contentwrapper-->
</div>
<script>
//    jQuery("select[name=member_group_filter]").chosen();
    jQuery("form[name=export_form] select").chosen();
</script>