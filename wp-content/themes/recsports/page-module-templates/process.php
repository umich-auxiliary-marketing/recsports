<? $count = $meta[$pcb . "process_steps"][0]; ?>

<ol class="process__step">
	<? for($i = 0; $i < $count; ++$i): ?>
		<li class="process__value"><span class="process__text paragraph--copy"><?= format_text($meta[$pcb . "process_steps_" . $i . "_step_text"][0]) ?> </span></li>
	<? endfor; ?>
</ol>
<? if(isset($meta[$pcb . "fine_print"]) && $meta[$pcb . "fine_print"][0]): ?>
	<div class="process__fineprint"><?= format_textarea($meta[$pcb . "fine_print"][0]); ?></div>
<? endif; ?>
