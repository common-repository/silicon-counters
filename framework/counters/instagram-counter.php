<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Silicon Counters Instagram Counter.
 */
class Silicon_Counters_Instagram_Counter extends Silicon_Counters_Counter {

	/**
	 * Counter ID.

	 */
	public $id = 'instagram';

	/**
	 * API URL.
	 */
	protected $api_url = 'https://instagram.com/';

	/**
	 * Test the counter is available.
	 */
	public function is_available( $settings ) {
		return ( isset( $settings['instagram_active'] ) && ! empty( $settings['instagram_username'] ) );
	}

	/**
	 * Get the total.
	 */
	public function get_total( $settings, $cache ) {
		if ( $this->is_available( $settings ) ) {
			$this->connection = wp_remote_get( $this->api_url . $settings['instagram_username']. '/?__a=1', array( 'timeout' => 60 ) );
            if ( is_wp_error( $this->connection ) || '400' <= $this->connection['response']['code'] ) {
				$this->total = ( isset( $cache[ $this->id ] ) ) ? $cache[ $this->id ] : 0;
			} else {
				$response = json_decode( $this->connection['body'], true );

				if (
					isset( $response['user']['followed_by']['count'] )
				) {
					$count = intval( $response['user']['followed_by']['count'] );

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
		$instagram_username = ! empty( $settings['instagram_username'] ) ? $settings['instagram_username'] : '';
		$icon = ! empty( $settings['instagram_icon'] ) ? $settings['instagram_icon'] : 'fa-instagram';
		$icon_color = ! empty( $settings['instagram_icon-color'] ) ? $settings['instagram_icon-color'] : '#fff';
		$icon_color_hover = ! empty( $settings['instagram_icon-color-hover'] ) ? $settings['instagram_icon-color-hover'] : '#fff';
		$counter_background = ! empty( $settings['instagram_counter-background'] ) ? $settings['instagram_counter-background'] : '#cd486b';
		$counter_background_hover = ! empty( $settings['instagram_counter-background-hover'] ) ? $settings['instagram_counter-background-hover'] : '#8a3ab9';
		$number_color = ! empty( $settings['instagram_number-color'] ) ? $settings['instagram_number-color'] : '#fff';
		$number_color_hover = ! empty( $settings['instagram_number-color-hover'] ) ? $settings['instagram_number-color-hover'] : '#fff';
		$label_color = ! empty( $settings['instagram_label-color'] ) ? $settings['instagram_label-color'] : '#fff';
		$label_color_hover = ! empty( $settings['instagram_label-color-hover'] ) ? $settings['instagram_label-color-hover'] : '#fff';
		return $this->get_view_li( $icon, $icon_color, $icon_color_hover, $counter_background, $counter_background_hover, $number_color, $number_color_hover, $label_color, $label_color_hover,'https://instagram.com/' . $instagram_username, $total, __( 'followers', 'silicon-counters' ), $text_color, $settings );
	}
}