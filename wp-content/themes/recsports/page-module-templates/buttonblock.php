<?
$buttons = intval($meta[$pcb . "buttons"][0]);
?>

<?
$class = $meta[$pcb . "use_theme_color"][0] ? "button--ctasecondary themed--link" : "";

for($i = 0; $i < $buttons; ++$i):
	$buttoncb = $pcb . "buttons_" . $i . "_";
	$button = get_link_array($meta, $buttoncb . "button");
	?>
		<? if(!$button['blank']): ?>
			<a class="button <?= $class ?>" href="<?= $button['url'] ?>" target='<?= $button['target']?>'><?= $button['title'] ?></a>
		<? endif; ?>
	<?
endfor;
?>
