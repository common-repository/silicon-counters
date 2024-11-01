<?php
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="wrap">
    <h1 style="margin-bottom: 10px;"><strong>SOCIAL COUNTERS SETTINGS</strong> | <small style="font-size: 14px;"><strong>Silicon Counters</strong> Plugin by <a target="_blank" href="https://siliconthemes.com/">Silicon Themes</a></small></h1>
    <hr>
    <form method="post" action="options.php">
        <?php
        submit_button();
        settings_fields( 'siliconcounters_settings' );
        do_settings_sections( 'siliconcounters_settings' );
        submit_button();
        include 'html-settings-system-status-page.php'
        ?>
    </form>
</div>