<?php
/**
 * Plugin Name: Silicon Counters
 * Plugin URI: https://siliconthemes.com/free-wordpress-social-media-plugin-counters/
 * Author URI: https://siliconthemes.com/
 * Description: Simple yet powerful tool to show social networks statistic. It supports: Facebook, Twitter, Google Plus, Instagram, Twitch, YouTube, Pinterest, Vimeo, GitHub, SoundCloud, Steam and also standard WordPress numbers like a comments, users and posts. 
 * Author: Silicon Themes
 * Version: 1.1.5
 * License: GPLv2 or later
 * Text Domain: silicon-counters
 * Domain Path: /languages/
 */

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( !class_exists( 'Silicon_Counters' ) ):
    class Silicon_Counters {
        const VERSION = '1.0';
        protected static $instance = null;

        /**
         * Initialize the plugin.
         */
        private
        function __construct() {
            // Load plugin text domain.
            add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

            // Include classes.
            $this->includes();
            $this->include_counters();

            if ( is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) ) {
                $this->admin_includes();
            }

            // Widget.
            add_action( 'widgets_init', array( $this, 'register_widget' ) );

            // Shortcode.
            add_shortcode( 'silicon_counters', array( 'Silicon_Counters_Shortcode', 'counter' ) );

            // Scripts.
            add_action( 'wp_enqueue_scripts', array( $this, 'styles_and_scripts' ) );
        }

        /**
         * Return an instance of this class.
         *
         * @return object A single instance of this class.
         */
        public static
        function get_instance() {
            if ( null == self::$instance ) {
                self::$instance = new self;
            }

            return self::$instance;
        }

        /**
         * Load the plugin text domain for translation.
         */
        public
        function load_plugin_textdomain() {
            load_plugin_textdomain( 'silicon-counters', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        }

        /**
         * Include admin actions.
         */
        protected
        function admin_includes() {
            include 'framework/class-admin.php';
        }

        /**
         * Include plugin functions.
         */
        protected
        function includes() {
            include_once 'framework/class-generator.php';
            include_once 'framework/abstract-counter.php';
            include_once 'framework/class-view.php';
            include_once 'framework/class-widget.php';
            include_once 'framework/class-shortcodes.php';
            include_once 'framework/silicon-counters-functions.php';
        }

        /**
         * Include counters.
         */
        protected
        function include_counters() {
            foreach ( glob( realpath( dirname( __FILE__ ) ) . '/framework/counters/*.php' ) as $filename ) {
                include_once $filename;
            }
        }

        /**
         * Register widget.
         */
        public
        function register_widget() {
            register_widget( 'SiliconCounters' );
        }

        /**
         * Register public styles and scripts.
         */
        public
        function styles_and_scripts() {
            wp_register_style( 'silicon-counters', plugins_url( 'framework/assets/css/silicon-counters.css', __FILE__ ), array(), Silicon_Counters::VERSION, 'all' );
            wp_enqueue_style( 'font-awesome', plugins_url( 'framework/assets/font-awesome/css/font-awesome.min.css', __FILE__ ), array(), Silicon_Counters::VERSION, 'all' );
            wp_enqueue_script( 'silicon-counters-js', plugins_url( 'framework/assets/js/silicon-counters.js', __FILE__ ), array( 'jquery' ), Silicon_Counters::VERSION, 'all' );
        }
    }

/**
 * Init the plugin.
 */
add_action( 'plugins_loaded', array( 'Silicon_Counters', 'get_instance' ) );

endif;