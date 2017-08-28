<?php

namespace Emrl;

use acf_field;
use _WP_Editors;

class AcfFieldLink extends acf_field
{
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;

        $this->name     = 'link';
        $this->label    = 'Link';
        $this->category = 'relational';

        $this->defaults = array(
            'show_fields' => array('title', 'target'),
        );

        $this->default_value = array(
            'url'    => null,
            'title'  => null,
            'target' => false,
        );

        $this->l10n = array(
            'sameTab' => __('same window/tab', 'acf-link'),
            'newTab'  => __('new window/tab', 'acf-link'),
        );

        // Fix for pages that don't have editors
        add_action('admin_print_footer_scripts', function () {
            if ( ! class_exists('_WP_Editors') and ( ! defined('DOING_AJAX') or ! DOING_AJAX)) {
                require_once ABSPATH.WPINC.'/class-wp-editor.php';
                wp_print_styles('editor-buttons');
                _WP_Editors::wp_link_dialog();
            }
        });

        parent::__construct();
    }

    public function input_admin_enqueue_scripts()
    {
        $dir = plugin_dir_url($this->path);

        wp_enqueue_script('wplink');
        wp_enqueue_script('acf-input-link', "{$dir}js/input.js");
        wp_enqueue_style('acf-input-link', "{$dir}css/input.css");
    }

    public function render_field_settings($field)
    {
        acf_render_field_setting($field, array(
            'label' => __('Display Fields', 'acf-link'),
            'type'  => 'checkbox',
            'name'  => 'show_fields',

            'choices' => array(
                'title'  => 'Display Title',
                'target' => 'Display Target',
            ),
        ));
    }

    public function render_field($field)
    {
        if ( ! is_array($field['value'])) {
            $field['value'] = $this->default_value;
        }

        if ( ! is_array($field['show_fields'])) {
            $field['show_fields'] = array();
        }

        $values = $field['value'];

        include dirname($this->path).'/templates/field.php';
    }
}
