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
        'availablity_notification_enable',
        __( '', 'wcn' ),
        'wcn_checkbox_field_1_render',
        'wcn_settings_page',
        'wcn_pluginPage_section'
    );
    add_settings_field(
        'availablity_notification_by',
        __( '', 'wcn' ),
        'wcn_checkbox_field_2_render',
        'wcn_settings_page',
        'wcn_pluginPage_section'
    );
    add_settings_field(
        'availablity_notification_mail_content',
        __( '', 'wcn' ),
        'wcn_checkbox_field_3_render',
        'wcn_settings_page',
        'wcn_pluginPage_section'
    );
    add_settings_field(
        'discount_notification_enable',
        __( '', 'wcn' ),
        'wcn_checkbox_field_4_render',
        'wcn_settings_page',
        'wcn_pluginPage_section'
    );
    add_settings_field(
        'discount_notification_by',
        __( '', 'wcn' ),
        'wcn_checkbox_field_5_render',
        'wcn_settings_page',
        'wcn_pluginPage_section'
    );
    add_settings_field(
        'discount_notification_mail_content',
        __( '', 'wcn' ),
        'wcn_checkbox_field_6_render',
        'wcn_settings_page',
        'wcn_pluginPage_section'
    );


}


function wcn_checkbox_field_1_render(  ) {

    $options = get_option( 'wcn_settings' );
    ?>
        <tr>
            <td><input id="availablity_notification" name="wcn_settings[availablity_notification]"
                    <?php echo isset( $options['availablity_notification'] ) && !empty($options['availablity_notification']) == 'true' ? 'checked' : ''; ?>
                       size='40' type="checkbox" value="true" /></td>
            <td><?php _e( 'Enable notification for availablity', 'wcn' ); ?></td>
        </tr>
<?php

}

function wcn_checkbox_field_2_render(){
    $options = get_option( 'wcn_settings' );
?>
    <tr>
        <td><?php _e( 'Availablity Notification by : ', 'wcn' );?></td>
        <td>
            <div><label><input type="checkbox" name="wcn_settings[availablity][notification_by][mail][enabled]" value="true"
                        <?php echo isset( $options['availablity']['notification_by']['mail']['enabled'] ) && ($options['availablity']['notification_by']['mail']['enabled']) == 'true' ? 'checked' : ''; ?>
                        /> Mail</label></div>
        </td>
    </tr>
    <?php
}
function wcn_checkbox_field_3_render(){
    $options = get_option( 'wcn_settings' );
?>
    <tr>
        <td><?php _e( 'Mail Content', 'wcn' ); ?></td>
        <td>
            <textarea name="wcn_settings[availablity][notification_by][mail][body]"  cols="100" rows="5"><?php echo isset( $options['availablity']['notification_by']['mail']['body'] ) ? $options['availablity']['notification_by']['mail']['body'] : '' ; ?></textarea>
        </td>
    </tr>
    <?php
}
function wcn_checkbox_field_4_render(){
    $options = get_option( 'wcn_settings' );
?>
    <tr>
        <td>
            <input id="discount_notification" name="wcn_settings[discount_notification]"
                <?php echo  isset($options['discount_notification']) &&  !empty($options['discount_notification']) == 'true'  ? 'checked' : ''; ?>
                   size='40' type="checkbox" value="true" /></td>
        <td><?php _e( 'Enable notification for discount', 'wcn' ); ?></td>
    </tr>
    <?php
}
function wcn_checkbox_field_5_render(){
    $options = get_option( 'wcn_settings' );
?>
    <tr>
        <td><?php _e( 'Discount Notification by : ', 'wcn' );?></td>
        <td>
            <div><label><input type="checkbox" name="wcn_settings[discount][notification_by][mail][enabled]" value="true"
                        <?php echo isset( $options['discount']['notification_by']['mail']['enabled'] ) && ($options['discount']['notification_by']['mail']['enabled']) == 'true' ? 'checked' : ''; ?>
                        /> Mail</label></div>
        </td>
    </tr>
    <?php
}
function wcn_checkbox_field_6_render(){
    $options = get_option( 'wcn_settings' );
?>
    <tr>
        <td><?php _e( 'Mail Content', 'wcn' ); ?></td>
        <td><textarea name="wcn_settings[discount][notification_by][mail][body]"  cols="100" rows="5"><?php echo isset( $options['discount']['notification_by']['mail']['body'] ) ? $options['discount']['notification_by']['mail']['body'] : '' ; ?></textarea></td>
    </tr>
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
