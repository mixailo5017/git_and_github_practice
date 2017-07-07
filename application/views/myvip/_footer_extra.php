<script type="text/javascript">
  /**
   * Using the _.extend option to setup our map as we desire.
   * @type {String}
   */
  $(document).ready(function() {
    window.map = new mapBoxMap();
    window.map.init(_.extend({
      hasSearch:   true,
      mapSelector: 'p_e_map',
      forum:       false
    }, <?= $map ?>));
  });
</script>