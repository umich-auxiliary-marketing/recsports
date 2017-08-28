(function($) {
	var facility = {
		load: function() {
			facility.rebind();
		},

		rebind: function() {
			$(".facilitybutton__link").click(facility.showmap);
		},

		showmap: function(e){
			$(e.currentTarget).parent().find(".sidebar-facility__map, .sidebar-facility__photo").css("display","block");
		}
	};

  $(document).ready(facility.load);

})(jQuery);
