<?php

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Silicon Counters Counter.
 */
abstract class Silicon_Counters_Counter {

    /**
     * Total count.
     */
    protected $total = 0;

    /**
     * Counter ID.
     */
    public $id = '';

    /**
     * Connection.
     */
    protected $connection = array();

    /**
     * Test the counter is available.
     */
    public

    function is_available( $settings ) {
        return false;
    }

    /**
     * Get the total.
     */
    public

    function get_total( $settings, $cache ) {
        return $this->total;
    }

    /**
     * Get the li element.
     */
    protected

    function get_view_li( $icon, $icon_color, $icon_color_hover, $counter_background, $counter_background_hover, $number_color, $number_color_hover, $label_color, $label_color_hover, $url, $count, $label, $color, $settings ) {
        $target_blank = isset( $settings[ 'target_blank' ] ) ? ' target="_blank"' : '';
        $rel_nofollow = isset( $settings[ 'rel_nofollow' ] ) ? ' rel="nofollow"' : '';
        $count = ( isset( $settings[ 'convert_thousand' ] ) && ( $count > 1000 ) ) ? round( $count / 1000, 1 ) . 'k': $count;
        $paddings = !empty( $settings[ 'paddings' ] ) ? 'padding:' . $settings[ 'paddings' ] . ';': 'padding:10px 10px 10px 10px;';
        $icon_size = !empty( $settings[ 'icon_size' ] ) ? $settings[ 'icon_size' ] : '24px';
        $number_size = !empty( $settings[ 'number_size' ] ) ? $settings[ 'number_size' ] : '16px';
        $label_size = !empty( $settings[ 'label_size' ] ) ? $settings[ 'label_size' ] : '16px';
        $html = sprintf( '<li class="count-%s">', $this->id );
        $html .= sprintf( '<a data-color-hover="%s" style="background-color:%s; %s" class="icon" href="%s"%s%s><span class="fa fa-fw %s" style="font-size:%s; color:%s" data-color-hover="%s"></span>', $counter_background_hover, $counter_background, $paddings, esc_url( $url ), $target_blank, $rel_nofollow, $icon, $icon_size, $icon_color, $icon_color_hover );
        $html .= '<span class="items">';
        $html .= sprintf( '<span data-color-hover="%s" class="count" style="font-size:%s; color:%s">%s</span>', $number_color_hover, $number_size, $number_color, $count );
        $html .= sprintf( '<span data-color-hover="%s" class="label" style="font-size:%s; color:%s">%s</span>', $label_color_hover, $label_size, $label_color, apply_filters( 'silicon_counters_label', $label, $this->id ) );
        $html .= '</span></a>';
        $html .= '</li>';

        return $html;
    }

    /**
     * Get conter view.
     */
    public

    function get_view( $settings, $total, $text_color ) {
        return '';
    }

    /**
     * Debug.
     */
    public

    function debug() {
        return $this->connection;
    }
}