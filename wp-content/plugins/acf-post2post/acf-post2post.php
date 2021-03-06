<?php

	/*
		Plugin Name: Post2Post for ACF
		Plugin URI: https://github.com/Hube2/acf-post2post
		Description: Two way relationship fields
		Version: 1.2.2
		Author: John A. Huebner II
		Author URI: https://github.com/Hube2
		GitHub Plugin URI: https://github.com/Hube2/acf-post2post
		License: GPL v2 or later

		Automatic creation of 2 way post relationship and post object fields

		This plugin will not provide any functionality if ACF is not installed

	*/

	// If this file is called directly, abort.
	if (!defined('WPINC')) {die;}

	new acf_post2post();

	class acf_post2post {

		public function __construct() {
			register_activation_hook(__FILE__, array($this, 'activate'));
			register_deactivation_hook(__FILE__, array($this, 'deactivate'));
			add_filter('acf/update_value/type=relationship', array($this, 'update_relationship_field'), 11, 3);
			add_filter('acf/update_value/type=post_object', array($this, 'update_relationship_field'), 11, 3);
			add_filter('jh_plugins_list', array($this, 'meta_box_data'));
		} // end public function __construct

			function meta_box_data($plugins=array()) {

				$plugins[] = array(
					'title' => 'Post2Post for ACF',
					'screens' => array('acf-field-group', 'edit-acf-field-group'),
					'doc' => 'https://github.com/Hube2/acf-post2post'
				);
				return $plugins;

			} // end function meta_box

		public function update_relationship_field($value, $post_id, $field) {
			$field_name = $field['name'];
			//echo $field_name; die;
			//echo $post_id; die;
			//echo get_post_meta($post_id, $field_name, true); die;
			$previous = maybe_unserialize(get_post_meta($post_id, $field_name, true));
			if ($previous === '') {
				$previous = array();
			}
			if (!is_array($previous)) {
				$previous = array($previous);
			}
			array_walk($previous, 'intval');
			// get new value
			$new = $value;
			if (!$new) {
				$new = array();
			}
			if (!is_array($new)) {
				$new = array($new);
			}
			//echo '<pre>'; print_r($previous); print_r($new); die;
			if (count($previous)) {
				foreach ($previous as $related_id) {
					if (!in_array($related_id, $new)) {
						$this->remove_relationship($related_id, $field_name, $post_id);
					}
				}
			}
			if (count($new)) {
				foreach ($new as $related_id) {
					$this->add_relationship($related_id, $field_name, $post_id);
				}
			}
			return $value;
		} // end public function update_relationship_field

		private function remove_relationship($post_id, $field_name, $related_id) {
			/*
				$post_id = the post id to remove the relationship from
				$field_name = the field name to update
				$related_id = the relationship to remove
			*/
			$field = $this->get_field($post_id, $field_name);
			$array_value = true;
			if ($field['type'] == 'post_object') {
				if (!$field['multiple']) {
					$array_value = false;
				}
			}
			$values = maybe_unserialize(get_post_meta($post_id, $field_name, true));
			if ($values == '') {
				$values = array();
			}
			if (!is_array($values)) {
				$values = array($values);
			}
			if (!count($values)) {
				// nothing to delete
				return;
			}
			$new_values = array();
			foreach ($values as $value) {
				if ($value != $related_id) {
					$new_values[] = $value;
				}
			}
			if (!count($new_values) && !$array_value) {
				$new_values = '';
			}
			update_post_meta($post_id, $field_name, $new_values);
			update_post_meta($post_id, '_'.$field_name, $field['key']);
		} // end private function remove_relationship

		private function add_relationship($post_id, $field_name, $related_id) {
			/*
				$post_id = the post id to add the relationship to
				$field_name = the field name to update
				$related_id = the relationship to add
			*/
			$field = $this->get_field($post_id, $field_name);
			//echo '<pre>'; print_r($field); die;
			if (!$field) {
				// field not found attached to this post
				return;
			}
			$max_posts = 0;
			$array_value = true;
			if ($field['type'] == 'post_object') {
				if (!$field['multiple']) {
					$max_posts = 1;
					$array_value = false;
				} elseif ($field['type'] == 'relationship') {
					if ($field['max']) {
						$max_posts = $field['max'];
					}
				}
			}
			$value = maybe_unserialize(get_post_meta($post_id, $field_name, true));
			if ($value == '') {
				$value = array();
			}
			if (!is_array($value)) {
				$value = array($value);
			}
			if (($max_posts == 0 || count($value) < $max_posts) &&
					!in_array($related_id, $value)) {
				$value[] = $related_id;
			} elseif ($max_posts > 0) {
				$overwrite_settings = apply_filters('acf-post2post/overwrite-settings', array());
				if (isset($overwrite_settings[$field_name]) &&
					isset($overwrite_settings[$field_name]['overwrite']) &&
					$overwrite_settings[$field_name]['overwrite']) {
					$type = 'first';
					if (isset($overwrite_settings[$field_name]['type']) &&
							in_array(strtolower($overwrite_settings[$field_name]['type']), array('first', 'last'))) {
						$type = strtolower($overwrite_settings[$field_name]['type']);
					}
					if ($type == 'first') {
						$remove = array_shift($value);
					} else {
						$remove = array_pop($value);
					}
					// remove this relationship from the post that was just removed
					$this->remove_relationship(intval($remove), $field_name, $post_id);
					$value[] = $related_id;
				} // end field overwrite
			} // end if else
			if (!$array_value) {
				$value = $value[0];
			}
			//echo '<pre>'; print_r($value); die;
			update_post_meta($post_id, $field_name, $value);
			update_post_meta($post_id, '_'.$field_name, $field['key']);
		} // end private function add_relationship

		public function get_field($post_id, $field_name) {
			$field = false;
			$found = false;
			$cache_key = 'get_field-'.$post_id.'-'.$field_name;
			$cache = wp_cache_get($cache_key, 'acfpost2post', false, $found);
			if ($found) {
				return $cache;
			}
			//echo 'after-cache'; die;
			$found = false;
			$field_groups = $this->post_field_groups($post_id);
			$field_group_count = count($field_groups);
			//echo '<pre>'; print_r($field_groups); die;
			for ($g=0; $g<$field_group_count; $g++) {
				$field_count = count($field_groups[$g]['fields']);
				for ($f=0; $f<$field_count; $f++) {
					if ($field_groups[$g]['fields'][$f]['name'] == $field_name &&
						in_array($field_groups[$g]['fields'][$f]['type'], array('relationship', 'post_object'))) {
						$field = $field_groups[$g]['fields'][$f];
						$found == true;
						break;
					}
				} // end for $f
				if ($found) {
					break;
				}
			} // end for $g
			wp_cache_set($cache_key, $field, 'acfpost2post');
			return $field;
		} // end public function get_field

		public function post_field_groups($post_id) {
			$found = false;
			$cache = wp_cache_get('post_field_groups-'.$post_id, 'acfpost2post', false, $found);
			if ($found) {
				return $cache;
			}
			$args = array('post_id' => $post_id);
			$field_groups = acf_get_field_groups($args);
			//echo '<pre>'; print_r($field_groups); die;
			$count = count($field_groups);
			for ($i=0; $i<$count; $i++) {
				$field_groups[$i]['fields'] = acf_get_fields($field_groups[$i]['key']);
			}
			//echo '<pre>'; print_r($field_groups); die;
			wp_cache_set('post_field_groups-'.$post_id, $field_groups, 'acfpost2post');
			return $field_groups;
		} // end public function post_field_groups

		public function activate() {
			// just in case I need to do something to activate
		} // end public function activate

		public function deactivate() {
			// just in case I need to do something to deactivate
		} // end public function deactivate

	} // end class acf_post2post
?>
