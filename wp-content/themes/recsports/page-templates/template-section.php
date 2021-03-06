<?php
/*
Template Name: Section Page
*/

?>

<?
$meta = get_post_meta(get_the_ID(), '', false);
$counter = 0;
$partial = "";
$collapse = false;
?>

<? include(locate_template('partial-templates/hero.php', false, false)); ?>

<div class="container main" role="document">
	<div class="wrapper">
		<? include(locate_template('partial-templates/sidebar.php', false, false)); ?>

		<? inject_page_notices(isset($meta["notices"]) && $meta["notices"][0] ? unserialize($meta["notices"][0]) : false); ?>

		<div class="content introduction">
			<? include(locate_template('partial-templates/introduction.php', false, false)); ?>
		</div>


		<? if(isset($meta["page_content"]) && $meta["page_content"][0]): foreach(unserialize($meta["page_content"][0]) as $partial): ?>
			<? $pcb = "page_content_" . $counter . "_"; ?>

			<? end_collapsed_section($collapse, $partial); ?>

			<div class="content <?= $partial ?>">
				<? include(locate_template('page-module-templates/' . $partial . '.php', false, false)); ?>
			</div>

			<? start_collapsed_section($collapse, $partial, $meta, $pcb . "collapse_section"); ?>


			<? $counter++; ?>
		<? endforeach; endif; ?>
		<? end_collapsed_section($collapse, $partial, true); ?>
		<? include(locate_template('partial-templates/contact.php', false, false)); ?>
	</div>
</div>

<?
	include(locate_template('partial-templates/finalhook.php', false, false));
	include(locate_template('partial-templates/social.php', false, false)); ?>
