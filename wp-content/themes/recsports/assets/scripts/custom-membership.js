(function($) {
	var membership = {
		load: function() {
			membership.rebind();

			var person = localStorage.getItem("person");
			if(person !== undefined && person !== null) {
				person = JSON.parse(person);
				membership.conclude(person.kind);
			}
		},

		rebind: function() {
			$(".membership__buttons input").change(function(e) {
				var choice = $(e.currentTarget);

				if(choice.hasClass("membership__option--additionals")) {
					membership.delve(choice.nextAll(".membership__secondary"));
				} else {
					membership.conclude((($(e.currentTarget).attr("id")).split("--"))[1]);
				}
			});

			$(".membership__reidentify").click(membership.restart);
			$(".membership__switch").click(membership.switch);
		},

		delve: function(secondary) {
			secondary.stop().slideDown({
				easing: $.bez([0.140, 0.995, 0.610, 1]),
				duration: 450
			});
			$(".membership").addClass("delved");
		},

		switch: function(e) {
			e.preventDefault();

			var identity = $(e.currentTarget).attr("data-membertype");

			$(".membership__conclusion").stop().slideUp({
				easing: $.bez([0.140, 0.995, 0.610, 1]),
				duration: 300,
				complete: function() { membership.conclude(identity); }
			});
		},

		conclude: function(identity) {
			$(".membership__identity").html(
				"You&rsquo;re " +
				membership.identities[identity].preamble + " " +
				"<span class='membership__name'>" +
				membership.identities[identity].name +
				"</span>."
			);

			$(".membership--expanded").removeClass("membership--expanded").addClass("ada--hidden");
			$(".membership__conclusion .membertype--" + identity).removeClass("ada--hidden").addClass("membership--expanded");

			$(".membership__hypothesis").stop().slideUp({
				easing: $.bez([0.140, 0.995, 0.610, 1]),
				duration: 450
			});
			$(".membership__conclusion").stop().slideDown({
				easing: $.bez([0.140, 0.995, 0.610, 1]),
				duration: 450
			});

			localStorage.setItem("person", JSON.stringify({
				kind: identity,
				preamble: membership.identities[identity].preamble,
				name: membership.identities[identity].name,
				categories: membership.identities[identity].categories
			}));
		},

		restart: function(e) {
			e.preventDefault();

			$(".membership__secondary").hide();
			$(".membership").removeClass("delved");
			$(".membership input:checked").prop("checked", false);

			$(".membership__hypothesis").stop().slideDown({
				easing: $.bez([0.140, 0.995, 0.610, 1]),
				duration: 300
			});
			
			$(".membership__conclusion").stop().slideUp({
				easing: $.bez([0.140, 0.995, 0.610, 1]),
				duration: 300
			});

			localStorage.removeItem("person");
		},

		convert: function(identity) {
			// Each identity has three parts: its specific ID (the key), a friendly
			// name, and an array of possible regroupings. All of this is stored here
			// and saved into localStorage when selected.

		},

		// The following objects are dynamic content. Since there's so much of it,
		// we can't reliably plop it all into the page itself.

		// Identities are stored in localStorage (so keep them lean).
		identities: {
			enrolledstudent: {
				preamble: "an",
				name: "enrolled student",
				categories: ["student"]
			},
			unenrolledstudent: {
				preamble: "an",
				name: "unenrolled student",
				categories: ["student"]
			},
			staff: {
				preamble: "a part of",
				name: "faculty, staff and University affiliates",
				categories: ["faculty"]
			},
			retiree: {
				preamble: "a",
				name: "retiree",
				categories: []
			},
			alum: {
				preamble: "an",
				name: "alum of the University",
				categories: ["affiliated"]
			},
			friend: {
				preamble: "a",
				name: "friend of the University",
				categories: []
			},
			studentspouse: {
				preamble: "the",
				name: "spouse of a student",
				categories: ["student"],
			},
			staffspouse: {
				preamble: "the",
				name: "spouse of a faculty or staff member",
				categories: ["faculty","staff"]
			},
			alumspouse: {
				preamble: "the",
				name: "spouse of an alumnus or alumnae",
				categories: ["alum"],
			},
			retireespouse: {
				preamble: "the",
				name: "spouse of a retiree",
				categories: ["retiree"],
			},
			child: {
				preamble: "a",
				name: "child of a member",
				categories: ["children"],
			},
			youngadultmember: {
				preamble: "a",
				name: "young adult whose parent is a member",
				categories: ["children"],
			},
			youngadult: {
				preamble: "a",
				name: "young adult",
				categories: ["children"]
			},
			guest: {
				preamble: "a",
				name: "guest",
				categories: []
			}
		}
	};

  $(document).ready(membership.load);

})(jQuery);
