<?
use Roots\Sage\Assets;
wp_enqueue_script('sage/sectionheader', Assets\asset_path('scripts/sectionheader.js'), array('jquery'), null, true);
?>

<a class="anchor" id="<?= $meta[$pcb . "anchor_for_section"][0] ?>"></a>
<hr>
<h2 class="sectionheader__header">
<? if(isset($meta[$pcb . "collapse_section"]) && $meta[$pcb . "collapse_section"][0]): ?>
<a data-anchor="<?= strtolower($meta[$pcb . "anchor_for_section"][0]) ?>" href="#<?= strtolower($meta[$pcb . "anchor_for_section"][0]) ?>" class="sectionheader__collapser themed--chevron gtm--moduletoggled">
<? endif; ?>
<span><?= format_text($meta[$pcb . "header_text"][0]) ?></span><? if(isset($meta[$pcb . "collapse_section"]) && $meta[$pcb . "collapse_section"][0]): ?><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 5 13"><path fill="none" vector-effect="non-scaling-stroke" stroke-width="2" stroke-miterlimit="10" d="M1 12l3-5.499L1 1"/></svg>
</a>
<? endif; ?>
</h2>
