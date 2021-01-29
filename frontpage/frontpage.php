<?php

class Zume_Open_Frontpage {
    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()

    private $version = 1;
    private $context = "zume-open";
    private $namespace;

    public function __construct() {
        $this->namespace = $this->context . "/v" . intval( $this->version );
        add_filter( 'dt_front_page', [ $this, 'front_page' ] );

        add_filter( 'desktop_navbar_menu_options', [ $this, 'nav_menu' ] );
        add_filter( 'off_canvas_menu_options', [ $this, 'nav_menu' ] );

        add_action( "template_redirect", [ $this, 'my_theme_redirect' ] );

        add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ], 1000 );

    }

    public function my_theme_redirect() {
        $url = dt_get_url_path();
        if ( strpos( $url, "dashboard" ) !== false ){
            $plugin_dir = dirname( __FILE__ );
            $path = $plugin_dir . '/template-dashboard.php';
            include( $path );
            die();
        }
    }

    public function scripts() {

        wp_enqueue_style( 'dashboard-css', plugin_dir_url( __FILE__ ) . '/style.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'style.css' ) );
/*
        wp_register_script( 'amcharts-core', 'https://www.amcharts.com/lib/4/core.js', false, '4' );
        wp_register_script( 'amcharts-charts', 'https://www.amcharts.com/lib/4/charts.js', false, '4' );
        wp_register_script( 'amcharts-animated', 'https://www.amcharts.com/lib/4/themes/animated.js', false, '4' );
        wp_enqueue_script( 'zume-open', plugin_dir_url( __FILE__ ) . '/dashboard.js', [
            'jquery',
            'jquery-ui',
            'lodash',
            'amcharts-core',
            'amcharts-charts',
            'amcharts-animated',
            'moment'
        ], filemtime( plugin_dir_path( __FILE__ ) . '/dashboard.js' ), true );
        wp_localize_script(
            'zume-open', 'wpApiDashboard', array(
                'root'                  => esc_url_raw( rest_url() ),
                'site_url'              => get_site_url(),
                'nonce'                 => wp_create_nonce( 'wp_rest' ),
                'current_user_login'    => wp_get_current_user()->user_login,
                'current_user_id'       => get_current_user_id(),
                'template_dir'          => get_template_directory_uri(),
                'translations'          => Zume_Open_Endpoints::instance()->translations(),
                'data'                  => Zume_Open_Endpoints::instance()->get_data(),
                'workload_status'       => get_user_option( 'workload_status', get_current_user_id() ),
                'workload_status_options' => dt_get_site_custom_lists()["user_workload_status"] ?? []
            )
        );
*/
    }

    public function logo(){
        $url = trailingslashit( plugin_dir_url(__FILE__) ) . 'zume-training-logo.svg';
        return $url;
    }

    public function front_page( $page ){
        return site_url( '/dashboard/' );
    }

    public function nav_menu(){
        ?>
        <li><a href="<?php echo esc_url( site_url( '/dashboard/' ) ); ?>"><?php esc_html_e( "Dashboard", "zume" ); ?></a></li>
        <?php
    }
}
Zume_Open_Frontpage::instance();
