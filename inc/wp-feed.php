<?php
namespace PLUGIN_CB\INC;

class Feed {
    
    public function __construct() {
        
        add_action('do', [$this,'disable'], 1);
        add_action('do_rdf', [$this,'disable'], 1);
        add_action('do_rss', [$this,'disable'], 1);
        add_action('do_rss2',[$this, 'disable'], 1);
        add_action('do_atom', [$this,'disable'], 1);
        add_action('do_rss2_comments', [$this,'disable'], 1);
        add_action('do_atom_comments', [$this,'disable'], 1);
        
    }
    
    public function disable() {
        wp_die( __('No feed available!') );
    }

}

new Feed();

