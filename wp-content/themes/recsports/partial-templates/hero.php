<?
$file = pathinfo(wp_get_attachment_url($meta['hero'][0]));
$fileclass = ($file['basename']) ? 'visual' : 'novisual';
?>

<div class="hero hero--<?= $fileclass ?>">
	<div class="hero__superfill" style="background-image:url(<?= isset($meta['poster_image']) ? wp_get_attachment_url($meta['poster_image'][0]) : ""; ?>)"></div>
	<div class="wrapper">
		<?
		if(!empty($meta['hero'][0])):
			if($file['extension'] == 'mp4' || $file['extension'] == 'mov'): ?>
				<video class="hero__visual" data-object-fit autoplay muted autostart loop preload="none" webkit-playsinline playsinline poster="<?= isset($meta['poster_image']) ? wp_get_attachment_url($meta['poster_image'][0]) : ""; ?>">
					<source src="<?= wp_get_attachment_url($meta['hero'][0]); ?>" type="<?= get_post_mime_type($meta['hero'][0]); ?>">
				</video>
			<? else: ?>
				<img class="hero__visual" src="<?= wp_get_attachment_url($meta['hero'][0]); ?>">
			<? endif; ?>
			<div class="hero__mobile" style="background-image:url('<?= isset($meta['mobile_image']) ? wp_get_attachment_url($meta['mobile_image'][0]) : ""; ?>')"></div>
		<? endif; ?>
		<div class="hero__title"><h1><?= format_text(get_the_title()) ?></h1></div>
	</div>
	<div class="hero__cover themed--svg-stroke">
		<svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" width="1440" height="34" stroke-width="0" viewBox="0 0 1440 34"><path fill="#FAFAF9" d="M1441 2L720 32.968-1 2v32h1442"/><path fill="none" stroke="#CFCFD0" stroke-width="4" stroke-miterlimit="10" d="M-1 2l721 30 721-30"/></svg>
	</div>
</div>
