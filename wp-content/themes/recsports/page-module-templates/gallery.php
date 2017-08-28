<?
use Roots\Sage\Assets;
wp_enqueue_script('sage/list', Assets\asset_path('scripts/list.js'), array('jquery'), null, true);

$photos = unserialize($meta[$pcb . "photos"][0]);
$descriptions = (isset($meta[$pcb . "show_description_under_photo"]) && $meta[$pcb . "show_description_under_photo"][0]);

?>
<div class="list__group list__group--grid list__group--sizesquare list__group--photogallery">
<?
foreach($photos as $photo) {
	$largephoto = wp_get_attachment_image_src($photo, "large");
	$fullphoto = wp_get_attachment_image_src($photo, "full");
	?>
	<div class="list__item list__item--photo">
		<a class="list__link list__link--description list__link--photo" href="#" style="background-image: url(<?=$largephoto[0]?>);">
		</a>
		<div class="list__expander">
			<div class="list__expanderarrow"></div>
			<a class="list__expanderclose" href="#"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M28 6.1L25.9 4 16 13.9 6.1 4 4 6.1l9.9 9.9L4 25.9 6.1 28l9.9-9.9 9.9 9.9 2.1-2.1-9.9-9.9"/></svg></a>
			<div class="list__expandercontent list__expandercontent--gallery">
				<div class="gallery__photo" style="background-image: url(<?=$fullphoto[0]?>);"></div>
				<? if($descriptions): ?>
					<div class="gallery__description paragraph--copy">
						<p><?= format_text(get_post_field("post_excerpt", $photo)) ?></p>
					</div>
				<? endif; ?>
			</div>
		</div>
	</div>
	<?
}
?>
</div>
