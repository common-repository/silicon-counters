<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Steam Counter.
 */
class Silicon_Counters_Steam_Counter extends Silicon_Counters_Counter {

	/**
	 * Counter ID.
	 */
	public $id = 'steam';

	/**
	 * API URL.
	 */
	protected $api_url = 'https://steamcommunity.com/groups/';

	/**
	 * Test the counter is available.
	 */
	public function is_available( $settings ) {
		return isset( $settings['steam_active'] ) && ! empty( $settings['steam_group_name'] );
	}

	/**
	 * Get the total.
	 */
	public function get_total( $settings, $cache ) {
		if ( $this->is_available( $settings ) ) {
			$this->connection = wp_remote_get( $this->api_url . $settings['steam_group_name'] . '/memberslistxml/?xml=1', array( 'timeout' => 60 ) );

			if ( is_wp_error( $this->connection ) || '400' <= $this->connection['response']['code'] ) {
				$this->total = ( isset( $cache[ $this->id ] ) ) ? $cache[ $this->id ] : 0;
			} else {
				try {
					$xml = @new SimpleXmlElement( $this->connection['body'], LIBXML_NOCDATA );
					$count = intval( $xml->groupDetails->memberCount );

					$this->total = $count;
				} catch ( Exception $e ) {
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
		$steam_group_name = ! empty( $settings['steam_group_name'] ) ? $settings['steam_group_name'] : '';
		$icon = ! empty( $settings['steam_icon'] ) ? $settings['steam_icon'] : 'fa-steam';
		$icon_color = ! empty( $settings['steam_icon-color'] ) ? $settings['steam_icon-color'] : '#fff';
		$icon_color_hover = ! empty( $settings['steam_icon-color-hover'] ) ? $settings['steam_icon-color-hover'] : '#fff';
		$counter_background = ! empty( $settings['steam_counter-background'] ) ? $settings['steam_counter-background'] : '#000';
		$counter_background_hover = ! empty( $settings['steam_counter-background-hover'] ) ? $settings['steam_counter-background-hover'] : '#222';
		$number_color = ! empty( $settings['steam_number-color'] ) ? $settings['steam_number-color'] : '#fff';
		$number_color_hover = ! empty( $settings['steam_number-color-hover'] ) ? $settings['steam_number-color-hover'] : '#fff';
		$label_color = ! empty( $settings['steam_label-color'] ) ? $settings['steam_label-color'] : '#fff';
		$label_color_hover = ! empty( $settings['steam_label-color-hover'] ) ? $settings['steam_label-color-hover'] : '#fff';
		return $this->get_view_li($icon, $icon_color, $icon_color_hover, $counter_background, $counter_background_hover, $number_color, $number_color_hover, $label_color, $label_color_hover, 'https://steamcommunity.com/groups/' . $steam_group_name, $total, __( 'members', 'silicon-counters' ), $text_color, $settings );
	}
}
