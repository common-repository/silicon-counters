<?php

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Silicon Counters Admin.
 */
class Silicon_Counters_Admin {

    /**
     * Plugin settings screen.
     *
     * @var string
     */
    public $settings_screen = null;

    /**
     * Plugin settings.
     *
     * @var array
     */
    public $plugin_settings = array();

    /**
     * Plugin settings.
     *
     * @var array
     */
    public $plugin_design = array();

    /**
     * Initialize the plugin admin.
     */
    public
    function __construct() {
        // Adds admin menu.
        add_action( 'admin_menu', array( $this, 'settings_menu' ) );

        // Init plugin options form.
        add_action( 'admin_init', array( $this, 'plugin_settings' ) );

        // Style and scripts.
        add_action( 'admin_enqueue_scripts', array( $this, 'styles_and_scripts' ) );

        // Actions links.
        add_filter( 'plugin_action_links_silicon-counters/silicon-counters.php', array( $this, 'action_links' ) );

        // System status report.
        add_action( 'admin_init', array( $this, 'report_file' ) );

        // Install/update plugin options.
        $this->maybe_install();
    }

    /**
     * Get the plugin options.
     *
     * @return array
     */
    protected static
    function get_plugin_options() {
        $twitter_oauth_description = sprintf( __( 'Follow instructions on %s site', 'silicon-counters' ), '<a href="https://siliconthemes.com/free-wordpress-social-media-plugin-counters/" target="_blank">Silicon Themes</a>' );
        $facebook_app_description =  sprintf( __( 'Follow instructions on %s site', 'silicon-counters' ), '<a href="https://siliconthemes.com/free-wordpress-social-media-plugin-counters/" target="_blank">Silicon Themes</a>' );
        $twitch_id_description =  sprintf( __( 'Follow instructions on %s site', 'silicon-counters' ), '<a href="https://siliconthemes.com/free-wordpress-social-media-plugin-counters/" target="_blank">Silicon Themes</a>' );
		$font_awesome = sprintf( __( 'You can choose any icon from  %s.', 'silicon-counters' ), '<a href="http://fontawesome.io/icons/" target="_blank">http://fontawesome.io/icons/</a>' );

        $settings = array(
            'siliconcounters_settings' => array(
                'settings' => array(
                    'title' => __( 'Settings', 'silicon-counters' ),
                    'fields' => array(
                        'icon_size' => array(
                            'title' => __( 'Icon Size', 'silicon-counters' ),
                            'type' => 'text',
                            'default' => '24px',
                            'description' => sprintf( __( 'Default: %s', 'silicon-counters' ), '<code>24px</code>' )
                        ),
                        'number_size' => array(
                            'title' => __( 'Number Size', 'silicon-counters' ),
                            'type' => 'text',
                            'default' => '16px',
                            'description' => sprintf( __( 'Default: %s', 'silicon-counters' ), '<code>16px</code>' )
                        ),
                        'label_size' => array(
                            'title' => __( 'Label Size', 'silicon-counters' ),
                            'type' => 'text',
                            'default' => '12px',
                            'description' => sprintf( __( 'Default: %s', 'silicon-counters' ), '<code>12px</code>' )
                        ),
                        'paddings' => array(
                            'title' => __( 'Paddings', 'silicon-counters' ),
                            'type' => 'text',
                            'default' => '10px',
                            'description' => sprintf( __( 'Counter paddings (Left, Top, Right, Bottom). For example %s', 'silicon-counters' ), '<code>10px 10px 10px 10px</code>' )
                        ),
                        'target_blank' => array(
                            'title' => __( 'Open URLs in new tab/window', 'silicon-counters' ),
                            'type' => 'checkbox',
                            'description' => sprintf( __( 'This option add %s in all counters URLs.', 'silicon-counters' ), '<code>target="_blank"</code>' )
                        ),
                        'rel_nofollow' => array(
                            'title' => __( 'Add nofollow in URLs', 'silicon-counters' ),
                            'type' => 'checkbox',
                            'description' => sprintf( __( 'This option add %s in all counters URLs.', 'silicon-counters' ), '<code>rel="nofollow"</code>' )
                        ),
                        'layout' => array(
                            'title' => __( 'Counters Layout', 'silicon-counters' ),
                            'default' => '0',
                            'type' => 'layout',
                            'options' => array( 'Vertical', 'Horizontal' )
                        ),
                        'convert_thousand' => array(
                            'title' => __( 'Convert thousands', 'silicon-counters' ),
                            'type' => 'checkbox',
                            'description' => sprintf( __( 'This option confert followers thousands to "k" format for example 1543 followes will be shown like 1.5k followers', 'silicon-counters' ), '<code>rel="nofollow"</code>' )
                        ),
                        'icons' => array(
                            'title' => __( 'Order', 'silicon-counters' ),
                            'type' => 'icons_order',
                            'description' => __( 'This option controls the order of the icons in the widget.', 'silicon-counters' )
                        )
                    )
                ),
                'twitter' => array(
                    'title' => __( 'Twitter', 'silicon-counters' ),
                    'fields' => array(
                        'twitter_active' => array(
                            'title' => '<span class="fa fa-fw fa-silicon-settings fa-twitter"></span>' . __( 'Twitter', 'silicon-counters' ),
                            'type' => 'checkbox'
                        ),
                        'twitter_icon' => array(
                            'title' => __( 'Icon', 'silicon-counters' ),
                            'default' => 'fa-twitter',
                            'type' => 'icon',
                            'description' => sprintf(
                                '%s<br />%s<br />',
                                $font_awesome,
                                sprintf( __( 'Default: %s', 'silicon-counters' ), '<code>fa-twitter</code>' )
                            )
                        ),
                        'twitter_icon-color' => array(
                            'title' => __( 'Icon Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'twitter_icon-color-hover' => array(
                            'title' => __( 'Icon Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'twitter_counter-background' => array(
                            'title' => __( 'Counter Background', 'silicon-counters' ),
                            'default' => '#33ccff',
                            'type' => 'color',
                        ),
                        'twitter_counter-background-hover' => array(
                            'title' => __( 'Counter Background on Hover', 'silicon-counters' ),
                            'default' => '#0084b4',
                            'type' => 'color',
                        ),
                        'twitter_number-color' => array(
                            'title' => __( 'Number Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'twitter_number-color-hover' => array(
                            'title' => __( 'Number Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'twitter_label-color' => array(
                            'title' => __( 'Label Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'twitter_label-color-hover' => array(
                            'title' => __( 'Label Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'twitter_user' => array(
                            'title' => __( 'Twitter Username', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => sprintf( __( 'Insert the Twitter username. Example: %s.', 'silicon-counters' ), '<code>SiliconDesign</code>' )
                        ),
                        'twitter_consumer_key' => array(
                            'title' => __( 'Twitter Consumer key', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => $twitter_oauth_description
                        ),
                        'twitter_consumer_secret' => array(
                            'title' => __( 'Twitter Consumer secret', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => $twitter_oauth_description
                        ),
                        'twitter_access_token' => array(
                            'title' => __( 'Twitter Access token', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => $twitter_oauth_description
                        ),
                        'twitter_access_token_secret' => array(
                            'title' => __( 'Twitter Access token secret', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => $twitter_oauth_description
                        ),
                        'twitter_shortcode' => array(
                            'title' => 'Shortcode',
                            'default' => 'twitter',
                            'type' => 'shortcode'
                        ),
                        'twitter_function' => array(
                            'title' => 'PHP Function',
                            'default' => 'twitter',
                            'type' => 'function'
                        )
                    )
                ),
                'googleplus' => array(
                    'title' => __( 'Google+', 'silicon-counters' ),
                    'fields' => array(
                        'googleplus_active' => array(
                            'title' => '<span class="fa fa-fw fa-silicon-settings fa-google-plus"></span>' . __( 'Google+', 'silicon-counters' ),
                            'type' => 'checkbox'
                        ),
                        'googleplus_icon' => array(
                            'title' => __( 'Icon', 'silicon-counters' ),
                            'default' => 'fa-google-plus',
                            'type' => 'icon',
                            'description' => sprintf(
                                '%s<br />%s<br />',
                                $font_awesome,
                                sprintf( __( 'Default: %s', 'silicon-counters' ), '<code>fa-google-plus</code>' )
                            )
                        ),
                        'googleplus_icon-color' => array(
                            'title' => __( 'Icon Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'googleplus_icon-color-hover' => array(
                            'title' => __( 'Icon Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'googleplus_counter-background' => array(
                            'title' => __( 'Counter Background', 'silicon-counters' ),
                            'default' => '#dc493c',
                            'type' => 'color',
                        ),
                        'googleplus_counter-background-hover' => array(
                            'title' => __( 'Counter Background on Hover', 'silicon-counters' ),
                            'default' => '#dc493c',
                            'type' => 'color',
                        ),
                        'googleplus_number-color' => array(
                            'title' => __( 'Number Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'googleplus_number-color-hover' => array(
                            'title' => __( 'Number Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'googleplus_label-color' => array(
                            'title' => __( 'Label Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'googleplus_label-color-hover' => array(
                            'title' => __( 'Label Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'googleplus_id' => array(
                            'title' => __( 'Google+ ID', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => sprintf(
                                '%s<br />%s <code>https://plus.google.com/<strong>106146333300678794719</strong></code> or <code>https://plus.google.com/<strong>+SiliconThemes</strong></code>',
                                __( 'Google+ page or profile ID.', 'silicon-counters' ),
                                __( 'Example:', 'silicon-counters' )
                            )
                        ),
                        'googleplus_api_key' => array(
                            'title' => __( 'Google API Key', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => sprintf( __( 'Follow instructions on %s site', 'silicon-counters' ), '<a href="https://siliconthemes.com/free-wordpress-social-media-plugin-counters/" target="_blank">Silicon Themes</a>' )
                        ),
                        'googleplus_shortcode' => array(
                            'title' => 'Shortcode',
                            'default' => 'googleplus',
                            'type' => 'shortcode'
                        ),
                        'googleplus_function' => array(
                            'title' => 'PHP Function',
                            'default' => 'googleplus',
                            'type' => 'function'
                        )
                    )
                ),
                'facebook' => array(
                    'title' => __( 'Facebook', 'silicon-counters' ),
                    'fields' => array(
                        'facebook_active' => array(
                            'title' => '<span class="fa fa-fw fa-silicon-settings fa-facebook-official"></span>' . __( 'Facebook', 'silicon-counters' ),
                            'type' => 'checkbox'
                        ),
                        'facebook_icon' => array(
                            'title' => __( 'Icon', 'silicon-counters' ),
                            'default' => 'fa-facebook-official',
                            'type' => 'icon',
                            'description' => sprintf(
                                '%s<br />%s<br />',
                                $font_awesome,
                                sprintf( __( 'Default: %s', 'silicon-counters' ), '<code>fa-facebook-official</code>' )
                            )
                        ),
                        'facebook_icon-color' => array(
                            'title' => __( 'Icon Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'facebook_icon-color-hover' => array(
                            'title' => __( 'Icon Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'facebook_counter-background' => array(
                            'title' => __( 'Counter Background', 'silicon-counters' ),
                            'default' => '#3B5998',
                            'type' => 'color',
                        ),
                        'facebook_counter-background-hover' => array(
                            'title' => __( 'Counter Background on Hover', 'silicon-counters' ),
                            'default' => '#5E80BF',
                            'type' => 'color',
                        ),
                        'facebook_number-color' => array(
                            'title' => __( 'Number Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'facebook_number-color-hover' => array(
                            'title' => __( 'Number Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'facebook_label-color' => array(
                            'title' => __( 'Label Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'facebook_label-color-hover' => array(
                            'title' => __( 'Label Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'facebook_id' => array(
                            'title' => __( 'Facebook Page ID', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => sprintf(
                                '%s<br />%s<br /><code>https://www.facebook.com/pages/edit/?id=<strong>1234567890</strong></code> %s <code>https://www.facebook.com/<strong>SiliconThemesCom</strong></code>.',
                                __( 'ID Facebook page. Must be the numeric ID or your page slug.', 'silicon-counters' ),
                                __( 'You can find this data clicking to edit your page on Facebook. The URL will be similar to this:', 'silicon-counters' ),
                                __( 'or', 'silicon-counters' )
                            )
                        ),
                        'facebook_app_id' => array(
                            'title' => __( 'Facebook App ID', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => $facebook_app_description
                        ),
                        'facebook_app_secret' => array(
                            'title' => __( 'Facebook App Secret', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => $facebook_app_description
                        ),
                        'facebook_shortcode' => array(
                            'title' => 'Shortcode',
                            'default' => 'facebook',
                            'type' => 'shortcode'
                        ),
                        'facebook_function' => array(
                            'title' => 'PHP Function',
                            'default' => 'facebook',
                            'type' => 'function'
                        )
                    )
                ),
                'vimeo' => array(
                    'title' => __( 'Vimeo', 'silicon-counters' ),
                    'fields' => array(
                        'vimeo_active' => array(
                            'title' => '<span class="fa fa-fw fa-silicon-settings fa-vimeo"></span>' . __( 'Vimeo', 'silicon-counters' ),
                            'type' => 'checkbox'
                        ),
                        'vimeo_icon' => array(
                            'title' => __( 'Icon', 'silicon-counters' ),
                            'default' => 'fa-vimeo',
                            'type' => 'icon',
                            'description' => sprintf(
                                '%s<br />%s<br />',
                                $font_awesome,
                                sprintf( __( 'Default: %s', 'silicon-counters' ), '<code>fa-vimeo</code>' )
                            )
                        ),
                        'vimeo_icon-color' => array(
                            'title' => __( 'Icon Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'vimeo_icon-color-hover' => array(
                            'title' => __( 'Icon Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'vimeo_counter-background' => array(
                            'title' => __( 'Counter Background', 'silicon-counters' ),
                            'default' => '#00adef',
                            'type' => 'color',
                        ),
                        'vimeo_counter-background-hover' => array(
                            'title' => __( 'Counter Background on Hover', 'silicon-counters' ),
                            'default' => '#0088cc',
                            'type' => 'color',
                        ),
                        'vimeo_number-color' => array(
                            'title' => __( 'Number Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'vimeo_number-color-hover' => array(
                            'title' => __( 'Number Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'vimeo_label-color' => array(
                            'title' => __( 'Label Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'vimeo_label-color-hover' => array(
                            'title' => __( 'Label Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'vimeo_username' => array(
                            'title' => __( 'Vimeo Username', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => sprintf( __( 'Insert your Vimeo username. Example: %s.', 'silicon-counters' ), '<code>siliconthemes</code>' )
                        ),
                        'vimeo_shortcode' => array(
                            'title' => 'Shortcode',
                            'default' => 'vimeo',
                            'type' => 'shortcode'
                        ),
                        'vimeo_function' => array(
                            'title' => 'PHP Function',
                            'default' => 'vimeo',
                            'type' => 'function'
                        )
                    )
                ),
                'youtube' => array(
                    'title' => __( 'YouTube', 'silicon-counters' ),
                    'fields' => array(
                        'youtube_active' => array(
                            'title' => '<span class="fa fa-fw fa-silicon-settings fa-youtube"></span>' . __( 'YouTube', 'silicon-counters' ),
                            'type' => 'checkbox'
                        ),
                        'youtube_icon' => array(
                            'title' => __( 'Icon', 'silicon-counters' ),
                            'default' => 'fa-youtube',
                            'type' => 'icon',
                            'description' => sprintf(
                                '%s<br />%s<br />',
                                $font_awesome,
                                sprintf( __( 'Default: %s', 'silicon-counters' ), '<code>fa-youtube</code>' )
                            )
                        ),
                        'youtube_icon-color' => array(
                            'title' => __( 'Icon Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'youtube_icon-color-hover' => array(
                            'title' => __( 'Icon Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'youtube_counter-background' => array(
                            'title' => __( 'Counter Background', 'silicon-counters' ),
                            'default' => '#bf2626',
                            'type' => 'color',
                        ),
                        'youtube_counter-background-hover' => array(
                            'title' => __( 'Counter Background on Hover', 'silicon-counters' ),
                            'default' => '#90030c',
                            'type' => 'color',
                        ),
                        'youtube_number-color' => array(
                            'title' => __( 'Number Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'youtube_number-color-hover' => array(
                            'title' => __( 'Number Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'youtube_label-color' => array(
                            'title' => __( 'Label Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'youtube_label-color-hover' => array(
                            'title' => __( 'Label Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'youtube_user' => array(
                            'title' => __( 'YouTube Channel ID', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => sprintf( __( 'Insert the YouTube Channel ID. Example: %s.', 'silicon-counters' ), '<code>SUIKSjhkhauy7OIU&ojoi</code>' )
                        ),
                        'youtube_url' => array(
                            'title' => __( 'YouTube Channel URL', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => sprintf( __( 'Insert the YouTube channel URL. Example: %s.', 'silicon-counters' ), '<code>https://www.youtube.com/user/siliconthemes</code>' )
                        ),
                        'youtube_api_key' => array(
                            'title' => __( 'Google API Key', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => sprintf( __( 'Follow instructions on %s site', 'silicon-counters' ), '<a href="https://siliconthemes.com/free-wordpress-social-media-plugin-counters/" target="_blank">Silicon Themes</a>' )
                        ),
                        'youtube_shortcode' => array(
                            'title' => 'Shortcode',
                            'default' => 'youtube',
                            'type' => 'shortcode'
                        ),
                        'youtube_function' => array(
                            'title' => 'PHP Function',
                            'default' => 'youtube',
                            'type' => 'function'
                        )
                    )
                ),
                'instagram' => array(
                    'title' => __( 'Instagram', 'silicon-counters' ),
                    'fields' => array(
                        'instagram_active' => array(
                            'title' => '<span class="fa fa-fw fa-silicon-settings fa-instagram"></span>' . __( 'Instagram', 'silicon-counters' ),
                            'type' => 'checkbox'
                        ),
                        'instagram_icon' => array(
                            'title' => __( 'Icon', 'silicon-counters' ),
                            'default' => 'fa-instagram',
                            'type' => 'icon',
                            'description' => sprintf(
                                '%s<br />%s<br />',
                                $font_awesome,
                                sprintf( __( 'Default: %s', 'silicon-counters' ), '<code>fa-instagram</code>' )
                            )
                        ),
                        'instagram_icon-color' => array(
                            'title' => __( 'Icon Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'instagram_icon-color-hover' => array(
                            'title' => __( 'Icon Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'instagram_counter-background' => array(
                            'title' => __( 'Counter Background', 'silicon-counters' ),
                            'default' => '#cd486b',
                            'type' => 'color',
                        ),
                        'instagram_counter-background-hover' => array(
                            'title' => __( 'Counter Background on Hover', 'silicon-counters' ),
                            'default' => '#8a3ab9',
                            'type' => 'color',
                        ),
                        'instagram_number-color' => array(
                            'title' => __( 'Number Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'instagram_number-color-hover' => array(
                            'title' => __( 'Number Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'instagram_label-color' => array(
                            'title' => __( 'Label Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'instagram_label-color-hover' => array(
                            'title' => __( 'Label Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'instagram_username' => array(
                            'title' => __( 'Instagram Username', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => __( 'Insert your Instagram Username.', 'silicon-counters' )
                        ),
                        'instagram_shortcode' => array(
                            'title' => 'Shortcode',
                            'default' => 'instagram',
                            'type' => 'shortcode'
                        ),
                        'instagram_function' => array(
                            'title' => 'PHP Function',
                            'default' => 'instagram',
                            'type' => 'function'
                        )
                    )
                ),
                'pinterest' => array(
                    'title' => __( 'Pinterest', 'silicon-counters' ),
                    'fields' => array(
                        'pinterest_active' => array(
                            'title' => '<span class="fa fa-fw fa-silicon-settings fa-pinterest"></span>' . __( 'Pinterest', 'silicon-counters' ),
                            'type' => 'checkbox'
                        ),
                        'pinterest_icon' => array(
                            'title' => __( 'Icon', 'silicon-counters' ),
                            'default' => 'fa-pinterest',
                            'type' => 'icon',
                            'description' => sprintf(
                                '%s<br />%s<br />',
                                $font_awesome,
                                sprintf( __( 'Default: %s', 'silicon-counters' ), '<code>fa-pinterest</code>' )
                            )
                        ),
                        'pinterest_icon-color' => array(
                            'title' => __( 'Icon Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'pinterest_icon-color-hover' => array(
                            'title' => __( 'Icon Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'pinterest_counter-background' => array(
                            'title' => __( 'Counter Background', 'silicon-counters' ),
                            'default' => '#e3262e',
                            'type' => 'color',
                        ),
                        'pinterest_counter-background-hover' => array(
                            'title' => __( 'Counter Background on Hover', 'silicon-counters' ),
                            'default' => '#ab171e',
                            'type' => 'color',
                        ),
                        'pinterest_number-color' => array(
                            'title' => __( 'Number Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'pinterest_number-color-hover' => array(
                            'title' => __( 'Number Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'pinterest_label-color' => array(
                            'title' => __( 'Label Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'pinterest_label-color-hover' => array(
                            'title' => __( 'Label Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'pinterest_username' => array(
                            'title' => __( 'Pinterest Username', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => sprintf( __( 'Insert your Pinterest username. Example: %s.', 'silicon-counters' ), '<code>siliconthemes</code>' )
                        ),
                        'pinterest_shortcode' => array(
                            'title' => 'Shortcode',
                            'default' => 'pinterest',
                            'type' => 'shortcode'
                        ),
                        'pinterest_function' => array(
                            'title' => 'PHP Function',
                            'default' => 'pinterest',
                            'type' => 'function'
                        )
                    )
                ),
                'github' => array(
                    'title' => __( 'GitHub', 'silicon-counters' ),
                    'fields' => array(
                        'github_active' => array(
                            'title' => '<span class="fa fa-fw fa-silicon-settings fa-github"></span>' . __( 'GitHub', 'silicon-counters' ),
                            'type' => 'checkbox'
                        ),
                        'github_icon' => array(
                            'title' => __( 'Icon', 'silicon-counters' ),
                            'default' => 'fa-github',
                            'type' => 'icon',
                            'description' => sprintf(
                                '%s<br />%s<br />',
                                $font_awesome,
                                sprintf( __( 'Default: %s', 'silicon-counters' ), '<code>fa-github</code>' )
                            )
                        ),
                        'github_icon-color' => array(
                            'title' => __( 'Icon Color', 'silicon-counters' ),
                            'default' => '#000',
                            'type' => 'color',
                        ),
                        'github_icon-color-hover' => array(
                            'title' => __( 'Icon Color on Hover', 'silicon-counters' ),
                            'default' => '#000',
                            'type' => 'color',
                        ),
                        'github_counter-background' => array(
                            'title' => __( 'Counter Background', 'silicon-counters' ),
                            'default' => '#f6f6f6',
                            'type' => 'color',
                        ),
                        'github_counter-background-hover' => array(
                            'title' => __( 'Counter Background on Hover', 'silicon-counters' ),
                            'default' => '#f1f1f1',
                            'type' => 'color',
                        ),
                        'github_number-color' => array(
                            'title' => __( 'Number Color', 'silicon-counters' ),
                            'default' => '#000',
                            'type' => 'color',
                        ),
                        'github_number-color-hover' => array(
                            'title' => __( 'Number Color on Hover', 'silicon-counters' ),
                            'default' => '#000',
                            'type' => 'color',
                        ),
                        'github_label-color' => array(
                            'title' => __( 'Label Color', 'silicon-counters' ),
                            'default' => '#999999',
                            'type' => 'color',
                        ),
                        'github_label-color-hover' => array(
                            'title' => __( 'Label Color on Hover', 'silicon-counters' ),
                            'default' => '#999999',
                            'type' => 'color',
                        ),
                        'github_username' => array(
                            'title' => __( 'GitHub Username', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => sprintf( __( 'Insert your GitHub username. Example: %s.', 'silicon-counters' ), '<code>siliconthemes</code>' )
                        ),
                        'github_shortcode' => array(
                            'title' => 'Shortcode',
                            'default' => 'github',
                            'type' => 'shortcode'
                        ),
                        'github_function' => array(
                            'title' => 'PHP Function',
                            'default' => 'github',
                            'type' => 'function'
                        )
                    )
                ),
                'soundcloud' => array(
                    'title' => __( 'SoundCloud', 'silicon-counters' ),
                    'fields' => array(
                        'soundcloud_active' => array(
                            'title' => '<span class="fa fa-fw fa-silicon-settings fa-soundcloud"></span>' . __( 'SoundCloud', 'silicon-counters' ),
                            'type' => 'checkbox'
                        ),
                        'soundcloud_icon' => array(
                            'title' => __( 'Icon', 'silicon-counters' ),
                            'default' => 'fa-soundcloud',
                            'type' => 'icon',
                            'description' => sprintf(
                                '%s<br />%s<br />',
                                $font_awesome,
                                sprintf( __( 'Default: %s', 'silicon-counters' ), '<code>fa-soundcloud</code>' )
                            )
                        ),
                        'soundcloud_icon-color' => array(
                            'title' => __( 'Icon Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'soundcloud_icon-color-hover' => array(
                            'title' => __( 'Icon Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'soundcloud_counter-background' => array(
                            'title' => __( 'Counter Background', 'silicon-counters' ),
                            'default' => '#ff993f',
                            'type' => 'color',
                        ),
                        'soundcloud_counter-background-hover' => array(
                            'title' => __( 'Counter Background on Hover', 'silicon-counters' ),
                            'default' => '#e79a57',
                            'type' => 'color',
                        ),
                        'soundcloud_number-color' => array(
                            'title' => __( 'Number Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'soundcloud_number-color-hover' => array(
                            'title' => __( 'Number Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'soundcloud_label-color' => array(
                            'title' => __( 'Label Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'soundcloud_label-color-hover' => array(
                            'title' => __( 'Label Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'soundcloud_username' => array(
                            'title' => __( 'SoundCloud Username', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => __( 'Insert your SoundCloud Username.', 'silicon-counters' )
                        ),
                        'soundcloud_client_id' => array(
                            'title' => __( 'SoundCloud Client ID', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => sprintf( __( 'Insert your SoundCloud App Client ID. Generate this data in %s.', 'silicon-counters' ), '<a href="http://soundcloud.com/you/apps/new" target="_blank">http://soundcloud.com/you/apps/new</a>' )
                        ),
                        'soundcloud_shortcode' => array(
                            'title' => 'Shortcode',
                            'default' => 'soundcloud',
                            'type' => 'shortcode'
                        ),
                        'soundcloud_function' => array(
                            'title' => 'PHP Function',
                            'default' => 'soundcloud',
                            'type' => 'function'
                        )
                    )
                ),
                'steam' => array(
                    'title' => __( 'Steam', 'silicon-counters' ),
                    'fields' => array(
                        'steam_active' => array(
                            'title' => '<span class="fa fa-fw fa-silicon-settings fa-steam"></span>' . __( 'Steam', 'silicon-counters' ),
                            'type' => 'checkbox'
                        ),
                        'steam_icon' => array(
                            'title' => __( 'Icon', 'silicon-counters' ),
                            'default' => 'fa-steam',
                            'type' => 'icon',
                            'description' => sprintf(
                                '%s<br />%s<br />',
                                $font_awesome,
                                sprintf( __( 'Default: %s', 'silicon-counters' ), '<code>fa-steam</code>' )
                            )
                        ),
                        'steam_icon-color' => array(
                            'title' => __( 'Icon Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'steam_icon-color-hover' => array(
                            'title' => __( 'Icon Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'steam_counter-background' => array(
                            'title' => __( 'Counter Background', 'silicon-counters' ),
                            'default' => '#000',
                            'type' => 'color',
                        ),
                        'steam_counter-background-hover' => array(
                            'title' => __( 'Counter Background on Hover', 'silicon-counters' ),
                            'default' => '#222',
                            'type' => 'color',
                        ),
                        'steam_number-color' => array(
                            'title' => __( 'Number Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'steam_number-color-hover' => array(
                            'title' => __( 'Number Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'steam_label-color' => array(
                            'title' => __( 'Label Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'steam_label-color-hover' => array(
                            'title' => __( 'Label Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'steam_group_name' => array(
                            'title' => __( 'Steam Group Name', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => sprintf( __( 'Insert your Steam Community group name. Example: %s.', 'silicon-counters' ), '<code>DOTALT</code>' )
                        ),
                        'steam_shortcode' => array(
                            'title' => 'Shortcode',
                            'default' => 'steam',
                            'type' => 'shortcode'
                        ),
                        'steam_function' => array(
                            'title' => 'PHP Function',
                            'default' => 'steam',
                            'type' => 'function'
                        )
                    )
                ),
                'twitch' => array(
                    'title' => __( 'Twitch', 'silicon-counters' ),
                    'fields' => array(
                        'twitch_active' => array(
                            'title' => '<span class="fa fa-fw fa-silicon-settings fa-twitch"></span>' . __( 'Twitch', 'silicon-counters' ),
                            'type' => 'checkbox'
                        ),
                        'twitch_icon' => array(
                            'title' => __( 'Icon', 'silicon-counters' ),
                            'default' => 'fa-twitch',
                            'type' => 'icon',
                            'description' => sprintf(
                                '%s<br />%s<br />',
                                $font_awesome,
                                sprintf( __( 'Default: %s', 'silicon-counters' ), '<code>fa-twitch</code>' )
                            )
                        ),
                        'twitch_icon-color' => array(
                            'title' => __( 'Icon Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'twitch_icon-color-hover' => array(
                            'title' => __( 'Icon Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'twitch_counter-background' => array(
                            'title' => __( 'Counter Background', 'silicon-counters' ),
                            'default' => '#6441a5',
                            'type' => 'color',
                        ),
                        'twitch_counter-background-hover' => array(
                            'title' => __( 'Counter Background on Hover', 'silicon-counters' ),
                            'default' => '#483d8b',
                            'type' => 'color',
                        ),
                        'twitch_number-color' => array(
                            'title' => __( 'Number Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'twitch_number-color-hover' => array(
                            'title' => __( 'Number Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'twitch_label-color' => array(
                            'title' => __( 'Label Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'twitch_label-color-hover' => array(
                            'title' => __( 'Label Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'twitch_username' => array(
                            'title' => __( 'Twitch Username', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => __( 'Insert your Twitch username.', 'silicon-counters' )
                        ),
						'twitch_client_id' => array(
                            'title' => __( 'Twitch Client ID', 'silicon-counters' ),
                            'type' => 'text',
                            'description' => $twitch_id_description
                        ),
                        'twitch_shortcode' => array(
                            'title' => 'Shortcode',
                            'default' => 'twitch',
                            'type' => 'shortcode'
                        ),
                        'twitch_function' => array(
                            'title' => 'PHP Function',
                            'default' => 'twitch',
                            'type' => 'function'
                        )
                    )
                ),
                'posts' => array(
                    'title' => __( 'Posts', 'silicon-counters' ),
                    'fields' => array(
                        'posts_active' => array(
                            'title' => '<span class="fa fa-fw fa-silicon-settings fa-paper-plane-o"></span>' . __( 'Posts', 'silicon-counters' ),
                            'type' => 'checkbox'
                        ),
                        'posts_icon' => array(
                            'title' => __( 'Icon', 'silicon-counters' ),
                            'default' => 'fa-paper-plane-o',
                            'type' => 'icon',
                            'description' => sprintf(
                                '%s<br />%s<br />',
                                $font_awesome,
                                sprintf( __( 'Default: %s', 'silicon-counters' ), '<code>fa-paper-plane-o</code>' )
                            )
                        ),
                        'posts_icon-color' => array(
                            'title' => __( 'Icon Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'posts_icon-color-hover' => array(
                            'title' => __( 'Icon Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'posts_counter-background' => array(
                            'title' => __( 'Counter Background', 'silicon-counters' ),
                            'default' => '#28afa6',
                            'type' => 'color',
                        ),
                        'posts_counter-background-hover' => array(
                            'title' => __( 'Counter Background on Hover', 'silicon-counters' ),
                            'default' => '#22899e',
                            'type' => 'color',
                        ),
                        'posts_number-color' => array(
                            'title' => __( 'Number Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'posts_number-color-hover' => array(
                            'title' => __( 'Number Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'posts_label-color' => array(
                            'title' => __( 'Label Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'posts_label-color-hover' => array(
                            'title' => __( 'Label Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'posts_post_type' => array(
                            'title' => __( 'Post Type', 'silicon-counters' ),
                            'default' => 'post',
                            'type' => 'post_type'
                        ),
                        'posts_url' => array(
                            'title' => __( 'URL', 'silicon-counters' ),
                            'default' => get_home_url(),
                            'type' => 'text'
                        ),
                        'posts_shortcode' => array(
                            'title' => 'Shortcode',
                            'default' => 'posts',
                            'type' => 'shortcode'
                        ),
                        'posts_function' => array(
                            'title' => 'PHP Function',
                            'default' => 'posts',
                            'type' => 'function'
                        )
                    )
                ),
                'users' => array(
                    'title' => __( 'Users', 'silicon-counters' ),
                    'fields' => array(
                        'users_active' => array(
                            'title' => '<span class="fa fa-fw fa-silicon-settings fa-users"></span>' . __( 'Users', 'silicon-counters' ),
                            'type' => 'checkbox'
                        ),
                        'users_icon' => array(
                            'title' => __( 'Icon', 'silicon-counters' ),
                            'default' => 'fa-users',
                            'type' => 'icon',
                            'description' => sprintf(
                                '%s<br />%s<br />',
                                $font_awesome,
                                sprintf( __( 'Default: %s', 'silicon-counters' ), '<code>fa-users</code>' )
                            )
                        ),
                        'users_icon-color' => array(
                            'title' => __( 'Icon Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'users_icon-color-hover' => array(
                            'title' => __( 'Icon Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'users_counter-background' => array(
                            'title' => __( 'Counter Background', 'silicon-counters' ),
                            'default' => '#81d742',
                            'type' => 'color',
                        ),
                        'users_counter-background-hover' => array(
                            'title' => __( 'Counter Background on Hover', 'silicon-counters' ),
                            'default' => '#81d742',
                            'type' => 'color',
                        ),
                        'users_number-color' => array(
                            'title' => __( 'Number Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'users_number-color-hover' => array(
                            'title' => __( 'Number Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'users_label-color' => array(
                            'title' => __( 'Label Color', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'users_label-color-hover' => array(
                            'title' => __( 'Label Color on Hover', 'silicon-counters' ),
                            'default' => '#fff',
                            'type' => 'color',
                        ),
                        'users_user_role' => array(
                            'title' => __( 'User Role', 'silicon-counters' ),
                            'default' => 'subscriber',
                            'type' => 'user_role'
                        ),
                        'users_label' => array(
                            'title' => __( 'Label', 'silicon-counters' ),
                            'default' => __( 'users', 'silicon-counters' ),
                            'type' => 'text'
                        ),
                        'users_url' => array(
                            'title' => __( 'URL', 'silicon-counters' ),
                            'default' => get_home_url(),
                            'type' => 'text'
                        ),
                        'users_shortcode' => array(
                            'title' => 'Shortcode',
                            'default' => 'users',
                            'type' => 'shortcode'
                        ),
                        'users_function' => array(
                            'title' => 'PHP Function',
                            'default' => 'users',
                            'type' => 'function'
                        )
                    )
                ),
                'comments' => array(
                    'title' => __( 'Comments', 'silicon-counters' ),
                    'fields' => array(
                        'comments_active' => array(
                            'title' => '<span class="fa fa-fw fa-silicon-settings fa-comments"></span>' . __( 'Comments', 'silicon-counters' ),
                            'type' => 'checkbox'
                        ),
                        'comments_icon' => array(
                            'title' => __( 'Icon', 'silicon-counters' ),
                            'default' => 'fa-comments',
                            'type' => 'icon',
                            'description' => sprintf(
                                '%s<br />%s<br />',
                                $font_awesome,
                                sprintf( __( 'Default: %s', 'silicon-counters' ), '<code>fa-comments</code>' )
                            )
                        ),
                        'comments_icon-color' => array(
                            'title' => __( 'Icon Color', 'silicon-counters' ),
                            'default' => '#000',
                            'type' => 'color',
                        ),
                        'comments_icon-color-hover' => array(
                            'title' => __( 'Icon Color on Hover', 'silicon-counters' ),
                            'default' => '#000',
                            'type' => 'color',
                        ),
                        'comments_counter-background' => array(
                            'title' => __( 'Counter Background', 'silicon-counters' ),
                            'default' => '#f6f6f6',
                            'type' => 'color',
                        ),
                        'comments_counter-background-hover' => array(
                            'title' => __( 'Counter Background on Hover', 'silicon-counters' ),
                            'default' => '#f1f1f1',
                            'type' => 'color',
                        ),
                        'comments_number-color' => array(
                            'title' => __( 'Number Color', 'silicon-counters' ),
                            'default' => '#000',
                            'type' => 'color',
                        ),
                        'comments_number-color-hover' => array(
                            'title' => __( 'Number Color on Hover', 'silicon-counters' ),
                            'default' => '#000',
                            'type' => 'color',
                        ),
                        'comments_label-color' => array(
                            'title' => __( 'Label Color', 'silicon-counters' ),
                            'default' => '#999999',
                            'type' => 'color',
                        ),
                        'comments_label-color-hover' => array(
                            'title' => __( 'Label Color on Hover', 'silicon-counters' ),
                            'default' => '#999999',
                            'type' => 'color',
                        ),
                        'comments_url' => array(
                            'title' => __( 'URL', 'silicon-counters' ),
                            'default' => get_home_url(),
                            'type' => 'text'
                        ),
                        'comments_shortcode' => array(
                            'title' => 'Shortcode',
                            'default' => 'comments',
                            'type' => 'shortcode'
                        ),
                        'comments_function' => array(
                            'title' => 'PHP Function',
                            'default' => 'comments',
                            'type' => 'function'
                        )
                    )
                )
            )
        );

        return $settings;
    }

    /**
     * Add plugin settings menu.
     */
    public
    function settings_menu() {
        $this->settings_screen = add_options_page(
            __( 'Silicon Counters', 'silicon-counters' ),
            __( 'Silicon Counters', 'silicon-counters' ),
            'manage_options',
            'silicon-counters',
            array( $this, 'settings_page' )
        );
    }

    /**
     * Plugin settings page.
     *
     * @return string
     */
    public
    function settings_page() {
        $screen = get_current_screen();



        // Load the plugin options.
        $this->plugin_settings = get_option( 'siliconcounters_settings' );
        $this->plugin_design = get_option( 'siliconcounters_design' );

        // Create tabs current class.
        $current_tab = '';
        if ( isset( $_GET[ 'tab' ] ) ) {
            $current_tab = $_GET[ 'tab' ];
        } else {
            $current_tab = 'settings';
        }
        // Reset transients when save settings page.
        if ( isset( $_GET[ 'settings-updated' ] ) ) {
            if ( true == $_GET[ 'settings-updated' ] ) {
                // Set transients.
                Silicon_Counters_Generator::reset_count();
                // Set the icons order.
                $icons = self::get_current_icons();
                $design = get_option( 'siliconcounters_settings', array() );
                $design[ 'icons' ] = implode( ',', $icons );
                update_option( 'siliconcounters_settings', $design );
            }
        }
        include 'html-settings-page.php';
    }

    /**
     * Plugin settings form fields.
     */
    public
    function plugin_settings() {

        // Process the settings.
        foreach ( self::get_plugin_options() as $settings_id => $sections ) {

            // Create the sections.
            foreach ( $sections as $section_id => $section ) {
                add_settings_section(
                    $section_id,
                    false,
                    array( $this, 'title_element_callback' ),
                    $settings_id
                );

                // Create the fields.
                foreach ( $section[ 'fields' ] as $field_id => $field ) {
                    switch ( $field[ 'type' ] ) {
                        case 'text':
                            add_settings_field(
                                $field_id,
                                $field[ 'title' ],
                                array( $this, 'text_element_callback' ),
                                $settings_id,
                                $section_id,
                                array(
                                    'tab' => $settings_id,
                                    'id' => $field_id,
                                    'class' => 'regular-text',
                                    'default' => isset( $field[ 'default' ] ) ? $field[ 'default' ] : '',
                                    'description' => isset( $field[ 'description' ] ) ? $field[ 'description' ] : ''
                                )
                            );
                            break;
                        case 'shortcode':
                            add_settings_field(
                                $field_id,
                                $field[ 'title' ],
                                array( $this, 'shortcode_element_callback' ),
                                $settings_id,
                                $section_id,
                                array(
                                    'tab' => $settings_id,
                                    'id' => $field_id,
                                    'class' => 'regular-text',
                                    'shortcode' => $field[ 'default' ],
                                )
                            );
                            break;
                        case 'function':
                            add_settings_field(
                                $field_id,
                                $field[ 'title' ],
                                array( $this, 'function_element_callback' ),
                                $settings_id,
                                $section_id,
                                array(
                                    'tab' => $settings_id,
                                    'id' => $field_id,
                                    'class' => 'regular-text',
                                    'function' => $field[ 'default' ],
                                )
                            );
                            break;
                        case 'icon':
                            add_settings_field(
                                $field_id,
                                $field[ 'title' ],
                                array( $this, 'icon_element_callback' ),
                                $settings_id,
                                $section_id,
                                array(
                                    'tab' => $settings_id,
                                    'id' => $field_id,
                                    'class' => 'regular-text',
                                    'icon' => $field[ 'default' ],
                                    'description' => isset( $field[ 'description' ] ) ? $field[ 'description' ] : ''
                                )
                            );
                            break;
                        case 'checkbox':
                            add_settings_field(
                                $field_id,
                                $field[ 'title' ],
                                array( $this, 'checkbox_element_callback' ),
                                $settings_id,
                                $section_id,
                                array(
                                    'tab' => $settings_id,
                                    'id' => $field_id,
                                    'description' => isset( $field[ 'description' ] ) ? $field[ 'description' ] : ''
                                )
                            );
                            break;
                        case 'post_type':
                            add_settings_field(
                                $field_id,
                                $field[ 'title' ],
                                array( $this, 'post_type_element_callback' ),
                                $settings_id,
                                $section_id,
                                array(
                                    'tab' => $settings_id,
                                    'id' => $field_id,
                                    'description' => isset( $field[ 'description' ] ) ? $field[ 'description' ] : ''
                                )
                            );
                            break;
                        case 'user_role':
                            add_settings_field(
                                $field_id,
                                $field[ 'title' ],
                                array( $this, 'user_role_element_callback' ),
                                $settings_id,
                                $section_id,
                                array(
                                    'tab' => $settings_id,
                                    'id' => $field_id,
                                    'description' => isset( $field[ 'description' ] ) ? $field[ 'description' ] : ''
                                )
                            );
                            break;
                        case 'layout':
                            add_settings_field(
                                $field_id,
                                $field[ 'title' ],
                                array( $this, 'layout_element_callback' ),
                                $settings_id,
                                $section_id,
                                array(
                                    'tab' => $settings_id,
                                    'id' => $field_id,
                                    'description' => isset( $field[ 'description' ] ) ? $field[ 'description' ] : '',
                                    'options' => $field[ 'options' ]
                                )
                            );
                            break;
                        case 'icons_order':
                            add_settings_field(
                                $field_id,
                                $field[ 'title' ],
                                array( $this, 'icons_order_element_callback' ),
                                $settings_id,
                                $section_id,
                                array(
                                    'tab' => $settings_id,
                                    'id' => $field_id,
                                    'description' => isset( $field[ 'description' ] ) ? $field[ 'description' ] : ''
                                )
                            );
                            break;
                        case 'color':
                            add_settings_field(
                                $field_id,
                                $field[ 'title' ],
                                array( $this, 'color_element_callback' ),
                                $settings_id,
                                $section_id,
                                array(
                                    'tab' => $settings_id,
                                    'id' => $field_id,
                                    'description' => isset( $field[ 'description' ] ) ? $field[ 'description' ] : '',
                                    'color' => $field[ 'default' ],
                                    'class' => 'regular-text',
                                )
                            );
                            break;

                        default:
                            break;
                    }
                }
            }

            // Register the setting.
            register_setting( $settings_id, $settings_id, array( $this, 'validate_options' ) );
        }
    }

    /**
     * Get option value.
     */
    protected
    function get_option_value( $id, $default = '' ) {
        $options = $this->plugin_settings;

        return ( isset( $options[ $id ] ) ) ? $options[ $id ] : $default;
    }

    /**
     * Title element callback.
     */
    public
    function title_element_callback( $args ) {
        echo!empty( $args[ 'id' ] ) ? '<div id="section-' . esc_attr( $args[ 'id' ] ) . '"></div>': '';
    }


    /**
     * Shortcode element callback.
     */
    public
    function shortcode_element_callback( $args ) {
        $shortcode = $args[ 'shortcode' ];
        $tab = $args[ 'tab' ];
        $id = $args[ 'id' ];
        $current = $this->get_option_value( $id);
        $class = isset( $args[ 'class' ] ) ? $args[ 'class' ] : 'small-text';
        $html = sprintf( '<p id="%1$s" name="%2$s[%1$s]" value="%3$s" class="%4$s" ><code>[silicon_counters code="%5$s"]</code></p>', $id, $tab, $current, $class, $shortcode );
        echo $html;
    }


    /**
     * Function element callback.
     */
    public
    function function_element_callback( $args ) {
        $function = $args[ 'function' ];
        $tab = $args[ 'tab' ];
        $id = $args[ 'id' ];
        $current = $this->get_option_value( $id);
        $class = isset( $args[ 'class' ] ) ? $args[ 'class' ] : 'small-text';
        $html = sprintf( '<p id="%1$s" name="%2$s[%1$s]" value="%3$s" class="%4$s" ><code>&lt;?php echo silicon_counters( "%5$s" ); ?&gt;</code></p>', $id, $tab, $current, $class, $function );
        echo $html;
    }


    /**
     * Icon element callback.
     */
    public
    function icon_element_callback( $args ) {
        $tab = $args[ 'tab' ];
        $id = $args[ 'id' ];
        $icon = $args[ 'icon' ];
        $class = isset( $args[ 'class' ] ) ? $args[ 'class' ] : 'small-text';
        $default = isset( $args[ 'icon' ] ) ? $args[ 'icon' ] : '';
        $current = $this->get_option_value( $id, $default );
        $current == '' ? $current = $default : '';
        $html = sprintf( '<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="%4$s" />', $id, $tab, $current, $class );

        // Displays option description.
        if ( isset( $args[ 'description' ] ) ) {
            $html .= sprintf( '<p class="description">%s</p>', $args[ 'description' ] );
        }

        echo $html;
    }


    /**
     * Text element callback.
     */
    public
    function text_element_callback( $args ) {
        $tab = $args[ 'tab' ];
        $id = $args[ 'id' ];
        $class = isset( $args[ 'class' ] ) ? $args[ 'class' ] : 'small-text';
        $default = isset( $args[ 'default' ] ) || empty( $args[ 'default' ] ) ? $args[ 'default' ] : '';
        $current = $this->get_option_value( $id, $default ) != '' ? $this->get_option_value( $id, $default ) : $default;
        $html = sprintf( '<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="%4$s" />', $id, $tab, $current, $class );

        // Displays option description.
        if ( isset( $args[ 'description' ] ) ) {
            $html .= sprintf( '<p class="description">%s</p>', $args[ 'description' ] );
        }

        echo $html;
    }

    /**
     * Checkbox field callback.
     */
    public
    function checkbox_element_callback( $args ) {
        $tab = $args[ 'tab' ];
        $id = $args[ 'id' ];
        $default = isset( $args[ 'default' ] ) ? $args[ 'default' ] : '';
        $current = $this->get_option_value( $id, $default );
        $html = sprintf( '<input type="checkbox" id="%1$s" name="%2$s[%1$s]" value="1"%3$s />', $id, $tab, checked( 1, $current, false ) );

        // Displays option description.
        if ( isset( $args[ 'description' ] ) ) {
            $html .= sprintf( '<p class="description">%s</p>', $args[ 'description' ] );
        }

        echo $html;
    }

    /**
     * Post Type element callback.
     */
    public
    function post_type_element_callback( $args ) {
        $tab = $args[ 'tab' ];
        $id = $args[ 'id' ];
        $default = isset( $args[ 'default' ] ) ? $args[ 'default' ] : 'post';
        $current = $this->get_option_value( $id, $default );
        $html = '';

        $html = sprintf( '<select id="%1$s" name="%2$s[%1$s]">', $id, $tab );
        foreach ( get_post_types( array( 'public' => true ), 'objects' ) as $key => $value ) {
            $html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $current, $key, false ), $value->label );
        }
        $html .= '</select>';

        // Displays option description.
        if ( isset( $args[ 'description' ] ) ) {
            $html .= sprintf( '<p class="description">%s</p>', $args[ 'description' ] );
        }

        echo $html;
    }

    /**
     * User Role element callback.
     */
    public
    function user_role_element_callback( $args ) {
        global $wp_roles;

        $tab = $args[ 'tab' ];
        $id = $args[ 'id' ];
        $default = isset( $args[ 'default' ] ) ? $args[ 'default' ] : 'subscriber';
        $current = $this->get_option_value( $id, $default );
        $html = '';

        $html = sprintf( '<select id="%1$s" name="%2$s[%1$s]">', $id, $tab );
        foreach ( $wp_roles->get_names() as $key => $value ) {
            $html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $current, $key, false ), $value );
        }
        $html .= sprintf( '<option value="%s"%s>%s</option>', 'all', selected( $current, 'all', false ), __( 'All Roles', 'silicon-counters' ) );
        $html .= '</select>';

        // Displays option description.
        if ( isset( $args[ 'description' ] ) ) {
            $html .= sprintf( '<p class="description">%s</p>', $args[ 'description' ] );
        }

        echo $html;
    }


    /**
     * Layout element callback.
     */
    public
    function layout_element_callback( $args ) {
        $tab = $args[ 'tab' ];
        $id = $args[ 'id' ];
        $default = isset( $args[ 'default' ] ) ? $args[ 'default' ] : 0;
        $current = $this->get_option_value( $id, $default );
        $html = sprintf( '<select name="%1$s[%2$s]">', $tab, $id );


        foreach ( $args[ 'options' ] as $option ) {
            $html .= sprintf( '<option  value="%1$s"%2$s>%1$s</option>', $option, selected( $current, $option, false ) );
        }
        $html .= '</select>';

        // Displays option description.
        if ( isset( $args[ 'description' ] ) ) {
            $html .= sprintf( '<p class="description">%s</p>', $args[ 'description' ] );
        }

        echo $html;
    }




    /**
     * Icons order element callback.
     *
     * @param array $args Field arguments.
     */
    public
    function icons_order_element_callback( $args ) {

        // Reset transients when save settings page.
        if ( isset( $_GET[ 'settings-updated' ] ) ) {
            if ( true == $_GET[ 'settings-updated' ] ) {
                // Set transients.
                Silicon_Counters_Generator::reset_count();
                // Set the icons order.
                $icons = self::get_current_icons();
                $design = get_option( 'siliconcounters_settings', array() );
                $design[ 'icons' ] = implode( ',', $icons );
                update_option( 'siliconcounters_settings', $design );
            }
        }
        $tab = $args[ 'tab' ];
        $id = $args[ 'id' ];
        $current = $this->get_option_value( $id );
        $html = '';

        $html .= '<div class="silicon-counters-icons-order">';
        $html .= sprintf( '<input type="hidden" id="%1$s" name="%2$s[%1$s]" value="%3$s" />', $id, $tab, $current );
        foreach ( explode( ',', $current ) as $icon ) {
            $html .= '<div class="social-icon" data-icon="' . $icon . '">' . $this->get_icon_name_i18n( $icon ) . '</div>';
        }
        $html .= '</div>';

        // Displays option description.
        if ( isset( $args[ 'description' ] ) ) {
            $html .= sprintf( '<p class="description">%s</p>', $args[ 'description' ] );
        }

        echo $html;
    }

    /**
     * Color element callback.
     */
    public
    function color_element_callback( $args ) {
        $tab = $args[ 'tab' ];
        $id = $args[ 'id' ];
        $default = isset( $args[ 'color' ] ) ? $args[ 'color' ] : '';
        $current = $this->get_option_value( $id, $default );
        $current == '' ? $current = $default : '';
        $class = isset( $args[ 'class' ] ) ? $args[ 'class' ] : 'small-text';

        $html = sprintf( '<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="silicon-counters-color-field %4$s" />', $id, $tab, $current, $class );


        // Displays option description.
        if ( isset( $args[ 'description' ] ) ) {
            $html .= sprintf( '<p class="description">%s</p>', $args[ 'description' ] );
        }

        echo $html;
    }

    /**
     * Valid options.
     */
    public
    function validate_options( $input ) {
        $output = array();

        foreach ( $input as $key => $value ) {
            if ( isset( $input[ $key ] ) ) {
                $output[ $key ] = sanitize_text_field( $input[ $key ] );
            }
        }

        return $output;
    }

    /**
     * Register admin styles and scripts.
     */
    public
    function styles_and_scripts() {
        $screen = get_current_screen();

        if ( $this->settings_screen && $screen->id === $this->settings_screen ) {
            wp_enqueue_style( 'font-awesome', plugins_url( 'assets/font-awesome/css/font-awesome.min.css',  __FILE__  ), array(), Silicon_Counters::VERSION, 'all' );
            wp_enqueue_style( 'silicon-counters', plugins_url( 'assets/css/silicon-counters.css', __FILE__  ), array(), Silicon_Counters::VERSION, 'all' );
            wp_enqueue_script( 'wp-color-picker' );
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'jquery-ui-sortable' );
            wp_enqueue_script( 'silicon-counters-admin', plugins_url( 'assets/js/admin.js',  __FILE__  ), array( 'jquery', 'wp-color-picker' ), Silicon_Counters::VERSION, true );
        }
    }

    /**
     * Adds custom settings url in plugins page.
     */
    public
    function action_links( $links ) {
        $settings = array(
            'settings' => sprintf(
                '<a href="%s">%s</a>',
                admin_url( 'options-general.php?page=silicon-counters' ),
                __( 'Settings', 'silicon-counters' )
            )
        );

        return array_merge( $settings, $links );
    }

    /**
     * Generate a system report file.
     */
    public
    function report_file() {
        if ( !current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( !isset( $_GET[ 'page' ] ) || !isset( $_GET[ 'tab' ] ) || !isset( $_GET[ 'debug_file' ] ) ) {
            return;
        }

        @ob_clean();

        $debug = array();
        $settings = get_option( 'siliconcounters_settings' );
        $cache = get_option( Silicon_Counters_Generator::$cache );
        $content = '';
        $counters = apply_filters( 'silicon_counters_counters_test', array(
            'Silicon_Counters_Facebook_Counter',
            'Silicon_Counters_GitHub_Counter',
            'Silicon_Counters_GooglePlus_Counter',
            'Silicon_Counters_Instagram_Counter',
            'Silicon_Counters_Pinterest_Counter',
            'Silicon_Counters_SoundCloud_Counter',
            'Silicon_Counters_Steam_Counter',
            'Silicon_Counters_Twitch_Counter',
            'Silicon_Counters_Twitter_Counter',
            'Silicon_Counters_Vimeo_Counter',
            'Silicon_Counters_YouTube_Counter',
        ) );

        foreach ( $counters as $counter ) {
            $_counter = new $counter();

            if ( $_counter->is_available( $settings ) ) {
                $_counter->get_total( $settings, $cache );
                $debug[ $_counter->id ] = $_counter->debug();
            }
        }

        // Set the content.
        $content .= '# ' . __( 'General Info', 'silicon-counters' ) . ' #' . PHP_EOL . PHP_EOL;
        $content .= __( 'Silicon Counters Version', 'silicon-counters' ) . ': ' . Silicon_Counters::VERSION . PHP_EOL;
        $content .= __( 'WordPress Version', 'silicon-counters' ) . ': ' . esc_attr( get_bloginfo( 'version' ) ) . PHP_EOL;
        $content .= __( 'WP Multisite Enabled', 'silicon-counters' ) . ': ' . ( ( is_multisite() ) ? __( 'Yes', 'silicon-counters' ) : __( 'No', 'silicon-counters' ) ) . PHP_EOL;
        $content .= __( 'Web Server Info', 'silicon-counters' ) . ': ' . esc_html( $_SERVER[ 'SERVER_SOFTWARE' ] ) . PHP_EOL;
        $content .= __( 'PHP Version', 'silicon-counters' ) . ': ' . ( function_exists( 'phpversion' ) ? esc_html( phpversion() ) : '' ) . PHP_EOL;
        $content .= 'fsockopen: ' . ( function_exists( 'fsockopen' ) ? __( 'Yes', 'silicon-counters' ) : __( 'No', 'silicon-counters' ) ) . PHP_EOL;
        $content .= 'cURL: ' . ( function_exists( 'curl_init' ) ? __( 'Yes', 'silicon-counters' ) : __( 'No', 'silicon-counters' ) ) . PHP_EOL . PHP_EOL;
        $content .= '# ' . __( 'Social Connections', 'silicon-counters' ) . ' #';
        $content .= PHP_EOL . PHP_EOL;

        if ( !empty( $debug ) ) {
            foreach ( $debug as $key => $value ) {
                $content .= '### ' . strtoupper( esc_attr( $key ) ) . ' ###' . PHP_EOL;
                $content .= print_r( $value, true );
                $content .= PHP_EOL . PHP_EOL;
            }
        } else {
            $content .= __( 'You do not have any counter that needs to connect remotely currently active', 'silicon-counters' );
        }

        header( 'Cache-Control: public' );
        header( 'Content-Description: File Transfer' );
        header( 'Content-Disposition: attachment; filename=silicon-counters-debug-' . date( 'y-m-d-H-i' ) . '.txt' );
        header( 'Content-Type: text/plain' );
        header( 'Content-Transfer-Encoding: binary' );

        echo $content;
        exit;
    }

    /**
     * Maybe install.
     */
    public static
    function maybe_install() {
        $version = get_option( 'siliconcounters_version', '0' );

        if ( version_compare( $version, Silicon_Counters::VERSION, '<' ) ) {

            // Install options and updated old versions for 3.0.0.
            if ( version_compare( $version, '3.0.0', '<' ) ) {
                foreach ( self::get_plugin_options() as $settings_id => $sections ) {
                    $saved = get_option( $settings_id, array() );

                    foreach ( $sections as $section_id => $section ) {
                        foreach ( $section[ 'fields' ] as $field_id => $field ) {
                            $default = isset( $field[ 'default' ] ) ? $field[ 'default' ] : '';

                            if ( isset( $saved[ $field_id ] ) || '' === $default ) {
                                continue;
                            }

                            $saved[ $field_id ] = $default;
                        }
                    }

                    update_option( $settings_id, $saved );
                }

                // Set the icons order.
                $icons = self::get_current_icons();
                $design = get_option( 'siliconcounters_settings', array() );
                $design[ 'icons' ] = implode( ',', $icons );
                update_option( 'siliconcounters_settings', $design );
            }

            // Save plugin version.
            update_option( 'siliconcounters_version', Silicon_Counters::VERSION );

            // Reset the counters.
            Silicon_Counters_Generator::reset_count();
        }
    }

    /**
     * Get current icons.
     *
     * @return array
     */
    protected static
    function get_current_icons() {
        $settings = get_option( 'siliconcounters_settings', array() );
        $current = isset( $settings[ 'icons' ] ) ? explode( ',', $settings[ 'icons' ] ) : array();
        $icons = array();

        if ( function_exists( 'preg_filter' ) ) {
            $saved = array_values( preg_filter( '/^(.*)_active/', '$1', array_keys( $settings ) ) );
        } else {
            $saved = array_values( array_diff( preg_replace( '/^(.*)_active/', '$1', array_keys( $settings ) ), array_keys( $settings ) ) );
        }

        $icons = array_unique( array_filter( array_merge( $current, $saved ) ) );

        // Exclude extra values.
        $diff = array_diff( $current, $saved );
        foreach ( $diff as $key => $value ) {
            unset( $icons[ $key ] );
        }

        return $icons;
    }

    /**
     * Get i18n counters.
     *
     * @return array
     */
    public
    function get_i18n_counters() {
        return apply_filters( 'Silicon_Counters_icon_name_i18n', array(
            'comments' => __( 'Comments', 'silicon-counters' ),
            'facebook' => __( 'Facebook', 'silicon-counters' ),
            'github' => __( 'GitHub', 'silicon-counters' ),
            'googleplus' => __( 'Google+', 'silicon-counters' ),
            'instagram' => __( 'Instagram', 'silicon-counters' ),
            'pinterest' => __( 'Pinterest', 'silicon-counters' ),
            'posts' => __( 'Posts', 'silicon-counters' ),
            'soundcloud' => __( 'SoundCloud', 'silicon-counters' ),
            'steam' => __( 'Steam', 'silicon-counters' ),
            'twitch' => __( 'Twitch', 'silicon-counters' ),
            'twitter' => __( 'Twitter', 'silicon-counters' ),
            'users' => __( 'Users', 'silicon-counters' ),
            'vimeo' => __( 'Vimeo', 'silicon-counters' ),
            'youtube' => __( 'YouTube', 'silicon-counters' ),
        ) );
    }

    /**
     * Get icons names.
     *
     * @param  string $slug
     *
     * @return string
     */
    protected
    function get_icon_name_i18n( $slug ) {
        $names = $this->get_i18n_counters();

        if ( !isset( $names[ $slug ] ) ) {
            return $slug;
        }

        return $names[ $slug ];
    }
}

new Silicon_Counters_Admin;