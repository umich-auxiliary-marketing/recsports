<?php
add_action( 'admin_menu', 'auxmktcal_add_admin_menu' );
add_action( 'admin_init', 'auxmktcal_settings_init' );


function auxmktcal_add_admin_menu(  ) {

	add_options_page( 'AuxMkt Google Calendar', 'AuxMkt Google Calendar', 'manage_options', 'auxmkt_google_calendar', 'auxmktcal_options_page' );

}


function auxmktcal_settings_init(  ) {

	register_setting( 'pluginPage', 'auxmktcal_settings' );

	add_settings_section(
		'auxmktcal_pluginPage_section',
		__( 'API credentials', 'wordpress' ),
		'auxmktcal_settings_section_callback',
		'pluginPage'
	);

	add_settings_field(
		'auxmktcal_developerkey',
		__( 'API developer key', 'wordpress' ),
		'auxmktcal_developerkey_render',
		'pluginPage',
		'auxmktcal_pluginPage_section'
	);

	add_settings_field(
		'auxmktcal_authconfigjson',
		__( 'Authorization config JSON', 'wordpress' ),
		'auxmktcal_authconfigjson_render',
		'pluginPage',
		'auxmktcal_pluginPage_section'
	);


}


function auxmktcal_developerkey_render(  ) {

	$options = get_option( 'auxmktcal_settings' );
	?>
	<input type='text' name='auxmktcal_settings[auxmktcal_developerkey]' value='<?php echo $options['auxmktcal_developerkey']; ?>'>
	<?php

}


function auxmktcal_authconfigjson_render(  ) {

	$options = get_option( 'auxmktcal_settings' );
	?>
	<input type='text' name='auxmktcal_settings[auxmktcal_authconfigjson]' value='<?php echo $options['auxmktcal_authconfigjson']; ?>'>
	<p class="description">First, place the config file in this plugin's folder under the &ldquo;credentials&rdquo; folder. Then, enter the filename of the JSON or P12 file.</p>
	<?php

}


function auxmktcal_settings_section_callback(  ) {

	echo __( 'Enter credentials here that will be used across the site.', 'wordpress' );

}


function auxmktcal_options_page(  ) {

	?>
	<div class="wrap">
	<form action='options.php' method='post'>

		<h1>AuxMkt Google Calendar</h1>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
	<hr>
	<h2>Tutorial: setting up a cron job for caching calendar data</h2>
	<p>In order to leverage as much built-in functionality, use a cron job to schedule the updating of your calendar data. You need to specify one crob job per desired dataset.</p>
	<ol>
		<li>Enable the Cronjob Scheduler plugin, including setting up cron as specified. (Note: you may need to modify the path of <code>wget</code> and/or add in the &ldquo;https://&rsquo; to the URL specified in the command.)</li>
		<li>In your <code>functions.php</code> file, create a function like so:<pre>
function <strong>cron_auxmkt_calendar_123</strong>() {
  AuxMkt_Google_Calendar::build_calendar_cache("<strong>yourcalendarid@cool.com</strong>");
}
add_action('<strong>cron_auxmkt_calendar_123</strong>', '<strong>cron_auxmkt_calendar_123</strong>');
</pre></li>
		<li>Return to the Cronjob Scheduler and paste the name of the function you just created (e.g., <code>cron_auxmkt_calendar_123</code>). Set the Run Schedule (that is, the time between checking for calendar updates) to at least every 10 minutes.</li>
		<li>Click the Create Cronjob button to save the job.</li>
	</ol>
	<p>If you ever need to trigger the cron job manually, or if you want to test it out, click the Run button after you have saved the entry. If you need to make edits to the job, delete the entry and start over.</p>
</div>
	<?php

}

?>
