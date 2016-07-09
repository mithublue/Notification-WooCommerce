<?php

class wcn_seller_admin_dashboard_basic{

    function __construct() {
        add_action( 'admin_notices', array( $this, 'seller_admin_notices' ) );
        add_action( 'wp_ajax_wcn_remove_admin_notification', array( $this, 'wcn_remove_admin_notification' ) );
    }

    /**
     * This notice to notify admin about
     * the notification created by the customers
     */
    function seller_admin_notices(){
        if( in_array( 'manage_woocommerce', get_currentuserinfo()->roles ) || in_array( 'administrator', get_currentuserinfo()->roles ) ){
            $admin_notification = WCN_Admin_notification_basic::get_notification( 'user_activity' );
            ?>
            <!-- app -->
            <div id="user-notification-app">
                <div v-if="admin_noti_length > 0">
                    <div class="notice user-notification-notice notice-warning is-dismissible">
                        <p><?php _e( 'You have some notifications waiting ! <a href="javascript:" @click="showModal = true">Take a look !</a>', 'wcn' ); ?></p>
                    </div>
                    <user-notification-modal :show.sync="showModal" :admin_notification="admin_notification"></user-notification-modal>
                </div>
            </div>
            <script>
                var admin_notification = JSON.parse('<?php echo html_entity_decode(json_encode($admin_notification)); ?>');
                var admin_noti_length = Object.keys(admin_notification).length;
            </script>
        <?php
        }
    }

    /**
     * Ajax
     * Remove admin notification
     * that has been watch
     */
    function wcn_remove_admin_notification() {
        $admin_notification = get_transient( 'wcn_admin_notification' );

        if( isset( $admin_notification[ $_POST['product_id' ] ] ) ) {
            unset( $admin_notification[ $_POST['product_id' ] ] );
        }

        set_transient( 'wcn_admin_notification', $admin_notification );

        print_r(get_transient( 'wcn_admin_notification') );

    }



    public static function init() {
        new wcn_seller_admin_dashboard_basic();
    }
}

wcn_seller_admin_dashboard_basic::init();