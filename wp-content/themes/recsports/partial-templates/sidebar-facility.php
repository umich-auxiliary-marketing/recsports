<aside class="sidebar-facility">
	<? $maplink = $lmeta["address_0_address_line_1"][0] . ", " . $lmeta["address_0_city"][0]  . ", " . $lmeta["address_0_state"][0] . " " . $lmeta["address_0_zip_code"][0]; ?>

	<?
		$mappin = isset($lmeta['map_pin']) ? unserialize($lmeta['map_pin'][0]) : array('lat' => "", 'lng' => "");
		$parklink = get_link_array($lmeta, "link_to_parking_page");
	?>

	<div class="sidebar-facility__mapblock">
		<? if(isset($lmeta["photo"])): ?>
			<div class="sidebar-facility__photo" style="background-image:url('<?= wp_get_attachment_url($lmeta["photo"][0]) ?>')"></div>
		<? endif; ?>
		<a class="sidebar-facility__maplink" href="http://maps.google.com/?q=<?= $maplink ?>" target="_blank" data-map="<?= $lmeta["abbreviation"][0]; ?>">
			<div class="sidebar-facility__map" style="background-image:url(https://maps.googleapis.com/maps/api/staticmap?center=<?=$mappin['lat']?>,<?=$mappin['lng']?>&size=500x350&zoom=17&scale=2&key=AIzaSyBlZt6bYKGMVXcLbUucc5RUZ_fShfEqWPY)" aria-label="Map of <?= (isset($lmeta['pagetitle']) ? $lmeta['pagetitle'][0] : get_the_title($location)); ?>"></div>
			<div class="sidebar-facility__pin"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill="#e84b37" d="M9 15c0-.7.1-1.6.7-2.58.23-.38.55-.8.92-1.3C11.68 9.7 13 7.94 13 6.17v-.24c0-2.55-2.06-4.72-4.57-4.9C8.3 1 8.2 1 8.06 1h-.12c-.13 0-.25 0-.37.02C5.07 1.22 3 3.38 3 5.92v.25c0 1.77 1.32 3.53 2.38 4.95.37.5.7.92.92 1.3.6.98.7 1.86.7 2.58h2zM8 8c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/></svg>
			</div>
		</a>
	</div>

	<div class="sidebar-facility__undermap">
		<a href="http://maps.google.com/?q=<?= urlencode($maplink) ?>" target="_blank" class="sidebar-facility__address">
			<div class="sidebar-facility__address-1"><?= format_text($lmeta["address_0_address_line_1"][0]) ?></div>
			<div class="sidebar-facility__address-2"><?= format_text($lmeta["address_0_address_line_2"][0]) ?></div>
			<div class="sidebar-facility__city">
				<?= $lmeta["address_0_city"][0] ?>,
				<?= $lmeta["address_0_state"][0] ?>
				<?= format_zip($lmeta["address_0_zip_code"][0]) ?>
				</div>
		</a>

		<ul class="sidebar-facility__directions">
			<li><a target="_blank" href="https://www.google.com/maps/dir//<?= $maplink ?>/@<?=$mappin['lat']?>,<?=$mappin['lng']?>,16z/">Get directions</a></li>
			<li><a target="<?= $parklink["target"] ?>" href="<?= $parklink["url"] ?>">Find parking nearby</a></li>
		</ul>

		<?
		$access = array(
			"public" => "This facility is open to the general public.",
			"members" => "A membership or valid pass is required to use this facility.",
			"sometimes_reserved" => "This facility is open to the general public except for scheduled events.",
			"always_reserved" => "This facility is reserved for scheduled events only.",
			"closed" => "This facility is temporarily closed.",
			"none" => "No access details specified."
		);
		?>

		<div class="sidebar-facility__access">
			<p class="sidebar-facility__accessexplanation"><?= $access[$lmeta["access_restrictions"][0]] ?></p>
			<? if(isset($lmeta["display_member_button"]) && $lmeta["display_member_button"][0]): ?>
				<a class="button button--cta themed--cta button--full" href="<?= esc_url(home_url('/')); ?>fitness/membership/">Become a Member</a>
			<? endif; ?>
		</div>
	</div>
</aside>
