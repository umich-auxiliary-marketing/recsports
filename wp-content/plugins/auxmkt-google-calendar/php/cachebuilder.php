<?
include_once("datetime.php");

class AuxMkt_Google_Calendar_Cacher {
	function __construct($args = array()) {
		return $this;
	}

	static function build_calendar_cache($calendar_id, $format_type = "") {
		$tick = microtime(true);
		require_once dirname(__FILE__) . '/google-api-php-client/vendor/autoload.php';

		// Set up the Google API client with the proper credentials and scopes.
		// Since the credentials are shared amongst all, be conscious of quotas.
		$client = new Google_Client();
		$client->setDeveloperKey('KeyGoesHere');
		$client->setAuthConfig(dirname(__FILE__) . '/../credentials/UmichRecSports-c8549514cafc.json');
		$client->setScopes('https://www.googleapis.com/auth/calendar.readonly');

		// Create the Google Calendar, hooking into the appropriate calendar as
		// specified by the supplied argument.
		$cal = new Google_Service_Calendar($client);

		// Set the parameters to download the events. Generous mininum and maximum bounds
		// are specified since this plugin is designed to run asynchronously (so download
		// as much as you want and let the client quickly look up the bits later).
		//$time_min = (new_datetime('now'))->sub(new DateInterval('P3M'));
		//$time_max = (new DateTime('now'))->add(new DateInterval('P1Y3M'));

		$params = array(
			'singleEvents' => true,
			'orderBy' => 'startTime',
			'timeMin' => addsub_datetime('now', false, 'P3M', 'Y-m-d\TH:i:sP'),
			'timeMax' => addsub_datetime('now', true, 'P1Y3M', 'Y-m-d\TH:i:sP')
		);

		// Get those events.
		$events_object = $cal->events->listEvents($calendar_id, $params);
		$calendar_updated_dt = new DateTime($events_object->updated);
		$calendar_updated_dt->setTimezone(new DateTimeZone($events_object->timeZone));
		$calendar_updated = datetime_tz($events_object->updated, 'Y-m-d H:i:s', $events_object->timeZone);

		// After the first download of events, we can tell when the calendar was
		// last updated. If a set of data exists, and the cache's update time is
		// newer than the calendar's update time, we can safely exit here and save
		// ourselves some API calls and CPU cycles.
		$file = dirname(__FILE__) . '/../output/' . $calendar_id . '.json';
		$current_cache = file_exists($file) ? file_get_contents($file) : false;
		$current_json = json_decode($current_cache);
		$dirty_check_dt = false;

		if($current_json) {
			$dirty_check_dt = new DateTime($current_json->update_times->dirty_check, new DateTimeZone($events_object->timeZone));
		}

		if($current_cache && $dirty_check_dt > $calendar_updated_dt) {
			$current_json->update_times->dirty_check = datetime_tz('now', 'Y-m-d H:i:s', $events_object->timeZone);
			file_put_contents($file, json_encode($current_json));

			return;
		}

		// Pack the events into a friendly object for direct access. This is what is
		// written to the JSON file that is then read in by a JavaScript method.
		$output_calendar = (object)array(
		"calendar_name" => $events_object->summary,
		"formatting_type" => $format_type,
		"update_times" => array(
			"calendar" => $calendar_updated,
			"cache" => datetime_tz('now', 'Y-m-d H:i:s', $events_object->timeZone),
			"dirty_check" => datetime_tz('now', 'Y-m-d H:i:s', $events_object->timeZone)
		),
		"timezone" => $events_object->timeZone,
		"events" => array(),
		"statistics" => (object)array(
			"pages_cached" => 0,
			"total_events" => 0,
			"processing_time_in_ms" => 0
		)
		);

		while(true) {
			foreach ($events_object->getItems() as $event) {

				// If the event is describing a closure, omit it.
				if(strpos(strtolower($event->summary), "closed") !== false) {
					continue;
				}

				// Transform Google's data into our own friendly data type.
				 $event_object = (object)array(
					"id" => $event->id,
					"url" => $event->htmlLink . "&ctz=" . $output_calendar->timezone,
					"title" => $event->summary,
					"description" => $event->description,
					"title_components" => array(),
					"description_pairs" => array(),
					"location" => $event->location,
					"start" => (object)array(
						"date" => format_datetime($event->start->dateTime ?: $event->start->date, 'Y-m-d'),
						"time" => format_datetime($event->start->dateTime ?: $event->start->date, 'H:i:s')
					),
					"end" => (object)array(
						"date" => format_datetime($event->end->dateTime ?: $event->end->date, 'Y-m-d'),
						"time" => format_datetime($event->end->dateTime ?: $event->end->date, 'H:i:s')
					),
					"is_all_day" => empty($event->start->dateTime),
					"is_cancelled" => false,
					"is_repetition" => false
				);

				// Pick apart string components, to (1) search for the keyword "cancelled"
				// and remove it from the list, and (2) maintain a stable order of parts.
				// Though not all calendars will care about this, it's better to do it now.
				$string_components = explode("-", $event_object->title);

				foreach($string_components as $component) {
					if(strpos(strtolower($component), "cancel") === false) {
						$event_object->title_components[] = trim($component);
					} else {
						$event_object->is_cancelled = true;
					}
				}

				// Similarly, break apart the description parts: first by line break, and then
				// by hyphens, if any are available.
				$string_components = explode("\n", $event_object->description);

				foreach($string_components as $component) {
					$line_components = explode('-', $component);
					$possible_key = strtolower(trim($line_components[0]));

					if($possible_key === 'instructor' || $possible_key === 'location') {
						$event_object->description_pairs[$possible_key] = trim($line_components[1]);

						// Catch things like "Location - Multi-purpose Room"
						for($i = 2; $i < count($line_components); ++$i) {
							$event_object->description_pairs[$possible_key] .= "-" . trim($line_components[$i]);
						}
					} else if(trim($component)) {
						$event_object->description_pairs['paragraph'] = trim($component);
					}
				}
// TODO: mutliday flagging
				// Create a date time range so that multi-day events appear correctly.
				$interval = new DateInterval('P1D');
				$start = new DateTime($event->start->dateTime ?: $event->start->date);
				$end = new DateTime($event->end->dateTime ?: $event->end->date);
				$period = new DatePeriod($start, $interval, $end);
				$ismultiday = false;

				// Blast the finished event array into the calendar object. For multi-day
				// events, blast into each one. Deep copies need to be made so that
				// in-progress loops can have varied event objects.
				foreach($period as $day) {
					$date = $day->format('Y-m-d');
					$event_object->is_repetition = $ismultiday;
					$output_calendar->events[$date][] = (object)(array)$event_object;
					++$output_calendar->statistics->total_events;

					$ismultiday = true;
				}
			}

			// Just so devs know, tally the pages that were looked up.
			$output_calendar->statistics->pages_cached++;

			// Exit the loop if no more "next page" tokens are found.
			if(!$events_object->nextPageToken) break;

			// If a next page token exists, that means there are more events to capture
			// but Google's API limited us from grabbing them all at once. Use the page
			// token to determine where to start up again and download the next set.
			$params["pageToken"] = $events_object->nextPageToken;
			$events_object = $cal->events->listEvents($calendar_id, $params);
		}

		// Sort the entire thing by date → time → title → location. This expensive
		// operation is done here to guarantee a stable order on the frontend.
		foreach($output_calendar->events as &$day) {
			usort($day, function($a, $b){
				if($a->start->time === $b->start->time) {
					if($a->title_components[0] === $b->title_components[0]) {
						if(!isset($a->title_components[1])) {
							return 1;
						}
						return strcasecmp($a->title_components[1], $b->title_components[1]);
					}
					return strcasecmp($a->title_components[0], $b->title_components[0]);
				}
				return strcasecmp($a->start->time, $b->start->time);
			});
		}

		$tock = microtime(true);

		// Calculate processing time.
		$output_calendar->statistics->processing_time_in_ms = ($tock - $tick) * 1000;

		// Write the contents of this file to JSON so that it can be transformed.
		file_put_contents($file, json_encode((object)$output_calendar));

		return;
	}

	static function sort_day_events(&$events, $props) {
		usort($events, function($a, $b){
			$a_time = new DateTime($a->start->time);
			$b_time = new DateTime($b->start->time);

			if($a_time === $b_time) {
				if($a->$props[1] === $b->$props[1]) {
					return strcasecmp($a->$props[2], $b->$props[2]);
				}
				return strcasecmp($a->$props[1], $b->$props[1]);
			}
			return ($a_time < $b_time) ? -1 : 1;
		});
	}
}

?>
