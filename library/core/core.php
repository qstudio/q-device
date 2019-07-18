<?php

namespace q\device\core;
use q\device\core\core as core;
use q\device\core\helper as helper;

// load it up ##
#\q\device\core\core::run();

class core extends \q_device {

    public static function run()
    {

        #\add_action( 'init', array( get_class(), 'init' ) );

    }


    public static function get_defined_post_types()
    {

        // get defined post_types via filter ##
        $post_types = \apply_filters( "q/device/post_types", self::$post_types ); // unserialize ( Q_STICKY_POST_TYPE );
        
        if ( ! is_array ( $post_types ) ) { 
            
            (array) $post_types; 
        
        }

        // helper::log( $post_types );

        // check post_types defined are allow ##
        foreach ( $post_types as $post_type ) {

            if ( ! \post_type_exists( $post_type )) {

                // helper::log( 'Removing post_type: '.$post_type );

                unset( $post_types[$post_type] );

            }

        }

        // log ##
        #helper::log( $post_types );

        // kick it back ##
        return $post_types;

    }




    public static function get_sticky_posts()
    {

        // start empty ##
        $sticky_posts = false;

        global $wpdb;
        
        $row = $wpdb->get_row( $wpdb->prepare( 
            "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", 
            'sticky_posts' 
        ) );

        // Has to be get_row instead of get_var because of funkiness with 0, false, null values
        if ( is_object( $row ) ) {

            $sticky_posts = \maybe_unserialize( $row->option_value );

        }

        // caste to array ##
        if ( ! is_array( $sticky_posts ) ) {

            (array) $sticky_posts;

        }

        // remove duplicate values ##
        $sticky_posts = array_unique( $sticky_posts );

        // log ##
        // helper::log( $sticky_posts );

        // kick it back ##
        return $sticky_posts;

    }

}