<? if($post && $post->post_name !== "sitemap"): ?>
	<a href="<?= esc_url(home_url('/')); ?>sitemap/" class="header__sitemap button">Go to the Sitemap Page</a>
<? endif; ?>

<div class="container utilities">
	<div class="wrapper">
		<?php wp_nav_menu(array(
			'theme_location' => 'utilities_nav',
			'menu_class' => 'utilities__nav'));
		?>
	</div>
</div>
