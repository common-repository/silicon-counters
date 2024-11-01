<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Silicon Counters Pinterest Counter.
 */
class Silicon_Counters_Pinterest_Counter extends Silicon_Counters_Counter {

	/**
	 * Counter ID.
	 */
	public $id = 'pinterest';

	/**
	 * API URL.
	 */
	protected $api_url = 'https://www.pinterest.com/';

	/**
	 * Test the counter is available.
	 */
	public function is_available( $settings ) {
		return isset( $settings['pinterest_active'] ) && ! empty( $settings['pinterest_username'] );
	}

	/**
	 * Get the total.
	 */
	public function get_total( $settings, $cache ) {
		if ( $this->is_available( $settings ) ) {
			$this->connection = wp_remote_get( $this->api_url . sanitize_text_field( $settings['pinterest_username'] ), array( 'timeout' => 60 ) );

			if ( is_wp_error( $this->connection ) ) {
				$this->total = ( isset( $cache[ $this->id ] ) ) ? $cache[ $this->id ] : 0;
			} else {
				$count = 0;

				if ( 200 == $this->connection['response']['code'] ) {
					$tags = array();
					$regex = '/property\=\"pinterestapp:followers\" name\=\"pinterestapp:followers\" content\=\"(.*?)" data-app/';
					preg_match( $regex, $this->connection['body'], $tags );

					$count = intval( $tags[1] );
				}

				if ( 0 < $count ) {
					$this->total = $count;
				} else {
					$this->total = ( isset( $cache[ $this->id ] ) ) ? $cache[ $this->id ] : 0;
				}

				// Just to make the system report more clear...
				$this->connection['body'] = '{"followers":' . $count . '}';
			}
		}

		return $this->total;
	}

	/**
	 * Get conter view.
	 */
	public function get_view( $settings, $total, $text_color ) {
		$pinterest_username = ! empty( $settings['pinterest_username'] ) ? $settings['pinterest_username'] : '';
		$icon = ! empty( $settings['pinterest_icon'] ) ? $settings['pinterest_icon'] : 'fa-pinterest';
		$icon_color = ! empty( $settings['pinterest_icon-color'] ) ? $settings['pinterest_icon-color'] : '#fff';
		$icon_color_hover = ! empty( $settings['pinterest_icon-color-hover'] ) ? $settings['pinterest_icon-color-hover'] : '#fff';
		$counter_background = ! empty( $settings['pinterest_counter-background'] ) ? $settings['pinterest_counter-background'] : '#e3262e';
		$counter_background_hover = ! empty( $settings['pinterest_counter-background-hover'] ) ? $settings['pinterest_counter-background-hover'] : '#ab171e';
		$number_color = ! empty( $settings['pinterest_number-color'] ) ? $settings['pinterest_number-color'] : '#fff';
		$number_color_hover = ! empty( $settings['pinterest_number-color-hover'] ) ? $settings['pinterest_number-color-hover'] : '#fff';
		$label_color = ! empty( $settings['pinterest_label-color'] ) ? $settings['pinterest_label-color'] : '#fff';
		$label_color_hover = ! empty( $settings['pinterest_label-color-hover'] ) ? $settings['pinterest_label-color-hover'] : '#fff';
		$label_size = ! empty( $settings['pinterest_label-size'] ) ? $settings['pinterest_label-size'] : '12px';
		return $this->get_view_li($icon, $icon_color, $icon_color_hover, $counter_background, $counter_background_hover, $number_color, $number_color_hover, $label_color, $label_color_hover, 'https://www.pinterest.com/' . $pinterest_username, $total, __( 'followers', 'silicon-counters' ), $text_color, $settings );
	}
}
