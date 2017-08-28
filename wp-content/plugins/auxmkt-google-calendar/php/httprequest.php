<?
include_once("datetime.php");
include_once('calendaroutput.php');

if(!isset($_GET["date"])) {
	$_GET["date"] = format_datetime("now", "Y-m-d");
}

// Failsafe for a malformed request.
if(!isset($_GET["view"]) || !isset($_GET["calendar_id"]) || !isset($_GET["enabled_views"])) {
	echo "false";
	return;
}

header('Content-Type: text/html; charset=utf-8');

$begin = new DateTime($_GET["date"]);
$end = new DateTime($_GET["date"]);

// Day is treated as the default--no changes are necessary.

if($_GET["view"] === "hours") {
	$di = new DateInterval("P4D");
	$end->add($di);
} else if($_GET["view"] === "halfweek") {
	$di = new DateInterval("P3D");
	$end->add($di);
} else if($_GET["view"] === "week") {
	$days_from_sunday = intval($begin->format("N")) % 7;

	if($days_from_sunday > 0) {
		$di = new DateInterval("P" . $days_from_sunday . "D");
		$begin->sub($di);
		$end->sub($di);
	}

	$end->add(new DateInterval("P6D"));
} else if($_GET["view"] === "month") {
	$di = new DateInterval("P1D");
	$begin->setDate(intval($begin->format('Y')), intval($begin->format('m')), 1);
	$end->setDate(intval($begin->format('Y')), (intval($begin->format('m')) + 1), 1);
	$end->sub($di);
}

// Though subsequent calls are loaded with JavaScript, load the initial call
// through PHP. That way, the page height is relatively stable for anchor jumps.
auxmkt_google_calendar(array(
	"calendar_id" => $_GET["calendar_id"],
	"secondary_calendar_id" => isset($_GET["second_calendar_id"]) ? $_GET["second_calendar_id"] : "",
	"day_start" => $begin->format('Y-m-d'),
	"day_end" => $end->format('Y-m-d'),
	"view" => $_GET["view"],
	"first_label" => isset($_GET["firstlabel"]) ? $_GET["firstlabel"] : "",
	"second_label" => isset($_GET["secondlabel"]) ? $_GET["secondlabel"] : "",
	"enabled_views" => $_GET["enabled_views"]
));

?>
