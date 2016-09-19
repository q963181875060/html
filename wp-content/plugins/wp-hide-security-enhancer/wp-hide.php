<?php
/*
Plugin Name: WP Hide & Security Enhancer
Plugin URI: http://www.nsp-code.com
Description: Hide and increase Security for your WordPress website instance using smart techniques. No files are changed on your server.
Author: Nsp Code
Author URI: http://www.nsp-code.com 
Version: 1.3.5.1
Text Domain: wp-hide-security-enhancer
Domain Path: /languages/ 
*/
    
    
    define('WPH_PATH',      plugin_dir_path(__FILE__));
    define('WPH_VERSION',   '1.3.5');
          
    //load language files
    add_action( 'plugins_loaded', 'WPH_load_textdomain'); 
    function WPH_load_textdomain() 
        {
            load_plugin_textdomain('wp-hide-security-enhancer', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages');
        }
    
    include_once(WPH_PATH . '/include/wph.class.php');
    include_once(WPH_PATH . '/include/functions.class.php');
    
    include_once(WPH_PATH . '/include/module.class.php');
    include_once(WPH_PATH . '/include/module.component.class.php');
    
    register_deactivation_hook(__FILE__, 'WPH_deactivated');
    register_activation_hook(__FILE__, 'WPH_activated');

    function WPH_activated($network_wide) 
        {
            
            flush_rewrite_rules();     
            
            global $wph;
            
            //check if permalinks where saved
            $wph->permalinks_not_applied   =   ! $wph->functions->rewrite_rules_applied();
            
            //reprocess components if the permalinks where applied
            if($wph->permalinks_not_applied   !== TRUE)
                {
                    $wph->_modules_components_run();
                }
            
        }

    function WPH_deactivated() 
        {
            global $wph;
            
            $wph->uninstall =   TRUE;
            flush_rewrite_rules();
            
            //redirect to old url   
        }
    

    global $wph;
    $wph    =   new WPH();
    $wph->init();
    
    /**
    * Early Turn ON buffering to allow a callback
    * 
    */
    ob_start(array($wph, 'ob_start_callback'));
        
    define('WPH_URL',    plugins_url('', __FILE__));
        
?>