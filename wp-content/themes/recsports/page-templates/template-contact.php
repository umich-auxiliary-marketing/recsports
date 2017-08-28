<?php
/*
Template Name: Contact Us Page
*/

?>

<?
$meta = get_post_meta(get_the_ID(), '', false);
$counter = 0;
$partial = "";
$collapse = false;
?>



<div class="container main" role="document">
	<div class="wrapper">
		<? include(locate_template('partial-templates/pagetitle.php', false, false)); ?>
		<? include(locate_template('partial-templates/sidebar.php', false, false)); ?>

		<? if(isset($meta["page_content"]) && $meta["page_content"][0]): foreach(unserialize($meta["page_content"][0]) as $partial): ?>
			<? $pcb = "page_content_" . $counter . "_"; ?>

			<? end_collapsed_section($collapse, $partial); ?>

			<div class="content <?= $partial ?>">
				<? include(locate_template('page-module-templates/' . $partial . '.php', false, false)); ?>
			</div>

			<? start_collapsed_section($collapse, $partial, $meta, $pcb . "collapse_section"); ?>


			<? $counter++; ?>
		<? endforeach; endif; ?>

		<?
		// WP_Query arguments
		$args = array (
			'post_type' => 'page',
			'post_status' => 'publish',
			'meta_query' => array(
        array(
          'key' => '_wp_page_template',
          'value' => 'page-templates/template-section.php', // template name as stored in the dB
        )
      ),
			'order'        => 'ASC',
			'orderby'      => 'title'
		);

		// The Query
		$sectionquery = new WP_Query( $args );
		?>

		<? while($sectionquery->have_posts()): ?>
			<?
			$sectionquery->the_post();
			$use_h2 = true;
			$meta = get_post_meta($post->ID, "", false);
			?>

		<? include(locate_template('partial-templates/contact.php', false, false)); ?>

	<? endwhile; ?>
<? wp_reset_postdata(); ?>


		<? end_collapsed_section($collapse, $partial, true); ?>
	</div>
</div>


<?
	include(locate_template('partial-templates/social.php', false, false)); ?>
