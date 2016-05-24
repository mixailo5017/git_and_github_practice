<script type="text/javascript">
    window.analytics=window.analytics||[],window.analytics.methods=["identify","group","track","page","pageview","alias","ready","on","once","off","trackLink","trackForm","trackClick","trackSubmit"],window.analytics.factory=function(t){return function(){var a=Array.prototype.slice.call(arguments);return a.unshift(t),window.analytics.push(a),window.analytics}};for(var i=0;i<window.analytics.methods.length;i++){var key=window.analytics.methods[i];window.analytics[key]=window.analytics.factory(key)}window.analytics.load=function(t){if(!document.getElementById("analytics-js")){var a=document.createElement("script");a.type="text/javascript",a.id="analytics-js",a.async=!0,a.src=("https:"===document.location.protocol?"https://":"http://")+"cdn.segment.com/analytics.js/v1/"+t+"/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(a,n)}},window.analytics.SNIPPET_VERSION="2.0.9",
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
</script>