<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Silicon Counters Facebook Counter.
 */
class Silicon_Counters_Facebook_Counter extends Silicon_Counters_Counter {

	/**
	 * Counter ID.
	 */
	public $id = 'facebook';

	/**
	 * API URL.
	 */
	protected $api_url = 'https://graph.facebook.com';

	/**
	 * Test the counter is available.
	 * @return bool
	 */
	public function is_available( $settings ) {
		return isset( $settings['facebook_active'] ) && ! empty( $settings['facebook_id'] ) && ! empty( $settings['facebook_app_id'] ) && ! empty( $settings['facebook_app_secret'] );
	}

	/**
	 * Get access token.
	 */
	protected function get_access_token( $settings ) {
		$url = sprintf(
			'%s/oauth/access_token?client_id=%s&client_secret=%s&grant_type=client_credentials',
			$this->api_url,
			sanitize_text_field( $settings['facebook_app_id'] ),
			sanitize_text_field( $settings['facebook_app_secret'] )
		);
		$access_token = wp_remote_get( $url, array( 'timeout' => 60 ) );

		if ( is_wp_error( $access_token ) || ( isset( $access_token['response']['code'] ) && 200 != $access_token['response']['code'] ) ) {
			return '';
		} else {
			return sanitize_text_field( json_decode( $access_token['body'], true )['access_token'] );
		}
	}

	/**
	 * Get the total.
	 */
	public function get_total( $settings, $cache ) {
		if ( $this->is_available( $settings ) ) {
			$access_token = $this->get_access_token( $settings );
			$url = sprintf(
				'%s%s?fields=fan_count&access_token=%s',
				$this->api_url.'/v2.7/',
				sanitize_text_field( $settings['facebook_id'] ),
				$access_token
			);

			$this->connection = wp_remote_get( $url, array( 'timeout' => 60 ) );

			if ( is_wp_error( $this->connection ) || ( isset( $this->connection['response']['code'] ) && 200 != $this->connection['response']['code'] ) ) {
				$this->total = ( isset( $cache[ $this->id ] ) ) ? $cache[ $this->id ] : 0;
			} else {
				$_data = json_decode( $this->connection['body'], true );

				if ( isset( $_data['fan_count'] ) ) {
					$count = intval( $_data['fan_count'] );

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
	 * @return string
	 */
	public function get_view( $settings, $total, $text_color ) {
        $facebook_id = ! empty( $settings['facebook_id'] ) ? $settings['facebook_id'] : '';
		$icon = ! empty( $settings['facebook_icon'] ) ? $settings['facebook_icon'] : 'fa-facebook-official';
		$icon_color = ! empty( $settings['facebook_icon-color'] ) ? $settings['facebook_icon-color'] : '#fff';
		$icon_color_hover = ! empty( $settings['facebook_icon-color-hover'] ) ? $settings['facebook_icon-color-hover'] : '#fff';
		$counter_background = ! empty( $settings['facebook_counter-background'] ) ? $settings['facebook_counter-background'] : '#3B5998';
		$counter_background_hover = ! empty( $settings['facebook_counter-background-hover'] ) ? $settings['facebook_counter-background-hover'] : '#5E80BF';
		$number_color = ! empty( $settings['facebook_number-color'] ) ? $settings['facebook_number-color'] : '#fff';
		$number_color_hover = ! empty( $settings['facebook_number-color-hover'] ) ? $settings['facebook_number-color-hover'] : '#fff';
		$label_color = ! empty( $settings['facebook_label-color'] ) ? $settings['facebook_label-color'] : '#fff';
		$label_color_hover = ! empty( $settings['facebook_label-color-hover'] ) ? $settings['facebook_label-color-hover'] : '#fff';
		return $this->get_view_li($icon, $icon_color, $icon_color_hover, $counter_background, $counter_background_hover, $number_color, $number_color_hover, $label_color, $label_color_hover, 'https://www.facebook.com/' . $facebook_id, $total, __( 'likes', 'silicon-counters' ), $text_color, $settings );
	}
}
