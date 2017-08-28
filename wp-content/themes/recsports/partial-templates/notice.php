<?
use Roots\Sage\Assets;
wp_enqueue_script('sage/notice', Assets\asset_path('scripts/notice.js'), array('jquery'), null, true);

if(!$nmeta || !is_numeric($notice)) { $notice = get_the_ID(); $nmeta = get_post_meta($notice, '', false);}
?>

<?
$icons = array(
	"level_20" => "notice-happy.svg",
	"level_40" => "notice-info.svg",
	"level_60" => "notice-alert.svg",
	"level_80" => "notice-alert.svg",
	"level_100" => "notice-stop.svg"
);

$hreftext = "";
if($nmeta["action_of_notice_when_clicked"][0] === "expand") {
	$hreftext = "href='" . get_permalink($notice) . "'";
} else if($nmeta["action_of_notice_when_clicked"][0] === "link") {
	$nlink = get_link_array($nmeta, "link_url");
	$hreftext = "href='" . $nlink["url"] . "' target='" . $nlink["target"] . "'";
}
?>

<a class="notice notice--<?= $nmeta['kind_of_notice'][0] ?> notice--action-<?= $nmeta["action_of_notice_when_clicked"][0] ?>" <?= $hreftext; ?>>
	<div class="notice__icon">
		<?= inject_svg("/dist/images/" . $icons[$nmeta["kind_of_notice"][0]], false) ?>
	</div>
	<div class="notice__message">
		<p>
			<span class="notice__crux"><?= format_text($nmeta["main_message"][0]) ?></span><?
				if(isset($nmeta["additional_details"]) && trim($nmeta["additional_details"][0]) !== ""):
			?> <span class="notice__details"><?= format_text($nmeta["additional_details"][0]) ?></span><? endif; ?><? if($nmeta['action_of_notice_when_clicked'][0] === "expand"): ?><svg class="notice__expander" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 5 13">
				<path fill="none" vector-effect="non-scaling-stroke" stroke-width="2" stroke="#3972bd" stroke-miterlimit="10" d="M1 12l3-5.499L1 1"></path>
			</svg>
			<? endif; ?>
		</p>

		<? if($nmeta["action_of_notice_when_clicked"][0] === "expand"): ?>
			<div class="notice__description">
				<?= format_wysiwyg($nmeta["extended_description"][0]) ?>
			</div>
		<? endif; ?>

		<? if($nmeta['kind_of_notice'][0] === 'level_80' || $nmeta['kind_of_notice'][0] === 'level_100' || (isset($nmeta['show_timestamp']) && $nmeta['show_timestamp'][0])): ?>
		<div class="notice__timestamp">
			Updated <?= time2str(get_post_modified_time('U', null, $notice)); ?>
		</div>
		<? endif; ?>
	</div>
</a>
