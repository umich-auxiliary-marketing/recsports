<?php
/*
Template Name: About Us Page
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

		<? if(isset($meta["staff_directory_thing"])): ?>
		<? $people = intval($meta["staff_directory_thing"][0]); ?>
			<? for($i = 0; $i < $people; ++$i): ?>
				<? $scb = "staff_directory_thing_" . $i . "_"; ?>
				<div class="content">
					<div class="paragraph--copy contact--paragraphs">
						<h3 class="staff__name">
							<?= format_text($meta[$scb . "first_name"][0]); ?>
							<?= format_text($meta[$scb . "last_name"][0]);
							?><? if(isset($meta[$scb . "certifications"]) && $meta[$scb . "certifications"][0]): ?>
								<span class="staff__certifications paragraph--smallertext"><?= format_text($meta[$scb . "certifications"][0]); ?></span>
							<? endif; ?>
						</h3>
						<p>
							<span class="staff__officialtitle paragraph--heaviertext"><?= format_text($meta[$scb . "official_title"][0]);
								if(isset($meta[$scb . "position"]) && $meta[$scb . "position"][0]):
								?></span><span>, <?= format_text($meta[$scb . "position"][0]); ?>
							<? endif; ?></span>
						</p>
					</div>
					<ul class="contact__list">
						<li class="contact__entry contact__entry--phone"><a href="tel:<?= format_phone($meta[$scb . "phone"][0]); ?>"><?= format_phone($meta[$scb . "phone"][0]); ?></a></li>
						<li class="contact__entry contact__entry--email"><a href="mailto:<?= format_text($meta[$scb . "email"][0]); ?>"><?= format_text($meta[$scb . "email"][0]); ?></a></li>
					</ul>
				</div>
			<? endfor; ?>
		<? endif; ?>


		<? end_collapsed_section($collapse, $partial, true); ?>
	</div>
</div>


<?
	include(locate_template('partial-templates/social.php', false, false)); ?>
