<?php


    class WPH_update
        {
            var $wph;
                                  
            function __construct()
                {
                    global $wph;
                    $this->wph          =   &$wph;
                    
                    $this->_run();
                }
                
                
            private function _run()
                {                    
                    $version        =   isset($this->wph->settings['version']) ?   $this->wph->settings['version'] :   1;
                    
                    if (version_compare($version, WPH_VERSION, '<')) 
                        {
                            
                            if(version_compare($version, '1.1', '<'))
                                {
                                    //structure and settings fields where changed since v1.1
                                    if( isset($this->wph->settings['module_settings']['rewrite_new_theme_path']) )
                                        {
                                            $module_settings    =   $this->wph->settings['module_settings'];
                                            $this->wph->settings['module_settings'] =   array();
                                          
                                            foreach($module_settings    as  $key    =>  $value)
                                                {
                                                    if(strpos($key, 'rewrite_') !== FALSE &&    strpos($key, 'rewrite_') ==  0)
                                                        $key    =   substr($key,    8);
                                                        
                                                    if(strpos($key, 'general_') !== FALSE &&    strpos($key, 'general_') ==  0)
                                                        $key    =   substr($key,    8);
                                                        
                                                    if(strpos($key, 'admin_') !== FALSE &&    strpos($key, 'admin_') ==  0)
                                                        $key    =   substr($key,    6);
                                                        
                                                    $key    =   trim($key);
                                                    if(empty($key))
                                                        continue;
                                                    
                                                    $this->wph->settings['module_settings'][$key]   =   $value;
                                                }
                                        }
                                    
                                    $version =   '1.1';
                                }
                            
                                                    
                            if(version_compare($version, '1.3', '<'))
                                {
                                    //flush rules
                                    add_action('wp_loaded',        array($this,    'flush_rules') , -1);
                                    
                                    $version =   '1.3';
                                }
                            
                            if(version_compare($version, '1.3.1', '<'))
                                {
                                    //run update 2   
                                    
                                    $version =   '1.3.1';
                                }
                            
                            if(version_compare($version, '1.3.2', '<'))
                                {
                                    //flush rules
                                    add_action('wp_loaded',        array($this,    'flush_rules') , -1); 
                                    
                                    $version =   '1.3.2';
                                }
                                
                            if(version_compare($version, '1.3.2.2', '<'))
                                {
                                    if(isset($this->wph->settings['module_settings']['remove_version']) &&  $this->wph->settings['module_settings']['remove_version']   ==  "yes")
                                        {
                                            $this->wph->settings['module_settings']['styles_remove_version']        =   'yes';
                                            $this->wph->settings['module_settings']['scripts_remove_version']       =   'yes';
                                            
                                            unset($this->wph->settings['module_settings']['remove_version']);   
                                        }
                                                                        
                                    $version =   '1.3.2.2';
                                }
                            
                            //save the last code version
                            $this->wph->settings['version'] =   WPH_VERSION;
                            $this->wph->functions->update_settings($this->wph->settings);
                                    
                        }
                    
                     
                }
            
 
            /**
            * Regenerate rewrite rules
            * 
            */
            function flush_rules()
                {
                    /** WordPress Misc Administration API */
                    require_once(ABSPATH . 'wp-admin/includes/misc.php');
                    
                    /** WordPress Administration File API */
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                    
                    flush_rewrite_rules();
                    
                    //clear the cache for W3 Cache              
                    if (function_exists('w3tc_pgcache_flush'))
                        w3tc_pgcache_flush();
                    
                    //recheck if permalinks where saved sucessfully and redirect
                    
                    /**
                    * ToDo Possible not necesarely?  
                    * 1) Through AJAX, plugin update - it trigger the first update so flush_rules()
                    * 2) Through regular plugin update, no new plugin files are being run
                    */
                    if( !defined( 'DOING_AJAX' ) &&  $this->wph->permalinks_not_applied   === FALSE   &&  $this->wph->functions->rewrite_rules_applied()  === TRUE)
                        {
                            //reload the page
                            wp_redirect($this->wph->functions->get_current_url());
                            die();
                        }
                          
                }
                
        }
        
        
?>