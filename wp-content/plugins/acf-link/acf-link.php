<?php
/**
 * Plugin Name: Advanced Custom Fields: Link
 * Description: <strong><strong>Modified</strong></strong> Allows you to choose a link to an existing piece of content, or enter your own. Modified to work with PHP 5.3.
 * Author:      Corey Worrell, EMRL
 * Author URI:  http://emrl.com
 * Version:     1.1.4
 */
add_action('acf/include_field_types', function ($version) {
    include 'src/AcfFieldLink.php';
    new Emrl\AcfFieldLink(__FILE__);
});
