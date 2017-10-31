<script type="text/javascript">
    !function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on"];analytics.factory=function(t){return function(){var e=Array.prototype.slice.call(arguments);e.unshift(t);analytics.push(e);return analytics}};for(var t=0;t<analytics.methods.length;t++){var e=analytics.methods[t];analytics[e]=analytics.factory(e)}analytics.load=function(t){var e=document.createElement("script");e.type="text/javascript";e.async=!0;e.src=("https:"===document.location.protocol?"https://":"http://")+"cdn.segment.com/analytics.js/v1/"+t+"/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(e,n)};analytics.SNIPPET_VERSION="4.0.0";
  
        window.analytics.load("<?php echo sa_tracking_id() ?>");

        var GVIP = GVIP || {};
        GVIP.App = GVIP.App || {};
        GVIP.App.Analytics = GVIP.App.Analytics || {};

        <?php $uid = (logged_in()) ? (int) sess_var('uid') : 0 ?>

        GVIP.App.Analytics.user_id = <?php echo $uid ?>;
        GVIP.App.Analytics.context = {
            "integrations": {
                "Intercom": {
                    "user_hash": "<?php echo hash_hmac("sha256", $uid, intercom_secure_key()) ?>"
                }
            }
        };

        <?php if (logged_in()) {
            // By default always pass User ID
            $user_properties = array('id' => $uid);
            // Add any user properties that have been passed to the view
            if (! empty($page_analytics['user_properties'])) {
                $user_properties = array_merge($user_properties, $page_analytics['user_properties']);
            }
            ?>
            window.analytics.identify(GVIP.App.Analytics.user_id, <?php echo json_encode($user_properties) ?>, GVIP.App.Analytics.context);
        <?php } ?>

        <?php
            // If the page category hasn't been provided default to 'Other'
            $category = empty($page_analytics['category']) ? 'Other' : $page_analytics['category'];
            // Put language in the properties by default
            $page_properties = array('Language' => sess_var('lang'));
            // Add any other properties that have been to the view

            if (! empty($page_analytics['properties'])) {
                $page_properties = array_merge($page_properties, $page_analytics['properties']);
            }
            // Render parameters for analytics.page() call
            $page_parameters = '"' . $category . '", document.title, ' . json_encode($page_properties, JSON_FORCE_OBJECT);
        ?>
        window.analytics.page(<?php echo $page_parameters ?>, GVIP.App.Analytics.context);

        <?php if (! empty($page_analytics['event'])) { ?>
            window.analytics.track(<?php echo '"' . $page_analytics['event']['name'] .'", ' . json_encode($page_analytics['event']['properties'], JSON_FORCE_OBJECT) ?>, GVIP.App.Analytics.context);
        <?php } ?>
    }}();
</script>