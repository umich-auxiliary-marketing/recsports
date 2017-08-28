<?php

class FrmMlcmpAppController {
	public static $min_version = '2.0';

	public static function load_lang() {
		load_plugin_textdomain( 'frmmlcmp', false, FrmMlcmpAppHelper::plugin_folder() . '/languages/' );
	}

	/**
	 * Print a notice if Formidable is too old to be compatible with the MailChimp add-on
	 */
	public static function min_version_notice() {
		if ( FrmMlcmpAppHelper::is_formidable_compatible() ) {
			return;
		}

		$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );
		echo '<tr class="plugin-update-tr active"><th colspan="' . $wp_list_table->get_column_count() . '" class="check-column plugin-update colspanchange"><div class="update-message">' .
		     __( 'You are running an outdated version of Formidable. This plugin may not work correctly if you do not update Formidable.', 'frmmlcmp' ) .
		     '</div></td></tr>';
	}

	/**
	 * Adds the updater
	 * Called by the admin_init hook
	 */
	public static function include_updater() {
		if ( class_exists( 'FrmAddon' ) ) {
			FrmMlcmpUpdate::load_hooks();
		}
	}

	/**
	 * Migrate settings if needed
	 *
	 * @since 2.0
	 */
	public static function install() {
		$mailchimp_db = new FrmMlcmpDb();
		$mailchimp_db->migrate();
	}

	/**
	 * Display admin notices if Formidable is too old or MailChimp settings need to be migrated
	 *
	 * @since 2.0
	 */
	public static function display_admin_notices() {
		// Don't display notices as we're upgrading
		$action = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : '';
		if ( $action == 'upgrade-plugin' && ! isset( $_GET['activate'] ) ) {
			return;
		}

		// Show message if Formidable is not compatible
		if ( ! FrmMlcmpAppHelper::is_formidable_compatible() ) {
			include( FrmMlcmpAppHelper::plugin_path() . '/views/notices/update_formidable.php' );

			return;
		}

		self::add_update_database_link();
	}

	/**
	 * Add link to update database
	 *
	 * @since 2.0
	 */
	private static function add_update_database_link() {
		$mailchimp_db = new FrmMlcmpDb();
		if ( $mailchimp_db->need_to_migrate_settings() ) {
			if ( is_callable( 'FrmAppHelper::plugin_url' ) ) {
				$url = FrmAppHelper::plugin_url();
			} else if ( defined( 'FRM_URL' ) ) {
				$url = FRM_URL;
			} else {
				return;
			}

			include( FrmMlcmpAppHelper::plugin_path() . '/views/notices/update_database.php' );
		}
	}

	public static function trigger_mailchimp( $action, $entry, $form ) {
		$settings = $action->post_content;
		self::send_to_mailchimp( $entry, $form, $settings );
	}

	public static function send_to_mailchimp( $entry, $form, $settings ) {
		$vars = array();

		self::get_field_values_for_mailchimp( $entry, $settings, $vars );

		if ( ! isset( $vars['EMAIL'] ) ) {
			//no email address is mapped
			return;
		}

		$interests = self::package_selected_group_interests( $entry, $settings );

		$email_array = $vars['EMAIL'];

		$subscriber_id = self::get_subscriber_id( $entry, $settings, $email_array );

		$double_optin = isset( $settings['optin'] ) ? $settings['optin'] : false;
		$sending_data = array(
			'id'            => $settings['list_id'],
			'subscriber_id' => $subscriber_id,
			'method'        => empty( $subscriber_id ) ? 'POST' : 'PUT',
			'email_address' => $email_array,
			'status'        => $double_optin ? 'pending' : 'subscribed',
			'status_if_new' => $double_optin ? 'pending' : 'subscribed',
			'merge_fields'  => $vars,
			'email_type'    => 'html',
		);

		if ( ! empty( $interests ) ) {
			$sending_data['interests'] = $interests;
		}

		self::send_now( $sending_data, $entry );
	}

	/**
	 * Get the subscriber ID if needed
	 *
	 * @since 2.0
	 *
	 * @param object $entry
	 * @param array $settings
	 * @param string $email
	 *
	 * @return string
	 */
	private static function get_subscriber_id( $entry, $settings, $email ) {
		$subscriber_id = '';

		// If editing existing entry, check entry for saved MailChimp id
		$entry_description = maybe_unserialize( $entry->description );
		if ( is_array( $entry_description ) && isset( $entry_description['mailchimp-leid'] ) && ! empty( $entry_description['mailchimp-leid'] ) && isset( $entry_description['mailchimp-leid'][ $settings['list_id'] ] ) ) {
			$subscriber_id = $entry_description['mailchimp-leid'][ $settings['list_id'] ];
		}

		// Maybe force update
		$update_existing = ! empty( $subscriber_id );

		$update_existing = apply_filters( 'frm_mlcmp_update_existing', $update_existing );

		if ( $update_existing && empty( $subscriber_id ) && $email ) {
			$subscriber_id = strtolower( $email );
			$subscriber_id = md5( $subscriber_id );
		}

		return $subscriber_id;
	}

	/**
	 * Package selected and non-selected groups to send to MailChimp
	 *
	 * @since 2.0
	 *
	 * @param object $entry
	 * @param array $settings
	 *
	 * @return array
	 */
	private static function package_selected_group_interests( $entry, $settings ) {
		$interests = array();

		if ( isset( $settings['groups'] ) ) {

			foreach ( $settings['groups'] as $group ) {
				$selected_grp = FrmMlcmpAppHelper::get_entry_or_post_value( $entry, $group['id'] );

				foreach ( (array) $group as $group_id => $value ) {
					if ( $group_id != 'id' && ! empty( $group_id ) && $value != '' ) {
						$selected               = array_search( $value, (array) $selected_grp );
						$interests[ $group_id ] = ( $selected !== false );
					}
				}
				unset( $group_id, $value );
			}
		}

		return $interests;
	}

	/**
	 * Get the field values to send to MailChimp
	 *
	 * @since 2.0
	 *
	 * @param object $entry
	 * @param array $settings
	 * @param array $vars
	 */
	private static function get_field_values_for_mailchimp( $entry, $settings, &$vars ) {
		$list_fields = self::get_list_fields( $settings['list_id'] );

		foreach ( $settings['fields'] as $field_tag => $field_id ) {
			if ( empty( $field_id ) ) {
				// don't sent an empty value
				continue;
			}

			$vars[ $field_tag ] = self::get_field_value_for_mailchimp( $field_tag, $field_id, $entry, $list_fields );
		}
	}

	/**
	 * Get a single field value to send to MailChimp
	 *
	 * @since 2.0
	 *
	 * @param string $field_tag
	 * @param string $field_id
	 * @param object $entry
	 * @param array $list_fields
	 *
	 * @return string
	 */
	private static function get_field_value_for_mailchimp( $field_tag, $field_id, $entry, $list_fields ) {
		$field_value = FrmMlcmpAppHelper::get_entry_or_post_value( $entry, $field_id );

		if ( is_numeric( $field_value ) ) {
			$field = FrmField::getOne( $field_id );
			if ( $field->type == 'user_id' ) {
				$user_data = get_userdata( $field_value );
				if ( $field_tag == 'EMAIL' ) {
					$field_value = $user_data->user_email;
				} else if ( $field_tag == 'FNAME' ) {
					$field_value = $user_data->first_name;
				} else if ( $field_tag == 'LNAME' ) {
					$field_value = $user_data->last_name;
				} else {
					$field_value = $user_data->user_login;
				}
			} else if ( is_callable( 'FrmEntriesHelper::display_value' ) ) {
				$field_value = FrmEntriesHelper::display_value( $field_value, $field, array( 'type'     => $field->type,
				                                                                             'truncate' => false,
				                                                                             'entry_id' => $entry->id
				) );
			} else if ( is_callable( 'FrmProEntryMetaHelper::display_value' ) ) {
				$field_value = FrmProEntryMetaHelper::display_value( $field_value, $field, array( 'type'     => $field->type,
				                                                                                  'truncate' => false,
				                                                                                  'entry_id' => $entry->id
				) );
			}
		} else {

			if ( is_string( $field_value ) && preg_match( '/^\d{2}\/\d{2}\/\d{4}$/', trim( $field_value ) ) ) {
				if ( is_callable( 'FrmProAppHelper::convert_date' ) ) {
					global $frmpro_settings;
					$field_value = FrmProAppHelper::convert_date( $field_value, $frmpro_settings->date_format, 'Y-m-d' );
				}
			}

			$list_field = false;
			if ( isset( $list_fields['merge_fields'] ) ) {
				foreach ( $list_fields['merge_fields'] as $lf ) {
					if ( $lf['tag'] == $field_tag ) {
						$list_field = $lf;
						continue;
					}
					unset( $lf );
				}
			}

			if ( $list_field ) {
				if ( isset( $list_field['options']['date_format'] ) ) {
					$date_format = str_replace( 'YYYY', 'Y', str_replace( 'DD', 'd', str_replace( 'MM', 'm', $list_field['options']['date_format'] ) ) );
					$field_value = date( $date_format, strtotime( $field_value ) );
				}
			}

		}

		if ( is_array( $field_value ) ) {
			$field_value = implode( ', ', $field_value );
		}

		return $field_value;
	}

	private static function send_now( $sending_data, $entry ) {
		$sending_data = apply_filters( 'frm_mlcmp_subscribe_data', $sending_data, $entry );

		// Allow the filter to stop submission
		if ( empty( $sending_data ) ) {
			return;
		}

		$subscribe = self::decode_call( '/lists/' . $sending_data['id'] . '/members/' . $sending_data['subscriber_id'], $sending_data );

		if ( isset( $subscribe['error'] ) ) {
			error_log( 'MailChimp subscribe error: ' . $subscribe['error'] );
			// TODO: log the error message
		} elseif ( isset( $subscribe['id'] ) ) {
			global $wpdb;

			// save the list user id to the entry for later editing
			$entry->description = (array) maybe_unserialize( $entry->description );
			if ( ! isset( $entry->description['mailchimp-leid'] ) || ! is_array( $entry->description['mailchimp-leid'] ) ) {
				$entry->description['mailchimp-leid'] = array();
			}
			$entry->description['mailchimp-leid'][ $sending_data['id'] ] = $subscribe['id'];

			$wpdb->update( $wpdb->prefix . 'frm_items',
				array( 'description' => serialize( $entry->description ) ),
				array( 'id' => $entry->id )
			);
		}
	}

	public static function get_groups( $list_id ) {
		$groups = self::decode_call( '/lists/' . $list_id . '/interest-categories', array( 'method' => 'GET' ) );
		if ( $groups && isset( $groups['error'] ) ) {
			$groups = false;
		}

		return $groups;
	}

	public static function get_group_options( $list_id, $group_id ) {
		return self::decode_call( '/lists/' . $list_id . '/interest-categories/' . $group_id . '/interests', array( 'method' => 'GET' ) );
	}

	/**
	 * The 3.0 API doesn't include the numeric id of the groups.
	 * This is required in order to maintain reverse compatability.
	 * The 2.0 API will be deprecated, so let's save this link up for later reference.
	 *
	 * @since 2.0
	 *
	 * @param array $groups
	 * @param string|int $list_id
	 *
	 * @return array
	 */
	public static function get_group_ids( $groups, $list_id ) {
		$group_ids = get_option( 'frm_mlcmp_groups' );
		if ( $group_ids && isset( $group_ids[ $list_id ] ) ) {
			return $group_ids[ $list_id ];
		}

		if ( empty( $group_ids ) ) {
			$group_ids = array();
		} else {
			$group_ids[ $list_id ] = array();
		}

		$old_groups = self::v2_call( '/lists/interest-groupings', array( 'id' => $list_id ) );
		if ( $old_groups && isset( $old_groups['error'] ) ) {
			$old_groups = false;
		}

		if ( $old_groups ) {
			foreach ( $groups['categories'] as $group ) {
				foreach ( $old_groups as $old_group ) {
					if ( $old_group['name'] == $group['title'] ) {
						$group_ids[ $list_id ][ $group['id'] ] = $old_group['id'];
					}
				}
			}
			update_option( 'frm_mlcmp_groups', $group_ids );
		}

		return $group_ids[ $list_id ];
	}

	public static function get_lists() {
		return self::decode_call( '/lists', array( 'count' => 100, 'method' => 'GET' ) );
	}

	public static function get_list_fields( $list_id ) {
		return self::decode_call( '/lists/' . $list_id . '/merge-fields', array( 'method' => 'GET' ) );
	}

	public static function v2_call( $endpoint, $args ) {
		$args['api_version'] = '2.0';

		return self::decode_call( $endpoint, $args );
	}

	public static function decode_call( $endpoint, $args = array(), $apikey = null ) {
		$res = self::call( $endpoint, $args, $apikey );

		return json_decode( $res, true );
	}

	public static function call( $endpoint, $args = array(), $apikey = null ) {
		if ( is_null( $apikey ) ) {
			$frm_mlcmp_settings = new FrmMlcmpSettings();
			$apikey             = $frm_mlcmp_settings->settings->api_key;
		}

		if ( isset( $args['api_version'] ) && $args['api_version'] == '2.0' ) {
			$url       = self::get_old_endpoint_url( $apikey, $endpoint );
			$post_args = self::setup_old_post_body( $apikey, $args );
		} else {
			$url       = self::get_endpoint_url( $apikey, $endpoint );
			$post_args = self::setup_post_body( $apikey, $args );
		}

		$res  = wp_remote_post( $url, $post_args );
		$body = wp_remote_retrieve_body( $res );

		if ( is_wp_error( $res ) ) {
			$message = __( 'You had an error communicating with the MailChimp API.', 'frmmlcmp' ) . $res->get_error_message();

			return json_encode( array( 'error' => $message, 'status' => 'error' ) );
		} elseif ( $body == 'error' || is_wp_error( $body ) ) {
			$message = __( 'You had an error communicating with the MailChimp API.', 'frmmlcmp' );

			return json_encode( array( 'error' => $message, 'status' => 'error' ) );
		} else {
			$body = json_decode( $body, true );
			if ( is_array( $body ) && isset( $body['title'] ) && isset( $body['detail'] ) ) {
				return json_encode( array( 'error' => $body['title'], 'status' => 'error' ) );
			}
		}

		return $res['body'];
	}

	private static function setup_post_body( $apikey, $args ) {
		$method = 'POST';
		if ( isset( $args['method'] ) ) {
			$method = $args['method'];
			unset( $args['method'] );
		}

		$post_args = array(
			'method'    => $method,
			'headers'   => array(
				'Content-type'  => 'application/json',
				'Authorization' => 'apikey ' . $apikey,
			),
			'sslverify' => false,
		);

		if ( ! empty( $args ) ) {
			if ( $method != 'GET' ) {
				$args = json_encode( $args );
			}
			$post_args['body'] = $args;
		}

		return $post_args;
	}

	private static function get_endpoint_url( $apikey, $endpoint ) {
		$dc = self::get_datacenter( $apikey );
		$dc = empty( $dc ) ? '' : $dc . '.';

		return 'https://' . $dc . 'api.mailchimp.com/3.0/' . $endpoint;
	}

	/**
	 * Call the 2.0 API
	 *
	 * @since 2.0
	 *
	 * @param string $apikey
	 * @param array $args
	 *
	 * @return array
	 */
	private static function setup_old_post_body( $apikey, $args ) {
		$args['apikey'] = $apikey;
		$post_args        = array(
			'body'      => json_encode( $args ),
			'sslverify' => false,
		);

		return $post_args;
	}

	/**
	 * Call the 2.0 API
	 *
	 * @since 2.0
	 *
	 * @param string $apikey
	 * @param string $endpoint
	 *
	 * @return string
	 */
	private static function get_old_endpoint_url( $apikey, $endpoint ) {
		$dc = self::get_datacenter( $apikey );
		$dc = empty( $dc ) ? '' : $dc . '.';

		return 'https://' . $dc . 'api.mailchimp.com/2.0/' . $endpoint . '.json';
	}

	public static function get_datacenter( $apikey ) {
		$dc = explode( '-', $apikey );

		return isset( $dc[ 1 ] ) ? $dc[ 1 ] : '';
	}

	public static function maybe_send_to_mailchimp() {
		_deprecated_function( __FUNCTION__, '2.0', 'FrmMlcmpAppController::trigger_mailchimp' );
	}

	public static function hidden_form_fields() {
		_deprecated_function( __FUNCTION__, '2.0', 'a custom function' );
	}

	public static function unsubscribe() {
		_deprecated_function( __FUNCTION__, '2.0', 'a custom function' );
	}

}