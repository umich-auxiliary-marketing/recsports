<?php

class FrmZapApiController{
    public static $timeout = 10;
    
    public static function load_hooks(){
        if ( false !== strpos( $_SERVER['REQUEST_URI'], '/frm-api/' ) ) {
			add_action( 'wp_loaded', 'FrmZapApiController::api_route' );
		}
		
		add_action('frm_after_create_entry', 'FrmZapApiController::send_new_entry', 41, 2);
		add_action('frm_after_update_entry', 'FrmZapApiController::send_updated_entry', 41, 2);
        add_action('frm_before_destroy_entry', 'FrmZapApiController::send_deleted_entry', 10, 2);
    }
    
    public static function send_new_entry($entry_id, $form_id) {
        self::send_entry($entry_id, $form_id, 'frm_after_create_entry');
    }
    
    public static function send_updated_entry($entry_id, $form_id) {
        self::send_entry($entry_id, $form_id, 'frm_after_update_entry');
    }
    
    public static function send_deleted_entry($entry_id, $entry = false) {
        if ( !$entry ) {
            $entry = FrmEntry::getOne( $entry_id );
            if ( !$entry ) {
                return;
            }
        }
        
        $form_id = $entry->form_id;
        self::send_entry($entry_id, $form_id, 'frm_before_destroy_entry');
    }
    
    private static function send_entry($entry_id, $form_id, $hook) {
        global $wpdb;
        
        $zaps = get_posts( array(
            'meta_key'      => 'frm_form_id',
        	'meta_value'    => $form_id,
        	'post_type'     => 'frm_api',
        	'post_status'   => 'publish',
        ) );
        
        if ( !$zaps ) {
            return;
        }
        
        foreach ( $zaps as $k => $zap ) {
            // make sure to only send Zaps for current hook
            if ( $zap->post_title != $hook || strpos($zap->post_excerpt, 'zapier.com/hooks') === false ) {
                unset($zaps[$k]);
            }
            
            unset($k, $h);
        }
        
        if ( empty($zaps) ) {
            return;
        }
        
        $body = self::get_entry_array($entry_id);
        self::send_to_zapier($body, $zaps);
    }
    
    private static function send_to_zapier($body, $zaps) {
        $headers = array();
        if ( empty($body) ) {
            $headers['X-Hook-Test'] = 'true';
        }
        
        $arg_array = array(
            'body'      => json_encode($body),
            'timeout'   => self::$timeout,
            'sslverify' => false,
            'ssl'       => true,
            'headers'   => $headers,
        );
        
        foreach ( $zaps as $zap ) {
            //TODO: allow for custom body from $post->post_content
            if ( is_numeric($zap) ) {
                $zap = get_post($zap);
                if ( !$zap ) {
                    continue;
                }
            }
            
            // only trigger Zapier hooks from this plugin
            if ( strpos($zap->post_excerpt, 'zapier.com/hooks') === false ) {
                continue;
            }
            
            $resp = wp_remote_post($zap->post_excerpt, $arg_array);
            unset($zap);
        }
        

    }
    
    private static function get_entry_array( $entry_id ) {
        if ( !method_exists('FrmEntriesController', 'show_entry_shortcode') ) {
            return array();
        }

        $entry = FrmEntry::getOne( $entry_id, true );

		add_filter( 'frm_date_format', 'FrmZapApiController::set_date_format' );

        $meta = FrmEntriesController::show_entry_shortcode(array(
            'format' => 'array', 'include_blank' => true, 'id' => $entry_id,
            'user_info' => false, 'entry' => $entry
        ));
        
        $data = maybe_unserialize($entry->description);
        
        $entry_array = array(
            'id' => $entry->id,
            'ip' => $entry->ip,
            'browser' => $data['browser'],
            'referrer' => $data['referrer'],
            'user_id' => FrmProFieldsHelper::get_display_name($entry->user_id, 'user_login'),
            'form_id' => $entry->form_id,
            'is_draft' => $entry->is_draft,
            'updated_by' => FrmProFieldsHelper::get_display_name($entry->updated_by, 'user_login'),
            'post_id' => $entry->post_id,
            'key' => $entry->item_key,
            'created_at' => get_date_from_gmt($entry->created_at),
            'updated_at' => get_date_from_gmt($entry->updated_at),
        );
        
        foreach ( $meta as $k => $m ) {
            // use field keys
            if ( !is_numeric($k) ) {
                $entry_array['x'. $k] = $m;
            }
            unset($k, $m);
        }
        
        return $entry_array;
    }

	/**
	 * Send dates in Y-m-d format for maximum compatibility
	 * @since 1.0.1
	 */
	public static function set_date_format() {
		return 'Y-m-d';
	}

    public static function api_route() {
        // allow without API key for testing
        if ( !is_user_logged_in() || !current_user_can('administrator') ){
            if ( !isset( $_SERVER['PHP_AUTH_USER'] ) ){
                /*
                * php-cgi under Apache does not pass HTTP Basic user/pass to PHP by default
                * For this workaround to work, add this line to your .htaccess file:
                * RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
                */

				if ( isset( $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ) && ! isset( $_SERVER['HTTP_AUTHORIZATION'] ) ) {
					$_SERVER['HTTP_AUTHORIZATION'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
				}

                if ( isset($_SERVER['HTTP_AUTHORIZATION']) && strlen($_SERVER['HTTP_AUTHORIZATION']) > 0 ) {
                    list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
                    if ( strlen($_SERVER['PHP_AUTH_USER']) == 0 || strlen($_SERVER['PHP_AUTH_PW']) == 0 ) {
                        unset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
                    }
                }
                
                if ( !isset( $_SERVER['PHP_AUTH_USER'] ) ) {
    			    status_header( 403 );
                    echo json_encode(array('error' => 'Your API key is missing.'));
                    die();
                }
    		}

            $api_key = get_site_option('frm_api_key');
            $check_key = $_SERVER['PHP_AUTH_USER'];
            if ( $api_key != $check_key ) {
                status_header( 403 );
                echo json_encode(array('error' => 'Your API key is incorrect: '. $check_key));
                die();
            }
            
            $admins = new WP_User_Query( array(
                'role' => 'Administrator', 'number' => 1, 'fields' => 'ID',
                'orderby' => 'ID', 'order' => 'ASC' 
            ) );
        	if ( empty($admins) ) {
        	    return;
        	}

			$user = reset( $admins->results );
			$user = get_userdata( $user );
        } else {
            $user = wp_get_current_user();
        }

        
        header('Content-Type: application/json; charset=' . get_option( 'blog_charset' ));
        header('Expires: '. gmdate("D, d M Y H:i:s", mktime(date('H')+2, date('i'), date('s'), date('m'), date('d'), date('Y'))) .' GMT');
        header('Last-Modified: '. gmdate('D, d M Y H:i:s') .' GMT');
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        
        //only allow for v1 for now
        list($url, $request) = explode('/frm-api/v1/', strtok( $_SERVER['REQUEST_URI'] , '?' ), 2);
        
        $data = json_decode(file_get_contents('php://input'));
        $request = untrailingslashit($request);
        if ( strpos($request, '/') ) {
            list($request, $atts) = explode('/', $request, 2);
            $atts = explode('/', $atts);
        } else {
            $atts = array();
        }
        if ( method_exists('FrmZapApiController', $request) ) {
            $response = self::$request($data, $user, $atts);
        } else {
            status_header( 409 );
            error_log( 'No route for '. $request .' '. print_r($atts,1) );
            $response = array('error' => 'There is no endpoint for '. $request);
        }

        echo json_encode($response);
        die();
    }
    
    // route /ping
    private static function ping() {
        return array(
            'status' => 'verified'
        );
    }
    
    // route /forms
    private static function forms($data) {
        // published and not template
	    $forms = array( 'forms' => (array) FrmForm::getAll( array( 'is_template' => 0, 'status' => 'published' ) ) );
	    
	    return $forms;
    }
    
    // route /form/:id
    // get form HTML
    private static function form($data, $user, $atts) {
        if ( !isset($atts[0]) ) {
            status_header( 409 );
            return array('error' => 'No form ID provided');
        }
        $id = $atts[0];
        
        if ( is_numeric($id) ) {
            $shortcode_atts = array('id' => $id);
        } else {
            $shortcode_atts = array('key' => $id);
        }
        $form = FrmAppController::get_form_shortcode($shortcode_atts);
	    return (array) $form;
    }
    
    // route /fields/:id
    private static function fields($data, $user, $atts) {
        if ( !isset($atts[0]) ) {
            status_header( 409 );
            return array('error' => 'No form ID provided');
        }
        $id = $atts[0];

	    $where = array();
	    if ( is_numeric( $id ) ) {
	        $where = array('fi.form_id' => $id);
	    } else {
	        $where = array('fi.form_key' => $id);
	    }
	    $fields = array( 'fields' => (array) FrmField::getAll( $where, 'field_order' ) );
	    return $fields;
    }
    
    // get custom fields in Zapier format
    // route /zap_fields/:id
    private static function zap_fields($data, $user, $atts) {
        if ( !isset($atts[0]) ) {
            status_header( 409 );
            return array('error' => 'No form ID provided');
        }
        $id = $atts[0];

	    $where = array('fi.form_id' => $id);
	    $fields = FrmField::getAll( $where, 'field_order' );
	    $zap_fields = array();
	    
	    $field_map = array(
	       'rte' => 'text',
	       'textarea' => 'text',
	       'number' => 'decimal',
	       'date' => 'datetime',
	       'scale' => 'int'
	    );
	    $field_map = apply_filters('frmzap_map_fields', $field_map, $fields, $id);
	    
	    foreach ( $fields as $f ) {
	        if ( in_array( $f->type, array('divider', 'captcha', 'break', 'html')) ) {
	            continue;
	        }
	        
	        $zap_fields[] = array(
	            'type' => (isset($field_map['type']) ? $field_map['type'] : 'unicode'),
	            'key' => 'x'. $f->field_key, //make sure key starts with an alpha
	            'required' => ($f->required ? true : false),
	            'label' => $f->name,
	            'help_text' => $f->description,
	            'default' => $f->default_value,
	        );
	    }
	    return $zap_fields;
    }
    
    // route /subscribe
    // save the url to send notifications
    private static function subscribe($data, $user) {
        if ( !isset($data->target_url) ) {
            status_header( 409 );
            return array('error' => 'No target URL provided');
        }

        // create zap to notify
        // Events: frm_after_create_entry, frm_after_update_entry, frm_after_delete_entry

        $post = array(
            'post_title' => $data->event,
            'post_content' => '',
            'post_excerpt' => $data->target_url,
            'post_type' => 'frm_api',
            'post_status' => 'publish',
        );

        if ( $user ) {
            $post['post_author'] = $user->ID;
        }
        
        $post_id = wp_insert_post($post);
        
        if ( $post_id ) {
            // Add form id
            if ( isset($data->form) && isset($data->form->form_id) ) {
                update_post_meta($post_id, 'frm_form_id', $data->form->form_id);
            }
            status_header( 201 );
        }

        return array('id' => $post_id);
    }
    
    // delete zap
    private static function unsubscribe($data) {
        if ( !isset($data->target_url) ) {
            status_header( 409 );
            return array('error' => 'No target URL provided');
        }
        
        global $wpdb;
        $post_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type=%s AND post_excerpt=%s", 'frm_api', $data->target_url));
        
        if ( is_numeric($post_id) ) {
            wp_delete_post($post_id);
        } else {
            status_header( 409 );
            return array('error' => 'No zap found to delete');
        }

        return array('id' => $post_id);
    }
}