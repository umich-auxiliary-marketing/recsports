(function($) {
	var policies = {
		load: function() {
			policies.rebind();
		},

		rebind: function() {
			$(".policies__collapser").click(function(e) {
				policies.toggle(e);
			});
		},

		toggle: function(e) {
			e.preventDefault();

			var heading = $(e.currentTarget).parent();
			var accordion = heading.next(".policies__content");

			if(heading.hasClass("expanded")) {
				policies.collapse(heading, accordion);
			} else {
				policies.expand(heading, accordion);
			}
		},

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

  $(document).ready(policies.load);

})(jQuery);
