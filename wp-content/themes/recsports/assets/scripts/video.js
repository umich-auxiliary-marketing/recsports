var forceVideoPause = false;
var videoPlayedFirst = false;

(function($) {
	var video = {

		el: "",

		load: function() {
			video.el = $('.hero video');

			if(!Modernizr.touchevents && (video.el.filter(":visible")).length) {
				video.el.on("playing", function(e) {
					if(!videoPlayedFirst) {
						console.log("Video began its first playthrough.");
						videoPlayedFirst = true;
						video.el.unbind("playing");
					}
				});
			}

			$(".hero").on("pausevideo", function(e) {
				video.pause();
			});

			$(".hero").on("playvideo", function(e) {
				if(!Modernizr.touchevents && (video.el.filter(":visible")).length) {
					video.play();
				}
			});
		},

		pause: function() {
			if(video.el.length && (video.el.filter(":visible")).length && !video.el.get(0).paused) {
				video.el.get(0).pause();
				console.log("Video paused.");
			}
		},

		play: function() {
			if(!forceVideoPause && video.el.length && (video.el.filter(":visible")).length && video.el.get(0).paused) {
				video.el.get(0).play();
				console.log("Video playing.");
			}
		},

		scroll: function(e) {

		},

		toggle: function(e) {
		},
	};

	$(document).ready(video.load);

})(jQuery);
