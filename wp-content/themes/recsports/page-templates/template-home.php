<?php
/**
 * Template Name: Home Page
 */
?>

<?
$meta = get_post_meta(get_the_ID(), '', false);
$counter = 0;
$snippetcounter = 1;

use Roots\Sage\Assets;
wp_enqueue_script('sage/list', Assets\asset_path('scripts/list.js'), array('jquery'), null, true);
?>

<?php get_template_part('../partial-templates/page', 'header'); ?>

<?php include(locate_template('partial-templates/homehero.php', false, false)); ?>

<div class="container container--facilities main">
	<section class="wrapper wrapper--facilities">
		<div class="content--facilities facilities">
			<h2 class="facilities__homeheader">Facilities &amp; Hours</h2>
			<div class="facilities__buttons list__group list__group--grid">
				<?
				$query = new WP_Query(array(
				  'post_type' => 'facility',
				  'post_status' => 'publish',
					'orderby' => 'menu_order',
					'order' => 'ASC',
					'posts_per_page' => -1
				));

				while ($query->have_posts()) :
				  $query->the_post();
				  $location = get_the_ID();
				  $lmeta = get_post_meta($location, false);
					$lmeta['pagetitle'] = array(get_the_title($location));
					?>
					<div class="list__item">
					<a class="facilitybutton__link list__link list__link--description facilitybutton--<?= format_classname($lmeta['abbreviation'][0]) ?>" data-facility="<?= $lmeta['abbreviation'][0] ?>" href="<?= get_permalink($location)?>">
						<div class="facilitybutton__title list__titletext"><?=format_text($lmeta['abbreviation'][0])?></div>
						<? if(isset($lmeta['sets_of_hours_to_show']) &&
							($lmeta['sets_of_hours_to_show'][0] == "one_gcal" || $lmeta['sets_of_hours_to_show'][0] == "two_gcal") && 
							isset($lmeta['main_calendar_0_google_calendar_id'])): ?>
							<div class="facilitybutton__status">
								<?= output_open_now($lmeta['main_calendar_0_google_calendar_id'][0]); ?>
							</div>
						<? endif; ?>
					</a>
					<div class="list__expander">
						<div class="list__expanderarrow"></div>
						<a class="list__expanderclose" href="#"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M28 6.1L25.9 4 16 13.9 6.1 4 4 6.1l9.9 9.9L4 25.9 6.1 28l9.9-9.9 9.9 9.9 2.1-2.1-9.9-9.9"/></svg></a>
						<div class="list__expandercontent list__expandercontent--facility content content--facility content--full">
							<? include(locate_template('partial-templates/facilitypartial.php', false, false)); ?>
						</div>
					</div>
					</div>
				<?
				endwhile;

				wp_reset_query();
				?>
			</div>
		</div>
	</section>
</div>

<div class="container container--facilitydetail">
	<section class="wrapper wrapper--facilitydetail facilitydetail">
	</section>
</div>


<div class="container container--snippet" role="document">
	<div class="wrapper wrapper--snippet">
		<? if($meta["snippets"][0]): foreach(unserialize($meta["snippets"][0]) as $partial): ?>
			<? $pcb = "snippets_" . $counter . "_"; ?>
			<? if($partial == "grouping_header"): ?>

				<div class="content--home grouping_header">
					<? if($counter > 0): ?><hr class="hr--snippet"><? endif; ?>
					<h2 class="snippet__homeheader"><?= $meta[$pcb . 'label'][0] ?></h2>
				</div>
			<? else: ?>
				<?
				$snippetrow = ($snippetcounter % 2 === 1 ? "snippet--odd" : "snippet--even");
				$color = (get_post_meta($meta[$pcb . 'section_page_object'][0], "section_color", true) ?: "recsports");
				?>

				<div class="content--home snippet <?= $snippetrow ?> snippet--<?= $color ?>">
					<div class="snippet__visual" style="background-image: url(<?= wp_get_attachment_url($meta[$pcb . 'photo'][0]) ?>)"></div>

					<div class="snippet__copy">
						<h3>
							<a class="snippet__sectiontitle locallink--<?= $color ?>" href="<?= get_permalink($meta[$pcb . 'section_page_object'][0]) ?>"><span><?= format_text(get_the_title($meta[$pcb . 'section_page_object'][0])) ?></span><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 5 13"><path fill="none" vector-effect="non-scaling-stroke" stroke-width="2" stroke-miterlimit="10" d="M1 12l3-5.499L1 1"/></svg></a>
						</h3>

						<div class="snippet__buttons">
							<? $link1 = get_link_array($meta, $pcb . "main_button"); ?>

							<? if(!$link1["blank"]): ?>
								<a class="snippet__buttons__primary button button--cta localcta--<?= $color ?>" href="<?= $link1["url"] ?>" target="<?= $link1["target"] ?>"><?= format_text($link1["title"]) ?></a>
							<? endif; ?>
							<? $link2 = get_link_array($meta, $pcb . "secondary_button"); ?>
							<? if($meta[$pcb . 'number_of_buttons'][0] === "two" && !$link2["blank"]): ?>
								<a class="snippet__buttons__secondary button button--ctasecondary locallink--<?= $color ?>"  href="<?= $link2["url"] ?>" target="<?= $link2["target"] ?>"><?= format_text($link2["title"]) ?></a>
							<? endif; ?>
						</div>
						<h4 class="snippet__headline"><?= format_text($meta[$pcb . 'headline'][0]) ?></h4>
						<p class="snippet__paragraph"><?= format_text($meta[$pcb . 'copy'][0]) ?></p>
					</div>
					<? $snippetcounter++; ?>
				</div>
			<? endif; ?>

			<? $counter++; ?>
		<? endforeach; endif; ?>
	</div>
</div>

<? include(locate_template('partial-templates/social.php', false, false)); ?>
