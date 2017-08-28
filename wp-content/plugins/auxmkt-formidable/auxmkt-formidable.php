<?php
/**
 * Plugin Name: Auxiliary Marketing Formidable Enhancements
 * Plugin URI: http://studentlife.umich.edu
 * Description: Enables LocalStorage for forms and turns on submit-by-AJAX by default.
 * Author: John Matula
 * Version: 1.0.0
 * Author URI: http://studentlife.umich.edu
 */



// Forces the AJAX submission to be on by default. A little sketchy since it
// can't be turned off, but then again, Formidable doesn't have a "null" state
// where we could potentially set the default and allow for later changes.
add_action('frm_additional_form_options', 'frm_add_new_form_opt', 10, 1 );
function frm_add_new_form_opt( $values ) { ?>
	<style>
	#ajax_submit, #js_validate, label[for=ajax_submit], label[for=js_validate] {
		opacity: .5;
		pointer-events: none;
	}
	</style>
<?php
}

add_filter('frm_form_options_before_update', 'frm_update_my_form_option', 20, 2);
function frm_update_my_form_option( $options, $values ){
	$options["ajax_submit"] = "1";
	$options["js_validate"] = "1";

	return $options;
}

// Offset addition so it doesn't get hidden by the headers
add_filter('frm_scroll_offset', 'frm_scroll_offset');
function frm_scroll_offset(){
  return 150; //adjust this as needed
}


// Setup Formidable to allow for custom checkboxes and radio buttons
add_filter('frm_replace_shortcodes', 'frm_change_my_html', 10, 3);
function frm_change_my_html($html, $field, $args){
	if ( in_array ( $field['type'], array( 'radio', 'checkbox' ) ) ) {
		$temp_array = explode('/>', $html);
		$new_html = '';
		foreach ( $temp_array as $key => $piece ) {
			// Get current for attribute
			if ( ( $pos = strpos( $piece, 'field_' . $field['field_key'] . '-' ) ) !== FALSE ) {
			    $new_key = substr( $piece, $pos );
			    $key_parts = explode( '"', $new_key, 2);
			    $new_key = reset( $key_parts );
			} else {
			    $new_html .= trim($piece);
			    continue;
			}
			// Move opening label tag
			$label = '<label for="' . $new_key . '">';
			$new_html .= str_replace( $label, '', trim($piece) );
			$new_html .= '/>' . $label;
	  	}
	  $html = $new_html;
	} else if($field['type'] === 'captcha') {
		//$html = str_replace('data-size="normal"', 'data-size="compact"', $html);
	}

  return $html;
}


// Include the label of the form into the submit button
add_action('frm_submit_button_action', 'submit_button_form_name');
function submit_button_form_name($form){
  echo ' data-formname="' . $form->name . '"';
}


// Prevent the "fieldset" from being added as it can't display: flex
add_filter( 'frm_filter_final_form', 'filter_hide_this' );
function filter_hide_this( $form ) {
	$form = str_replace("<fieldset>", "", $form);
	$form = str_replace("</fieldset>", "", $form);

	return $form;
}
