/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */


(function($) {

  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    'common': {
      init: function() {
				FastClick.attach(document.body);

				$("header").headroom({
					offset: 300,
					tolerance: {
						down: 5,
						up: 20
					},
					onPin: function() {
						$("body").addClass("js--header-pinned");
					},
					onUnpin: function() {
						$("body").removeClass("js--header-pinned");
					}
				});


				$(window).scroll(function() {

					if($('.hero video').length && $(window).scrollTop() > $('.main').position().top && !$('.hero video').get(0).paused) {
						$('.hero').trigger("pausevideo");
					} else if($('.hero video').length && $(window).scrollTop() < $('.main').position().top && $('.hero video').get(0).paused) {
						$('.hero').trigger("playvideo");
					}
				});

				$(".rates__select").change(function(e) {
					var selected = $(e.target).find(':selected').val();

					if(selected === "_listall") {
						$('.rates__grouphead').addClass("expanded");
						$(".rates__group").addClass("expanded");
					} else {
						$('.rates__grouphead').removeClass("expanded");
						$(".rates__group").removeClass("expanded");
						$(".rates__group--" + selected).addClass("expanded");
					}
				});

				$(".rates__select").trigger('change');

				$(':not(.frm_forms) a[href^="#"]:not(.sectionheader__collapser)').on('click',function (e) {
					if($(e.currentTarget).hasClass("js--ignorejump") || !$(this.hash).length) { return false; }

					e.preventDefault();

					$('html, body').stop().animate({
					  'scrollTop': $(this.hash).offset().top
					}, 450, $.bez([0.140, 0.995, 0.610, 1]));
				});
      },

      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired
      }
		},
    // Home page
    'home': {
      init: function() {

      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
      }
    },
    // About us page, note the change from about-us to about_us.
    'about_us': {
      init: function() {
        // JavaScript to be fired on the about us page
      }
    }
  };

  // The routing fires all common scripts, followed by the page specific scripts.
  // Add additional events for more control over timing e.g. a finalize event
  var UTIL = {
    fire: function(func, funcname, args) {
      var fire;
      var namespace = Sage;
      funcname = (funcname === undefined) ? 'init' : funcname;
      fire = func !== '';
      fire = fire && namespace[func];
      fire = fire && typeof namespace[func][funcname] === 'function';

      if (fire) {
        namespace[func][funcname](args);
      }
    },
    loadEvents: function() {
      // Fire common init JS
      UTIL.fire('common');

      // Fire page-specific init JS, and then finalize JS
      $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function(i, classnm) {
        UTIL.fire(classnm);
        UTIL.fire(classnm, 'finalize');
      });

      // Fire common finalize JS
      UTIL.fire('common', 'finalize');
    }
  };

  // Load Events
  $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
