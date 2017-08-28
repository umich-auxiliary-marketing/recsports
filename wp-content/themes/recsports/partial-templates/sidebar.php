<aside class="sidebar">
	<ul class="sidebar__contents">
		<?
			if(isset($meta["short_title"]) && $meta["short_title"][0]) { $title = format_text($meta["short_title"][0]); }
			else if(isset($sidebartitle)) { $title = format_text($sidebartitle); }
			else { $title = format_text(get_the_title()); }
		?>

		<li class="sidebar__header active"><a href="#_top" class="themed--pseudo sidebar__headerlink"><?= $title ?></a><span class="sidebar__directions">Page contents</span></li>
	<? $count = 0; $output = "" ?>
	<? if(isset($meta["page_content"]) && $meta["page_content"][0]): ?>
		<? foreach(unserialize($meta["page_content"][0]) as $partial):
			if($partial == "sectionheader") {
				$output .= '<li class="sidebar__link"><a class="themed--pseudo sidebar__contentlink" data-scroll="#' . $meta["page_content_" . $count . "_anchor_for_section"][0] . '" href="' . get_permalink($post) . "#" . strtolower($meta["page_content_" . $count . "_anchor_for_section"][0]) . '">' . format_text($meta["page_content_" . $count . "_header_text"][0]) . '</a></li>';
			}

			$count++;
		endforeach;
		?>
	<? elseif(isset($sidebarlinks)): ?>
		<? foreach($sidebarlinks as $linkanchor => $linklabel): ?>
			<? $output .= '<li class="sidebar__link"><a class="themed--pseudo sidebar__contentlink" data-scroll="#' . $linkanchor . '" href="' . get_permalink($post) . "#" . $linkanchor . '">' . format_text($linklabel) . '</a></li>'; ?>
		<? endforeach; ?>
	<? endif; ?>

	<? echo($output) ?>

	</ul>
	<a href="#" title="Toggle section navigation" class="sidebar__mobiletarget ada--hidden-text js--ignore-activenav">Toggle section navigation</a>
	<div class="sidebar__mobilefragment">
		<ul class="sidebar__mobilecontents">
			<li class="sidebar__mobiletop"><a href="#_top" class="themed--pseudo sidebar__contentlink sidebar__contentlink--top">Scroll to Top</a></li>
			<?= $output ?>
		</ul>
	</div>
	<? $skyscrapers = array(); ?>
	<? if(isset($meta["skyscrapers"]) && $meta["skyscrapers"][0]) {
		foreach(unserialize($meta["skyscrapers"][0]) as $pending) {
			if(get_post_status($pending) === 'publish') {
				$skyscrapers[] = $pending;
			}
		}
	}
	?>

	<? if(count($skyscrapers)): ?>
		<ul class="sidebar__skyscrapers">
			<? foreach($skyscrapers as $skyscraper): ?>
			<? if(get_post_status($skyscraper) === 'publish'): ?>
				<? $skymeta = get_post_meta($skyscraper); ?>
				<li class="sidebar__skyscraper__item">
					<? $skylink = get_link_array($skymeta, "link_url"); ?>
					<a class="sidebar__skyscraper__link" <? if($skymeta["include_a_link"][0]): ?>href="<?= $skylink["url"] ?>" target="<?= $skylink["target"] ?>"<? endif; ?>>
						<img class="sidebar__skyscraper__image" src="<?= wp_get_attachment_url($skymeta['image'][0]) ?>" ?>
					</a>
				</li>
			<? endif; ?>
		<? endforeach; ?>
	<? endif; ?>
</aside>
