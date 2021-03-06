<?php

class Zume_Training_Course {

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()

    public function __construct() {
        add_action( "template_redirect", [ $this, 'redirect' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ], 1000 );
    }

    public function redirect() {
        $url = dt_get_url_path();

        if ( strpos( $url, "course" ) !== false ){
            $plugin_dir = dirname( __FILE__ );
            $path = $plugin_dir . '/template-course.php';
            include( $path );
            die();
        }

    }

    public function scripts() {
        $url = dt_get_url_path();
        if ( strpos( $url, 'course' ) !== false ) {
            wp_enqueue_style( 'course-css', plugin_dir_url( __FILE__ ) . '/course.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'course.css' ) );

            wp_enqueue_script( 'zume-training-course', plugin_dir_url( __FILE__ ) . '/course.js', [
                'jquery',
                'jquery-ui',
                'lodash',
                'amcharts-core',
                'amcharts-charts',
                'amcharts-animated',
                'moment'
            ], filemtime( plugin_dir_path( __FILE__ ) . '/course.js' ), true );
            wp_localize_script(
                'zume-training-course', 'wpApiFrontpage', array(
                    'root'                  => esc_url_raw( rest_url() ),
                    'site_url'              => get_site_url(),
                    'nonce'                 => wp_create_nonce( 'wp_rest' ),
                    'current_user_login'    => wp_get_current_user()->user_login,
                    'current_user_id'       => get_current_user_id(),
                    'template_dir'          => get_template_directory_uri(),
                    'translations'          => [],
                    'data'                  => [],
                )
            );
        }
    }

    public function front_page( $page ){
        return site_url( '/dashboard/' );
    }
}
Zume_Training_Course::instance();
