<?php

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Silicon Counters Generator.
 */
class Silicon_Counters_Generator {

    /**
     * Transient name.
     */
    public static $transient = 'siliconcounters_counter';

    /**
     * Cache option name.
     */
    public static $cache = 'siliconcounters_cache';

    /**
     * Update the counters.
     */
    public static
    function get_count() {
        // Get transient.
        $total = get_transient( self::$transient );

        // Test transient if exist.
        if ( false != $total ) {
            return $total;
        }

        $total = array();
        $settings = get_option( 'siliconcounters_settings' );
        $cache = get_option( self::$cache );
        $counters = apply_filters( 'silicon_counters_counters', array(
            'Silicon_Counters_Twitch_Counter',
            'Silicon_Counters_Comments_Counter',
            'Silicon_Counters_Facebook_Counter',
            'Silicon_Counters_GitHub_Counter',
            'Silicon_Counters_GooglePlus_Counter',
            'Silicon_Counters_Instagram_Counter',
            'Silicon_Counters_Pinterest_Counter',
            'Silicon_Counters_Posts_Counter',
            'Silicon_Counters_SoundCloud_Counter',
            'Silicon_Counters_Steam_Counter',
            'Silicon_Counters_Twitter_Counter',
            'Silicon_Counters_Users_Counter',
            'Silicon_Counters_Vimeo_Counter',
            'Silicon_Counters_YouTube_Counter',
        ) );

        foreach ( $counters as $counter ) {
            $_counter = new $counter();
            $total[ $_counter->id ] = $_counter->get_total( $settings, $cache );
        }

        // Update plugin extra cache.
        update_option( self::$cache, $total );

        // Update counter transient.
        set_transient( self::$transient, $total, apply_filters( 'silicon_counters_transient_time', 60 * 60 * 24 ) ); // 24 hours.

        return $total;
    }

    /**
     * Delete the counters.
     */
    public static
    function delete_count() {
        delete_transient( self::$transient );
    }

    /**
     * Reset the counters.
     */
    public static
    function reset_count() {
        self::delete_count();
        self::get_count();
    }
}