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
}

WCN_Loader_Basic::init();