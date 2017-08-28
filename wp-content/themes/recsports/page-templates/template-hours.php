<?php
/*
Template Name: Hours List Page
*/

?>

<?
$meta = get_post_meta(get_the_ID(), '', false);
?>



<div class="container main" role="document">
	<div class="wrapper">
		<?
		include(locate_template('partial-templates/pagetitle.php', false, false));

		$sidebarlinks = array("indoor" => "Indoor Facilities", "outdoor" => "Outdoor Facilities");
		include(locate_template('partial-templates/sidebar.php', false, false));

		inject_page_notices(isset($meta["notices"]) && $meta["notices"][0] ? unserialize($meta["notices"][0]) : false);
		?>

		<div class="content sectionheader">
			<a class="anchor" id="indoor"></a>
			<hr>
			<h2 class="sectionheader__header">Indoor Facilities</h2>
		</div>
		<? generate_hours("indoor"); ?>

		<div class="content sectionheader">
			<a class="anchor" id="outdoor"></a>
			<hr>
			<h2 class="sectionheader__header">Outdoor Facilities</h2>
		</div>
		<? generate_hours("outdoor"); ?>

	</div>
</div>

<?
	include(locate_template('partial-templates/social.php', false, false)); ?>

<?
	function generate_hours($inorout) {
		global $post;
		$args = array (
			'post_type'    => array( 'facility' ),
			'post_status'  => array( 'publish' ),
			'order'        => 'ASC',
			'orderby'      => 'post_order',
			'meta_query' => array(
				array(
					'key'     => 'indoor_or_outdoor',
					'value'   => $inorout,
					'compare' => '=',
				),
			),
		);

		// The Query
		$facilityquery = new WP_Query( $args );
	?>

	<? while($facilityquery->have_posts()): ?>
		<? $facilityquery->the_post(); ?>

		<? $lmeta = get_post_meta($post->ID, "", false); ?>

		<? if($lmeta["sets_of_hours_to_show"][0] !== "hide"): ?>
		<div class="content policies">
		<h3 class="policies__subheader">
		<a class="policies__collapser themed--chevron" href="<?= get_permalink($post->ID) ?>">
			<span><?= format_text($post->post_title) ?></span><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 5 13"><path fill="none" vector-effect="non-scaling-stroke" stroke-width="2" stroke-miterlimit="10" d="M1 12l3-5.499L1 1"/></svg></a>
		</h3>

		<? inject_page_notices((isset($lmeta["notices"]) && $lmeta["notices"][0] ? unserialize($lmeta["notices"][0]) : false), false, true); ?>

		<? if($lmeta["sets_of_hours_to_show"][0] === "text_only"): ?>
		<div class="content paragraph paragraph--smallpadtop">
			<?= format_textarea($lmeta["hours_text_block"][0]); ?>
			<? if(isset($lmeta["disclaimer_text"]) && $lmeta["disclaimer_text"][0]): ?>
				<div class="paragraph--fineprint"><?= format_textarea($lmeta["disclaimer_text"][0]); ?></div>
			<? endif; ?>
		</div>
	<? else: ?>
	<div class="calendar calendar--smallpadtop">
		<?
		include(locate_template('page-module-templates/hours.php', false, false));
		?>
	</div>
	<? endif; ?>

	</div>


	<? endif; ?>

	<? endwhile; ?>
	<? wp_reset_postdata(); ?>
	<?
}
?>
