<script src="/js/map_search.js<?php echo asset_version('map_search.js') ?>"></script>

<script type="text/javascript">
    /**
     * Using the _.extend option to setup our map as we desire.
     * @type {String}
     */
    $(document).ready(function() {
        var map = new mapBoxMap();
        map.init(_.extend({
            hasSearch:    false,
            mapSelector:  'p_e_map',
            forum:        true,
            usePins:      false
        }, <?= $map ?>));
    });
</script>
