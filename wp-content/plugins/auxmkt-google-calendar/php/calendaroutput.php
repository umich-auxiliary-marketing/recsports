<?
include_once("datetime.php");

function auxmkt_google_calendar( $args = array() ) {
	$cal = isset($cal) ? $cal : new AuxMkt_Google_Calendar( $args );
	return $cal;
}

// The AuxMkt_Google_Calendar class generates a calendar fragment based on the
// dates requested. Because it hooks into the cache system, it's speedy to
// use PHP to build the markup and blast it back into the page document.
class AuxMkt_Google_Calendar {
	function __construct($args = array()) {
		$defaults = array(
			'calendar_id' => 						"",
			'secondary_calendar_id' => 	"",
			'day_start' => 							datetime_tz("now", "Y-m-d"), 					// date in Y-m-d format (inclusive)
			'day_end' => 								datetime_tz("now", "Y-m-d"), 					// date in Y-m-d format (inclusive)
			'view' => 									"day", 																// [hours|day|halfweek|week|month]
			'wrapper_classes' => 				"",
			'first_label' =>						"",
			'second_label' =>						"",
			'time_zone' =>							"America/Detroit",
			'enabled_views' =>					array("hours","day","halfweek","week","month")
		);

		$this->args = $this->jvm_parse_args($args, $defaults);
		$a = $this->args;

		if(empty($a['calendar_id'])) {
			echo "<div class='paragraph--copy'><p>The calendar isn&rsquo;t properly configured. Site editors, check your Google Calendar ID.</p></div>";
			return;
		}

		// (1) Generate a DateInterval for the day(s) requested after type conversion.
		$this->datetime_begin = new DateTime($a['day_start']);
		$this->datetime_end = new DateTime($a['day_end']);
		$this->datetime_end2 = new DateTime($a['day_end']);
		$this->datetime_now = new DateTime("now");

		$tz = new DateTimeZone($this->args['time_zone']);

		$this->datetime_now->setTimezone($tz);

		$period = $this->generate_period($this->datetime_begin, $this->datetime_end);

		// (2) Download the event set as defined by the period. Though this is
		//     memory-heavy, on average, this parse-it-all solution only takes about
		//		 15 nanoseconds per 1 MB of JSON. Eh.
		//
		//		 This stinkos: since we are stuck on PHP 5.3 on Dev, Test, and Prod,
		//		 we will look to the Development server for output files. It will then
		//		 fall back on the guaranteed-to-be-there Production server for data.
		//     Though incredibly not ideal, this allows for more efficient data
		//     updating given the circumstances.
		$file = 'https://dev-recsports.studentlife.umich.edu/wp-content/plugins/auxmkt-google-calendar/output/' . $a['calendar_id'] . '.json';

		// Initialize session and set URL.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $file);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Get the response and close the channel.
		$response = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);

		$dataset = "";
		$days = "";
		$days2 = false;

		if($info['http_code'] !== 200 && $info['http_code'] !== 302) {
			echo "<!-- Attempting to use alternative calendar solution. -->";
			$file = dirname(__FILE__) . '/../output/' . $a['calendar_id'] . '.json';

			if(!file_exists($file)) {
				echo "<div class='paragraph--copy'><p>The data for this calendar is unavailable, but it will be soon. Try reloading the page in a few minutes.</p></div>";

				return;
			}

			$dataset = json_decode(file_get_contents($file));
		} else {
			$dataset = json_decode($response);
		}

		$event_format = $dataset->formatting_type;
		$days = $this->decode_events_for_period($period, $dataset);
		unset($dataset);

		if($a['secondary_calendar_id'] !== "") {

			$dataset2 = "";

			$file2 = 'https://dev-recsports.studentlife.umich.edu/wp-content/plugins/auxmkt-google-calendar/output/' . $a['secondary_calendar_id'] . '.json';

			// Initialize session and set URL.
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $file2);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			// Get the response and close the channel.
			$response = curl_exec($ch);
			$info = curl_getinfo($ch);

			if($info['http_code'] !== 200 && $info['http_code'] !== 302) {
				echo "<!-- Attempting to use alternative calendar solution for secondary. -->";

				$file2 = dirname(__FILE__) . '/../output/' . $a['secondary_calendar_id'] . '.json';
				if(!file_exists($file2)) {
					echo "The data for the secondary calendar is unavailable. Try reloading the page in a few minutes.";
					return;
				}

				$dataset2 = json_decode(file_get_contents($file2));
			} else {
				$dataset2 = json_decode($response);
			}

			$period2 = $this->generate_period($this->datetime_begin, $this->datetime_end2);
			$days2 = $this->decode_events_for_period($period2, $dataset2);
			unset($dataset2);
		}



		// (3) With the period generated, build the markup for each day captured.
		//     Containers are handled by theme and template files instead of this
		//     plugin. Output is directly echoed.
		$this->output_calendar_start($a['wrapper_classes'], $a['view'], $this->datetime_begin, $this->datetime_end, $a['enabled_views'], $a['calendar_id'], $a['secondary_calendar_id'], $a['first_label'], $a['second_label']);

		if($a['view'] == 'month') {
			$this->output_month_weeklies();
			$this->output_month_blank_message($days);
			$this->output_month_blanks($this->datetime_begin);
		} else if($a['view'] == 'hours') {
			$this->output_hours_headers($a['first_label'], $a['second_label']);
		}

		foreach($days as $date => $events){
			$this->output_wrapper_start($date, $events);
			switch($a['view']) {
				case 'hours':
					$this->output_hours_markup($date, $events, $a['first_label'], $a['second_label'], (isset($days2) ? $days2 : false));
					break;
				case 'day':
					$this->output_day_markup($date, $events, $event_format);
					break;
				case 'halfweek':
				case 'week':
					$this->output_week_dailies($date);
					$this->output_week_markup($date, $events, $event_format);
					break;
				case 'month':
					$this->output_month_dailies($date);
					$this->output_month_markup($date, $events, $event_format);
					break;
			}
			$this->output_wrapper_end();
		}

		$this->output_calendar_end();
	}

	protected function generate_period($start, $end) {
		$interval = new DateInterval('P1D');
		$end = $end->add($interval);
		$period = new DatePeriod($start, $interval, $end);

		return $period;
	}

	protected function decode_events_for_period($period, $dataset) {
		$json = new stdClass();

		foreach($period as $day) {
			$date = $day->format('Y-m-d');
			$json->$date = (isset($dataset->events->$date) ? $dataset->events->$date : array());
		}

		return $json;
	}

	protected function output_calendar_start($wrapper_classes, $view, $start_day, $end_day, $enabled_views, $calendar_id, $second_calendar_id, $firstlabel, $secondlabel) {
		$di = new DateInterval("P1D");
		$end_day_tester = new DateTime($end_day->format("Y-m-d"));

		$second_month = ($start_day->format("M") === $end_day_tester->sub($di)->format("M") ? "" : $end_day->format("M "));
		?>
		<div class="calendar__wrap calendar--<?= $view ?> <?= $wrapper_classes ?>" <?= $this->generate_javascript_data($view, $enabled_views, $start_day) ?> data-calendarid="<?= $calendar_id ?>" data-secondcalendarid="<?= $second_calendar_id ?>" data-firstlabel="<?= $firstlabel ?>" data-secondlabel="<?= $secondlabel ?>">
			<div class="calendar__controls">
				<div class="calendar__setdate">
					<div class="buttongroup">
						<? $this->output_calendar_increment_button(true, $start_day, $view); ?>
						<? $this->output_calendar_increment_button(false, $start_day, $view); ?>
					</div>
					<button class="calendar__gototoday" title="Go to today&rsquo;s date, <?= $this->datetime_now->format('F j') ?>" data-behavior="today" data-date="<?= $this->datetime_now->format('Y-m-d'); ?>">Today</button>
				</div>
				<div class="calendar__title">
					<? if($view === "day"): ?>
						<span class="calendar__title--full"><?= $start_day->format("F j"); ?></span>
						<span class="calendar__title--mobile"><?= $start_day->format("M j"); ?></span>
					<? elseif($view === "hours" || $view === "halfweek" || $view === "week"): ?>
						<?= $start_day->format("M j") . ' &ndash; ' . $second_month . $end_day->sub($di)->format("j"); ?>
					<? elseif($view === "month"): ?>
						<span class="calendar__title--full"><?= $start_day->format("F Y"); ?></span>
						<span class="calendar__title--mobile"><?= $start_day->format("M Y"); ?></span>
					<? endif ?>
				</div>
				<? if(count($enabled_views) > 1): ?>
				<div class="calendar__setview buttongroup">
					<?
						$this->output_calendar_view_button("day", "Day", $view, $enabled_views);					$this->output_calendar_view_button("halfweek", "4 Days", $view, $enabled_views);
						$this->output_calendar_view_button("week", "Week", $view, $enabled_views);
						$this->output_calendar_view_button("month", "Month", $view, $enabled_views);
					?>
				</div>
				<? endif; ?>
			</div>
			<div class="calendar__view">
		<?
	}

	protected function generate_javascript_data($view, $enabled_views, $date) {
		$output = " ";

		$output .= "data-enabled-views='[";
		foreach($enabled_views as $ev) {
			$output .= "\"" . $ev . "\",";
		}
		$output = (substr($output, 0, -1)) . "]' ";

		$output .= "data-date='" . $date->format("Y-m-d") . "' ";
		$output .= "data-view='" . $view . "'";

		return $output;
	}

	protected function output_calendar_view_button($view, $button_label, $selected_view, $enabled_views) {
		if(in_array($view, $enabled_views)): ?>
			<? $selected = ($selected_view === $view ? "calendar__button--selected button--selected" : ""); ?>
			<button title="Switch to <?=$button_label?> view" data-behavior="viewchange" data-view="<?= $view ?>" class="calendar__button calendar__viewchange calendar__button--<?= $view ?> <?= $selected ?>"><?= $button_label ?></button>
		<? endif;
	}

	protected function output_calendar_increment_button($is_prev, $date, $view) {

		$button_label = ($is_prev ? '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 6 17"><path fill="none" stroke="#000" stroke-width="2" vector-effect="non-scaling-stroke" stroke-miterlimit="10" d="M5 16L1 8.5 5 1"/></svg>' : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 6 17"><path fill="none" vector-effect="non-scaling-stroke" stroke="#000" stroke-width="2" stroke-miterlimit="10" d="M1 16l4-7.5L1 1"/></svg>');
		$button_class = "calendar__incrementer--" . ($is_prev ? "prev" : "next");
		$increment_date = new DateTime($date->format('Y-m-d'));
		$increment_interval = "";

		switch($view) {
			case "hours":
				$increment_interval = "P5D";
				break;
			case "day":
				$increment_interval = "P1D";
				break;
			case "halfweek":
				$increment_interval = "P4D";
				break;
			case "week":
				$increment_interval = "P7D";
				break;
			case "month":
				$increment_interval = "P1M";
				break;
		}

		$di = new DateInterval($increment_interval);
		if($is_prev) {
			$increment_date->sub($di);
		} else {
			$increment_date->add($di);
		}

		$button_title = ($is_prev ? 'Go back to ' : 'Go forward to ') . $increment_date->format('F j');

		?>
			<button title="<?=$button_title?>" class="calendar__incrementer <?= $button_class ?>" data-behavior="increment" data-date="<?=$increment_date->format('Y-m-d')?>"><?= $button_label ?></button>
		<?
	}

	protected function output_calendar_end() {
		?>
			</div>
			<div class="calendar__details">
				<div class="calendar__detailscontent">
				</div>
			</div>
		</div>
		<?
	}

	protected function output_wrapper_start($date, $events) {
		$today = $this->datetime_now->format('Y-m-d');
		$is_today = ($today === $date) ? "calendar__daywrap--today" : "";
		?>
		<div class="calendar__daywrap <?= $is_today ?> <?= count($events) ?: "calendar__daywrap--empty" ?>">
		<?
	}

	protected function output_wrapper_end() {
		?>
		</div>
		<?
	}

	protected function output_week_dailies($date) {
		$dt = new DateTime($date);
		?>
			<div class="calendar__dayheader">
				<span class="dayheader__weekday"><?= $dt->format('D'); ?></span>
				<span class="dayheader__number"><?= $dt->format('j'); ?></span>
			</div>
		<?
	}

	protected function output_month_weeklies() {
		?>
			<?
			$days = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");

			for($i = 0; $i < 7; ++$i) {
				?>
				<div class="calendar__dayheader <?=$i===6 ? 'calendar__dayheader--lastday' : ''?>"><?= $days[$i] ?></div>
				<?
			}
			?>
		<?
	}

	protected function output_month_blanks($date) {
		$number = intval($date->format("N")) % 7;

		for($i = 1; $i <= $number; ++$i) {
			?>
			<div class="calendar__blank"></div>
			<?
		}
	}

	protected function output_month_blank_message($days) {
		foreach($days as $date => $events) {
			foreach ($events as $event) {
				if(count($event)) {
					return;
				}
			}
		}

		?>
		<div class="calendar__emptymonth">
			There are no events scheduled this month.
		</div>
		<?
	}

	protected function output_month_dailies($date) {

	}

	protected function output_hours_headers($first_label, $second_label = "") {
		?>
		<div class="calendar__hours-calheader">
			<div class="calendar__hours-header"></div>
			<div class="calendar__hours-set"><?= $first_label ?></div>
			<? if($second_label !== ""): ?>
				<div class="calendar__hours-set"><?= $second_label ?></div>
			<? endif; ?>
		</div>
		<?
	}

	protected function output_hours_markup($date, $events, $first_label = "", $second_label = "", $second_set = false) {
		?>
		<div class="calendar__hours-header">
			<?
			$output = format_datetime($date, "D, M j");
			if($output === format_datetime("now", "D, M j")): ?>
				<?=format_datetime($date, "D, M")?> <span class="calendar__daylabel--today"><?=format_datetime($date, "j")?></span>
			<? else: ?>
				<?= $output; ?>
			<? endif; ?>
		</div>
		<div class="calendar__hours-set">
			<span class="calendar__hours-mobilelabel"><?= $first_label ?></span>
			<?
			if(!$events) {
				?>
				<span class="calendar__closed">Closed</span>
				<?
			}
			foreach($events as $event) {
				?>
				<div class="calendar__hours-range">
					<?= format_nice_time($event->start->time); ?> &ndash; <?= format_nice_time($event->end->time); ?>
				</div>
				<?
			}
			?>
		</div>
		<?

		if($second_set) {
			?>
			<div class="calendar__hours-set">
				<span class="calendar__hours-mobilelabel"><?= $second_label ?></span>
				<?
				if(!isset($second_set->{$date}) || !count($second_set->{$date})) {
					?>
					<span class="calendar__closed">Closed</span>
					<?
				}
				if(isset($second_set->{$date})) {
					foreach($second_set->{$date} as $second_event) {
						?>
						<div class="calendar__hours-range">
							<?= format_nice_time($second_event->start->time); ?> &ndash; <?= format_nice_time($second_event->end->time); ?>
						</div>
						<?
					}
				}
				?>
			</div>
			<?
		}
	}

	protected function output_day_markup($date, $events, $event_format) {
		if(count($events) === 0) {
			?>
			<div class="calendar__noevents">There are no scheduled events for today.</div>
			<?
			return;
		}

		foreach($events as $event) {
			if($event->is_all_day) continue;

			$this->output_description_fragment($event, $event_format);
		}
	}

	protected function output_week_markup($date, $events, $event_format) {
		if(count($events) === 0) {
			?>
			<div class="calendar__noevents">No events</div>
			<?
			return;
		}

		foreach($events as $event) {
			if($event->is_all_day) continue;

			$is_cancelled = $event->is_cancelled ? "calendar__event--cancelled" : "";
			?>
			<a class="calendar__event <?= $is_cancelled; ?>" href="<?= $event->url ?>" target="_blank">
				<div class="event__attributes">
					<span class="event__time"><?= format_nice_time($event->start->time); ?></span><span class="event__location"><?= isset($event->title_components[1]) ? $event->title_components[1] : "" ?></span>
				</div>
				<div class="event__title"><?= $event->title_components[0] ?></div>
			</a>
			<?
			$this->output_description_fragment($event, $event_format);
		}
	}

	protected function output_month_markup($date, $events, $event_format) {
		?>
		<div class="calendar__daynumberheader">
			<span class="dayheader__numbermonth"><?= format_datetime($date, 'D, M'); ?></span>
			<span class="dayheader__number">
				<?= format_datetime($date, 'j'); ?>
			</span>
		</div>
		<?
		foreach($events as $event) {
			$is_cancelled = $event->is_cancelled ? "calendar__event--cancelled" : "";
			?>
			<a class="calendar__event <?= $is_cancelled; ?>" href="<?= $event->url ?>" target="_blank">
				<div class="event__title"><?= $event->title_components[0] ?></div>
			</a>
			<?
			$this->output_description_fragment($event, $event_format);
		}
	}

	protected function output_description_fragment($event, $event_format) {
		$is_cancelled = $event->is_cancelled ? "fullevent--cancelled" : "";

		?>
		<div class="fullevent <?= $is_cancelled ?>">
			<a class="fullevent__close" href="#"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M28 6.1L25.9 4 16 13.9 6.1 4 4 6.1l9.9 9.9L4 25.9 6.1 28l9.9-9.9 9.9 9.9 2.1-2.1-9.9-9.9"/></svg></a>
			<div class="fullevent__header">
				<h3 class="fullevent__title"><a href="<?= $event->url ?>" target="_blank"><?= $event->title_components[0] ?></a></h3>
				<div class="fullevent__tags">
					<? if($event->is_cancelled): ?>
						<div class="fullevent__tag">Cancelled</div>
					<? endif; ?>
					<? for($i = 2; $i < count($event->title_components); ++$i): ?>
						<div class="fullevent__tag"><?=$event->title_components[$i]?></div>
					<? endfor; ?>
				</div>
			</div>

			<? if($event_format === "groupx"): ?>
			<div class="fullevent__attributes">
				<dl class="fullevent__time">
					<dt>Time</dt>
					<dd><?= format_nice_time($event->start->time) . " &ndash; " . format_nice_time($event->end->time); ?></dd>
				</dl>
				<dl class="fullevent__location">
					<dt>Location &amp; Room</dt>
					<dd><?= (isset($event->title_components[1]) ? $event->title_components[1] . ", " : "") . $this->fallback($event->description_pairs, "location", "&mdash;") ?></dd>
				</dl>
				<!--<dl class="fullevent__intensity">
					<dt>Intensity</dt>
					<dd>&mdash;</dd>
				</dl>-->
				<dl class="fullevent__instructor">
					<dt>Instructor</dt>
					<dd><?= $this->fallback($event->description_pairs, "instructor", "&mdash;") ?></dd>
				</dl>
			</div>
			<p class="fullevent__description">
				<?= $event->description_pairs->paragraph ?>
			</p>
		<? elseif($event_format === "oatrips"): ?>
			<div class="fullevent__daterange">
				<?= format_datetime($event->start->date, "l, M d") ?> at <?= format_nice_time($event->start->time) ?> &ndash; <?= format_datetime($event->end->date, "l, M d") ?> at <?= format_nice_time($event->end->time) ?>
			</div>
			<div class="fullevent__paragraph paragraph--copy">
				<p><?= $event->description ?></p>
			</div>
		<? else: ?>
			<div class="fullevent__paragraph paragraph--copy">
				<p><?= $event->description ?></p>
			</div>
		<? endif; ?>
				</div>
		<?
	}

	protected function jvm_parse_args( $args, $defaults = '' ) {
		if ( is_object( $args ) )
			$r = get_object_vars( $args );
		elseif ( is_array( $args ) )
			$r =& $args;
		else
			parse_str( $args, $r );

		if ( is_array( $defaults ) )
			return array_merge( $defaults, $r );
		return $r;
	}

	protected function fallback($known_obj, $testing, $fallback) {
		if(isset($known_obj->$testing)) {
			return $known_obj->$testing;
		} else {
			return $fallback;
		}
	}
}

?>
