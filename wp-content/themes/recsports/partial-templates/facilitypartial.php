<?
	if(!$lmeta) {
		$location = get_the_ID();
		$lmeta = get_post_meta($location, '', false);
	}
?>

<? include(locate_template('partial-templates/sidebar-facility.php', false, false)); ?>

<div class="facilitypartial__main">
<div class="content">
	<h1 class="facilitypartial__title"><?= $lmeta['pagetitle'][0] ?></h1>
</div>

<? inject_page_notices(isset($lmeta["notices"]) && $lmeta["notices"][0] ? unserialize($lmeta["notices"][0]) : false); ?>


<? if($lmeta["sets_of_hours_to_show"][0] !== "hide"): ?>
<div class="content">
	<h2 class="facilitypartial__sectionheader">Hours</h2>
</div>

<? if($lmeta["sets_of_hours_to_show"][0] === "text_only"): ?>
<div class="content paragraph--smallpadtop">
	<?= format_textarea($lmeta["hours_text_block"][0]); ?>
	<? if(isset($lmeta['disclaimer_text']) && $lmeta['disclaimer_text'][0]): ?>
		<div class="paragraph--fineprint"><?= format_textarea($lmeta["disclaimer_text"][0]); ?></div>
	<? endif; ?>
</div>
<? else: ?>
<div class="content calendar">
	<?
	include(locate_template('page-module-templates/hours.php', false, false));
	?>
</div>
<? endif; ?>
<? endif; ?>

<div class="content sectionheader">
	<h2 class="facilitypartial__sectionheader">Facility Access</h2>
	<div class="facilitypartial__access paragraph--copy paragraph--smallpadtop">
		<?= format_wysiwyg($lmeta["building_access"][0]); ?>
	</div>
</div>

<? if(isset($lmeta["show_activities"]) && $lmeta["show_activities"][0]): ?>
<div class="content sectionheader">
	<h2><a class="facilitypartial__sectionheader themed--chevron" href="<?= get_permalink($location) ?>"><span>Activities</span><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 5 13"><path fill="none" vector-effect="non-scaling-stroke" stroke-width="2" stroke-miterlimit="10" d="M1 12l3-5.499L1 1"/></svg></a></h2>
</div>
<? endif; ?>

<? if(isset($lmeta["show_additional_details"]) && $lmeta["show_additional_details"][0]): ?>
<div class="content sectionheader">
	<h2><a class="facilitypartial__sectionheader themed--chevron" href="<?= get_permalink($location) ?>"><span><?= format_text($lmeta["title"][0]) ?></span><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 5 13"><path fill="none" vector-effect="non-scaling-stroke" stroke-width="2" stroke-miterlimit="10" d="M1 12l3-5.499L1 1"/></svg></a></h2>
</div>
<? endif; ?>

<div class="content paragraph--fineprint">
	<p>To see all the information associated with this facility, <a href="<?= get_permalink($location) ?>">go to the full facility page</a>.</p>
</div>
</div>
