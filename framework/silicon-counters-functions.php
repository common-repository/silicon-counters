<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * All counters function.
 */
function silicon_counters_all() {
	$count = Silicon_Counters_Generator::get_count();

	return $count;
}

/**
 * Get counter function.
 */
function silicon_counters( $counter = '' ) {
	$count = silicon_counters_all();

	return isset( $count[ $counter ] ) ? $count[ $counter ] : 0;
}


/**
 * Get widget counter function.
 */
function get_scp_widget() {
	return Silicon_Counters_View::get_view();
}
