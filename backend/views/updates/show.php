<div class="centercontent">
    <div class="pageheader notab">
        <h1 class="pagetitle">Project Feed</h1>
        <span class="pagedesc">&nbsp;</span>
    </div>

    <div id="contentwrapper" class="contentwrapper">
        <div class="one_half">
            <div class="contenttitle2">
                <h3>Comment Details</h3>
            </div>

            <table cellpadding="0" cellspacing="0" class="table invoicefor">
                <tbody>
                    <tr>
                        <td width="30%">ID:</td>
                        <td width="70%"><?php echo $update['id'] ?></td>
                    </tr>
                    <tr>
                        <td>Author:</td>
                        <td><a href="/<?php echo index_page() ?>/myaccount/<?php echo $update['author'] ?>"><?php echo $update['author_name'] ?></a></td>
                    </tr>
                    <tr>
                        <td>Project:</td>
                        <td><a href="/<?php echo index_page() ?>/projects/edit/<?php echo $update['project_slug'] ?>"><?php echo $update['project_name'] ?></a></td>
                    </tr>
                    <tr>
                        <td>Created:</td>
                        <td><?php echo format_date($update['created_at'], 'm/d/Y H:s:i') ?></td>
                    </tr>
                    <tr>
                        <td>Reply to:</td>
                        <td><a href="/<?php echo index_page() ?>/updates/<?php echo $update['reply_to'] ?>"><?php echo $update['reply_to_content'] ?></a></td>
                    </tr>
                    <tr>
                        <td>Status:</td>
                        <td><?php echo empty($update['deleted_at']) ? 'Active' : 'Inactive' ?></td>
                    </tr>
                    <tr>
                        <td>Content:</td>
                        <td><?php echo $update['content'] ?></td>
                    </tr>
                </tbody>
            </table>
            <p>&nbsp;</p>
        </div>
    </div><!--contentwrapper-->
</div>