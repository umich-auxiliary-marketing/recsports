<? $menu = get_post_meta(wp_get_nav_menu_object('Main Menu')->term_id, 'nested_menu', true) ?>

<header class="container header">
  <div class="wrapper">
    <a class="header__logo" href="<?= esc_url(home_url('/')); ?>"><h1><?php bloginfo('name'); ?></h1></a>

		<nav class="navigation navigation--main">
			<? foreach($menu as $menu_item): ?>
				<div class="navigation__item">
					<div class="navigation__linkbit"><?= $menu_item->title ?></div>
					<div class="navigation__submenuwrap">
						<ul class="navigation__submenu navigation__submenu--<?= count($menu_item->children); ?>">
							<? foreach($menu_item->children as $menu_subitem): ?>
								<li class="navigation__subitem">
									<? $color = get_post_meta($menu_subitem->object_id, "section_color", true); ?>
									<a class="locallink--<?= $color ?>" href="<?= get_permalink($menu_subitem->object_id, false) ?>"><span><?= format_text($menu_subitem->title)?></span><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 5 13">
										<path fill="none" vector-effect="non-scaling-stroke" stroke-width="2" stroke-miterlimit="10" d="M1 12l3-5.499L1 1"></path>
									</svg></a>
									<?
									$sectionheads = get_post_meta($menu_subitem->object_id, "page_content", true);
									if($sectionheads && in_array("sectionheader", $sectionheads)):
										$counter = 0; ?>
										<ul class="navigation__subsubmenu">
										<? foreach($sectionheads as $sections):
											if($sections == "sectionheader"):
												?>
												<li class="navigation__subsubitem">
													<a href="<?= get_permalink($menu_subitem->object_id, false) . get_post_meta($menu_subitem->object_id, "page_content_" . $counter . "_anchor_for_section", true) ?>/"><?= format_text(get_post_meta($menu_subitem->object_id, "page_content_".$counter."_header_text", true)) ?></a>
												</li>
											<? endif; ++$counter; ?>
										<? endforeach; ?>
										</ul>
									<? endif; ?>
								</li>
							<? endforeach ?>
						</ul>
					</div>
				</div>
			<? endforeach; ?>
			<div class="navigation__item navigation__item--search">
				<a title="Search Recreational Sports" class="navigation__linkbit navigation__search" href="<?= get_bloginfo('url'); ?>/search/"><?= inject_svg('/dist/images/search.svg', false); ?></a>
			</div>
		</nav>

		<ul class="mobilenav navigation--mobile">
			<li class="mobilenav__link"><a href="<?= get_bloginfo('url'); ?>/facilities/"><?= inject_svg('/dist/images/location.svg', false); ?><span class="mobilenav__icontext">Facilities</span></a></li>
			<li class="mobilenav__link"><a href="<?= get_bloginfo('url'); ?>/hours/"><?= inject_svg('/dist/images/hours.svg', false); ?><span class="mobilenav__icontext">Hours</span></a></li>
			<li class="mobilenav__link mobilenav__link--menu"><a class="mobilenav__menulink" href="#"><?= inject_svg('/dist/images/menu.svg', false); ?><span class="mobilenav__icontext">Programs</span></a></li>
			<li class="mobilenav__link mobilenav__link--closemenu"><a class="mobilenav__closemenulink" href="#" data-originalscroll="0"><?= inject_svg('/dist/images/close-menu.svg', false); ?><span class="mobilenav__icontext">Close</span></a></li>
		</ul>

  </div>
</header>

<div class="darkener"></div>

<div class="mobilemenu">
	<div class="mobilemenu__grouping mobilemenu__grouping--padbottom">Quick Links</div>
	<div class="mobilemenu__utilities">
		<?php wp_nav_menu(array(
			'theme_location' => 'utilities_nav',
			'menu_class' => 'utilities__nav'));
		?>
	</div>
	<!-- foreach home page snippets as item;
		if grouping, spit it out;
		else if link,
			get its post title,
			primary button, and
			secondary button -->
	<!-- then, output a search link -->
	<!-- then, output the utilities links -->
	<?
	$hcount = 0;
	$hmeta = get_post_meta(get_option('page_on_front'), '', false); ?>

	<? foreach(unserialize($hmeta['snippets'][0]) as $partial): ?>
		<? $pcb = "snippets_" . $hcount . "_"; ?>
		<? if($partial == "grouping_header"): ?>
			<div class="mobilemenu__grouping"><?= $hmeta[$pcb . 'label'][0] ?></div>
		<? else: ?>
			<? $color = (get_post_meta($hmeta[$pcb . 'section_page_object'][0], "section_color", true) ?: "recsports"); ?>
			<div class="mobilemenu__mainlink"><a class="locallink--<?=$color?>" href="<?= get_permalink($hmeta[$pcb . 'section_page_object'][0]) ?>"><span><?= format_text(get_the_title($hmeta[$pcb . 'section_page_object'][0])) ?></span><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 5 13"><path fill="none" vector-effect="non-scaling-stroke" stroke-width="2" stroke-miterlimit="10" d="M1 12l3-5.499L1 1"/></svg></a></div>

			<div class="mobilemenu__secondarylinks">
				<? $link1 = get_link_array($hmeta, $pcb . "main_button"); ?>
				<? if(!$link1["blank"]): ?>
					<a href="<?= $link1["url"] ?>" target="<?= $link1["target"] ?>"><?= format_text($link1["title"]) ?></a>
				<? endif; ?>
				<? $link2 = get_link_array($hmeta, $pcb . "secondary_button"); ?>
				<? if($hmeta[$pcb . 'number_of_buttons'][0] === "two" && !$link2["blank"]): ?>
					<a href="<?= $link2["url"] ?>" target="<?= $link2["target"] ?>"><?= format_text($link2["title"]) ?></a>
				<? endif; ?>
			</div>


		<? endif; ?>
		<? ++$hcount; ?>
	<? endforeach; ?>
	<div class="mobilemenu__grouping mobilemenu__grouping--padbottom">Search</div>
	<div class="mobilemenu__search">
		<?php get_search_form(); ?>
	</div>
</div>
