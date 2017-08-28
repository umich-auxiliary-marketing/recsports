<?
// THIS NEEDS TO BE AT THE TOP.
// Redirection rules for section pages (so that subsection links are handled).
function add_query_vars($vars) {
	$vars[] = "sectionanchor";
	return $vars;
}
add_filter('query_vars', 'add_query_vars');

add_action('parse_request','affect_url');
function affect_url() {
	global $wp;

	if(isset($wp->query_vars['sectionanchor'])) {
		wp_redirect(get_bloginfo('url').'/'.$wp->query_vars['pagename'].'/#'.strtolower($wp->query_vars['sectionanchor']), 301);
		exit;
	}
}




function my_acf_init() {
	acf_update_setting('google_api_key', 'AIzaSyC75rKmk8wDd0bXaJmemJFz5pFL0W80_rg');
}
add_action('acf/init', 'my_acf_init');



add_action('init', 'recsports_redirection_rules', PHP_INT_MAX);
function recsports_redirection_rules() {
	add_rewrite_rule('^(?!secure|notice|location|wp-admin|wp-content)([^/]+)/([^/]+)/?', 'index.php?pagename=$matches[1]&sectionanchor=$matches[2]', 'top');
}


/* groupx|fitness|training|clubs|intramurals|rentals|outdoor|challenge */


function cron_testings () {
	$data = "The current time is " . date("D, d M Y H:i:s") . "... hooray!";
	file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/recsports/wp-content/crons/crontest.txt", $data);

}
add_action('cron_testings', 'cron_testings');

function cron_auxmkt_google_calendar() {
	AuxMkt_Google_Calendar_Cacher::build_calendar_cache("umich.edu_03trdc696kl0cjkdhpf69ui38g@group.calendar.google.com","groupx");
	AuxMkt_Google_Calendar_Cacher::build_calendar_cache("umich.edu_3f5h0rs9r3422rgsefdkeueoqs@group.calendar.google.com","ccrb");
	AuxMkt_Google_Calendar_Cacher::build_calendar_cache("umich.edu_1ou2dak29975ftdj9pl0i3nkg4@group.calendar.google.com","bellpool");
	AuxMkt_Google_Calendar_Cacher::build_calendar_cache("umich.edu_i2ie64trqplb1p9k1m5pohb86c@group.calendar.google.com","imsb");
	AuxMkt_Google_Calendar_Cacher::build_calendar_cache("umich.edu_03ksu9naojgm9q3ru5e5j35gf4@group.calendar.google.com","ncrb");
	AuxMkt_Google_Calendar_Cacher::build_calendar_cache("umich.edu_7boi038dp0ansqp836797fqbbc@group.calendar.google.com","ncrbpool");
	AuxMkt_Google_Calendar_Cacher::build_calendar_cache("umich.edu_8g3vk8gqr3nhu2c1tgnbno3hh0@group.calendar.google.com","coliseum");
	AuxMkt_Google_Calendar_Cacher::build_calendar_cache("umich.edu_j38o592smt3ks0pua865h42cl8@group.calendar.google.com","rentalcenter");
	AuxMkt_Google_Calendar_Cacher::build_calendar_cache("umich.edu_rajei872o8vjedc7birj2mdg6o@group.calendar.google.com","oatrips");
}
add_action('cron_auxmkt_google_calendar', 'cron_auxmkt_google_calendar');



// Modify TinyMCE settings for this theme
function my_format_TinyMCE( $in ) {
	$in['gecko_spellcheck'] = true;
	$in['keep_styles'] = false;
	$in['invalid_elements'] = "span,i,em,div,b";
	return $in;
}
add_filter( 'tiny_mce_before_init', 'my_format_TinyMCE' );



/**
 * Disable content pagination for post post type in the main loop
 *
 * @link http://wordpress.stackexchange.com/a/208784/26350
 */
add_filter( 'content_pagination', 'disable_content_pagination', 10, 2);
function disable_content_pagination( $pages, $post ) {
  if ( in_the_loop() && ($post->post_type === 'notice' || $post->post_type === 'location') ) {
  	$pages = array(join( '', $pages ));
	}

  return $pages;
}


add_filter('next_posts_link_attributes', 'posts_link_attributes');
add_filter('previous_posts_link_attributes', 'posts_link_attributes');

function posts_link_attributes() {
    return 'class="button"';
}



/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = array(
  'lib/assets.php',    // Scripts and stylesheets
  'lib/extras.php',    // Custom functions
  'lib/setup.php',     // Theme setup
  'lib/titles.php',    // Page titles
  'lib/wrapper.php',   // Theme wrapper class
  'lib/customizer.php' // Theme customizer
);

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);


function change_separator() {
    return "&bull;";
}
add_filter('document_title_separator', 'change_separator');


// Filter wp_get_document_title() to show hierarchical titles where it makes sense
function jvm_override_document_title_parts($title) {
	global $post;

	if(isset($title['tagline'])) {
    unset($title['tagline']);
  }

	if(is_front_page()) {
		$title["title"] = "Recreational Sports &bull; University of Michigan";
	}

  return $title;
}
add_filter( 'document_title_parts', 'jvm_override_document_title_parts', 10, 2 );



if(!is_admin()) {
	add_action( 'wp_default_scripts', function( $scripts ) {
		if ( ! empty( $scripts->registered['jquery'] ) ) {
			$jquery_dependencies = $scripts->registered['jquery']->deps;
			$scripts->registered['jquery']->deps = array_diff( $jquery_dependencies, array( 'jquery-migrate' ) );
		}
	} );


	// Removes jQuery 1 and uses jQuery 3 (but only on the front-end).
	add_filter( 'wp_enqueue_scripts', 'change_default_jquery', PHP_INT_MAX );

	function change_default_jquery( ){
	  wp_dequeue_script( 'jquery');
	  wp_deregister_script( 'jquery');
		wp_register_script( 'jquery', get_template_directory_uri() . '/dist/scripts/jquery.js', array(), false, true);
		wp_enqueue_script('jquery');
	}
}




function _wp_fix_issue_25449($upload_dir) {
	if ( is_ssl() ) {
		$upload_dir['url']     = set_url_scheme( $upload_dir['url'] , 'https' );
		$upload_dir['baseurl'] = set_url_scheme( $upload_dir['baseurl'] , 'https' );
	} else {
		$upload_dir['url']     = set_url_scheme( $upload_dir['url'] , 'http' );
		$upload_dir['baseurl'] = set_url_scheme( $upload_dir['baseurl'] , 'http' );
	}
	return $upload_dir;
}
add_filter( 'upload_dir' , '_wp_fix_issue_25449' );




// Plop SVG into the markup. It plays nicely with localhost.
function inject_svg($file, $is_attachment = true) {

	if($is_attachment) {
		$url = get_attached_file( $file );
	} else {
		$url = dirname(__FILE__) . $file;
	}

	// TODO: add something about a 404 response code
	include($url);
	return;
}


// Check for any notices associated with the page and display them.
function inject_page_notices($meta, $is_global_notices = false, $use_full_width = false) {
	// Check first that notices actually reference something real. If not, remove
	// it from the list.
	if(!$meta) return;
	if(is_string($meta)) $meta = array($meta);

	$notices = array();

	foreach($meta as $pending) {
		if(get_post_status($pending) === 'publish') {
			$notices[] = $pending;
		}
	}

	if(count($notices)) {
		if($is_global_notices) {?>
			<div class="container container--notice container--notice--home">
				<div class="wrapper wrapper--notice">
		<? } else { ?>
			<? $classes = ($use_full_width ? "noticeblock noticeblock--fullwidth" : "content noticeblock"); ?>
			<div class="<?=$classes?>">
		<? } ?>
		<? foreach($notices as $notice): ?>
			<? $nmeta = get_post_meta($notice, '', false); ?>
				<? include(locate_template('partial-templates/notice.php', false, false)); ?>
		<? endforeach;
		if($is_global_notices) {?>
		</div>
		</div>
		<? } else { ?>
		</div>
		<? }
	}
}



function mce_mod( $init ) {
    $init['block_formats'] = 'Paragraph=p;Header=h3;Sub-Header=h4;Sub-Sub-Header=h5';
    return $init;
}
add_filter('tiny_mce_before_init', 'mce_mod');



// Relative time strings.
function time2str($ts) {
    if(!ctype_digit($ts)) {
        $ts = strtotime($ts);
    }
    $diff = time() - $ts;
    if($diff == 0) {
        return 'now';
    } elseif($diff > 0) {
        $day_diff = floor($diff / 86400);
        if($day_diff == 0) {
            if($diff < 60) return 'just now';
            if($diff < 120) return '1 minute ago';
            if($diff < 3600) return floor($diff / 60) . ' minutes ago';
            if($diff < 7200) return '1 hour ago';
            if($diff < 86400) return floor($diff / 3600) . ' hours ago';
        }
        if($day_diff == 1) { return 'yesterday'; }
        if($day_diff < 7) { return $day_diff . ' days ago'; }
				if($day_diff < 14) { return 'a week ago'; }
        if($day_diff < 31) { return ceil($day_diff / 7) . ' weeks ago'; }
        if($day_diff < 60) { return 'last month'; }
        return date('F Y', $ts);
    } else {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);
        if($day_diff == 0) {
            if($diff < 120) { return 'in a minute'; }
            if($diff < 3600) { return 'in ' . floor($diff / 60) . ' minutes'; }
            if($diff < 7200) { return 'in an hour'; }
            if($diff < 86400) { return 'in ' . floor($diff / 3600) . ' hours'; }
        }
        if($day_diff == 1) { return 'tomorrow'; }
        if($day_diff < 4) { return date('l', $ts); }
        if($day_diff < 7 + (7 - date('w'))) { return 'next week'; }
        if(ceil($day_diff / 7) < 4) { return 'in ' . ceil($day_diff / 7) . ' weeks'; }
        if(date('n', $ts) == date('n') + 1) { return 'next month'; }
        return date('F Y', $ts);
    }
}


function start_collapsed_section(&$collapse, $partial, &$meta, $metakey) {
	if($partial == "sectionheader" && isset($meta[$metakey]) && $meta[$metakey][0]) {
		?>
		<div class="content collapsible">
		<?

		$collapse = true;
	}
}

function end_collapsed_section(&$collapse, $partial, $forceend = false) {
	if($collapse && ($forceend || $partial == "sectionheader")) {
		?>
		</div>
		<?

		$collapse = false;
	}
}
