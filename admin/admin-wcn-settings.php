<?php

class admin_wcn_settings{

    function __construct(){
        add_action( 'admin_init', array( $this, 'build_field_settings' ) );
        add_action( 'admin_menu' , array( $this, 'add_menu_page' ) );
    }

    function add_menu_page() {
        add_menu_page ( 'WC Notification Setting', 'WC Notification Setting', 'manage_options', 'plugin', array( $this , 'build_page' ) );
    }


    /**
     * Settings page for admin
     */
    function build_page() {
        ?>
        <div>
            <h2>Notification Settings</h2>
            <form action="options.php" method="post">
                <?php settings_fields('plugin_options'); ?>
                <?php do_settings_sections('plugin'); ?>

                <?php submit_button(); ?>
            </form></div>
    <?php
    }


    function build_field_settings() {
        register_setting( 'plugin_options', 'plugin_options', array( $this, 'plugin_options_validate' ) );
        add_settings_section('plugin_main', '', array( $this, 'plugin_section_text' ), 'plugin');
        add_settings_field('plugin_text_string', 'Plugin Text Input', array( $this, 'plugin_setting_string' ), 'plugin', 'plugin_main');
    }

    function plugin_section_text() {
    }

    function plugin_setting_string() {
        $options = get_option('plugin_options');
        ?>
        <table>
            <tr>
                <td>
                    <input id="discount_notification" name="plugin_options[discount_notification]"
                        <?php echo  isset($options['discount_notification']) &&  !empty($options['discount_notification']) == 'true'  ? 'checked' : ''; ?>
                           size='40' type="checkbox" value="true" /></td>
                <td><?php _e( 'Enable notification for discount', 'wcn' ); ?></td>
            </tr>
            <tr>
                <td><input id="availablity_notification" name="plugin_options[availablity_notification]"
                        <?php echo isset( $options['availablity_notification'] ) && !empty($options['availablity_notification']) == 'true' ? 'checked' : ''; ?>
                           size='40' type="checkbox" value="true" /></td>
                <td><?php _e( 'Enable notification for availablity', 'wcn' ); ?></td>
            </tr>
        </table>
    <?php
    }


    function plugin_options_validate($input) {
        return $input;
    }


    public static function init() {
        new admin_wcn_settings();
    }
}

admin_wcn_settings::init();