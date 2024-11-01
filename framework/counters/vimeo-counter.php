<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Silicon Counters Vimeo Counter.
 */
class Silicon_Counters_Vimeo_Counter extends Silicon_Counters_Counter {

	/**
	 * Counter ID.
	 */
	public $id = 'vimeo';

	/**
	 * API URL.
	 */
	protected $api_url = 'https://vimeo.com/api/v2/%s/info.json';

	/**
	 * Test the counter is available.
	 */
	public function is_available( $settings ) {
		return isset( $settings['vimeo_active'] ) && ! empty( $settings['vimeo_username'] );
	}

	/**
	 * Get the total.
	 */
	public function get_total( $settings, $cache ) {
		if ( $this->is_available( $settings ) ) {
			$this->connection = wp_remote_get( sprintf( $this->api_url, sanitize_text_field( $settings['vimeo_username'] ) ), array( 'timeout' => 60 ) );

			if ( is_wp_error( $this->connection ) || 200 != $this->connection['response']['code'] ) {
				$this->total = ( isset( $cache[ $this->id ] ) ) ? $cache[ $this->id ] : 0;
			} else {
				$_data = json_decode( $this->connection['body'], true );

				if ( isset( $_data['total_contacts'] ) ) {
					$count = intval( $_data['total_contacts'] );

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
		$vimeo_username = ! empty( $settings['vimeo_username'] ) ? $settings['vimeo_username'] : '';
		$icon = ! empty( $settings['vimeo_icon'] ) ? $settings['vimeo_icon'] : 'fa-vimeo';
		$icon_color = ! empty( $settings['vimeo_icon-color'] ) ? $settings['vimeo_icon-color'] : '#fff';
		$icon_color_hover = ! empty( $settings['vimeo_icon-color-hover'] ) ? $settings['vimeo_icon-color-hover'] : '#fff';
		$counter_background = ! empty( $settings['vimeo_counter-background'] ) ? $settings['vimeo_counter-background'] : '#00adef';
		$counter_background_hover = ! empty( $settings['vimeo_counter-background-hover'] ) ? $settings['vimeo_counter-background-hover'] : '#0088cc';
		$number_color = ! empty( $settings['vimeo_number-color'] ) ? $settings['vimeo_number-color'] : '#fff';
		$number_color_hover = ! empty( $settings['vimeo_number-color-hover'] ) ? $settings['vimeo_number-color-hover'] : '#fff';
		$label_color = ! empty( $settings['vimeo_label-color'] ) ? $settings['vimeo_label-color'] : '#fff';
		$label_color_hover = ! empty( $settings['vimeo_label-color-hover'] ) ? $settings['vimeo_label-color-hover'] : '#fff';
		return $this->get_view_li( $icon, $icon_color, $icon_color_hover, $counter_background, $counter_background_hover, $number_color, $number_color_hover, $label_color, $label_color_hover, 'https://vimeo.com/' . $vimeo_username, $total, __( 'followers', 'silicon-counters' ), $text_color, $settings );
	}
}
