<!DOCTYPE html>
<!--[if IE 9]><html class="ie9"> <![endif]-->
<!--[if gt IE 9]><!--> <html> <!--<![endif]-->
<head>
    <meta charset="UTF-8">
    <meta name="description" content="GViP is a global platform connecting infrastructure project developers with experts. GViP has over 1400 projects and over 2000 experts. Sign up to connect with leading experts." />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--[if lt IE 9]>
    <script src="/js/lib/html5shiv-printshiv.min.js"></script>
    <script src="/js/lib/html5shiv.min.js"></script>
    <![endif]-->

    <link href="/favicon-16x16.png" rel="icon" type="image/png" sizes="16x16">
    <link href="/favicon-32x32.png" rel="icon" type="image/png" sizes="32x32">

    <link href="/css/main.css<?php echo asset_version('main.css') ?>" rel="stylesheet" type="text/css">
    <?php // Extra styles ?>
    <?php if (! empty($styles)) {
        $styles = is_array($styles) ? $styles : array($styles);
        foreach ($styles as $style) { ?>
            <link href="/css/<?php echo $style ?>" rel="stylesheet" type="text/css">
    <?php }
    } ?>
    <title><?php echo empty($title) ? SITE_NAME : $title ?></title>

    <?php // Segment Analytics ?>
    <?php $this->load->view('templates/_segment_analytics', empty($page_analytics) ? array() : $page_analytics) ?>

    <?php // Extra stuff that we want to inject into head tags ?>
    <?php if (! empty($header_extra)) echo $header_extra ?>
</head>
<body class="<?php if (! empty($bodyclass)) echo $bodyclass ?>">
<div class="m-wrap">
<div class="wrapper">
<?php // Header ?>
<?php $this->load->view('layouts/header', $header) ?>

<?php // Content ?>
<?php $this->load->view($view, $content) ?>

<?php // Footer ?>
<?php $this->load->view('layouts/footer', $footer) ?>
</div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/js/lib/jquery-1.11.2.min.js"><\/script>')</script>
<script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
<script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.jquery.min.js"></script>

<?php // Data to provide to JavaScript ?>
<script type="text/javascript">
    var algoliaIndexMembers = <?php echo "'".$this->config->item('algolia')['index_members']."'" ?>;
    var algoliaIndexProjects = <?php echo "'".$this->config->item('algolia')['index_projects']."'" ?>;

    lang = new Array();
    <?php foreach ($this->lang->language['js-searchbox'] as $key => $val) { ?>
        lang['<?php echo $key ?>'] = "<?php echo addslashes($val);?>";
    <?php } ?>
</script>

<?php // Extra (per page) scripts ?>
<?php if (! empty($scripts)) {
    $scripts = is_array($scripts) ? $scripts : array($scripts);
    foreach ($scripts as $script) { ?>
        <script src="/js/<?php echo $script ?>"></script>
    <?php }
} ?>
<script src="/js/main.js<?php echo asset_version('main.js') ?>"></script>

<?php // Optimizely ?>
<script src="//cdn.optimizely.com/js/4480070248.js"></script>

<?php // Extra stuff that we want to inject into body tags ?>
<?php if (! empty($footer_extra)) echo $footer_extra ?>
</body>
</html>