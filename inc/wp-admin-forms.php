<?php
Namespace PLUGIN_CB\INC;

if( !defined('ABSPATH')) wp_die('Not authorized');

class WP_ADMIN_FORMS {
    
    private $postTypes = ['expense','income','share','investment','disbursement'];
    
    public function __construct() {
        add_action('admin_menu', [$this,'hdAddBox']);
        add_action('admin_head',[$this,'hdAddButtons']);
        // add the action 
        add_action( 'load-post-new.php', [$this,'formPostNewDisable'], 10, 1 ); 
    }
    
    //hide "add new" on wp-admin menu
    public function hdAddBox() {
        
        $user = wp_get_current_user();
        if( in_array( 'author', $user->roles ) OR in_array( 'editor', $user->roles ) ) :
            
            global $submenu;
            
            foreach( $this->postTypes as $postType ) 
                unset( $submenu['edit.php?post_type='.$postType][10] );
        
        endif;
    }
    
    //hide "add new" button on edit page
    public function hdAddButtons() {
        
        global $pagenow;
        
        if(is_admin()) :
          
            $user = wp_get_current_user();
        
            if( in_array( 'author', $user->roles ) OR in_array( 'editor', $user->roles ) ) :

            	if( $pagenow == 'edit.php' && in_array( $_GET['post_type'], $this->postTypes ) ) :
            	    
            	    echo '
            	        <style type="text/css">
            	            .wp-heading-inline + a,
            	            .tablenav,
            	            #favorite-actions { display: none; }
            
            	        </style>
            	    ';
            	    
                endif;
            endif;
        endif;
      
    }
    
    // define the load-post-new.php callback 
    public function formPostNewDisable( $wpsc_add_help_tabs ) {
        if( in_array( $_GET['post_type'], $this->postTypes ) )
            wp_die('Not Authorized');
    }
         
}

new WP_ADMIN_FORMS();