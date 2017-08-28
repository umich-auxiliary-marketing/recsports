<?
use Roots\Sage\Assets;
wp_enqueue_script('sage/policies', Assets\asset_path('scripts/policies.js'), array('jquery'), null, true);
?>

<?
$policies = intval($meta[$pcb . "policies"][0]);
$collapse_them = isset($meta[$pcb . "collapse_policies"]) && $meta[$pcb . "collapse_policies"][0];
?>

<?
for($i = 0; $i < $policies; ++$i):
	$policycb = $pcb . "policies_" . $i . "_";
	?>
	<h3 class="policies__subheader">
		<? if($collapse_them): ?>
			<a class="policies__collapser themed--chevron" href="#">
		<? endif; ?>
		<span><?= format_text($meta[$policycb . "subheader"][0]);?></span><? if($collapse_them): ?><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 5 13"><path fill="none" vector-effect="non-scaling-stroke" stroke-width="2" stroke-miterlimit="10" d="M1 12l3-5.499L1 1"/></svg></a>
		<? endif; ?>
	</h3>
	<div class="policies__content paragraph--copy <?= $collapse_them ? "collapsible" : "" ?>">
		<?= format_wysiwyg($meta[$policycb . "content"][0]);?>
	</div>
<? endfor; ?>
<? if(isset($meta[$pcb . "show_last_updated_date"]) && $meta[$pcb . "show_last_updated_date"][0]): ?>
	<p class="policies__updated">Page updated <?= time2str(get_post_modified_time('U', null, $post->ID)) ?>.</p>
<? endif; ?>
