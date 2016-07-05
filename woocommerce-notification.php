<?php
/*
Plugin Name: Notification - WooCommerce (working)
Plugin URI:
Description:
Version: 0.1
Author: Mithu A Quayium
Author URI:
License: GPL2
*/

/**
 * Copyright (c) YEAR Mithu A Quayium (email: cemithu06@gmail.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

define( 'WCN_ROOT', plugins_url( '', __FILE__ ) );

class WCN_Notification {

    function __construct() {

        add_action('admin_head',function(){
            require_once dirname(__FILE__).'/templates.php';
        });
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts_styles' ) );
        register_activation_hook(__FILE__, array( 'WCN_Admin_notification' , 'initial_setup' ) );
        register_activation_hook(__FILE__, array( $this , 'initial_setup' ) );
        $this->includes();
    }

    /**
     * Initial setup when
     * the plugin
     * actuvated
     */
    function initial_setup() {

        //create table
        global $wpdb;
        $table_name = $wpdb->prefix . 'wcn_notification';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
          id bigint(9) NOT NULL AUTO_INCREMENT,
          product_id bigint(9) NOT NULL,
          user_id bigint(9) NOT NULL,
          notification_for varchar(55) DEFAULT '' NOT NULL,
          notification_type varchar(55) DEFAULT '' NOT NULL,
          status varchar(55) DEFAULT '' NOT NULL,
          time datetime,
          PRIMARY KEY id (id)
        ) $charset_collate;"; //NOT NULL

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    /**
     * Includes necessary files
     */
    function includes(){

        if ( is_dir( dirname(__FILE__).'/inc/pro' ) ) {
            require_once dirname(__FILE__).'/inc/pro/loader.php';
        } else {
            require_once dirname(__FILE__).'/inc/basic/loader.php';
        }

        require_once dirname(__FILE__).'/wcn-data.php';
        require_once dirname(__FILE__).'/wcn-functions.php';
        require_once dirname(__FILE__).'/admin-notification.php';
        require_once dirname(__FILE__).'/user-public.php';

        if( is_admin() ) {
            require_once dirname(__FILE__).'/admin/admin-product.php';
            require_once dirname(__FILE__).'/admin/seller-admin-dashboard.php';
            require_once dirname(__FILE__).'/admin/admin-wcn-settings.php';
        }
    }

    /**
     * Initializes the WCN_Notification() class
     *
     * Checks for an existing WCN_Notification() instance
     * and if it doesn't find one, creates it.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function init() {

        static $instance = false;

        if ( ! $instance ) {
            $instance = new WCN_Notification();
        }

        return $instance;
    }

    /**
     * Enqueuing scripts and styles
     */
    function admin_enqueue_scripts_styles( $hook ) {
        wp_enqueue_script( 'wcn-vue-js', WCN_ROOT.'/assets/js/vue.js' );
        wp_enqueue_script( 'wcn-admin-notice_script', WCN_ROOT.'/assets/js/seller-admin-dashboard.js', array( 'wcn-vue-js', 'jquery') );
        wp_enqueue_style( 'wcn-admin-css', plugins_url('assets/css/admin.css',__FILE__) );
    }

}

WCN_Notification::init();