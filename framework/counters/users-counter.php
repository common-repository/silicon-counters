<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Silicon Counters Users Counter.
 */
class Silicon_Counters_Users_Counter extends Silicon_Counters_Counter {

	/**
	 * Counter ID.
	 */
	public $id = 'users';

	/**
	 * API URL.
	 */
	protected $api_url = '';

	/**
	 * Test the counter is available.
	 */
	public function is_available( $settings ) {
		return isset( $settings['users_active'] ) && ! empty( $settings['users_user_role'] );
	}

	/**
	 * Get the total.
	 */
	public function get_total( $settings, $cache ) {
		if ( $this->is_available( $settings ) ) {
			$users = count_users();

			if ( 'all' == $settings['users_user_role'] ) {
				$this->total = intval( $users['total_users'] );
			} else if ( ! empty( $users['avail_roles'][ $settings['users_user_role'] ] ) ) {
				$this->total = intval( $users['avail_roles'][ $settings['users_user_role'] ] );
			} else {
				$this->total = 0;
			}
		}

		return $this->total;
	}

	/**
	 * Get conter view.
	 */
	public function get_view( $settings, $total, $text_color ) {
		$url   = ! empty( $settings['users_url'] ) ? $settings['users_url'] : get_home_url();
		$label = ! empty( $settings['users_label'] ) ? $settings['users_label'] : __( 'users', 'silicon-counters' );
		$icon = ! empty( $settings['users_icon'] ) ? $settings['users_icon'] : 'fa-users';
		$icon_color = ! empty( $settings['users_icon-color'] ) ? $settings['users_icon-color'] : '#fff';
		$icon_color_hover = ! empty( $settings['users_icon-color-hover'] ) ? $settings['users_icon-color-hover'] : '#fff';
		$counter_background = ! empty( $settings['users_counter-background'] ) ? $settings['users_counter-background'] : '#81d742';
		$counter_background_hover = ! empty( $settings['users_counter-background-hover'] ) ? $settings['users_counter-background-hover'] : '#81d742';
		$number_color = ! empty( $settings['users_number-color'] ) ? $settings['users_number-color'] : '#fff';
		$number_color_hover = ! empty( $settings['users_number-color-hover'] ) ? $settings['users_number-color-hover'] : '#fff';
		$label_color = ! empty( $settings['users_label-color'] ) ? $settings['users_label-color'] : '#fff';
		$label_color_hover = ! empty( $settings['users_label-color-hover'] ) ? $settings['users_label-color-hover'] : '#fff';
		unset( $settings['target_blank'] );
		unset( $settings['rel_nofollow'] );

		return $this->get_view_li($icon, $icon_color, $icon_color_hover, $counter_background, $counter_background_hover, $number_color, $number_color_hover, $label_color, $label_color_hover, $url, $total, $label, $text_color, $settings );
	}
}
