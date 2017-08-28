<?php

namespace Roots\Sage\Setup;

use Roots\Sage\Assets;

/**
 * Theme setup
 */
function setup() {
  // Enable features from Soil when plugin is activated
  // https://roots.io/plugins/soil/
  add_theme_support('soil-clean-up');
  add_theme_support('soil-nav-walker');
  //add_theme_support('soil-nice-search');
  //add_theme_support('soil-jquery-cdn');
  add_theme_support('soil-relative-urls');

  // Make theme available for translation
  // Community translations can be found at https://github.com/roots/sage-translations
  load_theme_textdomain('sage', get_template_directory() . '/lang');

  // Enable plugins to manage the document title
  // http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
  add_theme_support('title-tag');

  // Register wp_nav_menu() menus
  // http://codex.wordpress.org/Function_Reference/register_nav_menus
  register_nav_menus(array(
    'utilities_nav' => 'Top Utilities Menu',
		'main_nav' => 'Main Menu',
		'footer_nav' => 'Footer Menu'
  ));

  // Enable post thumbnails
  // http://codex.wordpress.org/Post_Thumbnails
  // http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
  // http://codex.wordpress.org/Function_Reference/add_image_size
  add_theme_support('post-thumbnails');

  // Enable post formats
  // http://codex.wordpress.org/Post_Formats
  add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio'));

  // Enable HTML5 markup support
  // http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
  add_theme_support('html5', array('caption', 'comment-form', 'comment-list', 'gallery', 'search-form'));

  // Use main stylesheet for visual editor
  // To add custom styles edit /assets/styles/layouts/_tinymce.scss
  add_editor_style(Assets\asset_path('styles/main.css'));
}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup');

/**
 * Register sidebars
 */
function widgets_init() {
  register_sidebar(array(
    'name'          => __('Primary', 'sage'),
    'id'            => 'sidebar-primary',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ));

  register_sidebar(array(
    'name'          => __('Footer', 'sage'),
    'id'            => 'sidebar-footer',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ));
}
add_action('widgets_init', __NAMESPACE__ . '\\widgets_init');

/**
 * Determine which pages should NOT display the sidebar
 */
function display_sidebar() {
  static $display;

  isset($display) || $display = !in_array(true, array(
    // The sidebar will NOT be displayed if ANY of the following return true.
    // @link https://codex.wordpress.org/Conditional_Tags
    is_404(),
    is_front_page(),
    is_page_template('template-custom.php'),
  ));

  return apply_filters('sage/display_sidebar', $display);
}

/**
 * Theme assets
 */
function assets() {
  wp_enqueue_style('sage/css', Assets\asset_path('styles/main.css'), false, null);

	wp_enqueue_script('sage/velocity', Assets\asset_path('scripts/velocity.js'), array('jquery'), null, true);
	wp_enqueue_script('sage/sisyphus', Assets\asset_path('scripts/sisyphus.js'), array(), null, true);
	wp_enqueue_script('sage/throttle', Assets\asset_path('scripts/throttle.js'), array('jquery'), null, true);
	wp_enqueue_script('sage/fastclick', Assets\asset_path('scripts/fastclick.js'), array('jquery'), null, true);
	wp_enqueue_script('sage/stickykit', Assets\asset_path('scripts/stickykit.js'), array('jquery', 'sage/throttle'), null, true);
	wp_enqueue_script('sage/jquerypin', Assets\asset_path('scripts/jquery.pin.js'), array('jquery', 'sage/throttle'), null, true);
  wp_enqueue_script('sage/sidebar', Assets\asset_path('scripts/sidebar.js'), array('jquery', 'sage/throttle'), null, true);
	wp_enqueue_script('sage/visualnav', Assets\asset_path('scripts/activenav.js'), array('jquery', 'sage/throttle'), null, true);
	wp_enqueue_script('sage/headroom', Assets\asset_path('scripts/headroom.js'), array('jquery', 'sage/throttle'), null, true);
	wp_enqueue_script('sage/moment', Assets\asset_path('scripts/moment.js'), array('jquery'), null, true);
	wp_enqueue_script('sage/facility', Assets\asset_path('scripts/facility.js'), array('jquery'), null, true);
	wp_enqueue_script('sage/navigation', Assets\asset_path('scripts/navigation.js'), array('jquery', 'sage/video'), null, true);
	wp_enqueue_script('sage/stickers', Assets\asset_path('scripts/stickers.js'), array('jquery', 'sage/headroom', 'sage/throttle'), null, true);
	wp_enqueue_script('sage/video', Assets\asset_path('scripts/video.js'), array('jquery'), null, true);
	wp_enqueue_script('sage/js', Assets\asset_path('scripts/main.js'), array('jquery', 'sage/stickykit', 'sage/headroom', 'sage/throttle', 'sage/moment'), null, true);
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\assets', 100);
