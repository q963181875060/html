<?php


    class WPH
        {
            var $default_variables      =   array();
            var $templates_data         =   array();
            var $urls_replacement       =   array();
            
            var $server_htaccess_config =   FALSE;
            var $server_web_config      =   FALSE;
            
            var $modules                =   array();
            
            var $settings;
            
            var $functions;
            
            var $disable_filters        =   FALSE;
            var $permalinks_not_applied =   FALSE;
            
            var $doing_interface_save   =   FALSE;
            var $doing_reset_settings   =   FALSE;
            
            var $uninstall              =   FALSE;
            
            var $is_initialised         =   FALSE;
            
            var $conflicts              =   array();
               
            function __construct()
                {
                        
                }
            
            function __destruct()
                {
                
                }
                
                
            function init()
                {
                    $this->functions    =   new WPH_functions();
                    
                    $this->settings     =   $this->functions->get_settings();
                    
                    //set the urls_replacement priority blocks
                    $this->urls_replacement['high']     =   array();
                    $this->urls_replacement['normal']   =   array();
                    $this->urls_replacement['low']      =   array();
                    
                    //check for plugin update
                    $this->update();
                    
                    //set whatever the server use htaccess or web.config configuration file
                    $this->server_htaccess_config   =   $this->functions->server_use_htaccess_config_file();
                    $this->server_web_config        =   $this->functions->server_use_web_config_file();
                    
                    //check for recovery link run
                    if(isset($_GET['wph-recovery']))
                        $this->functions->do_recovery();
                    
                    //check for interface submit
                    if(is_admin()   && isset($_POST['wph-interface-nonce']))
                        {
                            $this->doing_interface_save =   TRUE;
                            $this->disable_filters      =   TRUE;
                        }
                        
                    //check for reset setings
                    if(is_admin()   && isset($_POST['reset-settings']))
                        {
                            $this->doing_reset_settings =   TRUE;
                            $this->disable_filters      =   TRUE;
                        }
                        
                    //check for permalink issues
                    $this->permalinks_not_applied   =   ! $this->functions->rewrite_rules_applied();
                    
                    $this->get_default_variables();
                    
                    $this->_load_modules();
                    
                    $this->add_default_replacements();
                    
                    /**
                    * Filters
                    */
                    add_action( 'activated_plugin', array($this, 'activated_plugin'), 999, 2 );
                    
                    remove_action( 'template_redirect', 'wp_redirect_admin_locations', 1000 );
                    
                    add_action('plugins_loaded',        array($this,    'plugin_conflicts') , -1);
                    
                    //change any links within email message
                    add_filter('wp_mail',               array($this,    'apply_for_wp_mail') , 999);
                    
                    //process redirects
                    add_action('wp_redirect',           array($this,    'wp_redirect') , 999, 2);
                    //hijack a redirect on permalink change
                    add_action('admin_head',            array($this,    'permalink_change_redirect') , 999, 2);
                    
                    add_action('logout_redirect',       array($this,    'logout_redirect') , 999, 3);
                                                            
                    //check if force 404 error
                    add_action('init',        array($this,    'check_for_404'));
                                        
                    add_action('admin_menu',            array($this,    'admin_menus'));
                    add_action('admin_init',            array($this,    'admin_init'), 11);
                    
                                        
                    //rebuild and change uppon settings modified
                    add_action('wph/settings_changed',  array($this,    'settings_changed'));
                    
                    //apache
                    add_filter('mod_rewrite_rules',     array($this,    'mod_rewrite_rules'), 999);
                    //IIS7 server
                    add_filter('iis7_url_rewrite_rules',     array($this,    'iis7_url_rewrite_rules'), 999);
                                        
                    //on switch theme
                    add_action('switch_theme',          array($this,    'switch_theme'));
                    
                    //admin notices
                    add_action( 'admin_notices',        array(&$this,   'admin_notices'));
                    
                    $this->is_initialised       =   TRUE;
                }
            
            
            /**
            * Update wrapper
            * 
            */
            function update()
                {
                    
                    //check for update from older version
                    include_once(WPH_PATH . '/include/update.class.php');
                    new WPH_update();   
                    
                }
                 
            function _load_modules()
                {
                    
                    $module_files   =   glob(WPH_PATH . "/modules/module-*.php");

                    foreach ($module_files as $filename)
                        {
                            $path_parts = pathinfo($filename);
                                                        
                            include_once(WPH_PATH . '/modules/' .   $path_parts['basename']);
                            
                            $module_name = str_replace('module-' , '', $path_parts['filename']);
                            $module_class_name      =   'WPH_module_'   .   $module_name;
                            $module                 =   new $module_class_name;
                            
                            //action available for mu-plugins
                            do_action('wp-hide/loaded_module', $module);
                            
                            $interface_menu_data    =   $module->get_interface_menu_data();
                            $menu_position          =   $interface_menu_data['menu_position'];
                            
                            $this->modules[$menu_position]        =   $module;

                        }
                        
                    //sort the modules array
                    ksort($this->modules);
                    
                    $this->_modules_components_run();
                    
                    //filter available for mu-plugins 
                    $this->modules  =   apply_filters('wp-hide/loaded_modules', $this->modules);
                    
                    //sort the replacement urls ??  by the length
                    //$keys = array_map('strlen', array_keys($arr));
                    //array_multisort($keys, SORT_DESC, $arr);
           
                }
                
            
            /**
            * Runt the components of loaded modules
            * 
            */
            function _modules_components_run()
                {
                    foreach($this->modules  as  $module)
                        {
                            //process the module fields
                            $module_settings  =   $this->functions->filter_settings(   $module->get_module_settings(), TRUE    );
                            
                            usort($module_settings, array($this->functions, 'array_sort_by_processing_order'));
                           
                            
                            if($this->disable_filters   ||  !is_array($module_settings)   || count($module_settings) < 1)
                                continue;
                                
                            foreach($module_settings    as  $module_setting)
                                {
                                    
                                    $field_id           =   $module_setting['id'];
                                    $saved_field_value  =   isset($this->settings['module_settings'][ $field_id ]) ?   $this->settings['module_settings'][ $field_id ]    :   '';
                                    
                                    $_class_instance    =   isset($module_setting['class_instance'])  ?   $module_setting['class_instance'] :   $module;
                                    
                                    //ignore callbacks if permalink is turned OFF
                                    if($this->functions->is_permalink_enabled() &&  $this->permalinks_not_applied   !== TRUE)
                                        {
                                            $_callback          =   isset($module_setting['callback'])  ?   $module_setting['callback'] :   '';
                                            if(empty($_callback))
                                                $_callback      =   '_init_'    .   $field_id;
                                            
                                            if (method_exists($_class_instance, $_callback)   && is_callable(array($_class_instance, $_callback)))
                                                $processing_data[]  =   call_user_func(array($_class_instance, $_callback), $saved_field_value);
                                        }
                                    
                                    //action available for mu-plugins    
                                    do_action('wp-hide/module_settings_process', $field_id, $saved_field_value, $_class_instance, $module);
                                }   
                        
                        }
                    
                }
            
                
                
            /**
            * run on admin_init action
            *     
            */
            function admin_init()
                {
                    //check for settings reset
                    if($this->doing_reset_settings  === TRUE)
                        {
                            $this->functions->reset_settings();
                        }
                    
                    //check for interface submit
                    if($this->doing_interface_save  === TRUE)
                        {
                            $this->functions->process_interface_save();
                        }
                }
            
            
            function admin_print_styles()
                {
                    
                    wp_register_style('WPHStyle', WPH_URL . '/css/wph.css');
                    wp_enqueue_style( 'WPHStyle'); 
                
                }
                
                
            function admin_print_scripts()
                {
                    
                    wp_register_script('wph', WPH_URL . '/js/wph.js');
                    
                    // Localize the script with new data
                    $translation_array = array(
                                            'reset_confirmation' => __('Are you sure to reset all settings? All options will be removed.',    'wp-hide-security-enhancer')
                                        );
                    wp_localize_script( 'wph', 'wph_vars', $translation_array );
                    
                    wp_enqueue_script( 'wph'); 
                
                }
                
                
            function admin_menus()
                {
                    include_once(WPH_PATH . '/include/admin-interface.class.php');
            
                    $this->admin_interface =    new WPH_interface(); 
                       
                    $hookID   =   add_menu_page('WP Hide', 'WP Hide', 'manage_options', 'wp-hide');
         
                    foreach($this->modules   as  $module)
                        {
                            $interface_menu_data    =   $module->get_interface_menu_data();
                                                    
                            $hookID   =             add_submenu_page( 'wp-hide', 'WP Hide', $interface_menu_data['menu_title'], 'manage_options', $interface_menu_data['menu_slug'], array($this->admin_interface,'_render'));
                            
                            add_action('admin_print_styles-' . $hookID ,    array($this, 'admin_print_styles'));
                            add_action('admin_print_scripts-' . $hookID ,   array($this, 'admin_print_scripts'));
                        }   
                    
                }
                
            
            function admin_notices()
                {
                    global $wp_rewrite;
                    
                    
                    //check for permalinks enabled
                    if (!$this->functions->is_permalink_enabled())
                        {
                            echo "<div class='error'><p>". __('Permalink is required to be turned ON for WP Hide & Security Enhancer to work', 'wp-hide-security-enhancer')  ."</p></div>";
                        }
                       
                    //check if the htaccess file is not writable
                    if(isset($this->settings['write_check_string']) &&  !empty($this->settings['write_check_string']))
                        {                            
                            $_write_check_string =   $this->functions->get_write_check_string();
                            if(empty($_write_check_string)  ||  $_write_check_string    !=  $this->settings['write_check_string'])
                                {
                                    if($this->server_htaccess_config    === TRUE)
                                        echo "<div class='error'><p>". __('Unable to write custom rules to your .htaccess. Is this file writable? <br />No mod is being applied.', 'wp-hide-security-enhancer')  ."</p></div>";
                                    
                                    if($this->server_web_config     === TRUE)
                                        echo "<div class='error'><p>". __('Unable to write custom rules to your web.config. Is this file writable? <br />No mod is being applied.', 'wp-hide-security-enhancer')  ."</p></div>";
                                }
                        }
                    
                    if(isset($_GET['reset_settings']))
                        {
                            echo "<div class='updated'><p>". __('All Settings where restored to default', 'wp-hide-security-enhancer')  ."</p></div>";
                            
                            $this->functions->settings_changed_check_for_cache_plugins();
                        }
                    
                        
                    if(isset($_GET['settings_updated']))
                        {
                            
                            //check for write permision
                            if($this->server_htaccess_config    === TRUE    &&  !$this->functions->is_writable_htaccess_config_file())
                                echo "<div class='error'><p>". __('Unable to write custom rules to your .htaccess. Is this file writable? <br />No mod is being applied.', 'wp-hide-security-enhancer')  ."</p></div>";
                            
                            if($this->server_web_config    === TRUE    &&  !$this->functions->is_writable_web_config_file())
                                echo "<div class='error'><p>". __('Unable to write custom rules to your web.config. Is this file writable? <br />No mod is being applied.', 'wp-hide-security-enhancer')  ."</p></div>";
                            
                            echo "<div class='updated'><p>". __('Settings saved', 'wp-hide-security-enhancer')  ."</p></div>";
                            
                            $this->functions->settings_changed_check_for_cache_plugins();
                        }
                    
                }
                        
            /**
            * Buffer Callback. This is the place to replace all data
            *     
            * @param mixed $buffer
            */
            function ob_start_callback( $buffer )
                {
                    
                    //check headers fir content-encoding
                    if(function_exists('apache_response_headers'))
                        {
                            $response_headers    =   apache_response_headers();
                        }
                        else  
                            {
                                $response_headers = $this->functions->parseRequestHeaders();
                            }
                            
                    if(isset($response_headers['Content-Encoding']) &&  $response_headers['Content-Encoding']   ==  "gzip")
                        {
                            //Decodes the gzip compressed buffer
                            $decoded    =   gzdecode($buffer);
                            if($decoded === FALSE   ||  $decoded    ==  '')
                                return $buffer;
                                
                            $buffer =   $decoded;
                        }
                                            
                    //replace the urls
                    $buffer =   $this->functions->content_urls_replacement($buffer,  $this->functions->get_replacement_list() );
                    
                    $buffer = apply_filters( 'wph/ob_start_callback', $buffer ); 
                    
                    if(isset($response_headers['Content-Encoding']) &&  $response_headers['Content-Encoding']   ==  "gzip")
                        {
                            //compress the buffer
                            $buffer    =   gzencode($buffer);
                        }
                        
                    return $buffer;
            
                }
            
            /**
            * check for any query and headers change
            * 
            */
            function check_for_404()
                {
                    if(!isset($_GET['wph-throw-404']))
                        return;
                        
                    global $wp_query;

                    $wp_query->set_404();
                    status_header(404);
                    
                    add_action('request',               array($this, 'change_request'), 999);
                    
                    remove_action( 'template_redirect', 'wp_redirect_admin_locations', 999 ); 
                                        
                }
                
            
            /**
            * Modify the request data to allow a 404 error page to trigger
            * 
            * @param mixed $query_vars
            */
            function change_request($query_vars)
                {
                    
                    return array();
                       
                }
                
            
            /**
            * The plugin always need to load first to ensure filters are loading before anything else
            * 
            */  
            function activated_plugin($plugin, $network_wide)
                {
                    if($network_wide)
                        {
                            $active_plugins = get_site_option( 'active_sitewide_plugins', array() );
                            
                            
                            
                            //$active_plugins = get_site_option( 'active_sitewide_plugins', array() );
                            
                            return;
                        }
                    
                    
                    $active_plugins = (array) get_option( 'active_plugins', array() );
                        
                    if(count($active_plugins)   <   2)
                        return;
                    
                    $plugin_path    =   'wp-hide-security-enhancer/wp-hide.php';
                    
                    $key            = array_search( $plugin_path, $active_plugins );
                    if($key === FALSE   ||  $key    <   1)
                        return;

                    array_splice    ( $active_plugins, $key, 1 );
                    array_unshift   ( $active_plugins, $plugin_path );
                    
                    update_option( 'active_plugins', $active_plugins );
                    
                }
            
            
            function wp_redirect($location, $status)
                {
                    if($this->uninstall === TRUE)
                        return $location;
                    
                    //do not replace 404 pages
                    global $wp_the_query;
                    if($wp_the_query->is_404())
                        return $location;
                        
                    $location =   $this->functions->content_urls_replacement($location,  $this->functions->get_replacement_list() );
                        
                    return $location; 
                }
            
            function logout_redirect($redirect_to, $requested_redirect_to, $user)
                {
                    $new_wp_login_php     =   $this->functions->get_module_item_setting('new_wp_login_php'  ,   'admin');
                    if (empty(  $new_wp_login_php ))
                        return $redirect_to;
                                        
                    $redirect_to =   str_replace('wp-login.php',  $new_wp_login_php,  $redirect_to);
                        
                    return $redirect_to; 
                }
                
            function generic_string_replacement($text)
                {
                    $text   =   $this->functions->content_urls_replacement($text,  $this->functions->get_replacement_list() );
                        
                    return $text;   
                    
                }
                     
            function get_setting_value($setting_name, $default_value    =   '')
                {
                    $setting_value  =   isset($this->settings['module_settings'][$setting_name])    ?   $this->settings['module_settings'][$setting_name]   :   $default_value;
                    
                    return $setting_value;
                }
                
                
            function settings_changed()
                {
                    //allow rewrite
                    flush_rewrite_rules(); 
                }
                
            function mod_rewrite_rules( $rules  )
                {
                    if($this->uninstall === TRUE)
                        return $rules;
                    
                    $processing_data    =   $this->get_components_rules();
                                           
                    //post-process the htaccess data    
                    $_rewrite_data =   array();
                    $_page_refresh  =   FALSE;
                    foreach($processing_data    as  $response)
                        {
                            if(isset($response['rewrite']) &&  !empty($response['rewrite']))
                                {
                                    $_rewrite_data[]   =   $response['rewrite'];
                                }
                                
                            if(isset($response['page_refresh']) &&  $response['page_refresh']   === TRUE)
                                $_page_refresh  =   TRUE;
                        }
                    
                    $write_check_string  =   time() . '_' . mt_rand(100, 99999);
                    $this->settings['write_check_string']   =   $write_check_string;
                    $this->functions->update_settings($this->settings);
                    
                    $new_rules  =   "RewriteRule ^index\.php$ - [L] \n\n#START - WP Hide & Security Enhancer\n#WriteCheckString:" . $write_check_string;
                    $new_rules  .=  "\n<IfModule mod_env.c>\nSetEnv HTTP_MOD_REWRITE On\n</IfModule>";
                    if(count($_rewrite_data)   >   0)
                        {
                            foreach($_rewrite_data as  $_htaccess_data_line)   
                                {
                                    $new_rules .=   "\n"    .   $_htaccess_data_line;
                                }                            
                        }
                    
                    $new_rules  .=  "\n#END - WP Hide & Security Enhancer\n";
                            
                    $new_rules      =   apply_filters('wp-hide/mod_rewrite_rules', $new_rules);
                        
                    //update the main rule variable
                    $rules  =   str_replace('RewriteRule ^index\\.php$ - [L]',  $new_rules, $rules);
                                           
                    return $rules;  
                     
                }
                
                
            function get_components_rules()
                {
                    
                    $processing_data    =   array();
                        
                    //loop all module settings and run the callback functions
                    foreach($this->modules   as  $module)
                        {
                            $module_settings  =   $this->functions->filter_settings(   $module->get_module_settings(), TRUE    );
                            
                            //sort by processing order
                            usort($module_settings, array($this->functions, 'array_sort_by_processing_order'));
                            
                            if(is_array($module_settings)   && count($module_settings) > 0)
                            foreach($module_settings    as  $module_setting)
                                {
                                    
                                    $field_id           =   $module_setting['id'];
                                    $saved_field_value  =   isset($this->settings['module_settings'][ $field_id ]) ?   $this->settings['module_settings'][ $field_id ]    :   '';
                                                                   
                                    $_class_instance    =   isset($module_setting['class_instance'])  ?   $module_setting['class_instance'] :   $module;
                                    $_callback          =   isset($module_setting['callback_saved'])  ?   $module_setting['callback_saved'] :   '';
                                    if(empty($_callback))
                                        $_callback      =   '_callback_saved_'    .   $field_id;
                                    
                                    if (method_exists($_class_instance, $_callback)   && is_callable(array($_class_instance, $_callback)))
                                        {
                                            $module_mod_rewrite_rules   =   call_user_func(array($_class_instance, $_callback), $saved_field_value);
                                            $module_mod_rewrite_rules   =   apply_filters('wp-hide/module_mod_rewrite_rules', $module_mod_rewrite_rules, $_class_instance);
                                            
                                            $processing_data[]          =   $module_mod_rewrite_rules;
                                        }
                                        
                                }
                        }
                        
                        
                    return $processing_data;
                    
                }
                
                
            function iis7_url_rewrite_rules( $wp_rules )
                {
                    $home_path          = get_home_path();
                    $web_config_file    = $home_path . 'web.config';
                    
                    //delete all WPH rules
                    $this->iis7_delete_rewrite_rules($web_config_file);
                    
                    if($this->uninstall === TRUE)
                        return $wp_rules;                        
                    
                    $processing_data    =   $this->get_components_rules();
                                           
                    //post-process the htaccess data    
                    $_rewrite_data =   array();
                    $_page_refresh  =   FALSE;
                    foreach($processing_data    as  $response)
                        {
                            if(isset($response['rewrite']) &&  !empty($response['rewrite']))
                                {
                                    $_rewrite_data[]   =   $response['rewrite'];
                                }
                                
                            if(isset($response['page_refresh']) &&  $response['page_refresh']   === TRUE)
                                $_page_refresh  =   TRUE;
                        }
                    
                    $write_check_string  =   time() . '_' . mt_rand(100, 99999);
                    $this->settings['write_check_string']   =   $write_check_string;
                    $this->functions->update_settings($this->settings);
                                
                    //add a write stricng
                    $_writestring_rule  =   '
                        <rule name="wph-CheckString">
                            <!-- WriteCheckString:'. $write_check_string  .' -->
                        </rule>';
                    array_unshift($_rewrite_data, $_writestring_rule);
                               
                    $this->iis7_add_rewrite_rule( $_rewrite_data, $web_config_file );

                    return $wp_rules;
                    
                }
                
                
           
            /**
            * Add a rewrite rule within specified file
            * 
            * @param mixed $filename
            */
            function  iis7_add_rewrite_rule( $rules, $filename )
                {
                    
                    if (!is_array($rules)    ||  count($rules)   <   1)
                        return false;
                    
                    if ( ! class_exists( 'DOMDocument', false ) ) {
                        return false;
                    }

                    // If configuration file does not exist then we create one.
                    if ( ! file_exists($filename) ) {
                        $fp = fopen( $filename, 'w');
                        fwrite($fp, '<configuration/>');
                        fclose($fp);
                    }
                    
                    $doc = new DOMDocument();
                    $doc->preserveWhiteSpace = false;

                    if ( $doc->load($filename) === false )
                        return false;

                    $xpath = new DOMXPath($doc);
        
                    // Check the XPath to the rewrite rule and create XML nodes if they do not exist
                    $xmlnodes = $xpath->query('/configuration/system.webServer/rewrite/rules');
                    if ( $xmlnodes->length > 0 ) {
                        $rules_node = $xmlnodes->item(0);
                    } else {
                        $rules_node = $doc->createElement('rules');

                        $xmlnodes = $xpath->query('/configuration/system.webServer/rewrite');
                        if ( $xmlnodes->length > 0 ) {
                            $rewrite_node = $xmlnodes->item(0);
                            $rewrite_node->appendChild($rules_node);
                        } else {
                            $rewrite_node = $doc->createElement('rewrite');
                            $rewrite_node->appendChild($rules_node);

                            $xmlnodes = $xpath->query('/configuration/system.webServer');
                            if ( $xmlnodes->length > 0 ) {
                                $system_webServer_node = $xmlnodes->item(0);
                                $system_webServer_node->appendChild($rewrite_node);
                            } else {
                                $system_webServer_node = $doc->createElement('system.webServer');
                                $system_webServer_node->appendChild($rewrite_node);

                                $xmlnodes = $xpath->query('/configuration');
                                if ( $xmlnodes->length > 0 ) {
                                    $config_node = $xmlnodes->item(0);
                                    $config_node->appendChild($system_webServer_node);
                                } else {
                                    $config_node = $doc->createElement('configuration');
                                    $doc->appendChild($config_node);
                                    $config_node->appendChild($system_webServer_node);
                                }
                            }
                        }
                    }

                    //append before other rules
                    $ref_node   =   $xpath->query('/configuration/system.webServer/rewrite/rules/rule[starts-with(@name,\'wordpress\')] | /configuration/system.webServer/rewrite/rules/rule[starts-with(@name,\'WordPress\')]');
                         
                    foreach($rules  as  $rule)
                        {
                            $rule_fragment = $doc->createDocumentFragment();
                            $rule_fragment->appendXML($rule);
                            
                            if($ref_node->length > 0)
                                $rules_node->insertBefore($rule_fragment, $ref_node->item(0));
                                else
                                $rules_node->appendChild($rule_fragment);
                        }

                    $doc->encoding = "UTF-8";
                    $doc->formatOutput = true;
                    saveDomDocument($doc, $filename);
             
                    return true;   
                    
                    
                }
           
           
           
            /**
            * Delete all wph rules within specified filename
            * 
            * @param mixed $filename
            */
            function iis7_delete_rewrite_rules( $filename )
                {
                    
                    if ( ! file_exists($filename) )
                        return true;

                    if ( ! class_exists( 'DOMDocument', false ) ) {
                        return false;
                    }

                    $doc = new DOMDocument();
                    $doc->preserveWhiteSpace = false;

                    if ( $doc -> load($filename) === false )
                        return false;
                    $xpath = new DOMXPath($doc);
                    $rules = $xpath->query('/configuration/system.webServer/rewrite/rules/rule[starts-with(@name,\'wph\')]');
                    if ( $rules->length > 0 ) 
                        {
                            
                            foreach($rules  as  $child)
                                {
                                    $parent = $child->parentNode;
                                    $parent->removeChild($child);        
                                }
                            
                            $doc->formatOutput = true;
                            saveDomDocument($doc, $filename);
                        }
                               
                    return true;   
                    
                }
                
            
            
            function get_default_variables()
                {   
                    $this->default_variables['include_url']         =   trailingslashit(    site_url()  )  . WPINC;
                    
                    $this->default_variables['template_url']        =   get_bloginfo('template_url');
                    $this->default_variables['stylesheet_uri']      =   get_stylesheet_directory_uri();
                    
                    $this->default_variables['plugins_url']         =   plugins_url();
                    
                    $wp_upload_dir  =   wp_upload_dir();
                    $this->default_variables['upload_url']          =   $wp_upload_dir['baseurl'];
                    
                    //used across modules
                    $this->default_variables['site_relative_path']  =   $this->functions->get_url_path( trailingslashit(    site_url()  ));
                    
                    //themes url
                    $this->templates_data['themes_url']                 =   trailingslashit(    get_theme_root_uri()    );
                    
                    $all_templates  =   $this->functions->get_themes();
                    $all_templates  =   $this->functions->parse_themes_headers($all_templates);
                    
                    $stylesheet     =   get_option( 'stylesheet' );
                                        
                    $this->templates_data['use_child_theme']            =   $this->functions->is_child_theme($stylesheet, $all_templates);
                    
                    $main_theme_directory                               =   $this->functions->get_main_theme_directory($stylesheet, $all_templates);
                    $this->templates_data['main']                       =   array();
                    $this->templates_data['main']['folder_name']        =   $main_theme_directory;
                    $this->templates_data['_template_' .  $main_theme_directory]    =   'main';
                    
                    if($this->templates_data['use_child_theme'])
                        {
                            $this->templates_data['child']         =   array();        
                            $this->templates_data['child']['folder_name']  =   $stylesheet;
                            $this->templates_data['_template_' .  $stylesheet]    =   'child';
                        }
                    
                    //catch the absolute siteurl in case wp folder is different than domain root
                    $this->default_variables['wordpress_directory']    =   '';
                    $this->default_variables['content_directory']      =   '';
                    $this->default_variables['plugins_directory']      =   '';
                    
                    
                    //content_directory
                    $this->default_variables['content_directory']   =   str_replace(ABSPATH, "", WP_CONTENT_DIR);
                    
                    $home_url   =   defined('WP_HOME')  ?   WP_HOME         :   get_option('home');
                    $home_url   =   untrailingslashit($home_url);
                    
                    $siteurl    =   defined('WP_HOME')  ?   WP_SITEURL      :   get_option('siteurl');
                    $siteurl    =   untrailingslashit($siteurl);
                    
                    $wp_directory   =   str_replace($home_url, "" , $siteurl);
                    $wp_directory   =   trim(trim($wp_directory), '/');
                    
                    if($wp_directory    !=  '')
                        {
                            $this->default_variables['wordpress_directory'] =   $wp_directory;
                            
                            $domain_ABSPATH =   str_replace($wp_directory, "", untrailingslashit( realpath( ABSPATH )));
                            
                            $content_directory  = str_replace($domain_ABSPATH, "" , untrailingslashit( realpath( WP_CONTENT_DIR )));
                            $this->default_variables['content_directory']   =   $content_directory;                            
                        }
                    
                }
                
            
            /**
            * Apply new changes for e-mail content too
            * 
            * @param mixed $atts
            */
            function apply_for_wp_mail($atts)
                {
                    
                    $atts['message'] =   $this->functions->content_urls_replacement($atts['message'],  $this->functions->get_replacement_list() );
                       
                    return $atts;
                       
                }
                
            
            /**
            * Add default Url Replacements
            * 
            */
            function add_default_replacements()
                {
                    
                    do_action('wp-hide/add_default_replacements', $this->urls_replacement);   
                }
       
                
            function switch_theme()
                {
                    $this->disable_filters  =   TRUE;
                    $this->get_default_variables();
                    
                    //allow rewrite
                    flush_rewrite_rules();
                    
                    $this->disable_filters  =   FALSE;    
                }
                
            function permalink_change_redirect()
                {
                    $screen = get_current_screen();
                    
                    if(empty($screen))
                        return;
                       
                    if($screen->base    !=  "options-permalink")
                        return;
                    
                    //recheck if the permalinks where sucesfully saved
                    $this->permalinks_not_applied   =   ! $this->functions->rewrite_rules_applied();
                    
                    //ignore if permalinks are available
                    if($this->permalinks_not_applied   === TRUE)
                        return;
                                        
                    $new_location   =   trailingslashit(    site_url()  )   . "wp-admin/options-permalink.php";   
                    
                    if($this->functions->is_permalink_enabled())
                        {
                            $new_admin_url     =   $this->functions->get_module_item_setting('admin_url'  ,   'admin');
                            if(!empty($new_admin_url))
                                $new_location      =   trailingslashit(    site_url()  )   . $new_admin_url .  "/options-permalink.php";
                        }
                        
                    $new_location   .=  '?settings-updated=true';
                    
                    //no need to redirect if it's on the same path
                    $request_uri    =   $_SERVER['REQUEST_URI'];
                    
                    $new_location_uri   =   $this->functions->get_url_path($new_location, TRUE);
                    if($request_uri ==  $new_location_uri)
                        return;
                    
                    wp_redirect( $new_location  );
                    die();
                }
            
            
            /**
            * General Plugins Conflicts Handle
            *     
            */
            function plugin_conflicts()
                {
                    
                    //w3-cache conflicts handle
                    include_once(WPH_PATH . 'conflicts/w3-cache.php');
                    WPH_conflict_handle_w3_cache::pagecache();
                    
                    //super-cache conflicts handle
                    include_once(WPH_PATH . 'conflicts/super-cache.php');
                    WPH_conflict_handle_super_cache::init();                    
                    
                }
                
                
                
            function log_save($text)
                {
                    
                    $myfile     = fopen(WPH_PATH . "/debug.txt", "a") or die("Unable to open file!");
                    $txt        =  $text   .   "\n";
                    fwrite($myfile, $txt);
                    fclose($myfile);   
                    
                }

            
        } 


?>