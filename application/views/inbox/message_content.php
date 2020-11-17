<?php
if ($message['msg'][0]['membertype'] == 5){
    $fullname = $message['msg'][0]['firstname'].' '.$message['msg'][0]['lastname'];
}
else
{
    $fullname = $message['msg'][0]['organization'];
}
?>
<div>
    <p>
        From: <?php echo $fullname;?> (<?php  print_r($message['msg'][0]['email']) ?>)
    </p>

    <h1>
        <?php  print_r($message['msg'][0]['msgsubject']) ?>
    </h1>
    <br>
    <p>
        <?php  print_r($message['msg'][0]['msgmessage']) ?>
    </p>

</div>
