<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Silicon Counters Posts Counter.
 */
class Silicon_Counters_Posts_Counter extends Silicon_Counters_Counter {

	/**
	 * Counter ID.
	 */
	public $id = 'posts';

	/**
	 * API URL.
	 */
	protected $api_url = '';

	/**
	 * Test the counter is available.
	 */
	public function is_available( $settings ) {
		return ( isset( $settings['posts_active'] ) );
	}

	/**
	 * Get the total.
	 */
	public function get_total( $settings, $cache ) {
		if ( $this->is_available( $settings ) ) {
			$post_type = ( isset( $settings['posts_post_type'] ) && ! empty( $settings['posts_post_type'] ) ) ? $settings['posts_post_type'] : 'posts';
			$data      = wp_count_posts( $post_type );

			if ( is_wp_error( $data ) ) {
				$this->total = ( isset( $cache[ $this->id ] ) ) ? $cache[ $this->id ] : 0;
			} else {
				$count = intval( $data->publish );

				$this->total = $count;
			}
		}

		return $this->total;
	}

	/**
	 * Get conter view.
	 */
	public function get_view( $settings, $total, $text_color ) {
		$post_type   = ( isset( $settings['posts_post_type'] ) && ! empty( $settings['posts_post_type'] ) ) ? $settings['posts_post_type'] : 'post';
		$post_object = get_post_type_object( $post_type );
		$url         = ! empty( $settings['posts_url'] ) ? $settings['posts_url'] : get_home_url();
		$icon        = ! empty( $settings['posts_icon'] ) ? $settings['posts_icon'] : 'fa-paper-plane-o';
		$icon_color = ! empty( $settings['posts_icon-color'] ) ? $settings['posts_icon-color'] : '#fff';
		$icon_color_hover = ! empty( $settings['posts_icon-color-hover'] ) ? $settings['posts_icon-color-hover'] : '#fff';
		$counter_background = ! empty( $settings['posts_counter-background'] ) ? $settings['posts_counter-background'] : '#28afa6';
		$counter_background_hover = ! empty( $settings['posts_counter-background-hover'] ) ? $settings['posts_counter-background-hover'] : '#22899e';
		$number_color = ! empty( $settings['posts_number-color'] ) ? $settings['posts_number-color'] : '#fff';
		$number_color_hover = ! empty( $settings['posts_number-color-hover'] ) ? $settings['posts_number-color-hover'] : '#fff';
		$label_color = ! empty( $settings['posts_label-color'] ) ? $settings['posts_label-color'] : '#fff';
		$label_color_hover = ! empty( $settings['posts_label-color-hover'] ) ? $settings['posts_label-color-hover'] : '#fff';
		unset( $settings['target_blank'] );
		unset( $settings['rel_nofollow'] );

		return $this->get_view_li($icon, $icon_color, $icon_color_hover, $counter_background, $counter_background_hover, $number_color, $number_color_hover, $label_color, $label_color_hover, $url, $total, strtolower( $post_object->label ), $text_color, $settings );
	}
}
