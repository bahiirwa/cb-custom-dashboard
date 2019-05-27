<?php
/*
Plugin Name: Custom Dashboard
Version: 1
Author: David of UG
Author URI: https://www.david.ug
Description: Custom Backend Dashboard
*/

Namespace PLUGIN_CB;

class Init {
    
    private $files;
    private $dir;
    
    public function __construct( $files = [], $dir ) {
        $this->files = $files;
        $this->dir = $dir;
    }
    
    public function scripts( ) {
        
        if(is_array($this->files ) AND sizeof($this->files) > 0 ) :
            foreach( $this->files as $file ):
                
                require_once "{$this->dir}/{$file}";
            
            endforeach;
        endif;
    }
}


$import = new Init(['wp-feed.php','wp-admin-dashboard.php','wp-admin-forms.php','wp-login-form.php'],'inc');
$import->scripts();
