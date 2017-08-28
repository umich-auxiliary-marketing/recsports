<?php

class FrmMlcmpAppHelper {

	private static $min_formidable_version = 2.0;

	public static function plugin_folder() {
		return basename( self::plugin_path() );
	}

	public static function plugin_path() {
		return dirname( dirname( __FILE__ ) );
	}

	/**
	 * Check if the current version of Formidable is compatible with MailChimp add-on
	 *
	 * @since 2.0
	 * @return mixed
	 */
	public static function is_formidable_compatible() {
		$frm_version = is_callable( 'FrmAppHelper::plugin_version' ) ? FrmAppHelper::plugin_version() : 0;

		return version_compare( $frm_version, self::$min_formidable_version, '>=' );
	}

	public static function get_default_options() {
		return array(
			'mailchimp'  => 0,
			'mlcmp_list' => array()
		);
	}

	public static function include_logic_row( $meta_name, $form_id, $list_id, $values ) {
		if ( ! method_exists( 'FrmProFormsController', 'include_logic_row' ) ) { // added in 1.07.05
			return;
		}

		FrmProFormsController::include_logic_row( array(
			'meta_name' => $meta_name,
			'condition' => array(
				'hide_field'      => ( isset( $values['hide_field'] ) && isset( $values['hide_field'][ $meta_name ] ) ) ? $values['hide_field'][ $meta_name ] : '',
				'hide_field_cond' => ( isset( $values['hide_field_cond'] ) && isset( $values['hide_field_cond'][ $meta_name ] ) ) ? $values['hide_field_cond'][ $meta_name ] : '',
				'hide_opt'        => ( isset( $values['hide_opt'] ) && isset( $values['hide_opt'][ $meta_name ] ) ) ? $values['hide_opt'][ $meta_name ] : '',
			),
			'type'      => 'mlcmp',
			'showlast'  => '.frm_mlcmp_fields_' . $list_id . ' .frm_add_logic_link',
			'key'       => 'mlcmp_' . $list_id,
			'form_id'   => $form_id,
			'id'        => 'frm_mlcmp_logic_' . $list_id . '_' . $meta_name,
			'names'     => array(
				'hide_field'      => 'options[mlcmp_list][' . $list_id . '][hide_field][]',
				'hide_field_cond' => 'options[mlcmp_list][' . $list_id . '][hide_field_cond][]',
				'hide_opt'        => 'options[mlcmp_list][' . $list_id . '][hide_opt][]',
			),
		) );
	}

	public static function get_entry_or_post_value( $entry, $field_id ) {
		$value = '';
		if ( ! empty( $entry ) && isset( $entry->metas[ $field_id ] ) ) {
			$value = $entry->metas[ $field_id ];
		} else if ( isset( $_POST['item_meta'][ $field_id ] ) ) {
			$value = $_POST['item_meta'][ $field_id ];
		}

		return $value;
	}
}