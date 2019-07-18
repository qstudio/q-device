<?php

// namespace ##
namespace q\device\core;

class helper extends \q_device {

    /**
    * check if a file exists with environmental fallback
    * first check the active theme ( pulling info from "device-theme-switcher" ), then the plugin
    *
    * @param    $include        string      Include file with path ( from library/  ) to include. i.e. - templates/loop-nothing.php
    * @param    $return         string      return method ( echo, return, require )
    * @param    $type           string      type of return string ( url, path )
    * @param    $path           string      path prefix
    * 
    * @since 0.1
    */
    public static function get( $include = null, $return = 'echo', $type = 'url', $path = "library/" )
    {

        // nothing passed ##
        if ( is_null( $include ) ) { 

            return false;            

        }

        // nada ##
        $template = false; 
        
        if ( self::$debug ) self::log( 'include: '.$include );
        
        // perhaps this is a child theme ##
        if ( 
            defined( 'Q_CHILD_THEME' )
            && Q_CHILD_THEME
            #&& \is_child_theme() 
            && file_exists( get_stylesheet_directory().'/'.$path.$include )
        ) {

            $template = get_stylesheet_directory_uri().'/'.$path.$include; // template URL ##
            
            if ( 'path' === $type ) { 

                $template = get_stylesheet_directory().'/'.$path.$include;  // template path ##

            }

            if ( self::$debug ) self::log( 'child theme: '.$template );

        }

        // load active theme over plugin ##
        elseif ( 
            file_exists( get_template_directory().'/'.$path.$include ) 
        ) { 

            $template = get_template_directory_uri().'/'.$path.$include; // template URL ##
            
            if ( 'path' === $type ) { 

                $template = get_template_directory().'/'.$path.$include;  // template path ##

            }

            if ( self::$debug ) self::log( 'parent theme: '.$template );

        // load from Plugin ##
        } elseif ( 
            file_exists( self::get_plugin_path( $path.$include ) )
        ) {

            $template = self::get_plugin_url( $path.$include ); // plugin URL ##

            if ( 'path' === $type ) {
                
                $template = self::get_plugin_path( $path.$include ); // plugin path ##
                
            } 

            if ( self::$debug ) self::log( 'plugin: '.$template );

        }

        if ( $template ) { // continue ##

            // apply filters ##
            $template = apply_filters( 'q_locate_template', $template );

            // echo or return string ##
            if ( 'return' === $return ) {

                if ( self::$debug ) helper::log( 'returned' );

                return $template;

            } elseif ( 'require' === $return ) {

                if ( self::$debug ) helper::log( 'required' );

                return require_once( $template );

            } else {

                if ( self::$debug ) helper::log( 'echoed..' );

                echo $template;

            }

        }

        // nothing cooking ##
        return false;

    }

    /**
     * Write to WP Error Log
     *
     * @since       1.5.0
     * @return      void
     */
    public static function log( $log )
    {

        if ( true === WP_DEBUG ) {

            $trace = debug_backtrace();
            $caller = $trace[1];

            $suffix = sprintf(
                __( ' - %s%s() %s:%d', 'Q_Scrape_Wordpress' )
                ,   isset($caller['class']) ? $caller['class'].'::' : ''
                ,   $caller['function']
                ,   isset( $caller['file'] ) ? $caller['file'] : 'n'
                ,   isset( $caller['line'] ) ? $caller['line'] : 'x'
            );

            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ).$suffix );
            } else {
                error_log( $log.$suffix );
            }

        }

    }


    /**
     * Pretty print_r / var_dump
     *
     * @since       0.1
     * @param       Mixed       $var        PHP variable name to dump
     * @param       string      $title      Optional title for the dump
     * @return      String      HTML output
     */
    public static function pr( $var, $title = null )
    {

        if ( $title ) $title = '<h2>'.$title.'</h2>';
        print '<pre class="var_dump">'; echo $title; var_dump($var); print '</pre>';

    }


    /**
     * Pretty print_r / var_dump with wp_die
     *
     * @since       0.1
     * @param       Mixed       $var        PHP variable name to dump
     * @param       string      $title      Optional title for the dump
     * @return      String      HTML output
     */
    public static function pr_die( $var, $title = null )
    {

        wp_die( self::pr( $var, $title ) );

    }



}