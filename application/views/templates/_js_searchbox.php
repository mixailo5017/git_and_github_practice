<script type="text/javascript">
    var algoliaIndexMembers = <?php echo "'".$this->config->item('algolia')['index_members']."'" ?>;
    var algoliaIndexProjects = <?php echo "'".$this->config->item('algolia')['index_projects']."'" ?>;

    <?php foreach ($this->lang->language['js-searchbox'] as $key => $val) { ?>
        lang['<?php echo $key ?>'] = "<?php echo addslashes($val);?>";
    <?php } ?>
</script>