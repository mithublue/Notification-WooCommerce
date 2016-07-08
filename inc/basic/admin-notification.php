<?php

class WCN_Admin_notification_basic {


    public static $initial_notification_data = array(
        'user_activity' => array(
            'new_notification_count' => 0,
            'new_notifications' => array(),
            'notification_strings' => array()
        )

    ); // an array transiend data will be store here

    public static $admin_notification;

    public static function init() {

    }
    /**
     * Set a notification
     *
     * arg $type type of notification ex: user_notification
     */
    public static function set_notification( $product_id, $user_id, $action_type, $responce){
        $admin_notification = get_transient( 'wcn_admin_notification' );
        if( !is_array( $admin_notification ) ) $admin_notification = array() ; '';

        if( $action_type == 'set' ) {
            $admin_notification[$product_id]['product_title'] = get_the_title( $product_id );
            $admin_notification[$product_id]['edit_link'] = get_edit_post_link( $product_id );
            $admin_notification[$product_id]['users'][] = $user_id;
        } elseif( $action_type == 'cancel' ) {
            unset( $admin_notification[$product_id]['users'][ array_search( $user_id , $admin_notification[$product_id]['users'] ) ] );
            if( empty ( $admin_notification[$product_id]['users'] ) ) {
                unset( $admin_notification[$product_id] );
            }
        }

        set_transient( 'wcn_admin_notification', $admin_notification );
    }

    /**
     * Get notification
     */
    public static function get_notification( $activity ){
        switch( $activity ) {
            case 'user_activity' :
                $admin_notification = get_transient( 'wcn_admin_notification' );
                break;
        }
        return $admin_notification;
    }

    /**
     * delete notification
     */
    public static function delete_notification( $args = array() ) {
        switch( $args['type'] ) {
            case 'user_activity' :
                $admin_notification = get_transient( 'wcn_admin_notification' );
                unset($admin_notification['user_activity']);
                if( set_transient( 'wcn_admin_notification', $admin_notification = array_merge( self::$initial_notification_data, $admin_notification ) ) ) {
                    return $admin_notification;
                };
                return false;
                break;
        }
    }


    /**
     * When plugin is activated
     */
    public static function initial_setup(){
        $admin_notification = get_transient( 'wcn_admin_notification' );

        if( !is_array( $admin_notification ) ) {
            set_transient( 'wcn_admin_notification', self::$initial_notification_data  );
        }
    }
}

new WCN_Admin_notification_basic();