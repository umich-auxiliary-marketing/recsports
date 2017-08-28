<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<? if(strpos($_SERVER['HTTP_HOST'], "localhost") === false):  ?>
		<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
	<? endif; ?>
	<link rel="mask-icon" sizes="any" href="<?php echo get_stylesheet_directory_uri(); ?>/favicons/safari-pin.svg" color="#00274c">
	<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicons/favicon.ico" />
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_stylesheet_directory_uri(); ?>/favicons/apple-touch-icon.png">
	<link rel="manifest" href="<?php echo get_stylesheet_directory_uri(); ?>/favicons/manifest.json">
	<link rel="mask-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicons/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="apple-mobile-web-app-title" content="Rec Sports">
	<meta name="application-name" content="Rec Sports">
	<meta name="theme-color" content="#f5c400">
	<? if($post): ?>
		<? if($google_description = get_post_meta($post->ID, "google_description", true)): ?>
			<meta name="description" content="<?= format_text($google_description) ?>">
			<meta name="og:description" content="<?= format_text($google_description) ?>">
		<? endif; ?>
		<? if($og_image = get_post_meta($post->ID, "open_graph_image", true)): ?>
			<? $dir = wp_upload_dir() ?>
			<meta name="og:image" content="<?= $dir["url"] . "/" . basename(get_attached_file($og_image)) ?: "" ?>">
		<? endif; ?>
	<? endif; ?>
  <?php wp_head(); ?>
</head>
