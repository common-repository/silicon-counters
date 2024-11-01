<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Silicon Counters Twitch Counter.
 */
class Silicon_Counters_Twitch_Counter extends Silicon_Counters_Counter {

	/**
	 * Counter ID.
	 */
	public $id = 'twitch';

	/**
	 * API URL.
	 */
	protected $api_url = 'https://api.twitch.tv/kraken/channels/';

	/**
	 * Test the counter is available.
	 */
	public function is_available( $settings ) {
		return isset( $settings['twitch_active'] ) && ! empty( $settings['twitch_username'] );
	}

	/**
	 * Get the total.
	 */
	public function get_total( $settings, $cache ) {
		
		if ( $this->is_available( $settings ) ) {
			$params = array(
				'timeout' => 60,
				'headers' => array(
					'Client-ID'=> sanitize_text_field( $settings['twitch_client_id'] ),
				)
			);

			$this->connection = wp_remote_get( $this->api_url . sanitize_text_field( $settings['twitch_username'] ), $params );

			if ( is_wp_error( $this->connection ) || ( isset( $this->connection['response']['code'] ) && 200 != $this->connection['response']['code'] ) ) {
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
		$twitch_username = ! empty( $settings['twitch_username'] ) ? $settings['twitch_username'] : '';
		$icon = ! empty( $settings['twitch_icon'] ) ? $settings['twitch_icon'] : 'fa-twitch';
		$icon_color = ! empty( $settings['twitch_icon-color'] ) ? $settings['twitch_icon-color'] : '#fff';
		$icon_color_hover = ! empty( $settings['twitch_icon-color-hover'] ) ? $settings['twitch_icon-color-hover'] : '#fff';
		$counter_background = ! empty( $settings['twitch_counter-background'] ) ? $settings['twitch_counter-background'] : '#6441a5';
		$counter_background_hover = ! empty( $settings['twitch_counter-background-hover'] ) ? $settings['twitch_counter-background-hover'] : '#483d8b';
		$number_color = ! empty( $settings['twitch_number-color'] ) ? $settings['twitch_number-color'] : '#fff';
		$number_color_hover = ! empty( $settings['twitch_number-color-hover'] ) ? $settings['twitch_number-color-hover'] : '#fff';
		$label_color = ! empty( $settings['twitch_label-color'] ) ? $settings['twitch_label-color'] : '#fff';
		$label_color_hover = ! empty( $settings['twitch_label-color-hover'] ) ? $settings['twitch_label-color-hover'] : '#fff';
		return $this->get_view_li($icon, $icon_color, $icon_color_hover, $counter_background, $counter_background_hover, $number_color, $number_color_hover, $label_color, $label_color_hover, 'http://www.twitch.tv/' . $twitch_username . '/profile', $total, __( 'followers', 'silicon-counters' ), $text_color, $settings );
	}
}
