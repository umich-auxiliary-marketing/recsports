<?
$hero_count = $meta['hero_visuals'][0];
$h = 'hero_visuals_' . mt_rand(0, intval($hero_count) - 1) . '_';
$file = pathinfo(wp_get_attachment_url($meta[$h . 'visual'][0]));
$fileclass = ($file['basename']) ? 'visual' : 'novisual';
?>

<div class="hero hero--<?= $fileclass ?>">
	<div class="hero__superfill" style="background-image:url(<?= isset($meta[$h . 'poster_image']) ? wp_get_attachment_url($meta[$h . 'poster_image'][0]) : ""; ?>)"></div>
	<div class="wrapper">
		<? if($file['extension'] == 'mp4' || $file['extension'] == 'mov'): ?>
			<video class="hero__visual" data-object-fit autoplay muted autostart loop preload="none" webkit-playsinline playsinline poster="<?= isset($meta[$h . 'poster_image']) ? wp_get_attachment_url($meta[$h . 'poster_image'][0]) : ""; ?>">
				<source src="<?= wp_get_attachment_url($meta[$h . 'visual'][0]); ?>" type="<?= get_post_mime_type($meta[$h . 'visual'][0]); ?>">
			</video>
			<div class="hero__mobile" style="background-image:url('<?= isset($meta[$h . 'mobile_image']) ? wp_get_attachment_url($meta[$h . 'mobile_image'][0]) : ""; ?>')"></div>
		<? else: ?>
			<img class="hero__visual" src="<?= wp_get_attachment_url($meta[$h . 'visual'][0]); ?>">
		<? endif; ?>
		<div class="hero__homecopy hero__homecopy--<?= $meta[$h . 'position_of_copy'][0] ?>"><span class="hero__homecopy__intro"><?= format_hero_textarea($meta[$h . 'copy_intro'][0]) ?></span><span class="hero__homecopy__tagline"><?= format_hero_textarea($meta[$h . 'copy_tagline'][0]) ?></span></div>
	</div>
	<div class="hero__cover themed--svg-stroke hero__cover--home">
		<svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" width="1440" height="34" stroke-width="0" viewBox="0 0 1440 34"><path class="coverfill" fill="#FAFAF9" d="M1441 2L720 32.968-1 2v32h1442"/><path fill="none" stroke="#407cca" stroke-width="4" stroke-miterlimit="10" d="M-1 2l721 30 721-30"/></svg>
	</div>
</div>
