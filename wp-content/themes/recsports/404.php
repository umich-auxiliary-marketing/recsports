<div class="container main">
	<div class="wrapper wrapper--centered">
		<?
			$full = true;
			include(locate_template('partial-templates/pagetitle.php', false, false));
		?>

		<div class="content content--full error">
			<div class="error__dude">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128"><path d="M53 33c6.63 0 12-5.37 12-12S59.62 9 53 9c-6.63 0-12 5.37-12 12s5.37 12 12 12zM110 5H98c-2.2 0-4 1.8-4 4v18c0 2.2 1.8 4 4 4h12c2.2 0 4-1.8 4-4V9c0-2.2-1.8-4-4-4zm0 22H98V9h12v18zM92.17 28.38L63.4 37H41c-.73 0-1.46.2-2.12.6l-16 10s0 .02-.02.03h-.03l-.3.25c-.1.08-.22.16-.3.25-.1.1-.2.2-.27.3-.1.1-.18.2-.25.3l-.2.35-.16.32c-.06.13-.1.26-.13.38l-.1.35c-.03.13-.04.27-.05.4l-.05.36.03.4c0 .12 0 .24.03.36.02.14.07.27.1.4l.1.36c.05.13.13.26.2.4.05.1.08.2.15.3l.03.03v.02l11 17C33.42 71.37 34.7 72 36 72c.75 0 1.5-.2 2.17-.64 1.86-1.2 2.4-3.68 1.2-5.53l-8.8-13.6L41 45.74V119c0 2.2 1.8 4 4 4s4-1.8 4-4V83h8v36c0 2.2 1.8 4 4 4s4-1.8 4-4V44.86l.15-.03 30-9c1.4-.42 2.37-1.53 2.7-2.84-2.76-.07-5.07-2.02-5.68-4.63z"/><path d="M105 20c0-3 1-6 1-8 0-1-1-2-2-2s-2 1-2 2c0 2 1 5 1 8h2z"/><circle cx="104" cy="24" r="2"/></svg>
			</div>
			<p class="error__message">Sorry about that. <a href="<?= esc_url(home_url('/')); ?>">Go to the home page</a> or try a search.</p>
		</div>
		<div class="searchformwrap searchformwrap--toprule content content--full">
			<?php get_search_form(); ?>
		</div>

	</div>
</div>
