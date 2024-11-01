<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Silicon Counters GitHub Counter.
 */
class Silicon_Counters_GitHub_Counter extends Silicon_Counters_Counter {

	/**
	 * Counter ID.
	 */
	public $id = 'github';

	/**
	 * API URL.
	 */
	protected $api_url = 'https://api.github.com/users/';

	/**
	 * Test the counter is available.
	 */
	public function is_available( $settings ) {
		return isset( $settings['github_active'] ) && ! empty( $settings['github_username'] );
	}

	/**
	 * Get the total.
	 */
	public function get_total( $settings, $cache ) {
		if ( $this->is_available( $settings ) ) {
			$this->connection = wp_remote_get( $this->api_url . sanitize_text_field( $settings['github_username'] ), array( 'timeout' => 60 ) );

			if ( is_wp_error( $this->connection ) || 200 != $this->connection['response']['code'] ) {
				$this->total = ( isset( $cache[ $this->id ] ) ) ? $cache[ $this->id ] : 0;
			} else {
				$_data = json_decode( $this->connection['body'], true );

				if ( isset( $_data['followers'] ) ) {
					$count = intval( $_data['followers'] );

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
		$github_username = ! empty( $settings['github_username'] ) ? $settings['github_username'] : '';
		$icon = ! empty( $settings['github_icon'] ) ? $settings['github_icon'] : 'fa-github';
		$icon_color = ! empty( $settings['github_icon-color'] ) ? $settings['github_icon-color'] : '#000';
		$icon_color_hover = ! empty( $settings['github_icon-color-hover'] ) ? $settings['github_icon-color-hover'] : '#000';
		$counter_background = ! empty( $settings['github_counter-background'] ) ? $settings['github_counter-background'] : '#f6f6f6';
		$counter_background_hover = ! empty( $settings['github_counter-background-hover'] ) ? $settings['github_counter-background-hover'] : '#f1f1f1';
		$number_color = ! empty( $settings['github_number-color'] ) ? $settings['github_number-color'] : '#000';
		$number_color_hover = ! empty( $settings['github_number-color-hover'] ) ? $settings['github_number-color-hover'] : '#000';
		$label_color = ! empty( $settings['github_label-color'] ) ? $settings['github_label-color'] : '#999';
		$label_color_hover = ! empty( $settings['github_label-color-hover'] ) ? $settings['github_label-color-hover'] : '#999';
		$label_size = ! empty( $settings['github_label-size'] ) ? $settings['github_label-size'] : '12px';
		return $this->get_view_li( $icon, $icon_color, $icon_color_hover, $counter_background, $counter_background_hover, $number_color, $number_color_hover, $label_color, $label_color_hover, 'https://github.com/' . $github_username, $total, __( 'followers', 'social-counters' ), $text_color, $settings );
	}
}
