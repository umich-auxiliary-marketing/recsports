(function($) {
	var challenge = {
		names: [
			"challengeaudience",
			"challengeprogramtype",
			"challengeduration",
			"challengeseason",
			"challengelocation"
		],

		prohibitions: {
			challengeprogramtype: [
				{
					tester: "challengeprogramtype--tower",
					autoselect: [
						"challengelocation--basecamp",
					],
					selectifnecessary: [
						["challengeduration--mini", "challengeduration--half"]
					],
					autodisable: [
						"challengeduration--mini",
						"challengelocation--offsite"
					]
				},
				{
					tester: "challengeprogramtype--ropes",
					autoselect: [
						"challengelocation--basecamp"
					],
					selectifnecessary: [
						["challengeduration--mini", "challengeduration--half"]
					],
					autodisable: [
						"challengeduration--mini",
						"challengelocation--offsite"
					]
				},
			]
		},

		base: 25.0,

		load: function() {
			challenge.rebind();
			$(".challenge__begin").click(function(e) {
				challenge.inquire(e);
			});
		},

		rebind: function() {
			$(".challenge__questionnaire input").change(challenge.selected);
		},

		selected: function(e) {
			var price = challenge.base;
			var currentOption = $(e.currentTarget);

			challenge.prevent(currentOption.attr("name"), currentOption.attr("id"));
			challenge.store(currentOption.attr("name"), currentOption.val());

			for(var i = 0; i < challenge.names.length; ++i) {
				var $selection = $("input[name='" + challenge.names[i] + "']").filter(":checked");

				if($selection.length) {
					price *= parseFloat($selection.attr("data-multiplier"));
				} else {
					challenge.undecided();
					return;
				}
			}

			challenge.decided(price);
			challenge.store("calculatedprice", ("$" + challenge.monetize(price, 2)));
		},

		decided: function(price) {
			$(".challenge__price").html("$" + challenge.monetize(price, 2));

			var $unfinished = $(".challenge__unfinished:visible");

			if($unfinished.length){
				$unfinished.stop().slideUp({
					easing: $.bez([0.140, 0.995, 0.610, 1]),
					duration: 450
				});
				$(".challenge__finished").stop().slideDown({
					easing: $.bez([0.140, 0.995, 0.610, 1]),
					duration: 450
				});
			}
		},

		store: function(key, value) {
			$("input[type=hidden]#field_response_" + key).val(value);
		},

		undecided: function() {
			var $finished = $(".challenge__finished:visible");
			if($finished.length) {
				$finished.stop().slideUp({
					easing: $.bez([0.140, 0.995, 0.610, 1]),
					duration: 450
				});
				$(".challenge__unfinished").stop().slideDown({
					easing: $.bez([0.140, 0.995, 0.610, 1]),
					duration: 450
				});
			}
		},

		prevent: function(name, value) {
			if(challenge.prohibitions[name] !== undefined) {
				$(".challenge__questionnaire input").removeAttr("disabled");

				var option = challenge.prohibitions[name];

				for(var i = 0; i < option.length; ++i) {
					if(option[i].tester === value) {

						for(var j = 0; j < option[i].autoselect.length; ++j) {
							$("#" + option[i].autoselect[j]).trigger("click");
						}

						for(var jk = 0; jk < option[i].selectifnecessary.length; ++jk) {
							if($("#" + option[i].selectifnecessary[jk][0]).is(":checked")) {
								$("#" + option[i].selectifnecessary[jk][1]).trigger("click");
							}
						}

						for(var k = 0; k < option[i].autodisable.length; ++k) {
							$("#" + option[i].autodisable[k]).attr("disabled","disabled");
						}

						return;
					}
				}
			}
		},

		monetize: function(num, decimals) {
			var t = Math.pow(10, decimals);
			return (Math.round((num * t) + (decimals>0?1:0)*(Math.sign(num) * (10 / Math.pow(100, decimals)))) / t).toFixed(decimals);
		},

		inquire: function(e) {
			e.preventDefault();

			var $button = $(".challenge__begin--button");
			var $form = $(".challenge__form");

			$(".challenge__finished:visible").removeClass("ada--hidden").removeAttr("style");

			if(!$form.filter(":visible").length) {
				$form.stop().slideDown({
					easing: $.bez([0.140, 0.995, 0.610, 1]),
					duration: 450
				}).removeClass("collapsed");
				$button.parent().addClass("collapsed");
			}
		}
	};

  $(document).ready(challenge.load);

})(jQuery);


Math.sign = Math.sign || function(x) {
  x = +x; // convert to a number
  if (x === 0 || isNaN(x)) {
    return Number(x);
  }
  return x > 0 ? 1 : -1;
};
