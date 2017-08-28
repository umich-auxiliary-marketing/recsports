<div class="container finalhook finalhook--<?= $meta['section_color'][0] ?>">
	<div class="finalhook__backdrop">
		<svg class="finalhook__backdrop__svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
		width="100%" height="340" enable-background="new 0 0 100 640" xml:space="preserve">
			<pattern width="40" height="340px" patternUnits="userSpaceOnUse" id="chevrons" viewBox="76 -340 40 340" overflow="visible">
				<g class="themed--svg-fill">
					<polygon fill="#000000" points="116,-340 112,-340 188,-169.5 112.445,0 116,0 192,-169.5 		"/>
					<polygon fill="#000000" points="108,-340 104,-340 180,-169.5 104.445,0 108,0 184,-169.5 		"/>
					<polygon fill="#000000" points="100,-340 96,-340 172,-169.5 96.445,0 100,0 176,-169.5 		"/>
					<polygon fill="#000000" points="92,-340 88,-340 164,-169.5 88.445,0 92,0 168,-169.5 		"/>
					<polygon fill="#000000" points="84,-340 80,-340 156,-169.5 80.445,0 84,0 160,-169.5 		"/>
					<polygon fill="#000000" points="76,-340 72,-340 148,-169.5 72.445,0 76,0 152,-169.5 		"/>
					<polygon fill="#000000" points="68,-340 64,-340 140,-169.5 64.445,0 68,0 144,-169.5 		"/>
					<polygon fill="#000000" points="60,-340 56,-340 132,-169.5 56.446,0 60,0 136,-169.5 		"/>
					<polygon fill="#000000" points="52,-340 48,-340 124,-169.5 48.446,0 52,0 128,-169.5 		"/>
					<polygon fill="#000000" points="44,-340 40,-340 116,-169.5 40.446,0 44,0 120,-169.5 		"/>
					<polygon fill="#000000" points="36,-340 32,-340 108,-169.5 32.446,0 36,0 112,-169.5 		"/>
					<polygon fill="#000000" points="28,-340 24,-340 100,-169.5 24.446,0 28,0 104,-169.5 		"/>
					<polygon fill="#000000" points="20,-340 16,-340 92,-169.5 16.446,0 20,0 96,-169.5 		"/>
					<polygon fill="#000000" points="12,-340 8,-340 84,-169.5 8.446,0 12,0 88,-169.5 		"/>
					<polygon fill="#000000" points="4,-340 0,-340 76,-169.5 0.446,0 4,0 80,-169.5 		"/>
				</g>
			</pattern>
			<rect fill="url(#chevrons)" width="100%" height="340"/>
		</svg>
	</div>
	<div class="wrapper">
		<div class="finalhook__statement"><?= format_text(empty($meta["bottom_headline"]) ? "" : $meta["bottom_headline"][0]) ?></div>
		<? $finalhook = get_link_array($meta, "action_button"); ?>
		<? if(!$finalhook["blank"]): ?>
			<a href="<?= $action["url"] ?>" target="<?= $action["target"] ?>" class="button button--cta button--ctafinalhook themed--cta"><?= format_text($action["title"]) ?></a>
		<? endif; ?>
	</div>
</div>
