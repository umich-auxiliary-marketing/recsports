<?php

class FrmMlcmpSettingsController {

	/**
	 * Add MailChimp tab in global settings
	 *
	 * @param array $sections
	 *
	 * @return array
	 */
	public static function add_settings_section( $sections ) {
		$sections['mailchimp'] = array( 'class' => 'FrmMlcmpSettingsController', 'function' => 'route' );

		return $sections;
	}

	public static function match_fields() {
		$form_id = isset( $_POST['form_id'] ) ? (int) $_POST['form_id'] : false;
		$list_id = isset( $_POST['list_id'] ) ? $_POST['list_id'] : false;
		if ( ! $form_id || ! $list_id ) {
			die();
		}

		$list_fields = FrmMlcmpAppController::get_list_fields( $list_id );

		$form_fields = FrmField::getAll( 'fi.form_id=' . (int) $form_id . " and fi.type not in ('break', 'divider', 'html', 'captcha', 'form')", 'field_order' );
		$groups      = FrmMlcmpAppController::get_groups( $list_id );

		$list_options = array( 'optin' => 0 );

		$action_control = FrmFormActionsController::get_form_actions( 'mailchimp' );
		$action_control->_set( $_POST['action_key'] );
		include( FrmMlcmpAppHelper::plugin_path() . '/views/action-settings/_match_fields.php' );


		die();
	}

	public static function add_list( $list_id = false, $active = true ) {
		$hide_mailchimp = $active ? '' : 'style="display:none;"';
		$die            = ( $list_id ) ? false : true;
		if ( ! $list_id && $_POST && isset( $_POST['list_id'] ) ) {
			$list_id = $_POST['list_id'];
		}

		$lists        = FrmMlcmpAppController::get_lists();
		$list_options = array( 'optin' => 0 );

		include( FrmMlcmpAppHelper::plugin_path() . '/views/settings/_list_options.php' );

		if ( $die ) {
			die();
		}
	}

	public static function add_logic_row() {
		if ( ! $_POST || ! isset( $_POST['list_id'] ) ) {
			die();
		}

		$list_id      = $_POST['list_id'];
		$form_id      = (int) $_POST['form_id'];
		$meta_name    = $_POST['meta_name'];
		$hide_field   = '';
		$list_options = array( 'hide_field' => array(), 'hide_field_cond' => array(), 'hide_opt' => array() );

		FrmMlcmpAppHelper::include_logic_row( $meta_name, $form_id, $list_id, $list_options );

		die();
	}

	public static function get_group_values() {
		$list_id  = $meta_name = sanitize_text_field( $_POST['list_id'] );
		$form_id  = absint( $_POST['form_id'] );
		$group_id = $_POST['group_id'];

		$groups = FrmMlcmpAppController::get_groups( $list_id );

		foreach ( $groups['categories'] as $group ) {
			if ( isset( $group['id'] ) && $group['id'] == $group_id ) {
				break;
			}
		}

		$new_field = FrmField::getOne( absint( $_POST['field_id'] ) );

		list( $form_options, $list_options ) = self::populate_options( compact( 'form_id', 'list_id' ) );

		$action_control = FrmFormActionsController::get_form_actions( 'mailchimp' );
		$action_control->_set( $_POST['action_key'] );
		require( FrmMlcmpAppHelper::plugin_path() . '/views/action-settings/_group_values.php' );

		wp_die();
	}

	public static function check_apikey() {
		// Validate nonce
		if ( ! isset( $_POST['wpnonce'] ) || ! wp_verify_nonce( $_POST['wpnonce'], 'frm_mlcmp' ) ) {
			die( json_encode( array( 'error' => __( 'You do not have permission to access this page.', 'frmmlcmp' ) ) ) );
		}

		// Validate inputs
		if ( ! isset( $_POST['apikey'] ) ) {
			die( json_encode( array( 'error' => __( 'No api key code was sent', 'frmmlcmp' ) ) ) );
		}

		$response = FrmMlcmpAppController::call( '', array( 'method' => 'GET' ), sanitize_text_field( $_POST['apikey'] ) );
		wp_die( $response );
	}

	public static function register_actions( $actions ) {
		$actions['mailchimp'] = 'FrmMlcmpAction';

		include_once( FrmMlcmpAppHelper::plugin_path() . '/models/FrmMlcmpAction.php' );

		return $actions;
	}

	public static function display_form() {
		$frm_mlcmp_settings = new FrmMlcmpSettings();

		require_once( FrmMlcmpAppHelper::plugin_path() . '/views/settings/form.php' );
	}

	public static function process_form() {
		$frm_mlcmp_settings = new FrmMlcmpSettings();

		//$errors = $frm_mlcmp_settings->validate($_POST,array());
		$errors = array();

		$frm_mlcmp_settings->update( $_POST );

		if ( empty( $errors ) ) {
			$frm_mlcmp_settings->store();
		}

		require_once( FrmMlcmpAppHelper::plugin_path() . '/views/settings/form.php' );
	}

	public static function route() {
		$action = FrmAppHelper::get_param( 'action' );
		if ( $action == 'process-form' ) {
			self::process_form();
		} else {
			self::display_form();
		}
	}

	/**
	 * @param array $atts - includes $form_id, $meta_name, $list_id
	 *
	 * @return array
	 */
	public static function populate_options( $atts ) {
		$form_id   = $atts['form_id'];
		$meta_name = $atts['meta_name'];
		$list_id   = $atts['list_id'];

		global $wpdb;
		$form_options = $wpdb->get_var( $wpdb->prepare( "SELECT options FROM {$wpdb->prefix}frm_forms WHERE id=%d", $form_id ) );
		$form_options = maybe_unserialize( $form_options );

		if ( isset( $form_options['mlcmp_list'][ $list_id ] ) ) {
			$list_options = $form_options['mlcmp_list'][ $list_id ];
		} else {
			$list_options = array( 'hide_field' => array(), 'hide_field_cond' => array(), 'hide_opt' => array() );
		}

		if ( isset( $meta_name ) && ! isset( $list_options['hide_field_cond'][ $meta_name ] ) ) {
			$list_options['hide_field_cond'][ $meta_name ] = '==';
		}

		return array( $form_options, $list_options );
	}

	/***********************************************************************
	 * Deprecated Functions
	 ************************************************************************/

	public static function migrate_to_2() {
		_deprecated_function( __FUNCTION__, '2.0', 'FrmMlcmpDb->migrate' );

		$mailchimp_db = new FrmMlcmpDb();
		$mailchimp_db->migrate();
	}

	public static function load_form_settings_hooks() {
		_deprecated_function( __FUNCTION__, '2.0', 'a custom function' );
	}

	public static function add_mailchimp_options() {
		_deprecated_function( __FUNCTION__, '2.0', 'a custom function' );
	}

	public static function setup_new_vars() {
		_deprecated_function( __FUNCTION__, '2.0', 'a custom function' );
	}

	public static function setup_edit_vars() {
		_deprecated_function( __FUNCTION__, '2.0', 'a custom function' );
	}

	public static function update_options() {
		_deprecated_function( __FUNCTION__, '2.0', 'a custom function' );
	}

	public static function mailchimp_options() {
		_deprecated_function( __FUNCTION__, '2.0', 'a custom function' );
	}
}