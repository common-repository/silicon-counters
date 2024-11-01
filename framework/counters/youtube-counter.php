<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Silicon Counters YouTube Counter.
 */
class Silicon_Counters_YouTube_Counter extends Silicon_Counters_Counter {

	/**
	 * Counter ID.
	 */
	public $id = 'youtube';

	/**
	 * API URL.
	 */
	protected $api_url = 'https://www.googleapis.com/youtube/v3/channels';

	/**
	 * Test the counter is available.
	 */
	public function is_available( $settings ) {
		return isset( $settings['youtube_active'] ) && ! empty( $settings['youtube_user'] ) && ! empty( $settings['youtube_api_key'] );
	}

	/**
	 * Get the total.
	 */
	public function get_total( $settings, $cache ) {
		if ( $this->is_available( $settings ) ) {
			$url = sprintf(
				'%s?part=statistics&id=%s&key=%s',
				$this->api_url,
				sanitize_text_field( $settings['youtube_user'] ),
				sanitize_text_field( $settings['youtube_api_key'] )
			);

			$this->connection = wp_remote_get( $url, array( 'timeout' => 60 ) );

			if ( is_wp_error( $this->connection ) || 400 <= $this->connection['response']['code'] ) {
				$this->total = ( isset( $cache[ $this->id ] ) ) ? $cache[ $this->id ] : 0;
			} else {
				$_data = json_decode( $this->connection['body'], true );

				if ( isset( $_data['items'][0]['statistics']['subscriberCount'] ) ) {
					$count = intval( $_data['items'][0]['statistics']['subscriberCount'] );

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
		$youtube_url = ! empty( $settings['youtube_url'] ) ? $settings['youtube_url'] : '';
		$icon = ! empty( $settings['youtube_icon'] ) ? $settings['youtube_icon'] : 'fa-youtube';
		$icon_color = ! empty( $settings['youtube_icon-color'] ) ? $settings['youtube_icon-color'] : '#fff';
		$icon_color_hover = ! empty( $settings['youtube_icon-color-hover'] ) ? $settings['youtube_icon-color-hover'] : '#fff';
		$counter_background = ! empty( $settings['youtube_counter-background'] ) ? $settings['youtube_counter-background'] : '#bf2626';
		$counter_background_hover = ! empty( $settings['youtube_counter-background-hover'] ) ? $settings['youtube_counter-background-hover'] : '#90030c';
		$number_color = ! empty( $settings['youtube_number-color'] ) ? $settings['youtube_number-color'] : '#fff';
		$number_color_hover = ! empty( $settings['youtube_number-color-hover'] ) ? $settings['youtube_number-color-hover'] : '#fff';
		$label_color = ! empty( $settings['youtube_label-color'] ) ? $settings['youtube_label-color'] : '#fff';
		$label_color_hover = ! empty( $settings['youtube_label-color-hover'] ) ? $settings['youtube_label-color-hover'] : '#fff';
		return $this->get_view_li($icon, $icon_color, $icon_color_hover, $counter_background, $counter_background_hover, $number_color, $number_color_hover, $label_color, $label_color_hover, $youtube_url, $total, __( 'subscribers', 'silicon-counters' ), $text_color, $settings );
	}
}
