<? $nmeta = get_post_meta(get_the_ID(), '', false); ?>
<div class="container main" role="document">
	<div class="wrapper">
		<div class="content content--full content--notitle">
			<? include(locate_template('partial-templates/notice.php')); ?>
		</div>

		<? $meta = $nmeta; $meta['suppress_contact_title'][0] = true; ?>
		<? include(locate_template('partial-templates/contact.php', false, false)); ?>

	</div>
</div>
