<?
use Roots\Sage\Assets;
wp_enqueue_script('sage/list', Assets\asset_path('scripts/list.js'), array('jquery'), null, true);

	$location = get_the_ID();

	if(!$lmeta) {
		$lmeta = get_post_meta($location, '', false);
	}
?>

<? include(locate_template('partial-templates/sidebar-facility.php', false, false)); ?>

<? inject_page_notices(isset($lmeta["notices"]) && $lmeta["notices"][0] ? unserialize($lmeta["notices"][0]) : false); ?>

<? if(isset($lmeta['about_this_facility']) && $lmeta['about_this_facility'][0] !== ""): ?>
<div class="content introduction">
	<div class="introduction__paragraph">
		<?= format_textarea($lmeta['about_this_facility'][0]); ?>
	</div>
</div>
<? endif; ?>

<? if($lmeta["sets_of_hours_to_show"][0] !== "hide"): ?>
<div class="content sectionheader">
	<hr>
	<a class="anchor" id="hours"></a>
	<h2 class="sectionheader__header">Hours</h2>
</div>

<? if($lmeta["sets_of_hours_to_show"][0] === "text_only"): ?>
<div class="content paragraph">
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
	<hr>
	<a class="anchor" id="access"></a>
	<h2 class="sectionheader__header">Facility Access</h2>
</div>
<div class="content facility__accessdetails paragraph">
	<?= format_wysiwyg($lmeta["building_access"][0]); ?>
</div>

<? if(isset($lmeta["show_activities"]) && $lmeta["show_activities"][0]): ?>
<div class="content sectionheader">
	<hr>
	<a class="anchor" id="activities"></a>
	<h2 class="sectionheader__header">Activities</h2>
</div>
<div class="content list">



<?
	$items = intval($lmeta["list_of_activities"][0]);
	$label = "activities";
	$verb = "are";
	$switchstatement = "Expand all into list view.";

	if($items == 1) {
		$label = "activity";
		$verb = "is";
	}

	?>
	<p class="list__counter paragraph--copyfree">There <?= $verb ?> <?= $items ?> <?= $label ?>. <a class="list__viewlink" href="#"><?=$switchstatement?></a></p>

	<div class="list__group list__group--grid">
	<?
	for($i = 0; $i < $items; ++$i) {
		$itemcb = "list_of_activities_" . $i . "_";
		$icondefined = (isset($lmeta[$itemcb . "icon"]) && $lmeta[$itemcb . "icon"][0] !== "");
		?>
		<div class="list__item <?= $icondefined ? "list__item--withicon" : "" ?>">
			<a class="list__link themed--parent-link list__link--description" href="#">
				<? if($icondefined): ?>
					<div class="list__icon themed--child-link">
						<? $pathinfo = pathinfo(wp_get_attachment_url($lmeta[$itemcb . "icon"][0]));
						if($pathinfo['extension'] === 'svg'):
							echo inject_svg($lmeta[$itemcb . "icon"][0]);
						else: ?>
							<img src="<?= wp_get_attachment_url($lmeta[$itemcb . "icon"][0]) ?>">
						<? endif; ?>
					</div>
				<? endif; ?>
				<div class="list__titletext">
					<div class="list__titlemain"><?= format_text($lmeta[$itemcb . "label"][0]) ?></div>
				</div>
			</a>
			<div class="list__expander">
				<div class="list__expanderarrow"></div>
				<a class="list__expanderclose" href="#"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M28 6.1L25.9 4 16 13.9 6.1 4 4 6.1l9.9 9.9L4 25.9 6.1 28l9.9-9.9 9.9 9.9 2.1-2.1-9.9-9.9"/></svg></a>
				<div class="list__expandercontent paragraph--copy">
					<? if(isset($lmeta[$itemcb . "availability"]) && $lmeta[$itemcb . "availability"][0] !== ""): ?>
						<h4>Times Offered</h4>
						<?= format_wysiwyg($lmeta[$itemcb . "availability"][0]); ?>
					<? endif; ?>

					<? if(isset($lmeta[$itemcb . "details"]) && $lmeta[$itemcb . "details"][0] !== ""): ?>
						<h4>Details</h4>
						<?= format_wysiwyg($lmeta[$itemcb . "details"][0]); ?>
					<? endif; ?>

					<? if(isset($lmeta[$itemcb . "related_link"]["url"]) && $lmeta[$itemcb . "related_link"]["url"] !== ""): ?>
						<? $itemlink = get_link_array($lmeta[$itemcb . "related_link"][0]) ?>
						<h4>See also</h4>
						<a href="<?= $itemlink["url"] ?>" target="<?= $itemlink["target"] ?>" class="button button--cta themed--cta"><?= $itemlink["title"] ?></a>
					<? endif; ?>

				</div>
			</div>
		</div>
		<?
	}
	?>
	</div>






</div>
<? endif; ?>

<? if(isset($lmeta["show_additional_details"]) && $lmeta["show_additional_details"][0]): ?>
<div class="content sectionheader">
	<hr>
	<a class="anchor" id="details"></a>
	<h2 class="sectionheader__header"><?= format_text($lmeta["title"][0]) ?></h2>
</div>
<div class="content paragraph">
	<?= format_wysiwyg($lmeta["additional_details"][0]) ?>
</div>
<? endif; ?>

<? $meta = $lmeta; ?>
<? include(locate_template('partial-templates/contact.php', false, false)); ?>
