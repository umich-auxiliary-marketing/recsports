<?
use Roots\Sage\Assets;
wp_enqueue_script('sage/pin-secondary', Assets\asset_path('scripts/jquery.pin-secondary.js'), array('jquery'), null, true);
wp_enqueue_script('sage/calendar', Assets\asset_path('scripts/calendar.js'), array('jquery', 'sage/stickykit', 'sage/headroom', 'sage/throttle', 'sage/pin-secondary','sage/sisyphus'), null, true);


$begin = new DateTime("now");
$end = new DateTime("now");
$begin->setTime(0, 0, 0);
$end->setTime(0, 0, 0);


// Day is treated as the default--no changes are necessary.

if($meta[$pcb . "default_view"][0] === "halfweek") {
	$di = new DateInterval("P3D");
	$end->add($di);
} else if($meta[$pcb . "default_view"][0] === "week") {
	$days_from_sunday = intval($begin->format("N")) % 7;

	if($days_from_sunday > 0) {
		$di = new DateInterval("P" . $days_from_sunday . "D");
		$begin->sub($di);
		$end->sub($di);
	}

	$di2 = new DateInterval("P6D");
	$end->add($di2);
} else if($meta[$pcb . "default_view"][0] === "month") {
	$di = new DateInterval("P1D");
	$begin->setDate(intval($begin->format('Y')), intval($begin->format('m')), 1);
	$end->setDate(intval($begin->format('Y')), (intval($begin->format('m')) + 1), 1);
	$end->sub($di);
}

// Though subsequent calls are loaded with JavaScript, load the initial call
// through PHP. That way, the page height is relatively stable for anchor jumps.
auxmkt_google_calendar(array(
	"calendar_id" => $meta[$pcb . "google_calendar_id"][0],
	"day_start" => $begin->format('Y-m-d'),
	"day_end" => $end->format('Y-m-d'),
	"view" => $meta[$pcb . "default_view"][0],
	"enabled_views" => unserialize($meta[$pcb . "enabled_views"][0])
));

?>
