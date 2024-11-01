<?php

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Silicon Counters View.
 */
class Silicon_Counters_View {

    /**
     * Widget view.
     */
    public static
    function get_view() {
        wp_enqueue_style( 'silicon-counters' );

        $settings = get_option( 'siliconcounters_settings' );
        $count = Silicon_Counters_Generator::get_count();
        $color = '';
        $icons = isset( $settings[ 'icons' ] ) ? array_map( 'sanitize_key', explode( ',', $settings[ 'icons' ] ) ) : array();
        $style = 'sc_' . strtolower( $settings[ 'layout' ] );

        $html = '<div class="silicon_counters">';
        $html .= '<ul class="' . $style . '">';
        foreach ( $icons as $icon ) {
            $class = 'silicon_counters_' . $icon . '_counter';

            if ( !isset( $count[ $icon ] ) ) {
                continue;
            }

            $total = apply_filters( 'silicon_counters_number_format', $count[ $icon ] );

            if ( class_exists( $class ) ) {
                $_class = new $class();
                $html .= $_class->get_view( $settings, $total, $color );
            } else {
                $html .= apply_filters( 'silicon_counters_' . $icon . 'html_counter', '', $settings, $total, $color );
            }
        }

        $html .= '</ul>';
        $html .= '</div>';

        return $html;
    }
}