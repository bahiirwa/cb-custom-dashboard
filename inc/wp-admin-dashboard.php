<?php

Namespace PLUGIN_CB\INC;

class WP_ADMIN_DASHBOARD {
    
    private $loggedInUser;
    
    public function __construct() {
        
        add_action('admin_enqueue_scripts',[$this,'adminScripts']);
        add_action( 'wp_before_admin_bar_render', [$this,'someMenusDisable']);
        add_action( 'admin_bar_menu', [$this,'adminBarMenu'], 10 );
        add_action( 'admin_menu', [$this,'adminMenu']);
        add_filter('screen_options_show_screen', [$this,'screenOptionsDisable']);
        add_filter('contextual_help_list',[$this,'helpTabDisable']);
		add_filter('admin_init',[$this,'detachDashboardMetaboxes']);
        add_filter('admin_init',[$this,'updateNag'],999);
        add_filter('admin_init',[$this,'customRoles'],999);
        remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker' );
        
        add_action( 'admin_head', [$this,'corProfileSubjectStart']);
         add_action( 'admin_footer',[$this, 'corProfileSubjectEnd'] );
    }
    
    public function adminScripts() {
        
    	if( in_array('author',$this->loggedInUser->roles) ) :
    		wp_enqueue_style('author-editor-styles', plugin_dir_url(__DIR__).'assets/css/author-editor.css');
    	endif;
    	
    	wp_enqueue_style('admin-styles', plugin_dir_url(__DIR__).'assets/css/style.css');
    }
    
    public function someMenusDisable() {
        global $wp_admin_bar;  
        
        $wp_admin_bar->remove_menu('new-content');
        $wp_admin_bar->remove_menu('site-name');
        $wp_admin_bar->remove_menu('comments');
        $wp_admin_bar->remove_menu('wp-logo');
        //$wp_admin_bar->remove_menu('my-account');
        $wp_admin_bar->remove_menu('user-info');        
        $wp_admin_bar->remove_menu('edit-profile');
    }
    
    public function adminMenu( ) {
        $user = wp_get_current_user();
        if( isset($user->roles) AND in_array('author',$user->roles) ) {
            remove_menu_page('edit.php');
            remove_menu_page('upload.php');
            remove_menu_page('edit-comments.php');
            remove_menu_page('tools.php');
        }
    }
    
    public function adminBarMenu( $meta = TRUE ) { 
        
        global $wp_admin_bar; 
        
        //if ( !is_user_logged_in() ) { return; }  
        if ( /*!is_super_admin() || */!is_admin_bar_showing() ) { return; } 
        
        $wp_admin_bar->add_menu([ 
            'id' => 'custom_menu',  
            'title' => get_bloginfo('name'),  
            'href' => get_dashboard_url(),
        ]);  

        $myAccount = $wp_admin_bar->get_node('my-account');
        
        $newText = str_replace( 'Howdy,', 'Hi,', $myAccount->title );
        
        $wp_admin_bar->add_node([
            'id' => 'my-account',
            'title' => $newText,
        ]);
        
    }
    
    public function howDay() {
        global $wp_admin_bar;
        
        $myAccount = $wp_admin_bar->get_node('my-account');
        
        $newText = str_replace( 'Howdy,', 'Hi,', $myAccount->title );
        
        $wp_admin_bar->add_node([
            'id' => 'my-account',
            'title' => $newText,
        ]);
    }
    
    public function updateNag() {
        $this->loggedInUser = wp_get_current_user();
         
        if( isset($this->loggedInUser->roles) && in_array('author',(array)$this->loggedInUser->roles)) :
            add_action('init', create_function('$a',"remove_action( 'init', 'wp_version_check' );"),2);
            add_filter('pre_option_update_core','__return_null');
            add_filter('pre_site_transient_update_core','__return_null');
        endif;
    }
    
    public function screenOptionsDisable() {
        return false;
    }

    public function helpTabDisable() {
        global $current_screen;
        $current_screen->remove_help_tabs();
    }

    /**
    * Removes the leftover 'Visual Editor', 'Keyboard Shortcuts' and 'Toolbar' options.
    */
    public function corRemovePersonalOptions( $buffer ) { 
        $buffer = preg_replace( '#<h2>'.__("Personal Options").'</h2>.+?/table>#s', '', $buffer, 1 );
        $buffer = preg_replace( '#<h2>'.__("About Yourself").'</h2>.+?/table>#s', '', $buffer, 1 );
        
        return $buffer;
        
    }

    public function corProfileSubjectStart() {
        ob_start( [$this,'corRemovePersonalOptions'] );
    }

    public function corProfileSubjectEnd() {
        ob_end_flush();
    }


	public function detachDashboardMetaboxes() {
        remove_meta_box( 'dashboard_welcome_panel', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
        remove_meta_box( 'wordfence_activity_report_widget', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');//since 3.8
    }
    
    public function customRoles() {
        add_role( 'other', 'Other', [] );
    }
}

new WP_ADMIN_DASHBOARD();