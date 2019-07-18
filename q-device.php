<?php

/* 
 * Device detection
 *
 * @package         q-device
 * @author          Q Studio <social@qstudio.us>
 * @license         GPL-2.0+
 * @link            http://qstudio.us/
 * @copyright       2019 Q Studio
 *
 * @wordpress-plugin
 * Plugin Name:     Q Device
 * Plugin URI:      https://www.qstudio.us
 * Description:     Device detection and body class declarations
 * Version:         1.0.0
 * Author:          Q Studio
 * Author URI:      https://www.qstudio.us
 * License:         GPL
 * Copyright:       Q Studio
 * Class:           q_device
 * Text Domain:     q-device
 * Domain Path:     /theme/language
 * GitHub Plugin URI: qstudio/q-device
 * 
*/

defined( 'ABSPATH' ) OR exit;

if ( ! class_exists( 'q_device' ) ) {
    
    // instatiate plugin via WP plugins_loaded - init is too late for CPT ##
    add_action( 'plugins_loaded', array ( 'q_device', 'get_instance' ), 5 );
    
    class q_device {
                
        // Refers to a single instance of this class. ##
        private static $instance = null;

        // Plugin Settings
        const version = '1.0.0';
        static $device = ''; // start false ##
        static $debug = false;
        // static $load_count = 0;
        const text_domain = 'q-device'; // for translation ##

        // plugin properties ##
        public static $properties = false;

        /**
         * Creates or returns an instance of this class.
         *
         * @return  Foo     A single instance of this class.
         */
        public static function get_instance() 
        {

            if ( 
                null == self::$instance 
            ) {

                self::$instance = new self;

            }

            return self::$instance;

        }
        
        
        /**
         * Instatiate Class
         * 
         * @since       0.2
         * @return      void
         */
        private function __construct() 
        {
            
            // activation ##
            register_activation_hook( __FILE__, array ( $this, 'register_activation_hook' ) );

            // deactvation ##
            register_deactivation_hook( __FILE__, array ( $this, 'register_deactivation_hook' ) );

            // set text domain ##
            add_action( 'init', array( $this, 'load_plugin_textdomain' ), 1 );
            
            // load properties ##
            #self::load_properties();

            // define debug ##
            self::$debug = 
                ( true === self::$debug ) ? 
                true : 
                class_exists( 'Q' ) ? 
                    \Q::$debug : // use Q debug setting, as plugin property not active ##
                    false ;

            // load libraries ##
            // self::load_libraries();

        }


        // the form for sites have to be 1-column-layout
        public function register_activation_hook() {

            #add_option( 'q_device_configured' );

            // flush rewrites ##
            #global $wp_rewrite;
            #$wp_rewrite->flush_rules();

        }


        public function register_deactivation_hook() {

            #delete_option( 'q_device_configured' );

        }


        
        /**
         * Load Text Domain for translations
         * 
         * @since       1.7.0
         * 
         */
        public function load_plugin_textdomain() 
        {
            
            // set text-domain ##
            $domain = self::text_domain;
            
            // The "plugin_locale" filter is also used in load_plugin_textdomain()
            $locale = apply_filters('plugin_locale', get_locale(), $domain);

            // try from global WP location first ##
            load_textdomain( $domain, WP_LANG_DIR.'/plugins/'.$domain.'-'.$locale.'.mo' );
            
            // try from plugin last ##
            load_plugin_textdomain( $domain, FALSE, plugin_dir_path( __FILE__ ).'library/language/' );
            
        }
        
        
        
        /**
         * Get Plugin URL
         * 
         * @since       0.1
         * @param       string      $path   Path to plugin directory
         * @return      string      Absoulte URL to plugin directory
         */
        public static function get_plugin_url( $path = '' ) 
        {

            return plugins_url( $path, __FILE__ );

        }
        
        
        /**
         * Get Plugin Path
         * 
         * @since       0.1
         * @param       string      $path   Path to plugin directory
         * @return      string      Absoulte URL to plugin directory
         */
        public static function get_plugin_path( $path = '' ) 
        {

            return plugin_dir_path( __FILE__ ).$path;

        }
        


        /**
        * Load Libraries
        *
        * @since        2.0
        */
		private static function load_libraries()
        {

            // methods ##
            require_once self::get_plugin_path( 'library/core/helper.php' );
            require_once self::get_plugin_path( 'library/core/core.php' );

            // backend ##
            // require_once self::get_plugin_path( 'library/admin/ajax.php' );
            
            // widgets for template ##
            #require_once self::get_plugin_path( 'library/theme/widget/search.php' );

            // frontend ##
            // require_once self::get_plugin_path( 'library/theme/theme.php' );

        }

    }

}