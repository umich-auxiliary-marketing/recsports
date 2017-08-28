<div class="container main" role="document">
	<div class="wrapper">
		<h1 class="content content--full pagetitle">Facilities</h1>

		<?
			$sidebartitle = "Facilities";
			$sidebarlinks = array("indoor" => "Indoor Facilities", "outdoor" => "Outdoor Facilities");

			include(locate_template('partial-templates/sidebar.php', false, false));
		?>

		<div class="content sectionheader">
			<a class="anchor" id="indoor"></a>
			<hr>
			<h2 class="sectionheader__header">Indoor Facilities</h2>
		</div>
		<? generate_facilities("indoor"); ?>

		<div class="content sectionheader">
			<a class="anchor" id="outdoor"></a>
			<hr>
			<h2 class="sectionheader__header">Outdoor Facilities</h2>
		</div>
		<? generate_facilities("outdoor"); ?>
	</div>
</div>

<?
function generate_facilities($inorout) {
	// WP_Query arguments
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
			<div class="content policies">
				<h3 class="policies__subheader">
				<a class="policies__collapser themed--chevron" href="<?= get_permalink($post->ID) ?>">
					<span><?= format_text($post->post_title) ?></span><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 5 13"><path fill="none" vector-effect="non-scaling-stroke" stroke-width="2" stroke-miterlimit="10" d="M1 12l3-5.499L1 1"/></svg></a>
				</h3>

				<? inject_page_notices(get_post_meta($post->ID, "notices", true), false, true); ?>

				<? $facilityintro = get_post_meta($post->ID, "about_this_facility", true); ?>
				<? if($facilityintro !== ""): ?>
					<p class="paragraph--smallpadtop paragraph--introcolor"><?= get_post_meta($post->ID, "about_this_facility", true) ?></p>
				<? endif; ?>
			</div>
		<? endwhile; ?>
	<? wp_reset_postdata(); ?>
<?
}
?>
