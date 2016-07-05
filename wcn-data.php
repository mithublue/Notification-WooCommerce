<?php

class WCN_data {

    /**
     * array(
     *  'availablity' => arr(
     *      'email' => array(
     *          'email_id' => array( 'approved' => true //or false
     *          )
     *      )
     *
     *  )
     *  'discount' => arr(
     *      'email' => array(
     *          'email_id' => array( 'approved' => true //or false
     *          )
     *      )
     *
     *  )
     * )
     * @var array
     */
    public static $notification_approval_data = array(
        'availablity' => array(
            'email' => array()
        ),
        'discount' => array(
            'email' => array()
        )
    );

    public static function initial_setup() {

    }
}