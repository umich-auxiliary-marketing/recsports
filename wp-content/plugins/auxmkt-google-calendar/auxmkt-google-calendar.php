<?php
/*
Plugin Name: Auxiliary Marketing Google Calendar
Plugin URI: http://studentlife.umich.edu
Description: Basic cacher of Google Calendar data to easily look up later.
Version: 1.0.0
Author: John Matula
Author URI: http://johnmatula.com
*/


if(is_admin()) {
	include_once('php/adminsettings.php');
}

include_once('php/cachebuilder.php');
include_once('php/calendaroutput.php');
include_once('php/httpopennow.php');

?>
