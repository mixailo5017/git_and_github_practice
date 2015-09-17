<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js gte-ie9" lang="en"> <!--<![endif]-->
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="description" content="GViP is a global platform connecting infrastructure project developers with experts. GViP has over 800 projects and over 1000 experts. Sign up to connect with leading experts." />
    <meta name="viewport" content="width=device-width" />

    <title><?php echo empty($title) ? SITE_NAME : $title ?></title>

    <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet" type="text/css" />

    <?php echo link_tag('css/style.css' . asset_version('style.css')) ?>

    <script src="/js/modernizr.js" type="text/javascript" ></script>

    <?php $this->load->view('templates/_segment_analytics', empty($page_analytics) ? array() : $page_analytics);?>

	<?php if (isset($header_extra) && $header_extra != '') echo $header_extra; ?>
</head>

<body id="<?php echo isset($bodyid) ? $bodyid : '' ?>" class="<?php echo isset($bodyclass) ? $bodyclass : '' ?>">
    <div class="wrapper">

    <?php $this->load->view('layouts/header') ?>

    <?php if (isset($breadcrumb) && $breadcrumb != '') { ?>

    <div class="content clearfix">
        <ul class="header_bread_crumb">
            <?php echo $breadcrumb ?>
        </ul>
    </div>
    <?php } ?>