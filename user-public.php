<?php

class WN_User_Public {

    public function __construct(){
        add_action( 'woocommerce_single_product_summary' , array( $this, 'notification_button' ), 40 );

        //css, js
        add_action( 'wp_enqueue_scripts' , array( $this, 'wp_enqueue_scripts_styles') );

        //ajax
        add_action( 'wp_ajax_wcn_set_notification', array( $this, 'wcn_set_notification' ) );
        add_action( 'wp_ajax_nopriv_wcn_set_notification', array( $this, 'wcn_set_notification' ) );
    }

    /**
     * Add notification button
     * @param $content
     */
    function notification_button() {

        if( !is_user_logged_in() ) return;
        ?>
        <div id="wn-product-notification-app">
            <?php
            global $product;
            $options = get_option('wcn_settings');

            if( !$product->is_in_stock() && isset( $options['availablity_notification'] ) && $options['availablity_notification'] == 'true' ) {
                $is_available = $this->has_user_checked_in( get_current_user_id(), $product->id, 'availablity', 'email' );
                ?>
                <input type="button" class="wn-notification-btn" value="<?php _e( 'Notify me if available', 'wcn' ); ?>" v-if="!is_available"
                @click="set_notification('<?php echo $product->id; ?>', 'availablity', 'email', 'set')"
                />
                <input type="button" class="wn-notification-btn" value="<?php _e( 'Cancel notification for availablity', 'wcn' ); ?>" v-if="is_available"
                    @click="set_notification('<?php echo $product->id; ?>', 'availablity', 'email', 'cancel' )"
                    />
            <?php
            }

            if( !$product->sale_price && isset( $options['discount_notification'] )  && $options['discount_notification'] == 'true' ) {
                $is_discount = $this->has_user_checked_in( get_current_user_id(), $product->id, 'discount', 'email' );
                    ?>
                    <input type="button" class="wn-notification-btn" value="<?php _e( 'Notify me if there is a discount', 'wcn' ); ?>" v-if="!is_discount"
                        @click="set_notification('<?php echo $product->id; ?>', 'discount', 'email', 'set' )"
                        />

                    <input type="button" class="wn-notification-btn" value="<?php _e( 'Cancel notification for discount', 'wcn' ); ?>" v-if="is_discount"
                        @click="set_notification('<?php echo $product->id; ?>', 'discount', 'email', 'cancel' )"
                        />
                    <?php
            }
            ?>
            <script>
                var is_available = '<?php echo isset( $is_available ) ? $is_available:''; ?>';
                var is_discount = '<?php echo isset( $is_discount ) ? $is_discount : ''; ?>';
            </script>
        </div>
    <?php
    }

    public static function init(){
        new WN_User_Public();
    }


    /**
     * Check if the user has checked for notification
     */
    function has_user_checked_in( $user_id, $product_id, $notification_for, $notification_type ) {

        global $wpdb;

        if( $product_id ) {
            $count = $wpdb->get_var( $wpdb->prepare(
                "SELECT COUNT(id) FROM ".$wpdb->prefix."wcn_notification WHERE product_id = %d AND user_id = %d
         AND notification_for = %s AND notification_type = %s AND status NOT IN ( 'complete', 'unapproved' )", $product_id, $user_id, $notification_for, $notification_type
            ) );

            if( $count ) {
                return true;
            }

            return false;

        }

        return false;
    }

    /**
     * Ajax
     * set notification
     */
    function wcn_set_notification() {

        if ( !is_user_logged_in() ) return;

        if( !is_numeric( $_POST['product_id'] ) ) return;

        $product_id = $_POST['product_id'];
        $action_type = $_POST['action_type'];
        $notification_for = $_POST['notification_for'];
        $notification_type = $_POST['notification_type'];

        //for later use
        $responce = array();

        $responce['notification_for'] = $notification_for;

        if( $action_type == 'set' ) {

            if( !empty( $product_id ) ) {

                // if email added, add it to the approval array too

                $args = apply_filters( 'wcn_filter_add_notification_args', array(
                    'product_id' => $product_id,
                    'user_id' => get_current_user_id(),
                    'notification_for' => $notification_for,
                    'notification_type' => $notification_type,
                    'status' => 'approved'
                ) );

                $result = wcn_functions::add_notification_to_product( $args );

                if( $result ) {
                    $responce['result'] = 'success';
                    $responce['msg'] = __( 'Your request for notification added', 'wcn' );
                } else {
                    $responce['result'] = 'error';
                    $responce['msg'] = __( 'You already set notification for this product', 'wcn' );
                }
            }

        //if action type is cancel
        } else if ( $action_type == 'cancel' ) {

            $not_status = apply_filters( 'wcn_filters_remove_notification_args', array( 'complete' ) );

            $result = wcn_functions::remove_notification_from_product( array(
                'product_id' => $product_id,
                'notification_for' => $notification_for,
                'notification_type' => $notification_type,
                'user_id' => get_current_user_id(),
                'not_status' => $not_status
            ) );

            if( $result ) {
                $responce['result'] = 'success';
                $responce['msg'] = __( 'Notification deleted', 'wcn' );
            } else {
                $responce['result'] = 'error';
                $responce['msg'] = __( 'You did not set notification', 'wcn' );
            }
        }

        do_action( 'wcn_set_admin_notification',  $product_id, get_current_user_id(), $action_type, $responce );

        echo json_encode( $responce );
        exit;
    }


    /**
     * Scripts and styles enqueue
     */
    function wp_enqueue_scripts_styles() {
        global $post;

        if( is_single() && get_post_type($post->ID) == 'product' ){

            wp_enqueue_style( 'wcn-public-css', plugins_url( 'assets/css/public.css', __FILE__ ) );
            wp_enqueue_script( 'wcn-vue-js', plugins_url( 'assets/js/vue.js' , __FILE__ ) );
            wp_enqueue_script( 'wcn-script', plugins_url( 'assets/js/public-script.js' , __FILE__ ), array( 'jquery', 'wcn-vue-js' ) );
            wp_localize_script( 'wcn-script', 'wcn_data', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'action' => 'wcn_set_notification'
            ) );
        }
    }
}

WN_User_Public::init();
