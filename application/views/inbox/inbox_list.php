<div class="mail-option">
    <!-- <div class="btn-group hidden-phone">
         <a data-toggle="dropdown" href="#" class="btn light_gray" aria-expanded="false">
             Modify
             <i class="fa fa-angle-down "></i>
         </a>
         <ul class="dropdown-menu">
             <li><a href="#"><i class="fa fa-pencil"></i> Mark as Read</a></li>
             <li><a href="#"><i class="fa fa-pencil"></i> Mark as Unread</a></li>
             <li class="divider"></li>
             <li><a href="#"><i class="fa fa-trash-o"></i> Delete</a></li>
         </ul>
     </div> -->

    <div class="btn-group">
        <h2>
            <?php
                if ($message['issent'] == true)
                {
                    echo 'Recipient:';
                }
                else {
                    echo 'From:';
                }

            ?>
        </h2>
    </div>

    <ul class="unstyled inbox-pagination">
        <li><span><?php echo $message['totalmessages'] ?> Total Messages</span></li>
        <!--<li>
            <a class="np-btn" href="#"><i class="fa fa-angle-left  pagination-left"></i></a>
        </li>
        <li>
            <a class="np-btn" href="#"><i class="fa fa-angle-right pagination-right"></i></a>
        </li>-->
    </ul>
</div>
<table class="table table-inbox table-hover">
    <tbody>

    <?php foreach($message['msg'] as $msgkey=>$msgval)
    {?>

        <tr onclick="DoNav('/inbox/message_view/<?php echo $msgval['msgid']?>')" class="unread">
            <!--<td class="inbox-small-cells">
                <input type="checkbox" class="mail-checkbox">
            </td>-->
            <?php
            if ($msgval['membertype'] == 5){
                $fullname = $msgval['firstname'].' '.$msgval['lastname'];
            }
            else
            {
                $fullname = $msgval['organization'];
            }
            ?>
                <td class="view-message  dont-show"><?php echo $fullname; ?></td>
                <td class="view-message "><?php echo $msgval['msgsubject']; ?></td>
                <td class="view-message  inbox-small-cells"></td>
                <td class="view-message  text-right"><?php echo date('F j, Y',strtotime($msgval['msgdatetime'])); ?></td>
        </tr>
        <script type="text/javascript">
            function DoNav(url)
            {
                document.location.href = url;
            }
        </script>

    <?php }
    ?>

    </tbody>
</table>