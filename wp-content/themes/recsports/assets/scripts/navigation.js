(function($) {
	var navigation = {
		load: function() {
			navigation.rebind();
		},

		rebind: function() {
			$(".mobilenav__menulink").click(navigation.expand);
			$(".mobilenav__closemenulink").click(navigation.collapse);

			$('.navigation--main').hover(function() {
				$('.darkener').addClass('visible');
			}, function() {
				$('.darkener').removeClass('visible');
			});

			$('.darkener').on({
				mouseover: function() {
					$('.darkener').removeClass('visible');
				},
				mouseenter: function() {
					$('.darkener').removeClass('visible');
				},
				click: function() {
					$('.darkener').removeClass('visible');
				}
			});

			$('.navigation__item > a').click(function(e) {
				if(!$(this).hasClass("navigation__search")) {
					e.preventDefault();
				}
			});
		},
		collapse: function(e) {
			e.preventDefault();

			forceVideoPause = false;

			$(e.currentTarget).parent().removeClass("expanded");
			$(".mobilenav__menulink").parent().removeClass("collapsed");
			$(".mobilemenu").removeClass("expanded");
			$("body, html").removeClass("js--modal-open");
			$(".global").css("top", "");

			$(".mobilenav__menulink").focus();
			$(window).scrollTop(parseInt($(e.currentTarget).attr("data-originalscroll")));
			$(window).trigger("scroll");
		},
		expand: function(e) {
			e.preventDefault();

			$(".mobilenav__closemenulink").attr("data-originalscroll", ($(window).scrollTop()));
			$(".mobilenav__closemenulink").parent().addClass("expanded");
			$(e.currentTarget).parent().addClass("collapsed");
			$(".mobilemenu").addClass("expanded").scrollTop(0);
			$(".global").css("top", -1 * parseInt($(".mobilenav__closemenulink").attr("data-originalscroll")));
			$("body, html").addClass("js--modal-open");

			$(".mobilenav__closemenulink").focus();
			forceVideoPause = true;
			$(".hero").trigger("pausevideo");
		},
	};

  $(document).ready(navigation.load);

})(jQuery);
