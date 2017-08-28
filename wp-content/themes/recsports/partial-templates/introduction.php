<div class="introduction__paragraph">
	<?= format_textarea($meta["introductory_paragraph"][0]) ?>
</div>
<? $action = get_link_array($meta, "action_button"); ?>

<? if(!$action["blank"]): ?>
	<a href="<?= $action["url"] ?>" target="<?= $action["target"] ?>" class="introduction__button button button--cta themed--cta"><?= format_text($action["title"]) ?></a>
<? endif; ?>
