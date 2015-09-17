<!DOCTYPE html>
<html lang="en">
<head>
    <!-- http://templates.mailchimp.com/resources/inline-css/ -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--    <meta charset="UTF-8">-->
    <title></title>
    <style>
        @import url(http://fonts.googleapis.com/css?family=Open+Sans:400,700);
        body{background:#f3f3f3; color:#373b43; font-family: 'Open Sans', Helvetica, Arial, sans-serif; font-weight:400;}
        .background{background:#f3f3f3;}
        .contain{width:575px; margin:auto;}
        .message{border:1px solid #D9D9D9; background:#FFF;}
        table{border-collapse:collapse; font-family: 'Open Sans', Helvetica, Arial, sans-serif; font-weight:400;}
        thead th, thead td{background:#EDEDF0; border-top:1px solid #F8F8F9;border-bottom:1px solid #D9D9D9;}
        thead td:first-child{border-left:#F8F8F9;}
        thead th{color:#373b43; font-size:16px; text-align:left; vertical-align: middle}

        thead th a{color:#ed4300; text-decoration: underline;}
        img{display:block;}
        img.ib{display:inline-block; vertical-align: middle;}
        ol,ul{margin:0; padding-bottom:25px;}
        ul ul, ul ul ul{margin-top:5px; padding-bottom:0;}
        ol ol{list-style-type: lower-alpha; margin-top:5px;padding-bottom:0;}
        ol ol ol{list-style-type: lower-roman;margin-top:5px;padding-bottom:0;}
        .message a{color:#40a7e2; text-decoration: underline;}
        .message a:hover, .message a:focus{color:#6cbbe9;}
        h1{font-size:21px; color:#373b43; padding-bottom: 9px;font-weight:bold;margin:0;}
        h2{font-size:16px; color:#373b43; padding-bottom: 9px;font-weight:bold;margin:0;}
        h3{font-size:14px; text-transform:uppercase; color:#373b43; padding-bottom: 9px;font-weight:bold;margin:0;}
        h4{font-size:13px;  color:#373b43; padding-bottom: 9px;font-weight:bold;margin:0;}
        h5{font-size:13px;  text-transform:uppercase; color:#373b43; padding-bottom: 9px;font-weight:bold;margin:0;}
        p{font-size:13px;color:#373b43; line-height:1.3; padding-bottom:27px;margin:0;}
        li{font-size:13px;color:#373b43;line-height:1.2; margin:0 0 5px;}

        blockquote p {font-size:30px; color:#3a96d6; font-weight:normal; padding-bottom:20;}
        blockquote p.cite{color:#373b43; font-weight: bold; font-size:13px;}

        img[align="left"]{border-right:23px solid #FFF; border-bottom:10px solid #FFF;}
        img[align="right"]{border-left:23px solid #FFF; border-bottom:10px solid #FFF;}

        .footer p{color: #798397;font-size: 11px; line-height:1.2;}

        table.dark thead th, table.dark thead td{background:#515663; color:#FFF; border:none;}
        table.dark thead th a{color:#40a7e2; text-decoration: underline;}
        table.dark thead th a:hover, table.dark thead th a:focus{color:#6cbbe9;}
    </style>
</head>
<body>

<?php $spacer = base_url() . 'images/email/spacer.gif' ?>

<div class="background" style="background: #f3f3f3;">
    <div class="contain" style="width: 575px;margin: auto;">

        <div class="header">
            <div><img src="<?php echo $spacer ?>" alt="" height="1" width="300" style="display: block;"></div>
            <a href="<?php echo base_url() ?>"><img src="<?php echo base_url() ?>images/email/logo.png" alt="<?php echo SITE_NAME ?>" style="display: block;"></a>
            <div><img src="<?php echo $spacer ?>" alt="" height="1" style="display: block;"></div>
        </div><!-- header -->
