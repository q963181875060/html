<?php


    class WPH_functions
        {
            var $wph;
                                  
            function __construct()
                {
                    global $wph;
                    $this->wph          =   &$wph;
                }
            
            function __destruct()
                {
                
                }
            
            function get_module_default_setting()
                {
                    $defaults   = array (
                                            'id'                =>  '',
                                            'visible'           =>  TRUE,
                                            'label'             =>  '',
                                            'description'       =>  '',
                                            'value_description' =>  '',
                                            'input_type'        =>  'text',
                                            'default_value'     =>  '',
                                            'sanitize_type'     =>  array('sanitize_title'),
                                            
                                            'callback'          =>  '',
                                            'processing_order'  =>  10,
                                        );   
                    
                    return $defaults;
                }
                
            function filter_settings($module_settings, $strip_splits    =   FALSE)
                {
                    if(!is_array($module_settings)  || count($module_settings) < 1)
                        return $module_settings;
                    
                    $defaults   =   $this->get_module_default_setting();
                    
                    foreach($module_settings    as  $key    =>  $module_setting)
                        {
                            if(isset($module_setting['type'])   &&  $module_setting['type'] ==  'split')
                                {
                                    if($strip_splits    === TRUE)
                                        unset($module_settings[$key]);
                                        
                                    continue;
                                }
                            
                            $module_setting   =   wp_parse_args( $module_setting, $defaults );
                            
                            switch($module_setting['input_type'])
                                {
                                    case    'text' :
                                                        $defaults_type   = array (
                                                                                'placeholder'                =>  '',
                                                                            );
                                                        $module_setting   =   wp_parse_args( $module_setting, $defaults_type );
                                                        
                                                        break;   
                                    
                                    
                                }
       
                            $module_settings[$key]  =   $module_setting;
                        }
                    
                    $module_settings    =   array_values($module_settings);
                    
                    return $module_settings;
                    
                }
                               
            
            function reset_settings()
                {
                    
                    $nonce  =   $_POST['_wpnonce'];
                    if ( ! wp_verify_nonce( $nonce, 'wp-hide-reset-settings' ) )
                        return FALSE;
                    
                    global $wph;
                    
                    foreach($wph->modules   as  $module)
                        {
                            //proces the fields
                            $module_settings    =   $this->filter_settings(   $module->get_module_settings(), TRUE    );
                            
                            foreach($module_settings as $module_setting)
                                {
                                    if(isset($module_setting['type'])   &&  $module_setting['type'] ==  'split')
                                        continue;
                                    
                                    $field_name =   $module_setting['id'];
                                    
                                    $value      =   isset($module_setting['default_value'])  ?   $module_setting['default_value'] :   '';
                         
                                    //save the value
                                    $wph->settings['module_settings'][ $field_name ]  =   $value;
                                }   
                            
                        }
                             
                    //update the settings
                    $this->update_settings($wph->settings);
                    
                    //trigger the settings changed action
                    do_action('wph/settings_changed', $screen_slug, $tab_slug);
                    
                    //redirect
                    $new_admin_url     =   $this->get_module_item_setting('admin_url'  ,   'admin');
                    if(!empty($new_admin_url)   &&  $this->is_permalink_enabled())
                        $new_location       =   trailingslashit(    site_url()  )   . $new_admin_url .  "/admin.php?page=wp-hide";
                        else
                        $new_location       =   trailingslashit(    site_url()  )   .  "wp-admin/admin.php?page=wp-hide";
                         
                    $new_location   .=  '&reset_settings=true';
                        
                    wp_redirect($new_location);
                    die();
                    
                }
                
            function process_interface_save()
                {
                    $nonce  =   $_POST['wph-interface-nonce'];
                    if ( ! wp_verify_nonce( $nonce, 'wph/interface_fields' ) )
                        return FALSE;
                        
                    $screen_slug  =   $_GET['page'];
                    if(empty($screen_slug))
                        return FALSE;
                        
                    $tab_slug     =   isset($_GET['component'])   ?   $_GET['component']  :   FALSE;
                        
                    $module =   $this->get_module_by_slug($screen_slug);
                    if(!is_object($module))
                        return FALSE;
                    
                    //if no tag slug check if module use tabs and use the very first one
                    if(empty($tab_slug)   &&  $module->use_tabs  === TRUE)
                        {
                            //get the first component
                            foreach($module->components   as  $module_component)
                                {
                                    if( ! $module_component->title)
                                        continue;
                                    
                                    $tab_slug =   $module_component->id;
                                    break;
                                }  
                            
                        }
                    
                    global $wph;
                        
                    //proces the fields
                    $module_settings    =   $this->filter_settings(   $module->get_module_settings($tab_slug)    );
                    
                    foreach($module_settings as $module_setting)
                        {
                            if(isset($module_setting['type'])   &&  $module_setting['type'] ==  'split')
                                continue;
                            
                            $field_name =   $module_setting['id'];
                            
                            $value      =   isset($_POST[$field_name])  ?   $_POST[$field_name] :   '';
                            
                            //if empty use the default
                            if(empty($value))
                                $value  =   $module_setting['default_value'];
                                     
                            //sanitize value
                            foreach($module_setting['sanitize_type']    as  $sanitize)
                                {
                                    $value  =   call_user_func_array($sanitize, array($value));   
                                }
                                
                            //save the value
                            $wph->settings['module_settings'][ $field_name ]  =   $value;
                        }
                        
                    //update the settings
                    $this->update_settings($wph->settings);
                    
                    //trigger the settings changed action
                    do_action('wph/settings_changed', $screen_slug, $tab_slug);
                    
                    //redirect
                    $new_admin_url     =   $this->get_module_item_setting('admin_url'  ,   'admin');
                    if(!empty($new_admin_url)   &&  $this->is_permalink_enabled())
                        $new_location       =   trailingslashit(    site_url()  )   . $new_admin_url .  "/admin.php?page="   .   $screen_slug;
                        else
                        $new_location       =   trailingslashit(    site_url()  )   .  "wp-admin/admin.php?page="   .   $screen_slug;
                    
                    if($tab_slug !==    FALSE)
                        $new_location   .=  '&component=' . $tab_slug;
                    
                    $new_location   .=  '&settings_updated=true';
                        
                    wp_redirect($new_location);
                    die();
                }
                
                
            function settings_changed_check_for_cache_plugins()
                {
                    
                    $active_plugins = (array) get_option( 'active_plugins', array() ); 
                            
                    //cache plugin nottice
                    if(array_search('w3-total-cache/w3-total-cache.php',    $active_plugins)    !== FALSE)  
                        {
                            //check if just flushed
                            if(!isset($_GET['w3tc_note']))
                                echo "<div class='error'><p>". __('W3 Total Cache Plugin is active, make sure you clear the cache for new changes to apply', 'wp-hide-security-enhancer')  ."</p></div>";
                        }
                    if(array_search('wp-super-cache/wp-cache.php',    $active_plugins)    !== FALSE)  
                        {
                            echo "<div class='error'><p>". __('WP Super Cache Plugin is active, make sure you clear the cache for new changes to apply', 'wp-hide-security-enhancer')  ."</p></div>";
                        }    
                    
                }
                
                
            /**
            * Return the module class by it's slug
            * 
            * @param mixed $module_slug
            */
            function get_module_by_slug($module_slug)
                {
                    global $wph;
                    
                    $found_module   =   FALSE;
                    
                    foreach($wph->modules     as  $module)
                        {
                            $interface_menu_data    =   $module->get_module_slug();
                            
                            if($interface_menu_data ==  $module_slug)
                                {
                                    $found_module   =   $module;
                                    break;                            
                                }
                        }
                        
                    return $found_module;
                }
            
            /**
            * Used on early access when WP_Rewrite is not available
            * 
            */
            function is_permalink_enabled()
                {
                    
                    $permalink_structure    =   get_option('permalink_structure');
                    
                    if (    empty($permalink_structure)   )
                        return FALSE;
                        
                    return TRUE;
                        
                }
            
            
            
            /**
            * return the server home path
            * 
            */
            function get_home_path()
                {
                    
                    $home    = set_url_scheme( get_option( 'home' ), 'http' );
                    $siteurl = set_url_scheme( get_option( 'siteurl' ), 'http' );
                    if ( ! empty( $home ) && 0 !== strcasecmp( $home, $siteurl ) ) 
                            {
                                $wp_path_rel_to_home    = str_ireplace( $home, '', $siteurl ); /* $siteurl - $home */
                                $pos                    = strripos( str_replace( '\\', '/', $_SERVER['SCRIPT_FILENAME'] ), trailingslashit( $wp_path_rel_to_home ) );
                                
                                if($pos !== FALSE)
                                    {
                                        $home_path              = substr( $_SERVER['SCRIPT_FILENAME'], 0, $pos );
                                        $home_path              = trailingslashit( $home_path );        
                                    }
                                    else
                                    {
                                        $wp_path_rel_to_home    =   '\\' . trim($wp_path_rel_to_home, '/');
                                        $pos                    =   strpos( realpath(ABSPATH), $wp_path_rel_to_home);
                                        $home_path              =   substr( realpath(ABSPATH), 0, $pos );
                                        $home_path              =   trailingslashit( $home_path );        
                                    }
                            } 
                        else 
                            {
                                $home_path = ABSPATH;
                            }

                    $home_path  =   str_replace( '\\', '/', $home_path );
                    
                    return $home_path;
                       
                }
            
            
            /**
            * return whatever server using the .htaccess config file
            * 
            */
            function server_use_htaccess_config_file()
                {
                    $home_path = $this->get_home_path();
                    
                    $htaccess_file = $home_path.'.htaccess';
                    if ($this->apache_mod_loaded('mod_rewrite', true))
                        return TRUE;
                    
                    return FALSE;
                    
                }
            
            
            /**
            * Does the specified module exist in the Apache config?
            *
            * @since 2.5.0
            *
            * @global bool $is_apache
            *
            * @param string $mod     The module, e.g. mod_rewrite.
            * @param bool   $default Optional. The default return value if the module is not found. Default false.
            * @return bool Whether the specified module is loaded.
            */
            function apache_mod_loaded($mod, $default = false) 
                {
               
                    if ( function_exists( 'apache_get_modules' ) ) 
                        {
                            $mods = apache_get_modules();
                            if ( in_array($mod, $mods) )
                                return true;
                        } 
                    elseif (getenv('HTTP_MOD_REWRITE')  !== FALSE) 
                            {
                              $mod_found =  getenv('HTTP_MOD_REWRITE')    ==  'On' ? true : false ;
                              return    $mod_found; 
                            } 
                    elseif ( function_exists( 'phpinfo' ) && false === strpos( ini_get( 'disable_functions' ), 'phpinfo' ) ) {
                            ob_start();
                            phpinfo(8);
                            $phpinfo = ob_get_clean();
                            if ( false !== strpos($phpinfo, $mod) )
                                return true;
                    
                    }
                            
                    return $default;
                    
                }
                
            
            /**
            * return whatever the htaccess config file is writable
            *     
            */
            function is_writable_htaccess_config_file()
                {
                    $home_path = $this->get_home_path();
                    
                    $htaccess_file = $home_path.'.htaccess';
                    
                    if ((!file_exists($htaccess_file) && is_writable($home_path) && $this->is_permalink_enabled()) || is_writable($htaccess_file))
                        return TRUE;
                        
                    return FALSE;
                    
                }
                
            /**
            * return whatever server using the .htaccess config file
            * 
            */
            function server_use_web_config_file()
                {
                    $home_path = $this->get_home_path();
                    
                    $web_config_file = $home_path . 'web.config';
                    
                    if ( iis7_supports_permalinks() )
                        return TRUE;
                        
                    return FALSE;
                    
                }
            
            
            /**
            * return whatever the web.config config file is writable
            *     
            */
            function is_writable_web_config_file()
                {
                    $home_path = $this->get_home_path();
                    
                    $web_config_file = $home_path . 'web.config';
                    
                    if ( ( ! file_exists($web_config_file) && win_is_writable($home_path) && $this->is_permalink_enabled() ) || win_is_writable($web_config_file) )
                        return TRUE;
                        
                    return FALSE;
                    
                }          
            
            
            function get_write_check_string()
                {
                    $home_path = $this->get_home_path();
                    
                    global $wp_rewrite;
                    
                    $result =   FALSE;
                    
                    //check for .htaccess 
                    if ( $this->server_use_htaccess_config_file()   &&  file_exists($home_path . '.htaccess'))
                        {
                                         
                            if ( $markerdata = explode( "\n", implode( '', file( $home_path . '.htaccess' ) ) ));
                                {
                                    foreach ( $markerdata as $markerline ) 
                                        {
                                            if (strpos($markerline, '#WriteCheckString:') !== false)
                                                {
                                                    $result =   trim(str_replace( '#WriteCheckString:',  '', $markerline));
                                                    break;
                                                }
                                        }
                                }
                        }
                    
                    //check for web.config
                    if ( $this->server_use_web_config_file()    &&  file_exists( $home_path . 'web.config' ))
                        {
                            $file_data  =   file( $home_path . 'web.config' );
                            if(!empty($file_data))
                                {
                                    if ( $markerdata = explode( "\n", implode( '', $file_data ) ));
                                        {
                                            foreach ( $markerdata as $markerline ) 
                                                {
                                                    preg_match("'<rule name=\"wph-.*?<!-- WriteCheckString:([0-9_]+) --></rule>'si", $markerline, $matches);
                                                    if(isset($matches[1]))
                                                        {
                                                            $result =   $matches[1]; 
                                                        }
                                                        
                                                    if (!isset($matches[1])   &&  strpos($markerline, '<!-- WriteCheckString:') !== false)
                                                        {
                                                            $result =   trim(str_ireplace( '<!-- WriteCheckString:',  '', $markerline));
                                                            $result =   trim(str_replace( '-->',  '', $result));
                                                            $result =   trim($result);
                                                            
                                                            break;
                                                        }
                                                }
                                        }   
      
                                }
                                
                        }
                        
                    return $result;    
                    
                }
            
            
            function rewrite_rules_applied()
                {
                    $status = TRUE;
                    
                    if(isset($this->wph->settings['write_check_string'])   && !empty($this->wph->settings['write_check_string']))
                        {
                            $_write_check_string =   $this->get_write_check_string();
                            if(empty($_write_check_string)  ||  $_write_check_string    !=  $this->wph->settings['write_check_string'])
                                $status   =   FALSE;
                        }
                        else
                        {
                            //disable, as settings never being saved or came from old version  
                            $status   =   FALSE;
                        }
                        
                    return $status;
                }
            
                
            /**
            * 
            * Check if theme is is customize mode
            *     
            */
            function is_theme_customize()
                {
                    
                    if (    strpos($_SERVER['REQUEST_URI'] ,'customize.php')   !== FALSE    )
                        return TRUE;
                        
                    if (    isset($_POST['wp_customize'])  && $_POST['wp_customize']   ==  "on" )   
                        return TRUE;        
                    
                    return FALSE;
                    
                }
                
            
            /**
            * return settings
            *     
            */
            function get_settings()
                {
                    $settings   =   get_option('wph_settings');
                    
                    $defaults   = array (
                                            'module_settings'   =>  array(),
                                            'recovery_code'     =>  ''
                                        );
                    
                    $settings   =   wp_parse_args( $settings, $defaults );
                    
                    return $settings;
                    
                }
                
            
            
            /**
            * Return a Module Item value setting
            * 
            * @param mixed $item_id
            */
            function get_module_item_setting($item_id)
                {
                    
                    $settings   =   $this->get_settings();
                    
                    $value      =   isset($settings['module_settings'][ $item_id ])  ?   $settings['module_settings'][ $item_id] :   '';
                    
                    $value      =   apply_filters('wp-hide/get_module_item_setting', $value, $item_id);
                    
                    return $value;
                    
                }
                
            
            /**
            * Save the settings
            *     
            * @param mixed $settings
            */
            function update_settings($settings)
                {
                    update_option('wph_settings', $settings);
                }
                
                
            function get_url_path($url, $is_file_path   =   FALSE)
                {
                    if(!$is_file_path)
                        $url            =   trailingslashit(    $url    );
                        
                    $url_parse      =   parse_url(  $url   );
                    
                    /*
                    $root           =   isset($url_parse['scheme']) ?   $url_parse['scheme']    .   '://'    :   '';
                    $root           .=  isset($url_parse['host']) ?   $url_parse['host']  :   '';
                    */
                    
                    $path           =   $url_parse['path'];
                    
                    if(!$is_file_path)
                        $path           =   trailingslashit(    $path   );
                    
                    if($path    !=  '/' && strlen($path) > 1)
                        {
                            $path   =   ltrim($path, '/');
                            $path   =   '/' .   $path;
                        }
                    
                    if(isset($url_parse['query']))
                        $path   .=  '?' .   $url_parse['query'];
                    
                    return $path;
                    
                }
                
            
            /**
            * return the url relative to domain root
            * 
            * @param mixed $url
            */
            function get_url_path_relative_to_domain_root($url)
                {
                    
                    $url    =   str_replace(trailingslashit(  site_url()  ), "" , $url);
                       
                    return $url;
                    
                }
                
                
            
            function untrailingslashit_all($value)
                {
                    $value  =   ltrim(rtrim($value, "/"),  "/");
                    
                    return $value;
                }    
                
            function sanitize_file_path_name($value)
                {
                    $value  =   trim($value);
                    
                    if(empty($value))
                        return $value;
                    
                    $parts  =   explode("/",    $value);
                    $parts  =   array_filter($parts);
                    
                    foreach($parts  as  $key    =>  $part_item)
                        {
                            $parts[$key]    =   sanitize_file_name($part_item);
                        }
                        
                    $value  =   implode("/", $parts);
                    
                    $value  =   strtolower($value);
                    
                    return $value;
                }
            
            function php_extension_required($value)
                {
                    $value  =   trim($value);
                    
                    if($value   ==  '')
                        return '';
                    
                    $extension  =   substr($value, -4);
                    if(strtolower($extension)   !=  '.php')
                        $value  .=  '.php';    
                                        
                    return $value;
                }
                
                
            function get_current_url()
                {
                    
                    $current_url    =   'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    
                    return  $current_url;
                                        
                }
                
            
            /**
            * Add replacement withint the list
            * 
            * @param mixed $old_url
            * @param mixed $new_url
            */
            function add_replacement($old_url, $new_url, $priority  =   'normal')
                {
                
                    if($this->replacement_exists($old_url))
                        return;
                        
                    $this->wph->urls_replacement[ $priority ][ $old_url ]  =   $new_url;   
                    
                }
                
            
            /**
            * Return whatever a replacement exists or not
            * The old url should be provided
            *     
            * @param mixed $old_url
            */
            function replacement_exists($old_url)
                {
                    
                    if(count($this->wph->urls_replacement)  <   1)
                        return FALSE;
                    
                    foreach($this->wph->urls_replacement    as  $priority   =>  $replacements_block)
                        {
                            if(isset($this->wph->urls_replacement[ $old_url ]))
                                return TRUE;
                        }
                        
                    return FALSE;
                                        
                }
                
                
            
            /**
            * Return a list of replacements
            * 
            */
            function get_replacement_list()
                {
                    
                    $replacements   =   array();
                    
                    if(count($this->wph->urls_replacement)  <   1)
                        return $replacements;
                    
                    foreach($this->wph->urls_replacement    as  $priority   =>  $replacements_block)
                        {
                            if(!is_array($replacements_block)   ||  count($replacements_block) < 1)
                                continue;
                            
                            foreach($replacements_block as  $old_url   =>  $new_url)
                                {
                                    $replacements[ $old_url ] =   $new_url;
                                }
                        }
                        
                    return $replacements;   
                    
                }
            
            
            /**
            * Replace the urls within given content
            * 
            * @param mixed $text
            * @param mixed $replacements
            */
            function content_urls_replacement($text, $replacements)
                {
                    //process the replacements
                    if( count($replacements)  <   1)
                        return $text;
                    
                    //exclude scheme to match urls without it
                    $_replacements  =   array();
                    $_relative_url_replacements  =   array();
                    
                    $home_url   =   home_url();
                    
                    foreach($replacements   as $old_url =>  $new_url)
                        {
                            //add quote to make sure it's actualy a link value and is right at the start of text
                            $_relative_url_replacements[ '"' . str_ireplace(   $home_url,   "", $old_url)   ] =   '"' . str_ireplace(   $home_url,   "", $new_url);
                            $_relative_url_replacements[ "'" . str_ireplace(   $home_url,   "", $old_url)   ] =   "'" . str_ireplace(   $home_url,   "", $new_url);
                            
                            $old_url    =   str_ireplace(   array('http://', 'https://'),   "", $old_url);
                            $new_url    =   str_ireplace(   array('http://', 'https://'),   "", $new_url);
                            
                            $_replacements[$old_url]    =   $new_url;
                        }
   
                    
                    $text =   str_ireplace(    array_keys($_replacements), array_values($_replacements)  ,$text   );
                    //relative urls replacements
                    $text =   str_ireplace(    array_keys($_relative_url_replacements), array_values($_relative_url_replacements)  ,$text   );
                    
                    //check for json encoded urls
                    foreach($_replacements   as $old_url =>  $new_url)
                        {
                            $old_url    =   trim(json_encode($old_url), '"');   
                            $new_url    =   trim(json_encode($new_url), '"'); 
                            
                            $text =   str_ireplace(    $old_url, $new_url  ,$text   );
                        }
                          
                    return $text;   
                }
                
            
            function default_scripts_styles_replace($object, $replacements)
                {
                    //update default dirs
                    if(isset($object->default_dirs))
                        {
                            foreach($object->default_dirs    as  $key    =>  $value)
                                {
                                    $object->default_dirs[$key]  =   str_replace(array_keys($replacements), array_values($replacements), $value);
                                }
                        }
                       
                    foreach($object->registered    as  $script_name    =>  $script_data)
                        {
                            $script_data->src   =   str_replace(array_keys($replacements), array_values($replacements), $script_data->src);
                            
                            $object->registered[$script_name]  =   $script_data;      
                        }
                        
                    return $object;
                }
                
                
            function check_headers_content_type($header_name, $header_value)
                {
                    
                    $headers    =   headers_list();
                    
                    foreach($headers    as  $header)
                        {
                            if(stripos($header, $header_name)   !== FALSE)
                                {
                                    if(stripos($header, $header_value)   !== FALSE)
                                        return TRUE;     
                                }
                        }
                        
                    
                    return FALSE;
                
                }
                
                
            function array_sort_by_processing_order($a, $b)
                {
                    return $a['processing_order'] - $b['processing_order'];
                }
            
            
            
            /**
            * Return the recovey code
            * 
            */
            function get_recovery_code()
                {
                    
                    $settings   =   $this->get_settings();
                    if(!isset($settings['recovery_code'])   ||  empty($settings['recovery_code']))
                        {
                            $recovery_code  =   $this->generate_recovery_code();
                        }
                        else
                        $recovery_code  =   $settings['recovery_code'];
                    
                    
                    return $recovery_code;
                }
            
            
            /**
            * Generate a recovery code
            * 
            */
            function generate_recovery_code()
                {
                    
                    $settings   =   $this->get_settings();   
                    
                    $recovery_code  =   md5(rand(1,9999) . microtime());
                    
                    $settings['recovery_code']  =   $recovery_code;
                    
                    $this->update_settings($settings);
                    
                    return $recovery_code;
                }
                
                
            /**
            * Trigger the recovery actions
            * 
            */
            function do_recovery()
                {
                    //feetch a new set of settings
                    $settings = $this->get_settings();
                    
                    $wph_recovery   =   isset($_GET['wph-recovery']) ?  $_GET['wph-recovery']   :   '';
                    if(empty($wph_recovery) ||  $wph_recovery   !=  $this->wph->settings['recovery_code'])
                        return;
                        
                    //change certain settings to default
                    $this->wph->settings['module_settings']['new_wp_login_php']  =   '';
                    $this->wph->settings['module_settings']['admin_url']         =   '';
                    
                    //update the settings
                    $this->update_settings($this->wph->settings);
                    
                    //available for mu-plugins
                    do_action('wph/do_recovery');                    
                    
                    
                    //add filter for rewriting the rules
                    add_action('wp_loaded',  array($this,    'wp_loaded_trigger_do_recovery'));
                    
                }
            
                
            function wp_loaded_trigger_do_recovery()
                {
                    /** WordPress Misc Administration API */
                    require_once(ABSPATH . 'wp-admin/includes/misc.php');
                    
                    /** WordPress Administration File API */
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                    
                    flush_rewrite_rules();   
                    
                    //redirect to homepage
                    wp_redirect(get_site_url());
                    
                    die();
                }
            
            
            /**
            * Check if filter / action exists for anonymous object
            * 
            * @param mixed $tag
            * @param mixed $class
            * @param mixed $method
            */
            function anonymous_object_filter_exists($tag, $class, $method)
                {
                    if ( !  isset( $GLOBALS['wp_filter'][$tag] ) )
                        return FALSE;
                    
                    $filters = $GLOBALS['wp_filter'][$tag];
                    
                    if ( !  $filters )
                        return FALSE;
                        
                    foreach ( $filters as $priority => $filter ) 
                        {
                            foreach ( $filter as $identifier => $function ) 
                                {
                                    if ( ! is_array( $function ) )
                                        continue;
                                    
                                    if ( ! $function['function'][0] instanceof $class )
                                        continue;
                                    
                                    if ( $method == $function['function'][1] ) 
                                        {
                                            return TRUE;
                                        }
                                }
                        }
                        
                    return FALSE;
                }
            
            /**
            * Replace a filter / action from anonymous object
            * 
            * @param mixed $tag
            * @param mixed $class
            * @param mixed $method
            */
            function remove_anonymous_object_filter( $tag, $class, $method ) 
                {
                    $filters = false;

                    if ( isset( $GLOBALS['wp_filter'][$tag] ) )
                        $filters = $GLOBALS['wp_filter'][$tag];

                    if ( $filters )
                    foreach ( $filters as $priority => $filter ) 
                        {
                            foreach ( $filter as $identifier => $function ) 
                                {
                                    if ( ! is_array( $function ) )
                                        continue;
                                    
                                    if ( ! $function['function'][0] instanceof $class )
                                        continue;
                                    
                                    if ( $method == $function['function'][1] ) 
                                        {
                                            remove_filter($tag, array( $function['function'][0], $method ), $priority);
                                        }
                                }
                        }
                }
                
            
            /**
            * An early instance of WordPress wp_mail core 
            * Unable to load pluggable.php where the function exists, as bein loaded using require
            *     
            * @param mixed $to
            * @param mixed $subject
            * @param mixed $message
            * @param mixed $headers
            * @param mixed $attachments
            */
            function wp_mail( $to, $subject, $message, $headers = '', $attachments = array() ) 
                {
                    // Compact the input, apply the filters, and extract them back out
                 
                    /**
                     * Filter the wp_mail() arguments.
                     *
                     * @since 2.2.0
                     *
                     * @param array $args A compacted array of wp_mail() arguments, including the "to" email,
                     *                    subject, message, headers, and attachments values.
                     */
                    $atts = apply_filters( 'wp_mail', compact( 'to', 'subject', 'message', 'headers', 'attachments' ) );
                 
                    if ( isset( $atts['to'] ) ) {
                        $to = $atts['to'];
                    }
                 
                    if ( isset( $atts['subject'] ) ) {
                        $subject = $atts['subject'];
                    }
                 
                    if ( isset( $atts['message'] ) ) {
                        $message = $atts['message'];
                    }
                 
                    if ( isset( $atts['headers'] ) ) {
                        $headers = $atts['headers'];
                    }
                 
                    if ( isset( $atts['attachments'] ) ) {
                        $attachments = $atts['attachments'];
                    }
                 
                    if ( ! is_array( $attachments ) ) {
                        $attachments = explode( "\n", str_replace( "\r\n", "\n", $attachments ) );
                    }
                    global $phpmailer;
                 
                    // (Re)create it, if it's gone missing
                    if ( ! ( $phpmailer instanceof PHPMailer ) ) {
                        require_once ABSPATH . WPINC . '/class-phpmailer.php';
                        require_once ABSPATH . WPINC . '/class-smtp.php';
                        $phpmailer = new PHPMailer( true );
                    }
                 
                    // Headers
                    if ( empty( $headers ) ) {
                        $headers = array();
                    } else {
                        if ( !is_array( $headers ) ) {
                            // Explode the headers out, so this function can take both
                            // string headers and an array of headers.
                            $tempheaders = explode( "\n", str_replace( "\r\n", "\n", $headers ) );
                        } else {
                            $tempheaders = $headers;
                        }
                        $headers = array();
                        $cc = array();
                        $bcc = array();
                 
                        // If it's actually got contents
                        if ( !empty( $tempheaders ) ) {
                            // Iterate through the raw headers
                            foreach ( (array) $tempheaders as $header ) {
                                if ( strpos($header, ':') === false ) {
                                    if ( false !== stripos( $header, 'boundary=' ) ) {
                                        $parts = preg_split('/boundary=/i', trim( $header ) );
                                        $boundary = trim( str_replace( array( "'", '"' ), '', $parts[1] ) );
                                    }
                                    continue;
                                }
                                // Explode them out
                                list( $name, $content ) = explode( ':', trim( $header ), 2 );
                 
                                // Cleanup crew
                                $name    = trim( $name    );
                                $content = trim( $content );
                 
                                switch ( strtolower( $name ) ) {
                                    // Mainly for legacy -- process a From: header if it's there
                                    case 'from':
                                        $bracket_pos = strpos( $content, '<' );
                                        if ( $bracket_pos !== false ) {
                                            // Text before the bracketed email is the "From" name.
                                            if ( $bracket_pos > 0 ) {
                                                $from_name = substr( $content, 0, $bracket_pos - 1 );
                                                $from_name = str_replace( '"', '', $from_name );
                                                $from_name = trim( $from_name );
                                            }
                 
                                            $from_email = substr( $content, $bracket_pos + 1 );
                                            $from_email = str_replace( '>', '', $from_email );
                                            $from_email = trim( $from_email );
                 
                                        // Avoid setting an empty $from_email.
                                        } elseif ( '' !== trim( $content ) ) {
                                            $from_email = trim( $content );
                                        }
                                        break;
                                    case 'content-type':
                                        if ( strpos( $content, ';' ) !== false ) {
                                            list( $type, $charset_content ) = explode( ';', $content );
                                            $content_type = trim( $type );
                                            if ( false !== stripos( $charset_content, 'charset=' ) ) {
                                                $charset = trim( str_replace( array( 'charset=', '"' ), '', $charset_content ) );
                                            } elseif ( false !== stripos( $charset_content, 'boundary=' ) ) {
                                                $boundary = trim( str_replace( array( 'BOUNDARY=', 'boundary=', '"' ), '', $charset_content ) );
                                                $charset = '';
                                            }
                 
                                        // Avoid setting an empty $content_type.
                                        } elseif ( '' !== trim( $content ) ) {
                                            $content_type = trim( $content );
                                        }
                                        break;
                                    case 'cc':
                                        $cc = array_merge( (array) $cc, explode( ',', $content ) );
                                        break;
                                    case 'bcc':
                                        $bcc = array_merge( (array) $bcc, explode( ',', $content ) );
                                        break;
                                    default:
                                        // Add it to our grand headers array
                                        $headers[trim( $name )] = trim( $content );
                                        break;
                                }
                            }
                        }
                    }
                 
                    // Empty out the values that may be set
                    $phpmailer->ClearAllRecipients();
                    $phpmailer->ClearAttachments();
                    $phpmailer->ClearCustomHeaders();
                    $phpmailer->ClearReplyTos();
                 
                    // From email and name
                    // If we don't have a name from the input headers
                    if ( !isset( $from_name ) )
                        $from_name = 'WordPress';
                 
                    /* If we don't have an email from the input headers default to wordpress@$sitename
                     * Some hosts will block outgoing mail from this address if it doesn't exist but
                     * there's no easy alternative. Defaulting to admin_email might appear to be another
                     * option but some hosts may refuse to relay mail from an unknown domain. See
                     * https://core.trac.wordpress.org/ticket/5007.
                     */
                 
                    if ( !isset( $from_email ) ) {
                        // Get the site domain and get rid of www.
                        $sitename = strtolower( $_SERVER['SERVER_NAME'] );
                        if ( substr( $sitename, 0, 4 ) == 'www.' ) {
                            $sitename = substr( $sitename, 4 );
                        }
                 
                        $from_email = 'wordpress@' . $sitename;
                    }
                 
                    /**
                     * Filter the email address to send from.
                     *
                     * @since 2.2.0
                     *
                     * @param string $from_email Email address to send from.
                     */
                    $phpmailer->From = apply_filters( 'wp_mail_from', $from_email );
                 
                    /**
                     * Filter the name to associate with the "from" email address.
                     *
                     * @since 2.3.0
                     *
                     * @param string $from_name Name associated with the "from" email address.
                     */
                    $phpmailer->FromName = apply_filters( 'wp_mail_from_name', $from_name );
                 
                    // Set destination addresses
                    if ( !is_array( $to ) )
                        $to = explode( ',', $to );
                 
                    foreach ( (array) $to as $recipient ) {
                        try {
                            // Break $recipient into name and address parts if in the format "Foo <bar@baz.com>"
                            $recipient_name = '';
                            if ( preg_match( '/(.*)<(.+)>/', $recipient, $matches ) ) {
                                if ( count( $matches ) == 3 ) {
                                    $recipient_name = $matches[1];
                                    $recipient = $matches[2];
                                }
                            }
                            $phpmailer->AddAddress( $recipient, $recipient_name);
                        } catch ( phpmailerException $e ) {
                            continue;
                        }
                    }
                 
                    // Set mail's subject and body
                    $phpmailer->Subject = $subject;
                    $phpmailer->Body    = $message;
                 
                    // Add any CC and BCC recipients
                    if ( !empty( $cc ) ) {
                        foreach ( (array) $cc as $recipient ) {
                            try {
                                // Break $recipient into name and address parts if in the format "Foo <bar@baz.com>"
                                $recipient_name = '';
                                if ( preg_match( '/(.*)<(.+)>/', $recipient, $matches ) ) {
                                    if ( count( $matches ) == 3 ) {
                                        $recipient_name = $matches[1];
                                        $recipient = $matches[2];
                                    }
                                }
                                $phpmailer->AddCc( $recipient, $recipient_name );
                            } catch ( phpmailerException $e ) {
                                continue;
                            }
                        }
                    }
                 
                    if ( !empty( $bcc ) ) {
                        foreach ( (array) $bcc as $recipient) {
                            try {
                                // Break $recipient into name and address parts if in the format "Foo <bar@baz.com>"
                                $recipient_name = '';
                                if ( preg_match( '/(.*)<(.+)>/', $recipient, $matches ) ) {
                                    if ( count( $matches ) == 3 ) {
                                        $recipient_name = $matches[1];
                                        $recipient = $matches[2];
                                    }
                                }
                                $phpmailer->AddBcc( $recipient, $recipient_name );
                            } catch ( phpmailerException $e ) {
                                continue;
                            }
                        }
                    }
                 
                    // Set to use PHP's mail()
                    $phpmailer->IsMail();
                 
                    // Set Content-Type and charset
                    // If we don't have a content-type from the input headers
                    if ( !isset( $content_type ) )
                        $content_type = 'text/plain';
                 
                    /**
                     * Filter the wp_mail() content type.
                     *
                     * @since 2.3.0
                     *
                     * @param string $content_type Default wp_mail() content type.
                     */
                    $content_type = apply_filters( 'wp_mail_content_type', $content_type );
                 
                    $phpmailer->ContentType = $content_type;
                 
                    // Set whether it's plaintext, depending on $content_type
                    if ( 'text/html' == $content_type )
                        $phpmailer->IsHTML( true );
                 
                    // If we don't have a charset from the input headers
                    if ( !isset( $charset ) )
                        $charset = get_bloginfo( 'charset' );
                 
                    // Set the content-type and charset
                 
                    /**
                     * Filter the default wp_mail() charset.
                     *
                     * @since 2.3.0
                     *
                     * @param string $charset Default email charset.
                     */
                    $phpmailer->CharSet = apply_filters( 'wp_mail_charset', $charset );
                 
                    // Set custom headers
                    if ( !empty( $headers ) ) {
                        foreach ( (array) $headers as $name => $content ) {
                            $phpmailer->AddCustomHeader( sprintf( '%1$s: %2$s', $name, $content ) );
                        }
                 
                        if ( false !== stripos( $content_type, 'multipart' ) && ! empty($boundary) )
                            $phpmailer->AddCustomHeader( sprintf( "Content-Type: %s;\n\t boundary=\"%s\"", $content_type, $boundary ) );
                    }
                 
                    if ( !empty( $attachments ) ) {
                        foreach ( $attachments as $attachment ) {
                            try {
                                $phpmailer->AddAttachment($attachment);
                            } catch ( phpmailerException $e ) {
                                continue;
                            }
                        }
                    }
                 
                    /**
                     * Fires after PHPMailer is initialized.
                     *
                     * @since 2.2.0
                     *
                     * @param PHPMailer &$phpmailer The PHPMailer instance, passed by reference.
                     */
                    do_action_ref_array( 'phpmailer_init', array( &$phpmailer ) );
                 
                    // Send!
                    try {
                        return $phpmailer->Send();
                    } catch ( phpmailerException $e ) {
                 
                        $mail_error_data = compact( $to, $subject, $message, $headers, $attachments );
                 
                        /**
                         * Fires after a phpmailerException is caught.
                         *
                         * @since 4.4.0
                         *
                         * @param WP_Error $error A WP_Error object with the phpmailerException code, message, and an array
                         *                        containing the mail recipient, subject, message, headers, and attachments.
                         */
                        do_action( 'wp_mail_failed', new WP_Error( $e->getCode(), $e->getMessage(), $mail_error_data ) );
                 
                        return false;
                    }
                }
                                  
        
            /**
            * Check the plugins directory and retrieve all plugin files with plugin data.
            *
            * WordPress only supports plugin files in the base plugins directory
            * (wp-content/plugins) and in one directory above the plugins directory
            * (wp-content/plugins/my-plugin). The file it looks for has the plugin data
            * and must be found in those two locations. It is recommended to keep your
            * plugin files in their own directories.
            *
            * The file with the plugin data is the file that will be included and therefore
            * needs to have the main execution for the plugin. This does not mean
            * everything must be contained in the file and it is recommended that the file
            * be split for maintainability. Keep everything in one file for extreme
            * optimization purposes.
            *
            * @since 1.5.0
            *
            * @param string $plugin_folder Optional. Relative path to single plugin folder.
            * @return array Key is the plugin file path and the value is an array of the plugin data.
            */
            function get_plugins($plugin_folder = '') 
                {
                 
                    $wp_plugins = array ();
                    $plugin_root = WP_PLUGIN_DIR;
                    if ( !empty($plugin_folder) )
                        $plugin_root .= $plugin_folder;

                    // Files in wp-content/plugins directory
                    $plugins_dir = @ opendir( $plugin_root);
                    $plugin_files = array();
                    if ( $plugins_dir ) {
                        while (($file = readdir( $plugins_dir ) ) !== false ) {
                            if ( substr($file, 0, 1) == '.' )
                                continue;
                            if ( is_dir( $plugin_root.'/'.$file ) ) {
                                $plugins_subdir = @ opendir( $plugin_root.'/'.$file );
                                if ( $plugins_subdir ) {
                                    while (($subfile = readdir( $plugins_subdir ) ) !== false ) {
                                        if ( substr($subfile, 0, 1) == '.' )
                                            continue;
                                        if ( substr($subfile, -4) == '.php' )
                                            $plugin_files[] = "$file/$subfile";
                                    }
                                    closedir( $plugins_subdir );
                                }
                            } else {
                                if ( substr($file, -4) == '.php' )
                                    $plugin_files[] = $file;
                            }
                        }
                        closedir( $plugins_dir );
                    }

                    if ( empty($plugin_files) )
                        return $wp_plugins;

                    foreach ( $plugin_files as $plugin_file ) {
                        if ( !is_readable( "$plugin_root/$plugin_file" ) )
                            continue;

                        $plugin_data = $this->get_plugin_data( "$plugin_root/$plugin_file", false, false ); //Do not apply markup/translate as it'll be cached.

                        if ( empty ( $plugin_data['Name'] ) )
                            continue;

                        $wp_plugins[plugin_basename( $plugin_file )] = $plugin_data;
                    }

                    return $wp_plugins;
                }
                
            
            /**
            * Parse plugin headers data
            *     
            * @param mixed $plugin_file
            * @param mixed $markup
            * @param mixed $translate
            */
            function get_plugin_data( $plugin_file, $markup = true, $translate = true ) 
                {

                    $default_headers = array(
                        'Name' => 'Plugin Name',
                        'PluginURI' => 'Plugin URI',
                        'Version' => 'Version',
                        'Description' => 'Description',
                        'Author' => 'Author',
                        'AuthorURI' => 'Author URI',
                        'TextDomain' => 'Text Domain',
                        'DomainPath' => 'Domain Path',
                        'Network' => 'Network',
                        // Site Wide Only is deprecated in favor of Network.
                        '_sitewide' => 'Site Wide Only',
                    );

                    $plugin_data = get_file_data( $plugin_file, $default_headers, 'plugin' );

                    // Site Wide Only is the old header for Network
                    if ( ! $plugin_data['Network'] && $plugin_data['_sitewide'] ) {
                        /* translators: 1: Site Wide Only: true, 2: Network: true */
                        _deprecated_argument( __FUNCTION__, '3.0', sprintf( __( 'The %1$s plugin header is deprecated. Use %2$s instead.' ), '<code>Site Wide Only: true</code>', '<code>Network: true</code>' ) );
                        $plugin_data['Network'] = $plugin_data['_sitewide'];
                    }
                    $plugin_data['Network'] = ( 'true' == strtolower( $plugin_data['Network'] ) );
                    unset( $plugin_data['_sitewide'] );

                    if ( $markup || $translate ) {
                        $plugin_data = _get_plugin_data_markup_translate( $plugin_file, $plugin_data, $markup, $translate );
                    } else {
                        $plugin_data['Title']      = $plugin_data['Name'];
                        $plugin_data['AuthorName'] = $plugin_data['Author'];
                    }

                    return $plugin_data;
                }
                
                
            /**
            * Alternative when apache_response_headers() not available
            * 
            */
            function parseRequestHeaders() 
                {
                    $headers = array();
                    foreach($_SERVER as $key => $value) 
                        {
                            if (substr($key, 0, 5) <> 'HTTP_') 
                                continue;
                                
                            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
                            $headers[$header] = $value;
                        }
                    
                    return $headers;
                }
            
            
            
            /**
            * Get available themes
            * 
            * @param mixed $args
            */
            function get_themes( $args = array() ) 
                {
                    global $wp_theme_directories;

                    $defaults = array( 'errors' => false, 'allowed' => null, 'blog_id' => 0 );
                    $args = wp_parse_args( $args, $defaults );

                    $theme_directories = search_theme_directories();

                    if ( count( $wp_theme_directories ) > 1 ) {
                        // Make sure the current theme wins out, in case search_theme_directories() picks the wrong
                        // one in the case of a conflict. (Normally, last registered theme root wins.)
                        $current_theme = get_stylesheet();
                        if ( isset( $theme_directories[ $current_theme ] ) ) {
                            $root_of_current_theme = get_raw_theme_root( $current_theme );
                            if ( ! in_array( $root_of_current_theme, $wp_theme_directories ) )
                                $root_of_current_theme = WP_CONTENT_DIR . $root_of_current_theme;
                            $theme_directories[ $current_theme ]['theme_root'] = $root_of_current_theme;
                        }
                    }

                    if ( empty( $theme_directories ) )
                        return array();

                    if ( is_multisite() && null !== $args['allowed'] ) {
                        $allowed = $args['allowed'];
                        if ( 'network' === $allowed )
                            $theme_directories = array_intersect_key( $theme_directories, WP_Theme::get_allowed_on_network() );
                        elseif ( 'site' === $allowed )
                            $theme_directories = array_intersect_key( $theme_directories, WP_Theme::get_allowed_on_site( $args['blog_id'] ) );
                        elseif ( $allowed )
                            $theme_directories = array_intersect_key( $theme_directories, WP_Theme::get_allowed( $args['blog_id'] ) );
                        else
                            $theme_directories = array_diff_key( $theme_directories, WP_Theme::get_allowed( $args['blog_id'] ) );
                    }

                    return $theme_directories;
                    
                }
            
            
            /**
            * Parse available themes headers
            * 
            */
            function parse_themes_headers( $all_templates )
                {
                    foreach( $all_templates as  $directory  =>  $theme_data)
                        {
                            
                            $theme_headers  =   $this->get_theme_headers( trailingslashit( $theme_data['theme_root']) . $theme_data['theme_file']);
                            $all_templates[$directory]['headers']   =  $theme_headers;
                            
                        }
                    
                    return $all_templates;
                       
                }
            
            
            function get_theme_headers($stylesheet_path)
                {
                    
                    $file_headers = array(
                                            'Name'        => 'Theme Name',
                                            'ThemeURI'    => 'Theme URI',
                                            'Description' => 'Description',
                                            'Author'      => 'Author',
                                            'AuthorURI'   => 'Author URI',
                                            'Version'     => 'Version',
                                            'Template'    => 'Template',
                                            'Status'      => 'Status',
                                            'Tags'        => 'Tags',
                                            'TextDomain'  => 'Text Domain',
                                            'DomainPath'  => 'Domain Path',
                                        );
                    
                    $theme_headers = get_file_data( $stylesheet_path, $file_headers, 'theme' );   
                    
                    return $theme_headers;
                    
                }
            
            
            /**
            * Return if a theme is child or not
            * 
            * @param mixed $theme_slug
            * @param mixed $all_themes
            */
            function is_child_theme($theme_slug, $all_themes)
                {
                    
                    $theme_data =   $all_themes[$theme_slug];
                        
                    if( isset($theme_data['headers']['Template']) &&  !empty($theme_data['headers']['Template']))
                        return TRUE;
                        
                    return FALSE;
                      
                }
                
                
            /**
            * Return main theme directory slug
            * 
            * @param mixed $theme_slug
            * @param mixed $all_themes
            */
            function get_main_theme_directory($theme_slug, $all_themes)
                {
                      
                    $theme_data         =   $all_themes[$theme_slug];
                    $theme_directory    =   $theme_slug;
                    
                    if( isset($theme_data['headers']['Template']) &&  !empty($theme_data['headers']['Template']))
                        {
                            $theme_directory    =   $theme_data['headers']['Template'];
                        }        
                    
                    return $theme_directory;
                    
                }
            
            
            /**
            * Recreate a url from a parsed array
            * 
            * @param mixed $parts
            */
            function build_parsed_url( $parse_url )
                {
                    $url    =   (isset($parse_url['scheme']) ? "{$parse_url['scheme']}:" : '') . 
                                ((isset($parse_url['user']) || isset($parse_url['host'])) ? '//' : '') . 
                                (isset($parse_url['user']) ? "{$parse_url['user']}" : '') . 
                                (isset($parse_url['pass']) ? ":{$parse_url['pass']}" : '') . 
                                (isset($parse_url['user']) ? '@' : '') . 
                                (isset($parse_url['host']) ? "{$parse_url['host']}" : '') . 
                                (isset($parse_url['port']) ? ":{$parse_url['port']}" : '') . 
                                (isset($parse_url['path']) ? "{$parse_url['path']}" : '') . 
                                (isset($parse_url['query']) ? "?{$parse_url['query']}" : '') . 
                                (isset($parse_url['fragment']) ? "#{$parse_url['fragment']}" : '');
   
                    return $url;
                    
                }
                
                
            function get_ad_banner()
                {
                    ob_start();
                    ?><div id="info_box">
                         <div id="p_right"> 
                            
                            <div id="p_socialize">
                                
                                <div class="p_s_item s_f">
                                    <div id="fb-root"></div>
                                    <script>(function(d, s, id) {
                                      var js, fjs = d.getElementsByTagName(s)[0];
                                      if (d.getElementById(id)) return;
                                      js = d.createElement(s); js.id = id;
                                      js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
                                      fjs.parentNode.insertBefore(js, fjs);
                                    }(document, 'script', 'facebook-jssdk'));</script>
                                    
                                    <div class="fb-like" data-href="https://www.facebook.com/Nsp-Code-190329887674484/" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
                                    
                                </div>
                                
                                <div class="p_s_item s_t">
                                    <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.wp-hide.com" data-text="Hide and increase Security for your WordPress website instance using smart techniques. No files are being changed." data-count="none">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
                                </div>
                                
                                <div class="p_s_item s_gp">
                                    <!-- Place this tag in your head or just before your close body tag -->
                                    <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>

                                    <!-- Place this tag where you want the +1 button to render -->
                                    <div class="g-plusone" data-size="small" data-annotation="none" data-href="http://nsp-code.com/"></div>
                                </div>
                                
                                <div class="clear"></div>
                            </div>
            
                        </div>
                        <p><?php    _e('Help us to improve this plugin by sending any improvement suggestions and reporting any issues at ', 'wp-hide-security-enhancer')  ?><a target="_blank" href="http://www.wp-hide.com/">www.wp-hide.com</a></p>
                        <p><?php    _e('Did you find this plugin useful? Please support our work by spread the word about this, or write an article about the plugin in your blog with a link to development site', 'wp-hide-security-enhancer') ?> <a href="http://www.wp-hide.com/" target="_blank"><strong>http://www.wp-hide.com/</strong></a></p>
                        
                        <div class="clear"></div>
                    </div><?php
                    
                    $content    =   ob_get_contents();
                    ob_end_clean();
                    
                    return $content;
                    
                }
        
            
        }
        
?>