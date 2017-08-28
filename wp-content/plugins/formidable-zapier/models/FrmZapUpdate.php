<?php

class FrmZapUpdate extends FrmAddon {

	public $plugin_file;
	public $plugin_name = 'Zapier';
	public $version = '1.02';

	public function __construct() {
		$this->plugin_file = dirname( dirname( __FILE__ ) ) . '/formidable-zapier.php';
		parent::__construct();
	}

	public static function load_hooks() {
		add_filter( 'frm_include_addon_page', '__return_true' );
		new FrmZapUpdate();
	}

}
