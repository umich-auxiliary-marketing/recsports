<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;

ini_set("zlib.output_compression", "Off");
//remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );

?>

<!doctype html>
<html <?php language_attributes(); ?>>
  <?php get_template_part('partial-templates/head'); ?>
  <body <?php body_class(); ?>>
		<?
		if ( function_exists( 'gtm4wp_the_gtm_tag' ) ) { gtm4wp_the_gtm_tag(); }
		?>
		<a class="anchor" id="_top"></a>
		<script>
			// This needs to be first thing to prevent hash jumps.
			var section = {
				pathname: window.location.pathname,
				search: window.location.search,
				firstHash: window.location.hash
			};
			var wp = "<?= home_url(); ?>";

			// No need to clear the window.history.hash; the following will do it. Plus,
			// Firefox got confused with window.history.hash setting, defaulting to
			// a pushstate. So sad.
			history.replaceState("", document.title, window.location.pathname + window.location.search);
		</script>

		<div class="global global--<?= get_post_meta(get_the_ID(), "section_color", true) ?: "recsports" ?>">
	    <?php
	      do_action('get_header');
				get_template_part('partial-templates/utilities');
				get_template_part('partial-templates/header');
	    ?>
			<? //var_dump(get_post_meta((int)get_option( 'page_on_front' ), "notices", true)); echo "!"; ?>

			<? inject_page_notices(get_post_meta((int)get_option( 'page_on_front' ), "notices", true), true); ?>

	    <?php include Wrapper\template_path(); ?>


	    <?php
	      do_action('get_footer');
	      get_template_part('partial-templates/footer');
				get_template_part('partial-templates/copyright');
	      wp_footer();
	    ?>
			<script>
			function frmThemeOverride_frmAfterSubmit(formReturned, pageOrder, errObj, object) {
				var submittedForm = $(object).find("legend.frm_hidden").html();

				if(typeof(formReturned) == 'undefined') {
					dataLayer.push({
						'event': 'FormSubmitted',
						'formname': submittedForm
					});
				}
			}
			</script>
		</div>
  </body>
</html>
