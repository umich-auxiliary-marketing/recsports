(function($) {
	var list = {
		load: function() {
			list.rebind();
		},

		rebind: function() {
			$(".list__link--description").click(list.toggle);
			$(".list__link--nothing").click(list.absorb);
			$(".list__expanderclose").click(list.close);
			$(".list__viewlink").click(list.reformat);
		},

		toggle: function(e) {
			e.preventDefault();

			var $link = $(e.currentTarget);
			var $item = $link.parent();
			var $expander = $item.children(".list__expander");

			if($item.hasClass('expanded')) {
				$item.removeClass('expanded');
				$expander.stop().slideUp({
					easing: $.bez([0.140, 0.995, 0.610, 1]),
					duration: 300
				});
			} else {
				var $previousItem = $('.list__item.expanded');
				var $previousItemExpander = $previousItem.children('.list__expander');
				var anyPrevious = false;

				if($previousItemExpander.length !== 0) {
					anyPrevious = list.adjustSiblingPosition($previousItemExpander.outerHeight(), $previousItem.offset().top, $link.outerHeight(), $item.offset().top);
				}

				if(!anyPrevious) {
					list.adjustBottomPosition($link);
				}

				$previousItem.removeClass('expanded');
				$previousItemExpander.stop().slideUp(
					300,
					$.bez([0.140, 0.995, 0.610, 1])
				);
				$item.addClass('expanded');
				$expander.stop().slideDown(
					300,
					$.bez([0.140, 0.995, 0.610, 1])
				);
			}
		},

		adjustSiblingPosition: function(previousHeight, previousOffset, previousLinkHeight, currentOffset) {
			if((previousOffset >= currentOffset)) {
				return false;
			}

			$('html, body').animate({
				scrollTop: currentOffset - previousHeight - $(".header").outerHeight() - 50
			}, 300, $.bez([0.140, 0.995, 0.610, 1]));

			return true;
		},

		adjustBottomPosition: function(link) {
			var windowHeight = $(window).height();
			var windowScrollTop = $(window).scrollTop();
			var linkHeight = link.outerHeight();
			var linkOffsetTop = link.offset().top;
			var linkScrollTop = linkOffsetTop - windowScrollTop;
			var headerHeight = $(".header").outerHeight();
			var buffer = 220;

			if((linkOffsetTop + linkHeight + buffer) > (windowScrollTop + windowHeight)) {
				$('html, body').animate({
					scrollTop: windowScrollTop + buffer
				}, 300, $.bez([0.140, 0.995, 0.610, 1]));
			}
		},

		close: function(e) {
			e.preventDefault();

			var $expander = $(e.currentTarget).parent();
			var $item = $expander.parent();

			$expander.stop().slideUp({
				easing: $.bez([0.140, 0.995, 0.610, 1]),
				duration: 450
			});
			$item.removeClass('expanded');
		},

		reformat: function(e) {
			e.preventDefault();

			$link = $(e.currentTarget);
			$group = $link.parent().next(".list__group");

			if($group.filter(":animated").length) {
				return;
			}

			$group.stop().slideUp(
				300,
				$.bez([0.140, 0.995, 0.610, 1]),
				function() {
					if($group.hasClass("list__group--grid")) {
						$group.removeClass("list__group--grid").addClass("list__group--list");
						$group.find('.list__expander').removeAttr("style");
						$group.find('.expanded').removeClass("expanded");
						$link.html("Collapse all into a grid.");
					} else {
						$group.removeClass("list__group--list").addClass("list__group--grid");
						$link.html("Expand all into a list.");
					}

					$group.stop().slideDown({
						easing: $.bez([0.140, 0.995, 0.610, 1]),
						duration: 450
					});

					list.rebind();
				}
			);
		},

		absorb: function(e) {
			e.preventDefault();
		}
	};

  $(document).ready(list.load);

})(jQuery);
