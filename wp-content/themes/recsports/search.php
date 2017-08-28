<div class="container main" role="document">
	<div class="wrapper">
<?
include(locate_template('partial-templates/pagetitle.php', false, false));
?>

<?php if (!have_posts()) : ?>
	<div class="paragraph--copy content content--toppad">
	  <p>Your search didn&rsquo;t return any results.</p>
	</div>

	<div class="searchformwrap searchformwrap--toprule content content--full">
		<?php get_search_form(); ?>
	</div>
<?php else: ?>

	<div class="searchformwrap searchformwrap--bottomrule content content--full">
		<?php get_search_form(); ?>
	</div>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('partial-templates/content', 'search'); ?>
<?php endwhile; ?>

		<div class="content content--full postnav"><!--
		--><?php the_posts_navigation(array(
				"prev_text" => "Next Page",
				"next_text" => "Previous Page",
				"screen_reader_text" => ""
			)); ?><!--
		--></div>
<?php endif; ?>
	</div>
</div>
