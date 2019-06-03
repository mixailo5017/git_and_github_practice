var $ = require("jquery");

module.exports = function() {
  // Don't try running magnificPopup if there are no videos on the page
  var videosOnPage = $(".play").length > 0;

  if (videosOnPage) {
    // Instantiate Magnific Popup, so video appears in modal upon click
    $(".play").magnificPopup({
      type: "iframe",
      iframe: {
        patterns: {
          youtube: {
            index: "youtube.com/", // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).

            id: "v=", // String that splits URL in a two parts, second part should be %id%
            // Or null - full URL will be returned
            // Or a function that should return %id%, for example:
            // id: function(url) { return 'parsed id'; }

            src:
              "//www.youtube.com/embed/%id%?autoplay=1&rel=0&showinfo=0&enablejsapi=1" // URL that will be set as a source for iframe.
          }
        },
        srcAction: "iframe_src"
      },
      callbacks: {
        open: registerYouTubeAPIEvent
      }
    });

    // Analytics
    var player;

    function onYouTubeIframeAPIReady() {
      // iframe doesn't exist until Magnific Popup has been called,
      // so moved this code to Magnific Popup callback (registerYouTubeAPIEvent)
    }

    function registerYouTubeAPIEvent() {
      player = new YT.Player(document.querySelector(".mfp-iframe"), {
        events: {
          onStateChange: onPlayerStateChange
        }
      });
    }

    function onPlayerStateChange(event) {
      var eventName;
      switch (event.data) {
        case YT.PlayerState.PLAYING:
          // Only interested in tracking when the video first starts playing 
          // (defined here as within the first second) — ignore if user hits
          // Pause and then Play mid-way through
          if (player.getCurrentTime() < 1) {
            eventName = "Video Started";
          } else {
            return;
          }
          break;
        case YT.PlayerState.ENDED:
          eventName = "Video Completed";
          break;
        default:
          return;
      }

      segmentAnalytics({
        event: {
          name: eventName,
          properties: {
            "Video Title": player.getVideoData().title // Take video title from YouTube API
          }
        }
      });
    }
  }
};
