<?php

class WCN_Loader_Basic {

    public function __construct(){
        add_filter( 'wcn_save_product_top', array( $this, 'add_post_data' ) );
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