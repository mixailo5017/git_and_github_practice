<div class="centercontent tables">

    <div class="pageheader notab">
        <h1 class="pagetitle">Project Feed</h1>
        <span class="pagedesc">&nbsp;</span>
    </div><!--pageheader-->

    <div id="contentwrapper" class="contentwrapper">
        <div class="contenttitle2">
            <h3>Project Feed</h3>
        </div><!--contenttitle-->
        <div class="notibar" style="display:none">
            <a class="close"></a>
            <p></p>
        </div>
        <div class="tableoptions">
<!--            <button class="deletebutton radius3" title="Delete Selected" name="dyntable_forums" id="#/admin.php/store/destroy">Delete Selected</button> &nbsp;-->
            <?php echo form_open(current_url(), array('name' => 'search_form')) ?>
                <strong class="filter-options">Filter by</strong>
                <div class="filter-options feed-id">
                    <label><span>ID:</span> <?php echo form_input('id', set_value('id', $filter['id'])) ?></label>
                </div>
                <div class="filter-options feed-author">
                    <label><span>Author:</span> <?php echo form_dropdown('author_id', $authors, set_value('author_id', $filter['author_id'])) ?></label>
                </div>
                <div class="filter-options feed-project">
                    <label><span>Project:</span> <?php echo form_dropdown('project_id', $projects, set_value('project_id', $filter['project_id'])) ?></label>
                </div>
                <div class="filter-options feed-date datepicker">
                    <label><span>Created:</span> <?php echo form_input('created_at', set_value('created_at', $filter['created_at'])); ?></label>
                </div>
                <?php echo form_submit('search', 'Load') ?>
            <?php echo form_close() ?>
        </div><!--tableoptions-->

        <table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_forums">
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
                    <th class="head0 nosort" align="center"><span class="center">
                        <?php echo form_checkbox(array(
                            'id' => 'select_all_header',
                            'name' => 'select_all_header',
                            'class' => 'checkall'
                        )); ?></span>
                    </th>
                    <th class="head1">ID</th>
                    <th class="head0">Author</th>
                    <th class="head1">Content</th>
                    <th class="head0">Reply to</th>
                    <th class="head1">Created</th>
                    <th class="head0">Status</th>
                    <th class="head1">Flags</th>
                    <th class="head0 nosort">Action</th>
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
                    <th class="head0">Author</th>
                    <th class="head1">Content</th>
                    <th class="head0">Reply to</th>
                    <th class="head1">Created</th>
                    <th class="head0">Status</th>
                    <th class="head1">Flags</th>
                    <th class="head0 nosort">Action</th>
                </tr>
            </tfoot>
            <tbody>
            <?php foreach($rows as $row) { ?>
                <tr data-id="<?php echo $row['id'] ?>">
                    <td align="center"><span class="center"><?php echo form_checkbox(array(
                                'id' => 'select_' . $row['id'],
                                'name' =>' select_' . $row['id'],
                                'value' => $row['id']
                            )); ?></span></td>
                    <td><?php echo $row['id'] ?></td>
                    <td><a href="/<?php echo index_page() ?>/myaccount/<?php echo $row['author'] ?>"><?php echo $row['author_name'] ?></a></td>
                    <td><a href="/<?php echo index_page() ?>/updates/<?php echo $row['id'] ?>"><?php echo $row['content'] ?></a></td>
                    <td><a href="/<?php echo index_page() ?>/updates/<?php echo $row['reply_to'] ?>"><?php echo $row['reply_to'] ?></a></td>
                    <td><?php echo format_date($row['created_at'], 'Y-m-d H:i:s') ?></td>
                    <td class="status"><?php echo empty($row['deleted_at']) ? 'Active' : 'Inactive' ?></td>
                    <td></td>
                    <td>
                        <?php $action = empty($row['deleted_at']) ? 'delete' : 'restore' ?>
                        <a href="#" class="soft_delete" data-type="update" data-id="<?php echo $row['id'] ?>" data-url="/<?php echo index_page() ?>/updates/" data-action="<?php echo $action ?>"><?php echo ucfirst($action) ?></a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div><!--contentwrapper-->
</div>

<script>
    jQuery("form[name=search_form] select").chosen();
    jQuery("form[name=search_form] select").on('change', function(){
        var activeSelect = jQuery(".chzn-container-active a span").text();
        jQuery(".chzn-container-active a").attr("title", activeSelect);
    });
    jQuery(".datepicker input").datepicker({ defaultDate: '' });
</script>