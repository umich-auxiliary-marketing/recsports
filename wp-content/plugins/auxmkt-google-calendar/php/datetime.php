<?
	// PHP 5.3 friendly functions for DateTime generation.
	function format_datetime($string, $format) {
		$dt = new DateTime($string);

		return $dt->format($format);
	}

	function datetime_tz($string, $format, $timezone = "America/Detroit") {
		$dt = new DateTime($string);
		$tz = new DateTimeZone($timezone);

		$dt->setTimezone($tz);

		return $dt->format($format);
	}

	function addsub_datetime($string, $add = true, $interval, $format) {
		$dt = new DateTime($string);
		$di = new DateInterval($interval);

		if($add) {
			$dt->add($di);
		} else {
			$dt->sub($di);
		}

		return $dt->format($format);
	}

	function format_nice_time($time) {
		if(!is_a($time, "DateTime")) $time = new DateTime($time);

		$output = $time->format('g:i\&\n\b\s\p\;a');

		if($output === "12:00&nbsp;am") return "12&nbsp;midnight";
		if($output === "12:00&nbsp;pm") return "12&nbsp;noon";

		return $output;
	}
?>
