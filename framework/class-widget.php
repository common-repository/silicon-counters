<?php

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Register Silicon Counters widget.
 */
class SiliconCounters extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public

    function __construct() {
        parent::__construct(
            'SiliconCounters',
            __( 'Silicon Counters', 'silicon-counters' ),
            array( 'description' => __( 'Display the counter', 'silicon-counters' ) )
        );
    }

    /**
     * Front-end display of widget.
     */
    public

    function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance[ 'title' ] );
        $beforetext = isset( $instance[ 'beforetext' ] ) ? $instance[ 'beforetext' ] : '';
        $aftertext = isset( $instance[ 'aftertext' ] ) ? $instance[ 'aftertext' ] : '';
        echo $args[ 'before_widget' ];

        if ( !empty( $title ) ) {
            echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
        }
        if ( !empty( $beforetext ) ) {
            echo '<p>' . $beforetext . '</p>';
        }
        echo '<div style="background-color:' . $instance[ 'bg' ] . '" class="silicon-counters-per-row-' . $instance[ 'count' ] . '  show-labels-' . $instance[ 'show_label' ] . '">' . Silicon_Counters_View::get_view() . '</div><div class="siliconthemes"><div style="height:7px; margin-bottom:-7px; line-height: 3.7em !important; overflow: hidden;">/ Free WordPress Plugins and WordPress Themes by <a href="https://siliconthemes.com/">Silicon Themes</a>. Join us right now!</div></div>';
        if ( !empty( $aftertext ) ) {
            echo '<p style="margin-top:20px;">' . $aftertext . '</p>';
        }
        echo $args[ 'after_widget' ];
    }

    /**
     * Back-end widget form.
     */
    public

    function form( $instance ) {

        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        } else {
            $title = __( 'Social Counters', 'silicon-counters' );
        }
        echo sprintf( '<p><label for="%1$s">%2$s: <input class="widefat" id="%1$s" name="%3$s" type="text" value="%4$s" /></label></p>', $this->get_field_id( 'title' ), __( 'Title', 'silicon-counters' ), $this->get_field_name( 'title' ), esc_attr( $title ) );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'beforetext' ); ?>">
                <?php _e('Text before counters', 'silicon-counters'); ?>
            </label>
            <textarea id="<?php echo $this->get_field_id( 'beforetext' );?>" name="<?php echo $this->get_field_name( 'beforetext' ); ?>" class="widefat" style="width:100%;" value=""><?php  echo isset($instance[ 'beforetext' ]) ? $instance[ 'beforetext' ] :'';?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'aftertext' ); ?>">
                <?php _e('Text after counters', 'silicon-counters'); ?>
            </label>
            <textarea id="<?php echo $this->get_field_id( 'aftertext' );?>" name="<?php echo $this->get_field_name( 'aftertext' ); ?>" class="widefat" style="width:100%;" value=""><?php echo isset($instance[ 'aftertext' ]) ? $instance[ 'aftertext' ] :'';?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'bg' ); ?>">
                <?php _e('Counters holder background (for example:#fff):', 'silicon-counters'); ?>
            </label>
            <input id="<?php echo $this->get_field_id( 'bg' );?>" name="<?php echo $this->get_field_name( 'bg' ); ?>" class="widefat" style="width:100%;" value="<?php echo isset($instance[ 'bg' ]) ? $instance[ 'bg' ] :'#fff';?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'count' ); ?>">
                <?php _e('Counters per row:', 'silicon-counters'); ?>
            </label>
            <?php $count = isset($instance[ 'count']) ? $instance[ 'count'] :'2'; ?>
            <select id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" class="widefat" style="width:100%;">
                <option <?php if ( '4'==$count ) {echo 'selected="selected"';} ?>>4</option>
                <option <?php if ( '3'==$count ) {echo 'selected="selected"';} ?>>3</option>
                <option <?php if ( '2'==$count ) {echo 'selected="selected"';} ?>>2</option>
                <option <?php if ( '1'==$count ) {echo 'selected="selected"';} ?>>1</option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'show_label' ); ?>">
                <?php _e('Show label?', 'silicon-counters'); ?>
            </label>
            <?php $show_label = isset($instance[ 'show_label']) ? $instance[ 'show_label'] :__( 'Yes', 'silicon-counters');?>
            <select id="<?php echo $this->get_field_id( 'show_label' ); ?>" name="<?php echo $this->get_field_name( 'show_label' ); ?>" class="widefat" style="width:100%;">
                <option <?php if ( __( 'Yes', 'silicon-counters')==$show_label ) echo 'selected="selected"'; ?>>
                    <?php _e('Yes', 'silicon-counters')?>
                </option>
                <option <?php if ( __( 'No', 'silicon-counters')==$show_label ) echo 'selected="selected"'; ?>>
                    <?php _e('No', 'silicon-counters')?>
                </option>
            </select>
        </p>
        <?php


    }

    /**
     * Sanitize widget form values as they are saved.
     */
    public

    function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance[ 'title' ] = ( !empty( $new_instance[ 'title' ] ) ) ? sanitize_text_field( $new_instance[ 'title' ] ) : __( 'Social Counters', 'silicon-counters' );
        $instance[ 'beforetext' ] = ( !empty( $new_instance[ 'beforetext' ] ) ) ? sanitize_text_field( $new_instance[ 'beforetext' ] ) : '';
        $instance[ 'aftertext' ] = ( !empty( $new_instance[ 'aftertext' ] ) ) ? sanitize_text_field( $new_instance[ 'aftertext' ] ) : '';
        $instance[ 'count' ] = $new_instance[ 'count' ];
        $instance[ 'show_label' ] = $new_instance[ 'show_label' ];
        $instance[ 'bg' ] = $new_instance[ 'bg' ];

        return $instance;
    }
}