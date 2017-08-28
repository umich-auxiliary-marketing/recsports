<?
$contacts = isset($meta["contacts"]) ? intval($meta["contacts"][0]) : 0;
if($contacts):
	$title = (!isset($meta['suppress_contact_title']) || !$meta['suppress_contact_title'][0]) ? format_text(get_the_title($post)) : "Us";

	if(isset($meta['group_name_to_contact']) && $meta['group_name_to_contact'][0]) {
		$title = $meta['group_name_to_contact'][0];
	}
?>
	<div class="content contact <?= (isset($use_h2) && $use_h2) ? "contact--paragraphs" : "" ?>">
		<? if(isset($use_h2) && $use_h2): ?>
			<div class="paragraph--copy"><h2><?= $title ?></h2></div>
		<? else: ?>
			<a class="anchor" id="contact"></a>
			<hr>
			<h2 class="contact__header">Contact <?= $title ?></h2>
		<? endif; ?>

		<ul class="contact__list">
			<?
			for($i = 0; $i < $contacts; ++$i):
				$ccb = "contacts_" . $i . "_";
				$link = "";
				$label = "";
				$target = "_self";
				$title = "";

				switch($meta[$ccb . "type"][0]):
					case "phone":
						$link = "tel:+1 " . $meta[$ccb . "phone_number"][0];
						$label = format_phone($meta[$ccb . "phone_number"][0]);
						$title = "Call " . format_phone($meta[$ccb . "phone_number"][0]);
					break;
					case "email":
						$link = "mailto:" . $meta[$ccb . "email"][0];
						$label = $meta[$ccb . "email"][0];
						$title = "Email " . $meta[$ccb . "email"][0];
					break;
					case "location":
						$link = get_permalink($meta[$ccb . "location"][0]);
						$label = format_text(get_the_title($meta[$ccb . "location"][0]));
						$title = "Open address in Google Maps";
					break;
					case "link":
						$la = get_link_array($meta, $ccb . "link");
						$link = $la["url"];
						$label = $la["title"];
						$target = $la["target"];
						$title = "";
					break;
				endswitch;
				?>
				<li class="contact__entry contact__entry--<?= $meta[$ccb . "type"][0] ?>">
					<a title="<?= $title ?>" target="<?= $target ?>" href="<?= $link ?>"><?= $label ?></a>
				</li>
				<?
			endfor;
			?>
		</ul>
	</div>
<? endif; ?>
