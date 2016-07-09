<?php
add_action( 'admin_menu', 'wcn_add_admin_menu' );
add_action( 'admin_init', 'wcn_settings_init' );


function wcn_add_admin_menu(  ) {

    add_menu_page( 'Notification WooCommerce', 'Notification WooCommerce', 'manage_options', 'notification_woocommerce', 'wcn_options_page' );

}


function wcn_settings_init(  ) {

    register_setting( 'wcn_settings_page', 'wcn_settings' );

    add_settings_section(
        'wcn_pluginPage_section',
        __( 'Notification Settings', 'wcn' ),
        'wcn_settings_section_callback',
        'wcn_settings_page'
    );

    add_settings_field(
        'availablity_notification',
        __( '', 'wcn' ),
        'wcn_checkbox_field_0_render',
        'wcn_settings_page',
        'wcn_pluginPage_section'
    );


}


function wcn_checkbox_field_0_render(  ) {

    $options = get_option( 'wcn_settings' );
    ?>
    <table>
        <tr>
            <td><input id="availablity_notification" name="wcn_settings[availablity_notification]"
                    <?php echo isset( $options['availablity_notification'] ) && !empty($options['availablity_notification']) == 'true' ? 'checked' : ''; ?>
                       size='40' type="checkbox" value="true" /></td>
            <td><?php _e( 'Enable notification for availablity', 'wcn' ); ?></td>
        </tr>
        <tr>
            <td>
                <input id="discount_notification" name="wcn_settings[discount_notification]"
                    <?php echo  isset($options['discount_notification']) &&  !empty($options['discount_notification']) == 'true'  ? 'checked' : ''; ?>
                       size='40' type="checkbox" value="true" /></td>
            <td><?php _e( 'Enable notification for discount', 'wcn' ); ?></td>
        </tr>
    </table>
<?php

}


function wcn_settings_section_callback() {
}


function wcn_options_page(  ) {

    ?>
    <form action='options.php' method='post'>

        <h2>Notification WooCommerce</h2>

        <?php
        settings_fields( 'wcn_settings_page' );
        do_settings_sections( 'wcn_settings_page' );
        submit_button();
        ?>

    </form>
<?php

}
