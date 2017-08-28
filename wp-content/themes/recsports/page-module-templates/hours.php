<?
use Roots\Sage\Assets;
wp_enqueue_script('sage/calendar', Assets\asset_path('scripts/calendar.js'), array('jquery', 'sage/stickykit', 'sage/headroom', 'sage/throttle', 'sage/moment'), null, true);

$begin = new DateTime("now");
$end = new DateTime("now");

$di = new DateInterval("P4D");
$end->add($di);

// Though subsequent calls are loaded with JavaScript, load the initial call
// through PHP. That way, the page height is relatively stable for anchor jumps.
if(isset($lmeta['main_calendar_0_google_calendar_id'])) {
	auxmkt_google_calendar(array(
		"calendar_id" => $lmeta['main_calendar_0_google_calendar_id'][0],
		"secondary_calendar_id" => (isset($lmeta['secondary_calendar_0_google_calendar_id']) ? $lmeta['secondary_calendar_0_google_calendar_id'][0] : ""),
		"day_start" => $begin->format('Y-m-d'),
		"day_end" => $end->format('Y-m-d'),
		"view" => "hours",
		"enabled_views" => array("hours"),
		"first_label" => (isset($lmeta['main_calendar_0_label']) ? $lmeta['main_calendar_0_label'][0] : ""),
		"second_label" => (isset($lmeta['secondary_calendar_0_label']) ? $lmeta['secondary_calendar_0_label'][0] : "")
	));
}

?>
