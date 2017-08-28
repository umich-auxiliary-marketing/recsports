(function($) {
	var notice = {
		load: function() {
			notice.rebind();
		},

		rebind: function() {
			$(".notice--action-expand").click(function(e) {
				notice.toggle(e);
			});
		},

		toggle: function(e) {
			e.preventDefault();

			var targ = $(e.currentTarget);

			if(targ.hasClass("expanded")) {
				notice.collapse(targ);
			} else {
				notice.expand(targ);
			}
		},

		expand: function(target) {

			target.addClass("expanded");

			target.find(".notice__description").stop().slideDown({
				easing: $.bez([0.140, 0.995, 0.610, 1]),
				duration: 300
			});
		},

		collapse: function(target) {
			target.removeClass("expanded");

			target.find(".notice__description").stop().slideUp({
				easing: $.bez([0.140, 0.995, 0.610, 1]),
				duration: 450
			});
		}


	};

  $(document).ready(notice.load);

})(jQuery);
