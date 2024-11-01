<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Silicon Counters SoundCloud Counter.
 */
class Silicon_Counters_SoundCloud_Counter extends Silicon_Counters_Counter {

	/**
	 * Counter ID.
	 */
	public $id = 'soundcloud';

	/**
	 * API URL.
	 */
	protected $api_url = 'https://api.soundcloud.com/users/';

	/**
	 * Test the counter is available.
	 */
	public function is_available( $settings ) {
		return ( isset( $settings['soundcloud_active'] ) && ! empty( $settings['soundcloud_username'] ) && ! empty( $settings['soundcloud_client_id'] ) );
	}

	/**
	 * Get the total.
	 */
	public function get_total( $settings, $cache ) {
		if ( $this->is_available( $settings ) ) {
			$this->connection = wp_remote_get( $this->api_url . $settings['soundcloud_username'] . '.json?client_id=' . $settings['soundcloud_client_id'], array( 'timeout' => 60 ) );

			if ( is_wp_error( $this->connection ) || '400' <= $this->connection['response']['code'] ) {
				$this->total = ( isset( $cache[ $this->id ] ) ) ? $cache[ $this->id ] : 0;
			} else {
				$response = json_decode( $this->connection['body'], true );

				if ( isset( $response['followers_count'] ) ) {
					$count = intval( $response['followers_count'] );

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
		$soundcloud_username = ! empty( $settings['soundcloud_username'] ) ? $settings['soundcloud_username'] : '';
		$icon = ! empty( $settings['soundcloud_icon'] ) ? $settings['soundcloud_icon'] : 'fa-soundcloud';
		$icon_color = ! empty( $settings['soundcloud_icon-color'] ) ? $settings['soundcloud_icon-color'] : '#fff';
		$icon_color_hover = ! empty( $settings['soundcloud_icon-color-hover'] ) ? $settings['soundcloud_icon-color-hover'] : '#fff';
		$counter_background = ! empty( $settings['soundcloud_counter-background'] ) ? $settings['soundcloud_counter-background'] : '#ff993f';
		$counter_background_hover = ! empty( $settings['soundcloud_counter-background-hover'] ) ? $settings['soundcloud_counter-background-hover'] : '#e79a57';
		$number_color = ! empty( $settings['soundcloud_number-color'] ) ? $settings['soundcloud_number-color'] : '#fff';
		$number_color_hover = ! empty( $settings['soundcloud_number-color-hover'] ) ? $settings['soundcloud_number-color-hover'] : '#fff';
		$label_color = ! empty( $settings['soundcloud_label-color'] ) ? $settings['soundcloud_label-color'] : '#fff';
		$label_color_hover = ! empty( $settings['soundcloud_label-color-hover'] ) ? $settings['soundcloud_label-color-hover'] : '#fff';
		return $this->get_view_li($icon, $icon_color, $icon_color_hover, $counter_background, $counter_background_hover, $number_color, $number_color_hover, $label_color, $label_color_hover, 'https://soundcloud.com/' . $soundcloud_username, $total, __( 'listens', 'silicon-counters' ), $text_color, $settings );
	}
}
