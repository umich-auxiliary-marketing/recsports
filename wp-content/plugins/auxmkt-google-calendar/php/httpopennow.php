<?
include_once("datetime.php");
include_once('calendaroutput.php');

// Failsafe for a malformed request.
function output_open_now($calendar_id = "", $simple = true) {

	$file = dirname(__FILE__) . '/../output/' . $calendar_id . '.json';
	if(!file_exists($file)) {
		echo "";
		return;
	}

	$today = new DateTime("now");
	$tomorrow = new DateTime("tomorrow");

	$dataset = json_decode(file_get_contents($file));

	$today_day = $today->format('Y-m-d');
	$tomorrow_day = $tomorrow->format('Y-m-d');

	$today_events = (isset($dataset->events->$today_day) ? $dataset->events->$today_day : array());
	$tomorrow_events = (isset($dataset->events->$tomorrow_day) ? $dataset->events->$tomorrow_day : array());

	unset($dataset);

	$output = (object)array(
		"will_open" => false,
		"current_status" => false,
		"time" => "",
		"string" => ""
	);

	// First, look if there are any openings today (either currently open or coming).
	foreach($today_events as $event) {
		$event_start = new DateTime($event->start->date . " " . $event->start->time);
		$event_end = new DateTime($event->end->date . " " . $event->end->time);

		if($today > $event_start && $today < $event_end) {
			$output->current_status = "open";
			$output->time = format_nice_time($event_end);
			$output->string = "Open until " . $output->time;
			outputter($output, $simple);
			return;
		} else if($today < $event_start) {
			$output->will_open = "today";
			$output->time = format_nice_time($event_start);
			$output->string = "Closed, opens at " . $output->time;
			outputter($output, $simple);
			return;
		}
	}

	// Fall-through. No openings left today, so check tomorrow's times.
	foreach($tomorrow_events as $event) {
		$event_start = new DateTime($event->start->date . " " . $event->start->time);
		$event_end = new DateTime($event->end->date . " " . $event->end->time);

		if($today < $event_start) {
			$output->will_open = "tomorrow";
			$output->time = format_nice_time($event_start);
			$output->string = "Closed, opens tomorrow at " . $output->time;
			outputter($output, $simple);
			return;
		}
	}

	// Fall-through. Neither today nor tomorrow have any valid events, so simply
	// return with an indefinite "closed" for this specific time.
	$output->current_status = "closed";
	$output->string = "Closed";
	outputter($output, $simple);
}

function outputter($output, $simple) {
	if($simple) {
		echo $output->string;
	} else {
		echo json_encode($output);
	}
}

?>
