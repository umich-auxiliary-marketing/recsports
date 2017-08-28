<?php
/*
Plugin Name: WP Dash Message
Description: <strong><strong>Modified</strong></strong> Add a welcome message dashboard widget and remove built-in WordPress dashboard widgets.
Version: 1.1.2
Author: Aleksandar Arsovski
License: GPL2
*/

/*  Copyright 2011  Aleksandar Arsovski  (email : alek_ars@hotmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// Internationalization setup
$wpdwm_plugin_dir = basename(dirname(__FILE__));
load_plugin_textdomain( 'wp-dash-message', false, $wpdwm_plugin_dir );


/*****************************************************************************************/
								/*Widget removal arrays*/
/*****************************************************************************************/

// Titles for all the widgets that can be removed from each dashboard
$rightnow_title = __( 'Right Now', 'wp-dash-message' );
$plugins_title = __( 'Plugins', 'wp-dash-message' );
$recentcomments_title = __( 'Recent Comments', 'wp-dash-message' );
$incominglinks_title = __( 'Incoming Links', 'wp-dash-message' );
$quickpress_title = __( 'QuickPress', 'wp-dash-message' );
$activity_title = __( 'Activity', 'wp-dash-message' );
$recentdrafts_title = __( 'Recent Drafts', 'wp-dash-message' );
$wordpressblog_title = __( 'WordPress Blog', 'wp-dash-message' );
$otherwordpressnews_title = __( 'Other WordPress News', 'wp-dash-message' );


// Network dashboard widget information array (used for checkboxes)
$meta_boxes_network = array(
	'network_dashboard_right_now' => $rightnow_title,
	'dashboard_plugins' => $plugins_title,
	'dashboard_primary' => $wordpressblog_title,
	'dashboard_secondary' => $otherwordpressnews_title
);

// Site dashboard widget information array (used for checkboxes)
$meta_boxes_site = array(
	//'dashboard_welcome_widget' => $welcome_title,
	'dashboard_right_now' => $rightnow_title,
	'dashboard_recent_comments' => $recentcomments_title,
	'dashboard_incoming_links' => $incominglinks_title,
	'dashboard_quick_press' => $quickpress_title,
	'dashboard_recent_drafts' => $recentdrafts_title,
	'dashboard_primary' => $wordpressblog_title ,
	'dashboard_secondary' => $otherwordpressnews_title,
	'dashboard_activity' => $activity_title
);

// Global dashboard widget information array (used for checkboxes)
$meta_boxes_global = array(
	//'dashboard_welcome_widget' => $welcome_title,
	'dashboard_primary' => $wordpressblog_title ,
	'dashboard_secondary' => $otherwordpressnews_title
);

/*****************************************************************************************/
/*****************************************************************************************/


// Hook for adding site level options menu in the settings menu bar
add_action( 'admin_menu', 'wpdwm_options_menu' );

// Hook for setting up the settings section in network-wide settings tab (for creating network-wide welcome messages and removing widgets from any dashboard)
//add_action( 'wpmu_options', 'wpdwm_network_settings' );

// Hook for updating the network-wide welcome massage and widget removal data data
//add_action( 'update_wpmu_options', 'wpdwm_save_network_settings' ) ;

// Hook calls function for registering/adding settings when admin area is accessed
add_action( 'admin_init', 'wpdwm_admin_init' );

// Remove widgets from site dashboard function
add_action( 'wp_dashboard_setup', 'wpdwm_remove_site_dash_widgets' );

// Remove widgets from network dashboard function
//add_action( 'wp_network_dashboard_setup', 'wpdwm_remove_network_dash_widgets' );

// Remove widgets from global dashboard function
//add_action( 'wp_user_dashboard_setup', 'wpdwm_remove_global_dashboard_widgets' );


/** Function for registering/adding setings
 * wpdwm_admin_init function.
 *
 * @access public
 * @return void
 */
function wpdwm_admin_init() {

	// Adding the dash message widget to the site level dashboard
	add_action( 'wp_dashboard_setup', 'wpdwm_add_dash_welcome_site' );

	// Adding the dash message widget to the global level dashboard (this is the dashboard that new users that don't belong to a site see by default)
	//add_action( 'wp_user_dashboard_setup', 'wpdwm_add_dash_welcome_global' );

	// Site-level settings section
	add_settings_section(
		'wpdwm_dash_settings_page_main',
		'',
		'wpdwm_main_section_text',
		'wpdwm_dash_settings_page'
	);

	// Site-level dashboard message text entry field
	add_settings_field(
		'wpdwm_welcome_text',
		__( 'Dashboard Message Content', 'wp-dash-message' ),
		'wpdwm_site_level_entry_field',
		'wpdwm_dash_settings_page',
		'wpdwm_dash_settings_page_main'
	);

	// Site-level remove dashboard widgets settings
	add_settings_field(
		'wpdwm_site_dash_widgets',
		__( 'Remove Site Dashboard Widgets', 'wp-dash-message' ),
		'wpdwm_site_level_dash_widget_options',
		'wpdwm_dash_settings_page',
		'wpdwm_dash_settings_page_main'
	);

	// Dash message text entry option
	register_setting( 'wpdwm_site_options', 'wp_dash_message', 'wp_dash_message_validate' );

	// Removing site widgets option
	register_setting( 'wpdwm_site_options', 'wp_remove_site_widgets' );

	// Adding the dash message widget into the widget removal arrays
	global $meta_boxes_site, $meta_boxes_global, $user_identity;
	$welcome_title = '<strong>Dashboard Message</strong>';
	$meta_boxes_site = array_merge( array( 'dashboard_welcome_widget' => $welcome_title ), $meta_boxes_site );
	$meta_boxes_global = array_merge( array( 'dashboard_welcome_widget' => $welcome_title ), $meta_boxes_global );
}


// Add the Dash Welcome widget and place it at the top of the SITE dashboard
/**
 * wpdwm_add_dash_welcome_site function.
 *
 * @access public
 * @return void
 */
function wpdwm_add_dash_welcome_site() {

	// Get user's data in order to display username in header
	global $user_identity;

	// Change second parameter to change the header of the widget
	wp_add_dashboard_widget(
		'dashboard_welcome_widget',	'Messages from Auxiliary Marketing', 'wpdwm_dashboard_welcome_widget_function'
	);

	// Globalize the metaboxes array, this holds all the widgets for wp-admin
	global $wp_meta_boxes;

	// Get the regular dashboard widgets array
	// (which has our new widget already but at the end)
	$wpdwm_normal_dashboard = $wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ];

	// Backup and delete our new dashbaord widget from the end of the array
	$wpdwm_dashboard_widget_backup = array( 'dashboard_welcome_widget' => $wpdwm_normal_dashboard[ 'dashboard_welcome_widget' ] );
	unset( $wpdwm_normal_dashboard[ 'dashboard_welcome_widget' ] );

	// Merge the two arrays together so our widget is at the beginning
	$wpdwm_sorted_dashboard = array_merge( $wpdwm_dashboard_widget_backup, $wpdwm_normal_dashboard );

	// Save the sorted array back into the original metaboxes
	$wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ] = $wpdwm_sorted_dashboard;

}


// Create the function to output the contents of the new Dashboard Widget
/**
 * wpdwm_dashboard_welcome_widget_function function.
 *
 * @access public
 * @return void
 */
function wpdwm_dashboard_welcome_widget_function() {

	// Display the site level widget entry first...
	$site_message = get_option( 'wp_dash_message' );
	echo apply_filters( 'the_content', $site_message[ 'message' ] );

	// Display the network level widget entry second...
	$network_message = get_site_option( 'wp_dash_message_network', '', true );

	if( $network_message != '' ) {
		echo $network_message;
	} ?>

	<!--CSS for Widget-->
	<style>
		#dashboard_welcome_widget {
			box-shadow: 0px 3px 4px rgba(0,0,0,.11);
		}
		#dashboard_welcome_widget .hndle {
			background: #333;
			color: white;
		}

	</style><?php
}

/** Options page
 * wpdwm_options_menu function.
 *
 * @access public
 * @return void
 */
function wpdwm_options_menu() {
	// Parameters for options: 1. site header name 2. setting menu bar name
	// 3. capability (decides whether user has access) 4. menu slug 5. options page function
	add_options_page(
		__( 'Dashboard Message Options', 'wp-dash-message' ),
		__( 'Dashboard Message', 'wp-dash-message' ),
		'manage_options',
		'wpdwm_options',
		'wpdwm_dash_settings_page'
	);
}

/** Site level options page set-up
 * wpdwm_dash_settings_page function.
 * settings page
 * @access public
 * @return void
 */
function wpdwm_dash_settings_page() {

	// Determines if user has permission to access options and if they don't error message is displayed
	if ( !current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have the permission to modify the custom dashboard message box.', 'wp-dash-message' ) );
	}?>

	<!-- Set up the page and populate it with the options section and save button -->
	<div class="wrap">
		<h2><?php _e( 'Dashboard Message', 'wp-dash-message' ) ?></h2>
		<form method="post" action="options.php"><?php
			settings_fields( 'wpdwm_site_options' );
			do_settings_sections( 'wpdwm_dash_settings_page' ); ?>
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
		</form>
	</div>
	<?php
}


// **Not used** (text function for text box area)
function wpdwm_main_section_text() { }


// **Not used** (text function for text box area)
function wpdwm_remove_widgets_text() { }


/** Sets up the site level entry field
 * wpdwm_site_level_entry_field function.
 *
 * @access public
 * @return void
 */
function wpdwm_site_level_entry_field() {

	// Get the site message entry
	$site_message = get_option( 'wp_dash_message' );

	// Get the widget removal options
	$WP_remove_option = get_option( 'wp_remove_site_widgets' );
	$WP_remove_site_option = get_site_option( 'wp_remove_site_widgets_N' ); ?>

	<!-- Creates the site level entry field and populates it with whatever is currently displayed on the widget site message wise -->
	<!-- The textarea is disabled if the widget is disabled on the site level -->
	<textarea id="wpdwm_welcome_text" name="wp_dash_message[message]" rows="15" cols="70"><?php echo $site_message[ 'message' ]; ?></textarea>
	<br /><?php

	// Shows the "HTML allowed" message if the widget hasn't been disabled on the site level dashboard
	if( !isset( $WP_remove_option[ 'dashboard_welcome_widget' ] ) && !isset( $WP_remove_site_option[ 'dashboard_welcome_widget' ] ) ) { ?>
		<span><?php
			_e( 'HTML allowed', 'wp-dash-message' ) ?>
		</span>
		<br /><?php
	}
	// Show the following message instead if the dashboard message widget is disabled through the network administrator options
	elseif( isset( $WP_remove_site_option[ 'dashboard_welcome_widget' ] ) ) { ?>
		<span class="description" style="color:#c00">
			<?php
			_e( 'The network administrator has deactivated the dashboard message widget. Please
			contact your network administrator if you wish to have the dashboard message
			widget re-activated.', 'wp-dash-message' ) ?>
		</span><?php
	}
	// Show the following message instead if the dashboard message widget is disabled through the site level administration options
	elseif( isset( $WP_remove_option[ 'dashboard_welcome_widget' ] ) ) { ?>
		<span class="description" style="color:#c00">
			<?php
			_e( 'One of the site administrators has deactivated the dashboard message widget. If
			you wish to re-activate the dashboard widget, simply deselect the appropriate checkbox
			in the section below and click on the "Save Changes" button.', 'wp-dash-message' ) ?>
		</span><?php
	}

}


/** Validation/clean-up of message. "trim" removes all spaces before and after text body. Returns the validated entry
 * wp_dash_message_validate function.
 *
 * @access public
 * @param mixed $input
 * @return $newinput -- Validated entry
 */
function wp_dash_message_validate($input) {
	$newinput[ 'message' ] =  trim( $input[ 'message' ] );
	return $newinput;
}


/** Removes all site level dashboard widgets that were checked off in the site and network level options
 * wpdwm_remove_site_dash_widgets function.
 *
 * @access public
 * @return void
 */
function wpdwm_remove_site_dash_widgets() {

	// Globalize the meta boxes array
	global $meta_boxes_site;

	// Get the site level and network level dashboard widget removal settings
	$WP_remove_option = get_option( 'wp_remove_site_widgets' );
	$WP_remove_site_option = get_site_option( 'wp_remove_site_widgets_N' );

	// Loop through all IDs
	foreach( $meta_boxes_site as $meta_box => $title )
	{
		// If the ID is marked as removed by site or network level setting...
		if( isset( $WP_remove_option[ $meta_box ] ) || isset( $WP_remove_site_option[$meta_box] ) ) {
			remove_meta_box( $meta_box, 'dashboard', 'normal' );
			remove_meta_box( $meta_box, 'dashboard', 'side' );
		}
	}
}



/** Sets up the site level dashboard widget removal checkboxes
 * wpdwm_site_level_dash_widget_options function.
 *
 * @access public
 * @return void
 */
function wpdwm_site_level_dash_widget_options() {

	global $meta_boxes_site;

	// Get the site level option for checkbox status
	$WP_remove_option = get_option( 'wp_remove_site_widgets' );

	// Get the network level option
	$WP_remove_site_option = get_site_option( 'wp_remove_site_widgets_N' );

	// Set up site dashboard widget removal checkboxes
	foreach( $meta_boxes_site as $meta_box => $title ) {
		// If the given dashboard widget is disabled through the network settings, remove it from the checkbox options in the site level settings page
		if ( !isset( $WP_remove_site_option[ $meta_box ] ) ) { ?>
			<label><input id='<?php echo $meta_box; ?>' type='checkbox' name='wp_remove_site_widgets[<?php echo $meta_box; ?>]'
			value='Removed' <?php isset( $WP_remove_option[$meta_box] ) ? checked( $WP_remove_option[ $meta_box ], 'Removed', true ) : NULL; ?> /><?php echo $title; ?></label><br><?php
		}
	} ?>

	<!-- Short info sentence -->
	<span class="description"><?php
		_e( 'Select all widgets you would like to remove from the site dashboard
		and click on the "Save Changes" button.', 'wp-dash-message' ) ?>
	</span><?php

	// Add another info message if multisite support is enabled
	if ( is_multisite() ) { ?>
		<br />

		<span class="description"><?php
			_e( 'NOTE: If widgets have been disabled through the "Network Settings"
			they can only be reactivated by a network admin and will not appear on
			this settings page.', 'wp-dash-message' ) ?>
		</span><?php
	}
}
