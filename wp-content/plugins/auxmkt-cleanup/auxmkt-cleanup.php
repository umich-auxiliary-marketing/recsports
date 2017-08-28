<?php
/**
 * Plugin Name: Auxiliary Marketing Cleanup
 * Plugin URI: http://studentlife.umich.edu
 * Description: A grab bag of admin cleanup and stylistic fixes for a better user experience.
 * Author: John Matula
 * Version: 1.0.0
 * Author URI: http://studentlife.umich.edu
 */


// Admin bar
function john_remove_admin_bar_links() {
 	global $wp_admin_bar;

 	//Remove WordPress Logo Menu Items
 	$wp_admin_bar->remove_menu('wp-logo'); // Removes WP Logo and submenus completely, to remove individual items, use the below mentioned codes

 	//Remove Site Name Items
 	$wp_admin_bar->remove_menu('view-site'); // 'Visit Site'
 	$wp_admin_bar->remove_menu('appearance'); // 'Appearance dropdown'
 	$wp_admin_bar->remove_menu('menus'); // 'Menus'
 	$wp_admin_bar->remove_menu('new-content'); // 'Menus'
 	$wp_admin_bar->remove_menu('customize'); // 'Menus'
 	$wp_admin_bar->remove_menu('nf');
	$wp_admin_bar->remove_menu('w3tc');

 	// Remove Comments Bubble
 	$wp_admin_bar->remove_menu('comments');

 	//Remove Update Link if theme/plugin/core updates are available
 	$wp_admin_bar->remove_menu('updates');
 }
 add_action( 'wp_before_admin_bar_render', 'john_remove_admin_bar_links', 99 );

 function replace_howdy( $wp_admin_bar ) {
 	$my_account=$wp_admin_bar->get_node('my-account');
 	$newtitle = str_replace( 'Howdy,', '', $my_account->title );
 	$wp_admin_bar->add_node( array(
 		'id' => 'my-account',
 		'title' => $newtitle,
 	) );
}
add_filter('admin_bar_menu', 'replace_howdy', 25);


// Admin footer
function edit_footer_admin () {

echo '<em>Powered by <a href="http://www.wordpress.org" target="_blank">WordPress</a>. Designed by <a href="mailto:marketing@umich.edu">U-M Student Life Auxiliary Marketing</a>.</em>';

}
add_filter('admin_footer_text', 'edit_footer_admin');


// Admin styles
add_action('admin_head', 'custom_admin_styling', PHP_INT_MAX);
function custom_admin_styling() {
  ?>
	<style>
    strong strong, .modified-plugin-badge {
      display: inline-block;
			font-size: 0.75em;
			background: rgba(40,40,40,.375);
			color: rgb(255,255,255);
			font-weight: bolder;
			padding: .25em .25em .125em .25em;
			margin:0 .25em 0 0;
			line-height:1em;
			text-transform: uppercase;
			border-radius: 2px;
    }

		em em, .custom-plugin-badge {
      display: inline-block;
			font-size: 0.75em;
			background: rgb(40,40,40);
			color: rgb(255,255,255);
			font-weight: bolder;
			font-style: normal;
			padding: .25em .25em .125em .25em;
			margin:0 .25em 0 0;
			line-height:1em;
			text-transform: uppercase;
			border-radius: 2px;
    }

		small, .not-here-text {
			font-size: 1em;
			color: rgba(40,40,40,.375);
		}

		#audience-adder {
			display: none;
		}

		/* Hide several media attachment features since our site should never expose them as a standalone piece of content */
		.media-sidebar .setting[data-setting="description"],
		.media-sidebar .setting[data-setting="alt"],
		.media-modal-content .setting[data-setting="description"],
		.media-modal-content .setting[data-setting="alt"],
		.media-modal-content .view-attachment,
		.post-type-attachment #post-body label[for="attachment_alt"],
		.post-type-attachment #post-body label[for="attachment_content"],
		.post-type-attachment #attachment_alt,
		.post-type-attachment #wp-attachment_content-wrap,
		.post-type-attachment #edit-slug-box,
		.post-type-attachment #wp-admin-bar-view {
			display: none !important;
		}

		ul.audience-specific-list {
			list-style-type: disc;
			margin-left: 1.25rem;
		}

		ul.audience-specific-list li {
			line-height: 1rem;
		}

		#pageparentdiv div p:last-child {
			display: none;
		}

		#submitdiv {
			box-shadow: 0px 3px 4px rgba(0,0,0,.11);
		}

		.acf-field[data-name="page_content"] > .acf-label {
			display: none;
		}

		.acf-flexible-content .layout[data-layout="sectionheader"] {
			margin-top: 40px;
		}

		.acf-flexible-content .layout[data-layout="sectionheader"]:first-child {
			margin-top: 0px;
		}

		.acf-flexible-content .layout[data-layout="sectionheader"] .acf-fc-layout-handle {
			font-weight: bold;
		}

		.notice__icon {
			display: inline-block;
			height: 32px;
			margin-right: 16px;
			vertical-align: middle;
			width: 32px;
		}

		.notice__icon--20 svg, .notice__icon--40 svg, .notice__icon--60 svg {
			fill: #407cca;
		}

		.notice__icon--80 svg {
			fill: #f5c400;
		}

		.notice__icon--100 svg {
			fill: #e84b37;
		}

		#toplevel_page_w3tc_dashboard .wp-menu-image {
			background-image: none !important;
		}

		.w3tc_menu_item_pro, #toplevel_page_w3tc_dashboard li a span {
			color: unset !important;
		}

		#pageparentdiv .inside p:nth-of-type(3), #pageparentdiv .inside p:nth-of-type(4) {
			display: none;
		}

		.wp-core-ui .button-red {
      background-color: #AA2C2A;
      border-color: #871715;
      box-shadow: 0px 1px 0px 0px #871715;
			text-shadow: 0 0 2px #871715;
    }
    .wp-core-ui .button-red:hover, .wp-core-ui .button-red:focus {
      background-color: #BD302E;
      border-color: #871715;
			box-shadow: 0px 1px 0px 0px #871715 !important;
    }

		.wp-core-ui .button-red:active {
			background-color: #972725;
			border-color: #871715;
			box-shadow: inset 0px 2px 0px 0px #871715 !important;
		}

		.responsive-table select {
			max-width: 100%;
		}

		.acf-hl.acf-soh-target.acf-soh-target-link {
			top: 5px;
		}

		.acf-table-root .acf-icon {
			opacity: 0;
			transition: opacity .15s;
		}

		.acf-table-root:hover .acf-icon {
			opacity: 1;
		}

		#wpadminbar {
			cursor: pointer;
		}

		a[data-code='frm_first'], a[data-code='frm_fifth'], a[data-code='frm_two_fifths'],
		a[data-code='frm_sixth'], a[data-code='frm_seventh'], a[data-code='frm_eighth'],
		a[data-code='frm_alignright'], a[data-code='frm_inline'], a[data-code='frm_full'],
		a[data-code='frm_grid_first'],a[data-code='frm_grid'],a[data-code='frm_grid_odd'],
		a[data-code='frm_two_col'], a[data-code='frm_three_col'], a[data-code='frm_four_col'],
		a[data-code='frm_total'], a[data-code='frm_scroll_box'], a[data-code='frm_text_block'],
		a[data-code='frm_capitalize'] {
			opacity: .5;
			pointer-events: none;
		}
  </style>
	<?
}


add_filter('mce_css', 'tuts_mcekit_editor_style');
function tuts_mcekit_editor_style($url) {

    if ( !empty($url) )
        $url .= ',';

    // Retrieves the plugin directory URL and adds editor stylesheet
    // Change the path here if using different directories
    $url .= trailingslashit( plugin_dir_url(__FILE__) ) . 'editor-styles.css';

    return $url;
}


add_action( 'admin_init', 'my_deregister_editor_expand' );
function my_deregister_editor_expand() {
	wp_deregister_script('editor-expand');
}

add_filter( 'tiny_mce_before_init', 'my_unset_autoresize_on' );
function my_unset_autoresize_on( $init ) {
	unset( $init['wp_autoresize_on'] );
return $init;
}


// Edit dashboard widgets
// Function that outputs the contents of the dashboard widget
function dashboard_widget_left_function( $post, $callback_args ) {
	echo "<h2>Welcome to WordPress!</h2><p>Glad you&rsquo;re here, " . wp_get_current_user()->display_name . ".</p><p>Before you edit, familiarize yourself with the Copy Style Guide and the Getting Started guides in Google Drive. You&rsquo;ll also find links to analytics tools and specs for various imagery across the site.</p>";
}

function dashboard_widget_right_function( $post, $callback_args ) {
	?>
		<div style='text-align: center'><p><img src='<?= plugin_dir_url(__FILE__) . "auxmktlogo-01.png"?>' style='width:200px'></p>
	      <hr>
				<p>3405 Michigan Union<br>530 S State Street<br>Ann Arbor, MI	48109-1308</p><p><a href='marketing@umich.edu'>marketing@umich.edu</a></p>
				<hr><p><small>Copyright &copy; <?= date('Y'); ?> The Regents of the University of Michigan</small></p></div>
	<?
}

// Function used in the action hook
function add_dashboard_widgets() {
	wp_add_dashboard_widget('dashboard_widget', 'Getting Started', 'dashboard_widget_left_function');
	add_meta_box('id', 'About Auxiliary Marketing', 'dashboard_widget_right_function', 'dashboard', 'side', 'high');
}

// Hide irrelevant meta boxes and buttons on the Edit screen
add_action( 'admin_init', 'remove_pods_shortcode_button', 14 );
function remove_pods_shortcode_button () {
	if(class_exists("PodsInit")) {
		remove_action( 'media_buttons', array( PodsInit::$admin, 'media_button' ), 12 );
	}
}

// Register the new dashboard widget with the 'wp_dashboard_setup' action
add_action('wp_dashboard_setup', 'add_dashboard_widgets' );



add_action( 'do_meta_boxes', 'remove_featured_image_metabox');
function remove_featured_image_metabox() {
	remove_meta_box('postimagediv', 'page', 'side');
}

add_action( 'init', 'add_func_if_logged_in');
function add_func_if_logged_in() {
	if(is_user_logged_in()) {
		wp_enqueue_script('admins/admin_js', trailingslashit( plugin_dir_url(__FILE__) ) . 'admin-scripts.js', array('jquery'), null, true);
	}
}



// Include content type descriptions in edit screen
add_action('admin_footer', 'add_description_to_edit_pages');
function add_description_to_edit_pages()
{
	$message = false;

	$result = array();
	preg_match("/edit-.*/", get_current_screen()->id, $result);

	if (count($result)) {
		$post_type = get_post_type_object(get_current_screen()->post_type);
		if(get_current_screen()->post_type == "page") {
			$message = "The general content type that controls per-unit section pages, homepage functionality, and extra info.";
		} else {
			if(get_current_screen()->base != "revision") {
				$message = $post_type->description;
			}
		}
  }

  if ($message) {
    ?><script>
      jQuery(function($) {
        $('<div class="page-description" style="margin:5px 0 15px 0; font-size:1.25em; font-style: italic; color: #999"></div>').text('<?php echo $message; ?>').insertAfter('#wpbody-content .wrap h1:eq(0)');
      });
    </script><?php
  }
}


// Include template column in the Edit Page view
add_filter( 'manage_pages_columns', 'page_column_views' );
add_action( 'manage_pages_custom_column', 'page_custom_column_views', 5, 2 );
function page_column_views( $defaults )
{
	$defaults['page-layout'] = __('Kind of page (template)');
	unset($defaults['comments']);
  return $defaults;
}

add_action( 'manage_pages_custom_column', 'page_custom_column_views', 5, 2 );
function page_custom_column_views( $column_name, $id )
{
	if ( $column_name === 'page-layout' ) {
		$set_template = get_post_meta( get_the_ID(), '_wp_page_template', true );
		if ( $set_template == 'default' ) {
			echo 'Default ';
		}

		$templates = get_page_templates();
		ksort( $templates );

		$print = "Page";

		foreach ( array_keys( $templates ) as $template ) :
			if ( $set_template == $templates[$template] )
				$print = $template;
		endforeach;

		echo $print;
	}
}


// Redirect attempts to access attachment pages back to the homepage
function jvm_attachment_redirect() {
	if (is_attachment()) {
		wp_redirect(home_url('/'), 301); // permanent redirect to post/page where image or document was uploaded
		exit;
	}
}

add_action('template_redirect', 'jvm_attachment_redirect',1);


// Remove unnecessary filters that take up time.
remove_filter( 'the_title', 'capital_P_dangit', 11 );
remove_filter( 'the_content', 'capital_P_dangit', 11 );
remove_filter( 'comment_text', 'capital_P_dangit', 31 );


// Remove TinyMCE editor on templates that don't need it
function remove_editor() {
    if (isset($_GET['post'])) {
        $id = $_GET['post'];
        $template = get_post_meta($id, '_wp_page_template', true);

        if($template == 'template-detailpage.php' ||
				   $template == 'template-inspirationpage.php' ||
					 $template == 'template-search.php'){
            remove_post_type_support( 'page', 'editor' );
        }
    }
}
add_action('init', 'remove_editor');






// Visibly displays service level to the top-right menu
function environment_menu() {
 	global $wp_admin_bar;

 	$server = $_SERVER['HTTP_HOST'];
 	$env = "Unknown Environment";
 	$envclass = "unknown";

 	if(strpos($server, "localhost") !== false) {
 		$env = "Localhost:3000";
 		$envclass = "localhost";
		echo "<style>#wpadminbar { background: #613354 } #__bs_notify__ { z-index: 100000 !important }</style>";
 	} else if(strpos($server, "dev-recsports") !== false) {
 		$env = "Development";
 		$envclass = "dev";
		echo "<style>#wpadminbar { background: #00786a }</style>";
 	} else if(strpos($server, "test-recsports") !== false) {
 		$env = "Test";
 		$envclass = "test";
		echo "<style>#wpadminbar { background: #c25f00 }</style>";
 	} else if(strpos($server, "recsports") !== false) {
 		$env = "Production";
 		$envclass = "prod";
		echo "<style>#wpadminbar { background: #55575a }</style>";
 	} else {

	}

 	echo "<style>#wpadminbar .ab-empty-item, #wpadminbar a.ab-item { color: #eee !important; }  #wpadminbar li:hover>.ab-item { color: #00b9eb !important; } #wpadminbar .env { width: 8px; height: 8px; border: 1px solid white; border-radius: 10px; display: inline-block; margin-right: 5px; } #wpadminbar .env.env-unknown { background: #777 } #wpadminbar .env.env-localhost { background: #815374 } #wpadminbar .env.env-dev { background: #1e988a } #wpadminbar .env.env-test { background: #ff9124 } #wpadminbar .env.env-prod { background: #ececed }</style>";

 	$menu_id = 'dwb';
 	$wp_admin_bar->add_menu(array('id' => $menu_id, 'title' => '<span class="env env-' . $envclass . '"></span>' . $env, 'parent' => 'top-secondary'));
 	$wp_admin_bar->add_menu(array('parent' => $menu_id, 'title' => '<span class="env env-localhost"></span>Localhost:3000', 'id' => 'env-localhost', 'href' => 'http://localhost:3000/recsports'));
 	$wp_admin_bar->add_menu(array('parent' => $menu_id, 'title' => '<span class="env env-dev"></span>Development', 'id' => 'env-dev', 'href' => 'http://dev-recsports.studentlife.umich.edu'));
 	$wp_admin_bar->add_menu(array('parent' => $menu_id, 'title' => '<span class="env env-test"></span>Test', 'id' => 'env-test', 'href' => 'http://test-recsports.studentlife.umich.edu'));
 	$wp_admin_bar->add_menu(array('parent' => $menu_id, 'title' => '<span class="env env-prod"></span>Production', 'id' => 'env-prod', 'href' => 'http://recsports.umich.edu'));
}
add_action('admin_bar_menu', 'environment_menu', 2000);
