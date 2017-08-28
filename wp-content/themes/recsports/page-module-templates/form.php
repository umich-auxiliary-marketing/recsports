<?
use Roots\Sage\Assets;
wp_enqueue_script('sage/form', Assets\asset_path('scripts/form.js'), array('jquery'), null, true);

$collapseclass = "";

if($meta[$pcb . "collapse_form"] && $meta[$pcb . "collapse_form"][0] === "1") {
	$collapseclass = "collapsed";

	// Get the name of the form so it can be passed to Google Tag Manager
	global $wpdb;
	$forms = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "frm_forms WHERE id = " . $meta[$pcb . "form_shortcode"][0]);
	$formname = (isset($forms[0]) ? $forms[0]->name : "unknown form");
	?>
	<div class="form__revealerwrap">
		<button data-formname="<?=$formname?>" class="form__revealer button button--cta themed--cta gtm--formrevealed"><?= empty($meta[$pcb . "reveal_form_button_label"][0]) ? "Show Form" : $meta[$pcb . "reveal_form_button_label"][0] ?></button>
	</div>

	<?
}
?>

<div class="form__shrinkwrap <?= $collapseclass ?>" data-formtitle="">
	<?= do_shortcode("[formidable id=" . $meta[$pcb . "form_shortcode"][0] . "]"); ?>
</div>
