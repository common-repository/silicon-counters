<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Silicon Counters Comments Counter.
 */
class Silicon_Counters_Comments_Counter extends Silicon_Counters_Counter {

	/**
	 * Counter ID.
	 */
	public $id = 'comments';

	/**
	 * API URL.
	 */
	protected $api_url = '';

	/**
	 * Test the counter is available.
	 */
	public function is_available( $settings ) {
		return ( isset( $settings['comments_active'] ) );
	}

	/**
	 * Get the total.
	 */
	public function get_total( $settings, $cache ) {
		if ( $this->is_available( $settings ) ) {
			$data = wp_count_comments();

			if ( is_wp_error( $data ) ) {
				$this->total = ( isset( $cache[ $this->id ] ) ) ? $cache[ $this->id ] : 0;
			} else {
				$count = intval( $data->approved );

				$this->total = $count;
			}
		}

		return $this->total;
	}

	/**
	 * Get conter view.
	 */
	public function get_view( $settings, $total, $text_color ) {
		$icon = ! empty( $settings['comments_icon'] ) ? $settings['comments_icon'] : 'fa-comments';
		$url  = ! empty( $settings['comments_url'] ) ? $settings['comments_url'] : get_home_url();
		$icon_color = ! empty( $settings['comments_icon-color'] ) ? $settings['comments_icon-color'] : '#000';
		$icon_color_hover = ! empty( $settings['comments_icon-color-hover'] ) ? $settings['comments_icon-color-hover'] : '#000';
		$counter_background = ! empty( $settings['comments_counter-background'] ) ? $settings['comments_counter-background'] : '#f6f6f6';
		$counter_background_hover = ! empty( $settings['comments_counter-background-hover'] ) ? $settings['comments_counter-background-hover'] : '#f1f1f1';
		$number_color = ! empty( $settings['comments_number-color'] ) ? $settings['comments_number-color'] : '#000';
		$number_color_hover = ! empty( $settings['comments_number-color-hover'] ) ? $settings['comments_number-color-hover'] : '#000';
		$label_color = ! empty( $settings['comments_label-color'] ) ? $settings['comments_label-color'] : '#999';
		$label_color_hover = ! empty( $settings['comments_label-color-hover'] ) ? $settings['comments_label-color-hover'] : '#999';
		
		unset( $settings['target_blank'] );
		unset( $settings['rel_nofollow'] );

		return $this->get_view_li( $icon, $icon_color, $icon_color_hover, $counter_background, $counter_background_hover, $number_color, $number_color_hover, $label_color, $label_color_hover, $url, $total, __( 'comments', 'silicon-counters' ), $text_color, $settings );
	}
}
