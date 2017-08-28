var json = false;
var calToReplace = false;
var calThrottleFlag = false;
var calThrottleTimer = false;

(function($) {
	var calendar = {
		filter: {
			groupxlocation: "all",
			groupxintensity: "all"
		},

		load: function() {
			calendar.rebind();

			$(".locationdetail").on("calendarload", function(e) {
				calendar.rebind(e);
			});
		},

		rebind: function() {
			// Rebind clickable buttons.
			$(".calendar__incrementer").click(calendar.increment);
			$(".calendar__gototoday").click(calendar.gototoday);
			$(".calendar__viewchange").click(calendar.viewchange);
			$(".calendar__event").click(calendar.eventclick);
			$(".calendar__filterer").change(calendar.setfilter);
			$(".fullevent__descriptiontoggle").click(calendar.expandDescription);
			$(window).resize(calendar.forcelist);
			$(window).trigger("resize");

			// Reset the sticky stuff; logic is handled in the stickers.js file.
			$('body').trigger('calrestick');

			// Reset the state of the list-detail view.
			$(".selected").removeClass("selected");

			// Reapply filters if any are available.
			var filter = localStorage.getItem("filters");
			if(filter !== undefined && filter !== null) {
				filter = JSON.parse(filter);

				if(filter.groupxlocation) {
					$(".filterer__selecter[data-filter=groupxlocation]").val(filter.groupxlocation).trigger("change");
					console.log(filter.groupxlocation);
				}
			}
		},

		increment: function(e) {
			var cal = $(e.currentTarget).parents(".calendar__wrap");
			calToReplace = $(e.currentTarget).parents(".calendar");

			calendar.requestCalendar(
				cal.data("view"),
				$(e.currentTarget).data("date"),
				cal.data("enabled-views"),
				cal.data("calendarid"),
				cal.data("secondcalendarid"),
				cal.data("firstlabel"),
				cal.data("secondlabel")
			);
		},

		gototoday: function(e) {
			if(!$(this).parents(".calendar").find(".calendar__daywrap--today").length) {
				calendar.increment(e);
			}
		},

		viewchange: function(e) {
			var cal = $(e.currentTarget).parents(".calendar__wrap");
			calToReplace = $(e.currentTarget).parents(".calendar");

			calendar.requestCalendar(
				$(e.currentTarget).data("view"),
				cal.data("date"),
				cal.data("enabled-views"),
				cal.data("calendarid"),
				cal.data("secondcalendarid"),
				cal.data("firstlabel"),
				cal.data("secondlabel")
			);
		},

		requestCalendar: function(view, date, enabled_views, calendar_id, second_calendar_id, first_label, second_label) {
			if(json) {
				console.log("Request is already in progress.");
				return;
			}

			json = $.ajax({
				url: wp + "/wp-content/plugins/auxmkt-google-calendar/php/httprequest.php",
				data: {
					view: view,
					date: date,
					enabled_views: enabled_views,
					calendar_id: calendar_id,
					second_calendar_id: second_calendar_id,
					firstlabel: first_label,
					secondlabel: second_label
				},
				success: calendar.successful,
				error: calendar.error,
				dataType: 'html'
			});
		},

		successful: function(data) {
			if(data !== "false") {
				calToReplace.html(data);
				calendar.rebind();
				$('body').trigger('sticky_kit:recalc');
			}

			json = false;
			calToReplace = false;
		},

		error: function(e) {
			json = false;
			calToReplace = false;
			console.log(e);
		},

		forcelist: function(e) {
			if($(window).width() >= 640) {
				return;
			}

			$(".calendar__wrap").each(function() {
				if($(this).is(".calendar--halfweek") || $(this).is(".calendar--week")) {
					var cal = $(this);
					calToReplace = $(this).parents(".calendar");

					calendar.requestCalendar(
						"day",
						cal.data("date"),
						cal.data("enabled-views"),
						cal.data("calendarid"),
						cal.data("secondcalendarid"),
						cal.data("firstlabel"),
						cal.data("secondlabel")
					);
				}
			});
		},

		eventclick: function(e) {
			e.preventDefault();

			var $event = $(e.currentTarget);
			var $cal = $event.parents(".calendar__wrap");
			var $view = $cal.find(".calendar__view");
			var $deets = $cal.find(".calendar__details");
			var $deetscontent = $deets.find(".calendar__detailscontent");

			if(($event).hasClass("selected")) {
				$(".selected").removeClass("selected");

				calendar.hideFullEvent($deets);

				return;
			}

			$deets.trigger('detach.ScrollToFixed');
			$(".selected").removeClass("selected");

			$event.addClass("selected");

			$deetscontent.html($event.next(".fullevent").html());
			calendar.showFullEvent($deets);

			$deets.scrollToFixed( { bottom: 0, limit: $view.offset().top + $view.height(), topOffset: $view.offset().top + 75 } );

			$(".fullevent__close").click(calendar.eventclose);
		},

		showFullEvent: function(targ) {
			targ.addClass("expanded");

			targ.stop().slideDown({
				easing: $.bez([0.140, 0.995, 0.610, 1]),
				duration: 300
			});
		},

		hideFullEvent: function(targ) {
			targ.stop().slideUp(450, $.bez([0.140, 0.995, 0.610, 1]), function() {
				targ.trigger('detach.ScrollToFixed');
				targ.removeClass("expanded");
			});
		},

		eventclose: function(e) {
			e.preventDefault();
			$(".selected").removeClass("selected");
			calendar.hideFullEvent($(e.currentTarget).parents(".calendar__details"));

		},

		expandDescription: function(e) {
			e.preventDefault();

			$(e.currentTarget).hide();
			$(e.currentTarget).parent(".fullevent").find(".fullevent__description").stop().slideDown({
				easing: $.bez([0.140, 0.995, 0.610, 1]),
				duration: 300
			});
		},

		setfilter: function(e) {
			e.preventDefault();
			calendar.hideFullEvent($(e.target).parents(".calendar__filterer").siblings(".calendar__details"));

			var targetFilter = $(e.target).attr("data-filter");
			var filterValue = $(e.target).val();

			var filters = localStorage.getItem("filters");
			filters = JSON.parse(filters) || {};
			filters[targetFilter] = filterValue;

			localStorage.setItem("filters", JSON.stringify(filters));

			if(targetFilter === "groupxlocation") {
				calendar.filterLocation(targetFilter, filterValue);
			}
		},

		filterLocation: function(filter, location) {
			$(".calendar__event, .fullevent").removeClass("ada--hidden");

			if(location !== "all" && location !== undefined) {
				$(".calendar__event:not(.calendar__event--location-" + location + "), .fullevent:not(.calendar__event--location-" + location + ")").addClass("ada--hidden");
			}
		}
	};

  $(document).ready(calendar.load);

})(jQuery);
