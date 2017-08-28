<?php
/**
 * Plugin Name: Auxiliary Marketing Formatting
 * Plugin URI: http://studentlife.umich.edu
 * Description: Functions used widely across the site to ensure a consistent formatting of text and data.
 * Author: John Matula
 * Version: 1.0.0
 * Author URI: http://studentlife.umich.edu
 */


// Set up a link parser.
function get_link_array(&$meta, $inputstring = "") {
	$output = "";

	// A value of (string) "1" indicates a blank, serialized array.
	if(isset($meta[$inputstring]) && $meta[$inputstring][0] !== "1") {
		$output = unserialize($meta[$inputstring][0]);
		$output["target"] = ($output["target"] ? "_blank" : "_self");
	} else {
		$output = array("url" => "", "title" => "", "target" => "_self");
	}

	$output["blank"] = empty($output["url"]);

	return $output;
}


// Include some core-critical meta data parser functions.
function format_text($input = "") {
	return str_replace(array('U-M', 'Group-X'), array('U‑M', 'Group‑X'), wptexturize($input));
}

function format_price($input = "0") {
	$decimal = strpos($input, ".");

	if($decimal !== false && (intval(substr($input, ++$decimal)) !== 0)) {
		return number_format($input, 2, ".", ",");
	} else {
		return number_format($input, 0, ".", ",");
	}
}

function format_classname($input = "") {
	return strtolower(str_replace(array(' ', '\'', '"', '.', '!', '?', ','), array('_', '', '', '', '', '', ''), $input));
}

function format_textarea($input = "") {
	return str_replace(array('U-M', 'Group-X'), array('U‑M', 'Group‑X'), wptexturize(wpautop($input)));
}

function format_hero_textarea($input = "") {
	return str_replace(array('U-M', 'Group-X', '<p>', '</p>'), array('U‑M', 'Group‑X', '', ''), wptexturize(wpautop($input)));
}

function format_wysiwyg($input = "") {
	return do_shortcode(str_replace(array('U-M', 'Group-X'), array('U‑M', 'Group‑X'), wptexturize(wpautop($input))));
}

function format_phone($input = "") {
	return preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $input);
}

function format_target($input = "") {
	return $input ? "_blank" : "_self";
}

function format_zip($input = "") {
	return str_replace('-', '‑', ("&thinsp;" . $input));
}

function format_array_to_sentence($array = array()) {
	if( empty( $array ) || ! is_array( $array ) ) {
		return $array;
	}

	switch( count( $array ) ) {
		case 1:
			$string = array_pop( $array );
			break;
		case 2:
			$string = implode( ' and ', $array );
			break;
		default:
			$last_string = array_pop( $array );
			$string = implode( ', ', $array ) . ', and ' . $last_string;
			break;
	}

	return $string;
}
