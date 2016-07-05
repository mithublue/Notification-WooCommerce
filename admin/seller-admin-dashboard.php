<?php

class wcn_seller_admin_dashboard{

    function __construct() {
        add_action( 'admin_notices', array( $this, 'seller_admin_notices' ) );
        add_action( 'wp_ajax_wcn_remove_notification', array( $this, 'remove_notification' ) );
    }

    /**
     * This notice to notify admin about
     * the notification created by the customers
     */
    function seller_admin_notices(){

        if( 1/*!in_array( 'manage_woocommerce', get_currentuserinfo()->roles ) || in_array( 'administrator', get_currentuserinfo()->roles )*/ ){
            $admin_notification = WCN_Admin_notification::get_notification( 'user_activity' );
            if( 1 /*isset( $admin_notification['new_notification_count'] ) && !empty( $admin_notification['new_notification_count'] )*/  ) {
                ?>
                <!-- app -->
                <div id="user-notification-app" >
                    <div v-if="admin_notification.new_notification_count != 0">
                        <div class="notice user-notification-notice notice-success is-dismissible">
                            <p><?php _e( 'You have some notifications waiting ! <a href="javascript:" @click="showModal = true">Take a look !</a>
| <a href="javascript:" class="notification-posts" data-href="'.admin_url('edit.php?post_type=wcn_notification').'">Go to Notifications</a>', 'wcn' ); ?></p>
                        </div>
                        <user-notification-modal :show.sync="showModal" :admin_notification="admin_notification"></user-notification-modal>
                    </div>
                </div>
                <script>
                    var admin_notification = JSON.parse('<?php echo json_encode($admin_notification); ?>');
                </script>
            <?php

            }
        }
    }


    public static function init() {
        new wcn_seller_admin_dashboard();
    }


    /**
     * Ajax
     * Remove notification
     */
    function remove_notification() {

        $admin_notification = WCN_Admin_notification::delete_notification( array(
            'type' => 'user_activity'
        ) );

        if( $admin_notification  ) {
            echo json_encode( $admin_notification );
        } else {
            echo $admin_notification;
        }
        exit;
    }

}

wcn_seller_admin_dashboard::init();