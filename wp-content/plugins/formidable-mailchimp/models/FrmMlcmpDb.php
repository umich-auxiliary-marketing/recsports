<?php

class FrmMlcmpDb {

	private $current_db_version = 0;
	private $new_db_version = 2;
	private $option_name = 'frm_mlcmp_db';

	public function __construct() {
		$this->set_db_version();
	}

	/**
	 * Check if MailChimp settings need migrating
	 *
	 * @since 2.0
	 * @return bool
	 */
	public function need_to_migrate_settings() {
		return $this->current_db_version < $this->new_db_version;
	}

	/**
	 * Migrate data to current version, if needed
	 *
	 * @since 2.0
	 */
	public function migrate() {
		if ( ! FrmMlcmpAppHelper::is_formidable_compatible() ) {
			return;
		}

		if ( $this->need_to_migrate_settings() ) {
			$this->migrate_to_2();
			update_option( $this->option_name, $this->new_db_version );
		}
	}

	/**
	 * Set the current db version property
	 * If it does not yet exist in the database, this will return 0
	 *
	 * @since 2.0
	 */
	private function set_db_version() {
		$this->current_db_version = (int) get_option( $this->option_name );
	}

	/**
	 * Convert settings to MailChimp action
	 * Update group settings for 3.0 API
	 *
	 * @since 2.0
	 */
	private function migrate_to_2() {
		$forms = FrmForm::getAll();
		foreach ( $forms as $form ) {
			FrmMlcmpActionController::migrate_settings_to_action( $form );
		}

		// Migrate old group settings to new group settings
		$action_control = FrmFormActionsController::get_form_actions( 'mailchimp' );
		$form_actions   = $action_control->get_all();

		foreach ( $form_actions as $form_action ) {
			if ( isset( $form_action->post_content['groups'] ) && ! empty( $form_action->post_content['groups'] ) ) {
				$list_id        = $form_action->post_content['list_id'];
				$updated_groups = array();
				foreach ( $form_action->post_content['groups'] as $group_id => $group_settings ) {
					self::get_new_group_settings( compact( 'group_id', 'group_settings', 'list_id' ), $updated_groups );
				}
				if ( ! empty( $updated_groups ) ) {
					$form_action->post_content['groups'] = $updated_groups;
					$action_control->save_settings( (array) $form_action );
				}
			}
		}
	}

	private static function get_new_group_settings( $args, &$updated_groups ) {
		if ( is_numeric( $args['group_id'] ) && isset( $args['group_settings']['id'] ) && is_numeric( $args['group_settings']['id'] ) ) {

			$groups       = FrmMlcmpAppController::get_groups( $args['list_id'] );
			$old_groups   = FrmMlcmpAppController::get_group_ids( $groups, $args['list_id'] );
			$new_group_id = array_search( $args['group_id'], $old_groups );

			if ( $new_group_id ) {
				$group_opts         = FrmMlcmpAppController::get_group_options( $args['list_id'], $new_group_id );
				$new_group_settings = array( 'id' => $args['group_settings']['id'] );

				foreach ( $group_opts['interests'] as $group_opt ) {
					if ( isset( $args['group_settings'][ $group_opt['name'] ] ) ) {
						$new_group_settings[ $group_opt['id'] ] = $args['group_settings'][ $group_opt['name'] ];
					}
				}
				$updated_groups[ $new_group_id ] = $new_group_settings;
			}
		}
	}
}
