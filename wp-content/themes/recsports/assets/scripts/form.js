(function($) {
	var form = {
		load: function() {
			form.rebind();
		},

		rebind: function() {
			console.log("Form equipped.");
			$(".form__revealer").click(form.reveal);
			$(".frm_submit button, .frm_submit input, .frm_submit .frm_button_submit").click(form.forceScroll);
		},

		reveal: function(e) {
			e.preventDefault();

			var $button = $(e.currentTarget).parent();
			var $form = $button.next(".form__shrinkwrap");

			$form.stop().slideDown({
				easing: $.bez([0.140, 0.995, 0.610, 1]),
				duration: 450
			}).removeClass("collapsed");
			$button.stop().hide();
		},

		forceScroll: function(e) {
			var offset = $(e.currentTarget).parents(".form__shrinkwrap").offset().top;

			$('html, body').animate({
				scrollTop: offset - $(".header").height() - 50
			}, 300, $.bez([0.140, 0.995, 0.610, 1]));

			setTimeout(form.rebind, 1500);
			setTimeout(form.rebind, 3500);
		}
	};

  $(document).ready(form.load);

})(jQuery);
