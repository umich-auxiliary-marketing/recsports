<div class="oembed__shrinkwrap" data-oembed="<?=preg_replace('/\W+/','',strtolower(strip_tags($meta[$pcb . "source"][0])));?>">
	<?= wp_oembed_get($meta[$pcb . "source"][0]); ?>
</div>
