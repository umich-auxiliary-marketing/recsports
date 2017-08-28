<?php
/*
Template Name: Search Page
*/

?>

<div class="container main" role="document">
	<div class="wrapper">
		<? include(locate_template('partial-templates/pagetitle.php', false, false)); ?>
		<div class="searchformwrap searchformwrap--toprule content content--full">
			<?php get_search_form(); ?>
		</div>		
	</div>
</div>

<?	include(locate_template('partial-templates/social.php', false, false)); ?>
