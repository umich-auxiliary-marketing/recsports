(function($) {
	var stickers = {
		load: function() {
			stickers.rebind();
			// No need to do this, Calendar handles it
			//stickers.rebindCalendar();

			$('body').on('calrestick',stickers.rebindCalendar);
		},

		rebind: function() {
			$("header").fixTo('.global', {
				className: 'sticky',
				useNativeSticky: false
			});
			$(".sidebar").fixTo('.main', {
				className: 'sticky',
				useNativeSticky: false,
				headroomDiv: ".header"
			});
		},

		rebindCalendar: function() {
			$(".calendar__dayheader").each(function() {
				$(this).fixTo('.calendar__view', {
					className: 'sticky',
					useNativeSticky: false,
					headroomDiv: ".header"
				});
			});
		},
	};

  $(document).ready(stickers.load);

})(jQuery);
