(function($) {
	var sidebar = {
		load: function() {
			sidebar.rebind();
		},

		rebind: function() {
			$("body").click(function(e) {
				if(!$(e.target).is(".sidebar__mobiletarget") && $(".sidebar__mobiletarget").hasClass("expanded")) {
					sidebar.collapse(e);
				}
			});

			$(".sidebar__mobiletarget").click(function(e) {
				e.preventDefault();

				if($(this).hasClass("expanded")) {
					sidebar.collapse(e);
				} else {
					sidebar.expand(e);
				}
			});

			$(".sidebar").on("sticky_kit:bottom", function(e) {
				$(".sidebar").addClass("sticky--bottom");
			});

			$(".sidebar").on("sticky_kit:unbottom", function(e) {
				$(".sidebar").removeClass("sticky--bottom");
			});

			$(".sidebar__contents").onePageNav({
				navItems: 'a',
				currentClass: "active",
				padTop: 70,
				scrollPadTop: 20,
				easing: $.bez([0.140, 0.995, 0.610, 1]),
				scrollSpeed: 450,
				headroomDiv: ".header"
			});

			$(".sidebar__contentlink").click(function(e) {
				var anchor = $(e.currentTarget).attr("data-scroll");
				var $testingHeader = $(anchor).next().next();

				if($testingHeader.is("h2") && $testingHeader.has('a')) {
					var heading = $testingHeader.children("a");
					var accordion = $testingHeader.parent().next(".collapsible");

					$(".sectionheader").trigger("expandsection", [heading, accordion]);
				}
			});

			$(".sidebar__mobilefragment").on('click', 'a', function(e) {
				var currentPos = $(this).parent().prevAll().length;
				$(".sidebar__contents").find('li').eq(currentPos).children('a').trigger('click');
				e.preventDefault();
			});
		},

		collapse: function(e) {
			$(".sidebar__mobiletarget").removeClass("expanded");
			$(".sidebar__mobilefragment").removeClass("expanded");
			$(".sidebar").removeClass("expanded");
			$(".darkener").removeClass("visible");
		},

		expand: function(e) {
			$(".sidebar__mobiletarget").addClass("expanded");
			$(".sidebar__mobilefragment").addClass("expanded");
			$(".sidebar").addClass("expanded");
			$(".darkener").addClass("visible");
		},
	};

  $(document).ready(sidebar.load);

})(jQuery);
