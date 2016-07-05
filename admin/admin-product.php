<?php

/**
 * Add an admin section to each product admin page
 * to manage the notification by the admin/seller
 * Class wcn_admin_product
 */

class WCN_Admin_Product{

    public function __construct(){
        add_action('save_post', array( $this, 'save_product_data' ) );
    }


    function save_product_data( $post_id ) {

        if ( get_post_type( $post_id ) != 'product' ) return;
        if( !current_user_can( 'edit_post' ) ) return;

        do_action( 'wcn_save_product_top' );
        $_POST = apply_filters( 'wcn_save_product_top', $_POST );
        $prod = new WC_Product( $post_id );
        $successful_row_id = array();

            //if admin allows to send notification (checking the send notification checkbox)
            if ( isset( $_POST['wcn_send_notification'] ) ) {

                $product_notification = wcn_functions::get_product_notification_data( $post_id, true );

                // if admin allow sending notification by checking the checkbox
                if( $prod->is_in_stock() ) {
                    if( isset( $_POST['wcn_send_notification']['availablity']['email'] ) ) {

                        $result_ids = wcn_functions::send_notification_to_customers(
                            array(
                                'notification_for' => 'availablity',
                                'notification_type' => 'email',
                                'data' => $product_notification,
                                'product_title' => get_the_title( $post_id ),
                              'product_link' => get_permalink( $post_id )
                            )
                        );
                        $successful_row_id = array_merge( $successful_row_id, $result_ids );
                    }
                }

                if( $prod->sale_price ) {

                    if( isset( $_POST['wcn_send_notification']['discount']['email'] ) ) {

                        $result_ids = wcn_functions::send_notification_to_customers(
                            array(
                                'notification_for' => 'discount',
                                'notification_type' => 'email',
                                'data' => $product_notification,
                                'product_title' => get_the_title( $post_id ),
                                'prdouct_link' => get_permalink( $post_id )
                            )
                        );
                        $successful_row_id = array_merge( $successful_row_id, $result_ids );
                    }
                }

                if( !empty( $successful_row_id ) ) {
                    wcn_functions::change_notification_status( array(
                            'case' => array(
                                'status' => 'id'
                            ),
                            'where_field' => array(
                                'id' => $successful_row_id,
                                'status' => 'approved'
                            ),
                            'update_field' => array(
                                'status' => 'complete'
                            )
                        )
                    );
                }
            }

    }


}



/**
 * Calls the class on the post edit screen.
 */
new WCN_Admin_Product();
