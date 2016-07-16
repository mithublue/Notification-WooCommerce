<?php

class WCN_Loader_Basic {

    public function __construct(){
        /**
         * filter src : admin/admin-product.php
         */
        add_filter( 'wcn_save_product_top', array( $this, 'add_post_data' ) );
        /**
         * action src : user-public.php
         */
        add_action( 'wcn_set_admin_notification' , array( $this, 'wcn_set_admin_notification_runner' ), 10 , 4 );

        add_action('admin_head',function(){
            require_once dirname(__FILE__).'/admin/templates.php';
        });

        /**
         * action src : woocommerce-notification.php
         */
        add_action( 'wcn_initial_setup' , array( $this, 'wcn_initial_setup_runner' ) );
        $this->includes();
    }

    public function wcn_set_admin_notification_runner( $product_id, $user_id, $action_type, $responce ) {
        WCN_Admin_notification_basic::set_notification( $product_id, $user_id, $action_type, $responce );
    }

    public function includes() {
        require_once dirname(__FILE__).'/admin-notification.php';

        if( is_admin() ) {
            require_once dirname(__FILE__).'/admin/seller-admin-dashboard.php';
        }
    }

    public static function init() {
        new WCN_Loader_Basic();
    }

    function add_post_data( $postdata ) {
        $postdata['wcn_send_notification'] = array(
            'availablity' => array(
                'email' => 'on'
            ),
            'discount' => array(
                'email' => 'on'
            )
        );
        return $postdata;
    }

    /**
     * Run the initial setup
     */
    function wcn_initial_setup_runner() {
        $options = get_option( 'wcn_settings' );

        if( !is_array( $options ) ) {
            $options = array(
                'availablity_notification' => 'true',
                'availablity' => array(
                    'notification_by' => array(
                        'mail' => array(
                            'enabled' => 'true',
                            'body' => 'Congratulations ! The product you were waiting for is now on store.
                Check your item and grab it from here  %product_link%'
                        )
                    )
                ),
                'discount_notification' => 'true',
                'discount' => array(
                    'notification_by' => array(
                        'mail' => array(
                            'enabled' => 'true',
                            'body' => 'Congratulations ! The product you were waiting for is now in discount.
                Check your item and grab it from here %product_link%',
                        )
                    )
                )
            );
        }
        update_option( 'wcn_settings', $options );

    }
}

WCN_Loader_Basic::init();