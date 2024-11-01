<table id="si-system-status" class="widefat" cellspacing="0">

    <thead>
        <tr>
            <th colspan="2">
                <?php _e( 'Environment', 'silicon-counters' ); ?>
            </th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td>
                <?php _e( 'Plugin Version', 'silicon-counters' ); ?>:</td>
            <td>
                <?php echo Silicon_Counters::VERSION; ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php _e( 'WordPress Version', 'silicon-counters' ); ?>:</td>
            <td>
                <?php echo esc_attr( get_bloginfo( 'version' ) ); ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php _e( 'WP Multisite Enabled', 'silicon-counters' ); ?>:</td>
            <td>
                <?php if ( is_multisite() ) echo __( 'Yes', 'silicon-counters' ); else echo __( 'No', 'silicon-counters' ); ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php _e( 'Web Server Info', 'silicon-counters' ); ?>:</td>
            <td>
                <?php echo esc_html( $_SERVER['SERVER_SOFTWARE'] ); ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php _e( 'PHP Version', 'silicon-counters' ); ?>:</td>
            <td>
                <?php if ( function_exists( 'phpversion' ) ) { echo esc_html( phpversion() ); } ?>
            </td>
        </tr>
        <tr>
            <?php
            $connection_status = 'error';
            $connection_note = __( 'Your server does not have fsockopen or cURL enabled. The scripts which communicate with the social APIs will not work. Contact your hosting provider.', 'silicon-counters' );

            if ( function_exists( 'fsockopen' ) || function_exists( 'curl_init' ) ) {
                if ( function_exists( 'fsockopen' ) && function_exists( 'curl_init' ) ) {
                    $connection_note = __( 'Your server has fsockopen and cURL enabled.', 'silicon-counters' );
                } elseif ( function_exists( 'fsockopen' ) ) {
                    $connection_note = __( 'Your server has fsockopen enabled, cURL is disabled.', 'silicon-counters' );
                } else {
                    $connection_note = __( 'Your server has cURL enabled, fsockopen is disabled.', 'silicon-counters' );
                }

                $connection_status = 'yes';
            }
            ?>
            <td>
                <?php _e( 'fsockopen/cURL', 'silicon-counters' ); ?>:</td>
            <td>
                <mark class="<?php echo $connection_status; ?>">
                    <?php echo $connection_note; ?>
                </mark>
            </td>
        </tr>
        <tr>
            <?php
            $remote_status = 'error';
            $remote_note = __( 'wp_remote_get() failed. This may not work with your server.', 'silicon-counters' );
            $response = wp_remote_get( 'https://siliconthemes.com/ip', array( 'timeout' => 60 ) );

            if ( !is_wp_error( $response ) && $response[ 'response' ][ 'code' ] >= 200 && $response[ 'response' ][ 'code' ] < 300 ) {
                $remote_status = 'yes';
                $remote_note = __( 'wp_remote_get() was successful.', 'silicon-counters' );
            } elseif ( is_wp_error( $response ) ) {
                $remote_note = __( 'wp_remote_get() failed. This plugin won\'t work with your server. Contact your hosting provider. Error:', 'silicon-counters' ) . ' ' . $response->get_error_message();
            }
            ?>
            <td>
                <?php _e( 'WP Remote Get', 'silicon-counters' ); ?>:</td>
            <td>
                <mark class="<?php echo $remote_status; ?>">
                    <?php echo $remote_note; ?>
                </mark>
            </td>
        </tr>
    </tbody>
</table>