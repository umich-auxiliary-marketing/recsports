(function($) {
	var sectionheader = {
		load: function() {
			sectionheader.rebind();
		},

		rebind: function() {
			$(".sectionheader__collapser").click(function(e) {
				sectionheader.toggle(e);
			});
		},

		toggle: function(e) {
			e.preventDefault();

			var heading = $(e.currentTarget);
			var accordion = heading.parents(".sectionheader").next(".collapsible");

			if(heading.hasClass("expanded")) {
				sectionheader.collapse(heading, accordion);
			} else {
				sectionheader.expand(heading, accordion);

				var windowHeight = $(window).height();
				var windowScrollTop = $(window).scrollTop();
				var linkHeight = heading.height();
				var linkOffsetTop = heading.offset().top;
				var linkScrollTop = linkOffsetTop - windowScrollTop;
				var headerHeight = $(".header").height();
				var buffer = 220;

				if((linkOffsetTop + linkHeight + buffer) > (windowScrollTop + windowHeight)) {
					$('html, body').animate({
						scrollTop: windowScrollTop + buffer
					}, 300, $.bez([0.140, 0.995, 0.610, 1]));
				}
			}
		},

		listenForHref: $(".sectionheader").on("expandsection", function(e, heading, accordion) {
			sectionheader.expand(heading, accordion);
		}),

		expand: function(heading, accordion) {

			heading.addClass("expanded");

			accordion.stop().slideDown({
				easing: $.bez([0.140, 0.995, 0.610, 1]),
				duration: 300
			});
		},

		collapse: function(heading, accordion) {
			heading.removeClass("expanded");

			accordion.stop().slideUp({
				easing: $.bez([0.140, 0.995, 0.610, 1]),
				duration: 450
			});
		}
	};

  $(document).ready(sectionheader.load);

})(jQuery);
