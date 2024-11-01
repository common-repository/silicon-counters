<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Silicon Counters Twitter Counter.
 */
class Silicon_Counters_Twitter_Counter extends Silicon_Counters_Counter {

	/**
	 * Counter ID.
	 */
	public $id = 'twitter';

	/**
	 * API URL.
	 */
	protected $api_url = 'https://api.twitter.com/1.1/users/show.json';

	/**
	 * Authorization.
	 */
	protected function authorization( $user, $consumer_key, $consumer_secret, $oauth_access_token, $oauth_access_token_secret ) {
		$query     = 'screen_name=' . $user;
		$signature = $this->signature( $query, $consumer_key, $consumer_secret, $oauth_access_token, $oauth_access_token_secret );

		return $this->header( $signature );
	}

	/**
	 * Build the Signature base string.
	 */
	private function signature_base_string( $url, $query, $method, $params ) {
		$return = array();
		ksort( $params );

		foreach( $params as $key => $value ) {
			$return[] = $key . '=' . $value;
		}

		return $method . '&' . rawurlencode( $url ) . '&' . rawurlencode( implode( '&', $return ) ) . '%26' . rawurlencode( $query );
	}

	/**
	 * Build the OAuth Signature.
	 */
	private function signature( $query, $consumer_key, $consumer_secret, $oauth_access_token, $oauth_access_token_secret ) {
		$oauth = array(
			'oauth_consumer_key'     => $consumer_key,
			'oauth_nonce'            => hash_hmac( 'sha1', time(), '1', false ),
			'oauth_signature_method' => 'HMAC-SHA1',
			'oauth_token'            => $oauth_access_token,
			'oauth_timestamp'        => time(),
			'oauth_version'          => '1.0'
		);

		$base_info = $this->signature_base_string( $this->api_url, $query, 'GET', $oauth );
		$composite_key = rawurlencode( $consumer_secret ) . '&' . rawurlencode( $oauth_access_token_secret );
		$oauth_signature = base64_encode( hash_hmac( 'sha1', $base_info, $composite_key, true ) );
		$oauth['oauth_signature'] = $oauth_signature;

		return $oauth;
	}

	/**
	 * Build the header.
	 */
	public function header( $signature ) {
		$return = 'OAuth ';
		$values = array();

		foreach( $signature as $key => $value ) {
			$values[] = $key . '="' . rawurlencode( $value ) . '"';
		}

		$return .= implode( ', ', $values );

		return $return;
	}

	/**
	 * Test the counter is available.
	 */
	public function is_available( $settings ) {
		return ( isset( $settings['twitter_active'] ) && ! empty( $settings['twitter_user'] ) && ! empty( $settings['twitter_consumer_key'] ) && ! empty( $settings['twitter_consumer_secret'] ) && ! empty( $settings['twitter_access_token'] ) && ! empty( $settings['twitter_access_token_secret'] ) );
	}

	/**
	 * Get the total.
	 */
	public function get_total( $settings, $cache ) {
		if ( $this->is_available( $settings ) ) {
			$user = $settings['twitter_user'];

			$params = array(
				'method'    => 'GET',
				'timeout'   => 60,
				'headers'   => array(
					'Content-Type'  => 'application/x-www-form-urlencoded',
					'Authorization' => $this->authorization(
						$user,
						$settings['twitter_consumer_key'],
						$settings['twitter_consumer_secret'],
						$settings['twitter_access_token'],
						$settings['twitter_access_token_secret']
					)
				)
			);

			$this->connection = wp_remote_get( $this->api_url . '?screen_name=' . $user, $params );

			if ( is_wp_error( $this->connection ) ) {
				$this->total = ( isset( $cache[ $this->id ] ) ) ? $cache[ $this->id ] : 0;
			} else {
				$_data = json_decode( $this->connection['body'], true );

				if ( isset( $_data['followers_count'] ) ) {
					$count = intval( $_data['followers_count'] );

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
		$twitter_user = ! empty( $settings['twitter_user'] ) ? $settings['twitter_user'] : '';
		$icon = ! empty( $settings['twitter_icon'] ) ? $settings['twitter_icon'] : 'fa-twitter';
		$icon_color = ! empty( $settings['twitter_icon-color'] ) ? $settings['twitter_icon-color'] : '#fff';
		$icon_color_hover = ! empty( $settings['twitter_icon-color-hover'] ) ? $settings['twitter_icon-color-hover'] : '#fff';
		$counter_background = ! empty( $settings['twitter_counter-background'] ) ? $settings['twitter_counter-background'] : '#33ccff';
		$counter_background_hover = ! empty( $settings['twitter_counter-background-hover'] ) ? $settings['twitter_counter-background-hover'] : '#0084b4';
		$number_color = ! empty( $settings['twitter_number-color'] ) ? $settings['twitter_number-color'] : '#fff';
		$number_color_hover = ! empty( $settings['twitter_number-color-hover'] ) ? $settings['twitter_number-color-hover'] : '#fff';
		$label_color = ! empty( $settings['twitter_label-color'] ) ? $settings['twitter_label-color'] : '#fff';
		$label_color_hover = ! empty( $settings['twitter_label-color-hover'] ) ? $settings['twitter_label-color-hover'] : '#fff';
		return $this->get_view_li($icon, $icon_color, $icon_color_hover, $counter_background, $counter_background_hover, $number_color, $number_color_hover, $label_color, $label_color_hover, 'https://twitter.com/' . $twitter_user, $total, __( 'followers', 'silicon-counters' ), $text_color, $settings );
	}
}
