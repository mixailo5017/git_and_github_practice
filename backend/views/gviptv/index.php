<div class="centercontent tables">

    <div class="pageheader notab">
        <h1 class="pagetitle">View GViP TV Videos</h1>
        <span class="pagedesc">&nbsp;</span>
    </div><!--pageheader-->

    <div id="contentwrapper" class="contentwrapper">
        <div class="contenttitle2">
            <?php echo heading("View All GViP TV Videos List", 3); ?>
        </div><!--contenttitle-->
        <div class="notibar" style="display:none">
            <a class="close"></a>
            <p></p>
        </div>

        <table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable_forums">
            <colgroup>
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
                <col class="con0" />
            </colgroup>
            <thead>
            <tr>
                <th class="head1">ID</th>
                <th class="head0">Title</th>
                <th class="head1">Category</th>
                <th class="head0">Status</th>
                <th class="head0 nosort">Action</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th class="head1">ID</th>
                <th class="head0">Title</th>
                <th class="head1">Category</th>
                <th class="head0">Status</th>
                <th class="head0 nosort">Action</th>
            </tr>
            </tfoot>
            <tbody>
            <?php
            foreach($rows as $row) {

                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td>
                        <a href="/<?php echo index_page(); ?>/gviptv/edit/<?php echo $row['id']; ?>">
                            <?php echo $row['title']; ?>
                        </a>
                    </td>
                    <td><?php echo $row['category']; ?></td>
                    <td><?php echo ($row['status'] == '1') ? 'Active' : 'Draft'; ?></td>

                    <td>
                        <a class="delete" href="gviptv/destroy/<?php echo $row['id']; ?>" >Delete</a>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div><!--contentwrapper-->
</div>
