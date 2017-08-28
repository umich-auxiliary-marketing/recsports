jQuery(document).ready(function() {
	jQuery("#wpadminbar").unbind("click");
	jQuery("#wpadminbar").click(function(e) {
		if(jQuery(e.target).is("#wpadminbar")) {
			e.preventDefault();
			e.stopPropagation();
			e.stopImmediatePropagation();
			return true;
		}
	});

});
