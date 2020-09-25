<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js gte-ie9" lang="en"> <!--<![endif]-->
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="description" content="GViP is a global platform connecting infrastructure project developers with experts. GViP has over 800 projects and over 1000 experts. Sign up to connect with leading experts." />
    <meta name="viewport" content="width=device-width" />

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->

    <title><?php echo empty($title) ? SITE_NAME : $title ?></title>

    <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet" type="text/css" />

    <?php echo link_tag('css/style.css' . asset_version('style.css')) ?>

    <script src="/js/modernizr.js" type="text/javascript" ></script>

    <?php $this->load->view('templates/_segment_analytics', empty($page_analytics) ? array() : $page_analytics);?>

    <?php if (isset($header_extra) && $header_extra != '') echo $header_extra; ?>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-43491822-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-43491822-3');
    </script>
</head>

<body id="<?php echo isset($bodyid) ? $bodyid : '' ?>" class="<?php echo isset($bodyclass) ? $bodyclass : '' ?>">
<div class="wrapper">

    <?php $this->load->view('inbox/header_view') ?>
