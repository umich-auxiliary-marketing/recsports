<?
use Roots\Sage\Assets;
wp_enqueue_script('sage/list', Assets\asset_path('scripts/list.js'), array('jquery'), null, true);

$items = intval($meta[$pcb . "items"][0]);
$itemsize = (isset($meta[$pcb . "item_size"]) ? $meta[$pcb . "item_size"][0] : "regular");
$behavior = $meta[$pcb . "item_behavior"][0];
$label = "items";
$verb = "are";
$switchstatement = "Expand all into a list.";

if($items == 1) {
	$label = "item";
	$verb = "is";
}

if($meta[$pcb . "default_view"][0] === "list") {
	$switchstatement = "Collapse all into a grid.";
}

if(isset($meta[$pcb . "item_label"]) && $meta[$pcb . "item_label"][0]) {
	$label = $meta[$pcb . "item_label"][0];
}

?>
<p class="list__counter paragraph--copyfree">There <?= $verb ?> <?= $items ?> <?= $label ?>. <a class="list__viewlink" href="#"><?=$switchstatement?></a></p>

<div class="list__group list__group--<?= $meta[$pcb . "default_view"][0] ?> list__group--size<?=$itemsize?>">
<?
for($i = 0; $i < $items; ++$i) {
	$itemcb = $pcb . "items_" . $i . "_";
	$destination = ($behavior === "link" ? get_link_array($meta, $buttoncb . "button") : array("url" => "#", "target" => "_self"));
	$text = ($behavior === "description" ? format_wysiwyg($meta[$itemcb . "text_to_show"][0]) : "");
	$icondefined = ($meta[$itemcb . "icon"][0] && $meta[$itemcb . "icon"][0] !== "");

	?>
	<div class="list__item <?= $icondefined ? "list__item--withicon" : "" ?>">
		<a class="list__link themed--parent-link list__link--<?=$behavior?>" target="<?= $destination["target"] ?>" href="<?= $destination["url"] ?>">
			<? if($icondefined): ?>
				<div class="list__icon themed--child-link">
					<? $pathinfo = pathinfo(wp_get_attachment_url($meta[$itemcb . "icon"][0]));
					if($pathinfo['extension'] === 'svg'):
						echo inject_svg($meta[$itemcb . "icon"][0]);
					else: ?>
						<img src="<?= wp_get_attachment_url($meta[$itemcb . "icon"][0]) ?>">
					<? endif; ?>
				</div>
			<? endif; ?>
			<div class="list__titletext">
				<div class="list__titlemain">
					<?= format_text($meta[$itemcb . "label"][0]) ?>
				</div>
				<? if(isset($meta[$itemcb . "label_secondary"]) && $meta[$itemcb . "label_secondary"][0] !== ""): ?>
					<div class="list__titlesecondary">
						<?= format_text($meta[$itemcb . "label_secondary"][0]) ?>
					</div>
				<? endif; ?>
			</div>
		</a>
		<div class="list__expander">
			<div class="list__expanderarrow"></div>
			<a class="list__expanderclose" href="#"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M28 6.1L25.9 4 16 13.9 6.1 4 4 6.1l9.9 9.9L4 25.9 6.1 28l9.9-9.9 9.9 9.9 2.1-2.1-9.9-9.9"/></svg></a>
			<div class="list__expandercontent paragraph--copy"><?= $text ?></div>
		</div>
	</div>
	<?
}
?>
</div>
