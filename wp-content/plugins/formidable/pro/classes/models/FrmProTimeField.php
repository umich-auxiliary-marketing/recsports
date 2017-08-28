<?php

class FrmProTimeField {

	public static function time_array_to_string( &$value ) {
		if ( self::is_time_empty( $value ) ) {
			$value = '';
		} elseif ( is_array( $value ) ) {
			$new_value = $value['H'] . ':' . $value['m'];
			$new_value .= ( isset( $value['A'] ) ? ' ' . $value['A'] : '' );
			$value = $new_value;
		}
	}

	public static function is_time_empty( $value ) {
		$empty_string = ! is_array( $value ) && $value == '';
		$empty_array = is_array( $value ) && ( $value['H'] == '' || $value['m'] == '' );
		return $empty_string || $empty_array;
	}

	public static function get_time_options( $values ) {
		self::prepare_time_settings( $values );

		$options = array();
		self::get_single_time_field_options( $values, $options );

		$use_single_dropdown = FrmField::is_option_true( $values, 'single_time' );
		if ( ! $use_single_dropdown ) {
			self::get_multiple_time_field_options( $values, $options );
		}

		return $options;
	}

	private static function prepare_time_settings( &$values ) {
		self::fill_default_time( $values['start_time'], '00:00' );
		self::fill_default_time( $values['end_time'], '23:59' );

		$values['start_time_str'] = $values['start_time'];
		$values['end_time_str'] = $values['end_time'];

		self::split_time_setting( $values['start_time'], '00:00' );
		self::split_time_setting( $values['end_time'], '23:59' );

		self::step_in_minutes( $values['step'] );

		$values['hour_step'] = floor( $values['step'] / 60 );
		if ( ! $values['hour_step'] ) {
			$values['hour_step'] = 1;
		}

		if ( $values['end_time'][0] < $values['start_time'][0] ) {
			$values['end_time'][0] += 12;
		}
	}

	private static function fill_default_time( &$time, $default ) {
		if ( empty( $time ) ) {
			$time = $default;
		}
	}

	private static function split_time_setting( &$time ) {
		$separator = ':';

		$time = FrmProAppHelper::format_time( $time );
		$time = explode( $separator, $time );
	}

	private static function step_in_minutes( &$step ) {
		$separator = ':';
		$step = explode( $separator, $step );
		$step = ( isset( $step[1] ) ) ? ( ( $step[0] * 60 ) + $step[1] ) : ( $step[0] );
		if ( empty( $step ) ) {
			// force an hour step if none was defined to prevent infinite loop
			$step = 60;
		}
	}

	private static function get_single_time_field_options( $values, &$options ) {
		$time = strtotime( $values['start_time_str'] );
		$end_time = strtotime( $values['end_time_str'] );
		$format = ( $values['clock'] == 24 ) ? 'H:i' : 'g:i A';
		$values['step'] = $values['step'] * 60; //switch minutes to seconds

		$options[] = '';
		while ( $time <= $end_time ) {
			$options[] = date( $format, $time );
			$time += $values['step'];
		}
	}

	private static function get_multiple_time_field_options( $values, &$options ) {
		$all_times = $options;

		$options['H'] = array('');
		$options['m'] = array('');

		self::get_hours( $all_times, $options );
		self::get_minutes( $all_times, $options );

		if ( $values['clock'] != 24 ) {
			$options['A'] = array( 'AM', 'PM');
		}
	}

	private static function get_hours( $all_times, &$options ) {
		foreach ( $all_times as $time ) {
			list( $hour, $minute ) = explode( ':', $time );
			$options['H'][] = $hour;
			unset( $time );
		}

		$options['H'] = array_unique( $options['H'] );
	}

	private static function get_minutes( $all_times, &$options ) {

		foreach ( $all_times as $time ) {
			list( $hour, $minute ) = explode( ':', $time );
			if ( strpos( $minute, 'M' ) ) {
				// AM/PM is included, so strip it off
				$minute = str_replace( array( ' AM', ' PM' ), '', $minute );
			}

			$options['m'][] = $minute;
			unset( $time );
		}

		$options['m'] = array_unique( $options['m'] );
		sort( $options['m'] );
	}

	public static function show_time_field( $field, $values ) {

		if ( isset( $field['options']['H'] ) ) {
			if ( ! empty( $field['value'] ) && ! is_array( $field['value'] ) ) {
				$h = explode( ':', $field['value'] );
				$m = explode( ' ', $h[1] );
				$h = reset( $h );
				$a = isset( $m[1] ) ? $m[1] : '';
				$m = reset( $m );
			} else if ( is_array( $field['value'] ) ) {
				$h = isset( $field['value']['H'] ) ? $field['value']['H'] : '';
				$m = isset( $field['value']['m'] ) ? $field['value']['m'] : '';
				$a = isset( $field['value']['A'] ) ? $field['value']['A'] : '';
			} else {
				$h = $m = $a = '';
			}

			include( FrmAppHelper::plugin_path() . '/pro/classes/views/frmpro-fields/front-end/time.php' );
		} else {
			self::time_array_to_string( $field['value'] );
			include( FrmAppHelper::plugin_path() . '/pro/classes/views/frmpro-fields/front-end/time-single.php' );
		}
	}
}
