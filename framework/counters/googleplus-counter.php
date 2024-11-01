<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Silicon Counters Google+ Counter.
 */
class Silicon_Counters_GooglePlus_Counter extends Silicon_Counters_Counter {

	/**
	 * Counter ID.
	 */
	public $id = 'googleplus';

	/**
	 * API URL.
	 */
	protected $api_url = 'https://www.googleapis.com/plus/v1/people/';

	/**
	 * Test the counter is available.

	 */
	public function is_available( $settings ) {
		return ( isset( $settings['googleplus_active'] ) && ! empty( $settings['googleplus_id'] ) && ! empty( $settings['googleplus_api_key'] ) );
	}

	/**
	 * Get the total.
	 */
	public function get_total( $settings, $cache ) {
		if ( $this->is_available( $settings ) ) {
			$this->connection = wp_remote_get( $this->api_url . $settings['googleplus_id'] . '?key=' . $settings['googleplus_api_key'], array( 'timeout' => 60 ) );

			if ( is_wp_error( $this->connection ) || '400' <= $this->connection['response']['code'] ) {
				$this->total = ( isset( $cache[ $this->id ] ) ) ? $cache[ $this->id ] : 0;
			} else {
				$_data = json_decode( $this->connection['body'], true );

				if ( isset( $_data['circledByCount'] ) ) {
					$count = intval( $_data['circledByCount'] );

					$this->total = $count;
				} else {
					$this->total = ( isset( $cache[ $this->id ] ) ) ? $cache[ $this->id ] : 0;
				}
			}
		}

		return $this->total;
	}

	/**
	 * Get conter view.
	 */
	public function get_view( $settings, $total, $text_color ) {
		$googleplus_id = ! empty( $settings['googleplus_id'] ) ? $settings['googleplus_id'] : '';
		$icon = ! empty( $settings['googleplus_icon'] ) ? $settings['googleplus_icon'] : 'fa-google-plus';
		$icon_color = ! empty( $settings['googleplus_icon-color'] ) ? $settings['googleplus_icon-color'] : '#fff';
		$icon_color_hover = ! empty( $settings['googleplus_icon-color-hover'] ) ? $settings['googleplus_icon-color-hover'] : '#fff';
		$counter_background = ! empty( $settings['googleplus_counter-background'] ) ? $settings['googleplus_counter-background'] : '#dc493c';
		$counter_background_hover = ! empty( $settings['googleplus_counter-background-hover'] ) ? $settings['googleplus_counter-background-hover'] : '#dc493c';
		$number_color = ! empty( $settings['googleplus_number-color'] ) ? $settings['googleplus_number-color'] : '#fff';
		$number_color_hover = ! empty( $settings['googleplus_number-color-hover'] ) ? $settings['googleplus_number-color-hover'] : '#fff';
		$label_color = ! empty( $settings['googleplus_label-color'] ) ? $settings['googleplus_label-color'] : '#fff';
		$label_color_hover = ! empty( $settings['googleplus_label-color-hover'] ) ? $settings['googleplus_label-color-hover'] : '#fff';
		return $this->get_view_li( $icon, $icon_color, $icon_color_hover, $counter_background, $counter_background_hover, $number_color, $number_color_hover, $label_color, $label_color_hover, 'https://plus.google.com/' . $googleplus_id, $total, __( 'followers', 'silicon-counters' ), $text_color, $settings );
	}
}
