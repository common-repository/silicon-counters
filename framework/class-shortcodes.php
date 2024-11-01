<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Silicon Counters Shortcode.
 */
class Silicon_Counters_Shortcode {

	/**
	 * Counter.
	 */
	public static function counter( $atts ) {
		$count = Silicon_Counters_Generator::get_count();
		extract(
			shortcode_atts(
				array(
					'code' => 'twitter'
				),
				$atts
			)
		);

		$counter = $count[ $code ];

		return apply_filters( 'silicon_counters_number_format', $counter );
	}
}
