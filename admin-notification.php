<?php

class WCN_Admin_notification {


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
    public static function set_notification( $type,  $args = array() ){

        self::$admin_notification = get_transient( 'wcn_admin_notification' );

        switch( $type ) {
            case 'user_notification' :
                $default = array(
                    'user_notification_type' => 'availablity',
                    'result' => '',
                    'action' => 'set',
                    'email' => '',
                    'notification_post_id' => ''
                );

                $args = array_merge( $default, $args );

                if( $args['result'] == 'success' ) {

                    if( $args['action'] == 'set' ) {
                        ++self::$admin_notification['user_activity']['new_notification_count'];
                        self::$admin_notification['user_activity']['new_notifications'][ $args['notification_post_id'] ][] = $args['email'];
                        self::$admin_notification['user_activity']['notification_strings'][]
                            = $args['email'].' wants to be notified for '. $args['user_notification_type'];
                    } elseif ( $args['action'] == 'cancel' ) {
                        ++self::$admin_notification['user_activity']['new_notification_count'];
                        unset( self::$admin_notification['user_activity']['new_notifications'][ $args['notification_post_id'] ][ array_search( $args['email'], self::$admin_notification['user_activity']['new_notifications'][ $args['notification_post_id'] ] ) ] );
                        self::$admin_notification['user_activity']['notification_strings'][]
                            = $args['email'].' canceled notified for '. $args['user_notification_type'];
                    }
                }

                break;
        }

        set_transient('wcn_admin_notification', self::$admin_notification );
    }

    /**
     * Get notification
     */
    public static function get_notification( $type ){

        switch( $type ) {
            case 'user_activity' :
                $admin_notification = get_transient( 'wcn_admin_notification' );
                break;
        }
        return $admin_notification[$type];
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

WCN_Admin_notification::init();