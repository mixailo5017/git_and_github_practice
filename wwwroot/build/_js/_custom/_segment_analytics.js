function segmentAnalytics(data) {
    if (data.user_properties) {
        //var userId = parseInt(data.id, 10); // Make sure id that came is of type int
        window.analytics.identify(GVIP.App.Analytics.user_id, data.user_properties, GVIP.App.Analytics.context);
    }

    if (data.event) {
        window.analytics.track(data.event.name, data.event.properties, GVIP.App.Analytics.context);
    }
}

module.exports = segmentAnalytics;