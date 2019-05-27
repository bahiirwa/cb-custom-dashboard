<?php
Namespace PLUGIN_CB\INC;

class LOGIN_FORM {
    
    public function __construct() {
        add_action('login_enqueue_scripts',[$this,'loginFormStyle']);
        add_filter('login_headerurl',[$this,'homeURL']);
        add_filter('login_headertitle',[$this,'headerTitle']);

    }
    
    public function loginFormStyle() {
        
        wp_enqueue_style( 'custom-login', plugin_dir_url(__DIR__). 'assets/css/login.css' );
        wp_enqueue_script( 'custom-login', plugin_dir_url(__DIR__). 'assets/js/login.js' );
        
    }
    
    public function homeURL() {
        return home_url();
    }
    
    public function headerTitle() {
        return get_bloginfo('name');
    }

}

new LOGIN_FORM();