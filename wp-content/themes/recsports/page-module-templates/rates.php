<?
$groups = intval($meta[$pcb . "rate_groups"][0]);
$selected = false;

if($groups > 1):
?>
<div class="rates__header">
	<select class="rates__select">
		<?
		for($g = 0; $g < $groups; ++$g) {
			$groupcb = $pcb . "rate_groups_" . $g . "_";
			$selection = "";

			if ($meta[$groupcb . "default_group"][0] && !$selected) {
				$selection = "selected";
				$selected = true;
			}
			?>
			<option <?=$selection?> value="<?=format_classname($meta[$groupcb . "group_keyword"][0])?>"><?=format_text($meta[$groupcb . "group_label"][0])?></option>
			<?
		}
		?>
		<option disabled>&mdash;</option>
		<option value="_listall">List all</option>
	</select>
</div>
<?
endif;
?>
<div class="rates__content">
<?
for($i = 0; $i < $groups; ++$i) {
	$groupcb = $pcb . "rate_groups_" . $i . "_";
	$items = intval($meta[$groupcb . "items_for_sale"][0]);
	?>

	<h3 class="rates__grouphead expanded"><?= format_text($meta[$groupcb . "group_label"][0]) ?></h3>
	<div class="rates__group expanded rates__group--<?=format_classname($meta[$groupcb . "group_keyword"][0])?>">
	<?
	for($j = 0; $j < $items; ++$j) {
		$itemcb = $groupcb . "items_for_sale_" . $j . "_";
		?>
		<dl class="rates__item">
			<dt><?= format_text($meta[$itemcb . "item_label"][0]) ?></dt>
			<dd>$<?= format_price($meta[$itemcb . "price"][0]) ?><span class="rates__suffix"><?= format_text($meta[$itemcb . "price_suffix"][0]) ?></span></dd>
		</dl>
		<?
	}
	?>
	</div>
	<?
}
?>
</div>
<div class="rates__footer">
	<?=	inject_svg('/dist/images/payment-visa.svg', false) ?>
	<?= inject_svg('/dist/images/payment-mastercard.svg', false) ?>
	<?= inject_svg('/dist/images/payment-amex.svg', false) ?>
	<?= inject_svg('/dist/images/payment-discover.svg', false) ?>
	<?= inject_svg('/dist/images/payment-cash.svg', false) ?>
	<?= inject_svg('/dist/images/payment-check.svg', false) ?>
</div>

<? if($meta[$pcb . "fine_print_for_rates"][0]): ?>
<p class="rates__fineprint">
	<?= $meta[$pcb . "fine_print_for_rates"][0]; ?>
</p>
<? endif; ?>
