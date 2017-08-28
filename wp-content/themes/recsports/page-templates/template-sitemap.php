<?php
/*
Template Name: Sitemap
*/

?>

<?
	$menu = get_post_meta(wp_get_nav_menu_object('Main Menu')->term_id, 'nested_menu', true);
	$utilmenu = wp_get_nav_menu_items('Utility Menu');
	$footermenu = wp_get_nav_menu_items('Footer');
?>



<div class="container main" role="document">
	<div class="wrapper">
		<? include(locate_template('partial-templates/pagetitle.php', false, false)); ?>

		<div class="content container--sitemap paragraph--copy">
			<h2>Programs by Recreational Sports</h2>
			<ul>
				<? foreach($menu as $menu_item): ?>
						<li><?= $menu_item->title ?>
							<ul>
								<? foreach($menu_item->children as $menu_subitem): ?>
									<li>
										<? $color = get_post_meta($menu_subitem->object_id, "section_color", true); ?>
										<a class="locallink--<?= $color ?>" href="<?= get_permalink($menu_subitem->object_id, false) ?>"><?= format_text($menu_subitem->title)?></a>
										<?
										$sectionheads = get_post_meta($menu_subitem->object_id, "page_content", true);
										if($sectionheads && in_array("sectionheader", $sectionheads)):
											$counter = 0; ?>
											<ul>
											<? foreach($sectionheads as $sections):
												if($sections == "sectionheader"):
													?>
													<li>
														<a href="<?= get_permalink($menu_subitem->object_id, false) . get_post_meta($menu_subitem->object_id, "page_content_" . $counter . "_anchor_for_section", true) ?>"><?= format_text(get_post_meta($menu_subitem->object_id, "page_content_".$counter."_header_text", true)) ?></a>
													</li>
												<? endif; ++$counter; ?>
											<? endforeach; ?>
											</ul>
										<? endif; ?>
									</li>
								<? endforeach ?>
							</ul>
						</li>
				<? endforeach; ?>

			</ul>
			<hr>
			<h2>Quick Links</h2>
			<ul>
				<? foreach($utilmenu as $utilitem): ?>
					<li><a href="<?= $utilitem->url ?>"><?= format_text($utilitem->title) ?></a></li>
				<? endforeach; ?>
			</ul>
			<hr>
			<h2>Additional Services</h2>
			<ul>
				<? foreach($footermenu as $footeritem): ?>
					<li><a href="<?= $footeritem->url ?>"><?= format_text($footeritem->title) ?></a></li>
				<? endforeach; ?>
			</ul>
		</div>
	</div>
</div>

<?
	include(locate_template('partial-templates/social.php', false, false)); ?>
