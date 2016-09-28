<?php

    class WPH_module_rewrite_root_files extends WPH_module_component
        {
            
            function get_component_title()
                {
                    return "Root Files";
                }
                                                
            function get_module_settings()
                {
                    $this->module_settings[]                  =   array(
                                                                        'id'            =>  'new_wp_comments_post',
                                                                        'label'         =>  __('New wp-comments-post.php Path',    'wp-hide-security-enhancer'),
                                                                        'description'   =>  __('The default path is set to wp-comments-post.php',    'wp-hide-security-enhancer'),
                                                                        
                                                                        'value_description' =>  'e.g. user-input.php',
                                                                        'input_type'    =>  'text',
                                                                        
                                                                        'sanitize_type' =>  array(array($this->wph->functions, 'sanitize_file_path_name'), array($this->wph->functions, 'php_extension_required')),
                                                                        'processing_order'  =>  60
                                                                        );
                                                                    
                    $this->module_settings[]                  =   array(
                                                                        'id'            =>  'block_wp_comments_post_url',
                                                                        'label'         =>  __('Block wp-comments-post.php',    'wp-hide-security-enhancer'),
                                                                        'description'   =>  __('Block default wp-comments-post.php.',    'wp-hide-security-enhancer') . '<br />'.__('Apply only if ',    'wp-hide-security-enhancer') . '<b>New wp-comments-post.php Path</b> ' . __('is not empty.',    'wp-hide-security-enhancer'),
                                                                        
                                                                        'input_type'    =>  'radio',
                                                                        'options'       =>  array(
                                                                                                    'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                    'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                    ),
                                                                        'default_value' =>  'no',
                                                                        
                                                                        'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                        'processing_order'  =>  60
                                                                        
                                                                        );
                    
                    
                    $this->module_settings[]                  =   array(
                                                                        'type'            =>  'split'
                                                                        
                                                                        );
                    
                    
                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'block_license_txt',
                                                                    'label'         =>  __('Block license.txt',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('Block access to license.txt root file',    'wp-hide-security-enhancer'),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  60
                                                                    );
                                                                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'block_readme_html',
                                                                    'label'         =>  __('Block readme.html',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('Block access to readme.html root file',    'wp-hide-security-enhancer'),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  60
                                                                    );
                                                                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'block_wp_activate_php',
                                                                    'label'         =>  __('Block wp-activate.php',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('Block access to wp-activate.php file. This file confirms that the activation key that is sent in an email after a user signs up for a new blog matches the key for that user. If <b>anyone can register</b> on your site, you shold keep this off.',    'wp-hide-security-enhancer'),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  60
                                                                    );
                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'block_wp_cron_php',
                                                                    'label'         =>  __('Block wp-cron.php',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('Block access to wp-cron.php file. If remote cron calls not being used this can be set to Yes.',    'wp-hide-security-enhancer'),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  60
                                                                    );
                                                                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'block_default_wp_signup_php',
                                                                    'label'         =>  'Block wp-signup.php',
                                                                    'description'   =>  __('Block default wp-signup.php file. If <b>anyone can register</b> on your site, you shold keep this off.',  'wp-hide-security-enhancer'),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  55
                                                                    
                                                                    );
                                                                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'block_other_wp_files',
                                                                    'label'         =>  'Block other wp-*.php files',
                                                                    'description'   =>  __('Block other wp-*.php files. E.g. wp-blog-header.php, wp-config.php, wp-cron.php. Those files are used internally, blocking those will not affect any functionality. Other root files (wp-activate.php, wp-login.php, wp-signup.php) are ignored, they can be controlled through own setting.',  'wp-hide-security-enhancer'),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  55
                                                                    
                                                                    );
                                                                    
                    return $this->module_settings;   
                }
                
            
            
            function _init_new_wp_comments_post($saved_field_data)
                {
                   
                    if(empty($saved_field_data))
                        return FALSE;
                    
                    //add default plugin path replacement
                    $url            =   trailingslashit(    site_url()  ) .  'wp-comments-post.php';
                    $replacement    =   trailingslashit(    site_url()  ) .  $saved_field_data;
                    $this->wph->functions->add_replacement( $url , $replacement );
                    
                    return TRUE;
                }
                
            function _callback_saved_new_wp_comments_post($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                                                            
                    $default_path   =   $this->wph->functions->get_url_path( trailingslashit(site_url()) . 'wp-comments-post.php', TRUE   );
                               
                    if($this->wph->server_htaccess_config   === TRUE)
                        $processing_response['rewrite'] = "\nRewriteRule ^"    .   $saved_field_data   .   ' '. $default_path .' [L,QSA]'; 
                    
                    if($this->wph->server_web_config   === TRUE)
                        $processing_response['rewrite'] = '
                            <rule name="wph-new_wp_comments_post" stopProcessing="true">
                                <match url="^'.  $saved_field_data   .'"  />
                                <action type="Rewrite" url="'.  $default_path .'"  appendQueryString="true" />
                            </rule>
                                                            ';
                                
                    return  $processing_response;     
                    
                    
                }
            
            
            function _callback_saved_block_wp_comments_post_url($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                    
                    //prevent from blocking if the wp_comments_post is not modified
                    $new_wp_comments_post     =   ltrim(rtrim($this->wph->functions->get_module_item_setting('new_wp_comments_post'), "/"),  "/");
                    if (empty(  $new_wp_comments_post ))
                        return FALSE;
                    
                    if($this->wph->server_htaccess_config   === TRUE)
                        {                                        
                            $text   =   "RewriteCond %{ENV:REDIRECT_STATUS} ^$\n";
                            $text   .=   "RewriteRule ^wp-comments-post.php ".  $this->wph->default_variables['site_relative_path'] ."index.php [L]";
                        }
                        
                    if($this->wph->server_web_config   === TRUE)
                            $text   = '
                                        <rule name="wph-block_wp_comments_post_url" stopProcessing="true">
                                            <match url="^wp-comments-post.php"  />
                                            <action type="Rewrite" url="'.  $this->wph->default_variables['site_relative_path'] .'index.php" />  
                                        </rule>
                                                            ';
                               
                    $processing_response['rewrite'] = $text;            
                                
                    return  $processing_response;     
                    
                    
                }
                
                
            function _callback_saved_block_license_txt($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                                                            
                    if($this->wph->server_htaccess_config   === TRUE)
                        {
                            $text   =   "RewriteCond %{ENV:REDIRECT_STATUS} ^$\n";
                            $text   .=   "RewriteRule ^license.txt ".  $this->wph->default_variables['site_relative_path'] ."index.php [L]";
                        }
                    
                    if($this->wph->server_web_config   === TRUE)
                            $text   = '
                                        <rule name="wph-block_license_txt" stopProcessing="true">
                                            <match url="^license.txt"  />
                                            <action type="Rewrite" url="'.  $this->wph->default_variables['site_relative_path'] .'index.php" />  
                                        </rule>
                                                            ';
                               
                    $processing_response['rewrite'] = $text;            
                                
                    return  $processing_response;     
                    
                    
                }
                
            function _callback_saved_block_readme_html($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                    
                    if($this->wph->server_htaccess_config   === TRUE)
                        {                                        
                            $text   =   "RewriteCond %{ENV:REDIRECT_STATUS} ^$\n";
                            $text   .=   "RewriteRule ^readme.html ".  $this->wph->default_variables['site_relative_path'] ."index.php [L]";
                        }
                    
                    if($this->wph->server_web_config   === TRUE)
                            $text   = '
                                        <rule name="wph-block_readme_html" stopProcessing="true">
                                            <match url="^readme.html"  />
                                            <action type="Rewrite" url="'.  $this->wph->default_variables['site_relative_path'] .'index.php" />  
                                        </rule>
                                                            ';
                               
                    $processing_response['rewrite'] = $text;            
                                
                    return  $processing_response;     
                    
                    
                }
                
            function _callback_saved_block_wp_activate_php($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                    
                    if($this->wph->server_htaccess_config   === TRUE)
                        {                                        
                            $text   =   "RewriteCond %{ENV:REDIRECT_STATUS} ^$\n";
                            $text   .=   "RewriteRule ^wp-activate.php ".  $this->wph->default_variables['site_relative_path'] ."index.php [L]";
                        }
                    
                    if($this->wph->server_web_config   === TRUE)
                            $text   = '
                                        <rule name="wph-block_wp_activate_php" stopProcessing="true">
                                            <match url="^wp-activate.php"  />
                                            <action type="Rewrite" url="'.  $this->wph->default_variables['site_relative_path'] .'index.php" />  
                                        </rule>
                                                            ';
                               
                    $processing_response['rewrite'] = $text;            
                                
                    return  $processing_response;     
                    
                    
                }
                
                
            function _callback_saved_block_wp_cron_php($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                    
                    if($this->wph->server_htaccess_config   === TRUE)
                        {                                        
                            $text   =   "RewriteCond %{ENV:REDIRECT_STATUS} ^$\n";
                            $text   .=   "RewriteRule ^wp-cron.php ".  $this->wph->default_variables['site_relative_path'] ."index.php [L]";
                        }
                    
                    if($this->wph->server_web_config   === TRUE)
                            $text   = '
                                        <rule name="wph-block_wp_cron_php" stopProcessing="true">
                                            <match url="^wp-cron.php"  />
                                            <action type="Rewrite" url="'.  $this->wph->default_variables['site_relative_path'] .'index.php" />  
                                        </rule>
                                                            ';
                               
                    $processing_response['rewrite'] = $text;            
                                
                    return  $processing_response;     
                    
                    
                }
                
            function _callback_saved_block_default_wp_signup_php($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return  $processing_response;
                    
                    if($this->wph->server_htaccess_config   === TRUE)
                        {                              
                            $text   =       "RewriteCond %{ENV:REDIRECT_STATUS} ^$\n";
                            $text   .=      "RewriteRule ^wp-signup.php ".  $this->wph->default_variables['site_relative_path'] ."index.php [L]";
                        }
                        
                    if($this->wph->server_web_config   === TRUE)
                            $text   = '
                                        <rule name="wph-block_default_wp_signup_php" stopProcessing="true">
                                            <match url="^wp-signup.php"  />
                                            <action type="Rewrite" url="'.  $this->wph->default_variables['site_relative_path'] .'index.php" />  
                                        </rule>
                                                            ';
                               
                    $processing_response['rewrite'] = $text;    
                                                    
                    return  $processing_response;   
                }

            function _callback_saved_block_other_wp_files($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return  $processing_response;
                    
                    if($this->wph->server_htaccess_config   === TRUE)
                        {                              
                            $text   =       "RewriteCond %{ENV:REDIRECT_STATUS} ^$\n";
                            $text   .=       "RewriteCond %{REQUEST_FILENAME} -f\n";

                            $text   .=       "RewriteCond %{REQUEST_FILENAME} !wp-activate.php [NC]\n";
                            $text   .=       "RewriteCond %{REQUEST_FILENAME} !wp-cron.php [NC]\n";
                            $text   .=       "RewriteCond %{REQUEST_FILENAME} !wp-signup.php [NC]\n";
                            $text   .=       "RewriteCond %{REQUEST_FILENAME} !wp-comments-post.php [NC]\n";
                            $text   .=       "RewriteCond %{REQUEST_FILENAME} !wp-login.php [NC]\n";
                            
                            $text   .=      "RewriteRule ^wp-([a-z-])+.php ".  $this->wph->default_variables['site_relative_path'] ."index.php [L]";
                        }
                        
                    if($this->wph->server_web_config   === TRUE)
                        $text = '
                            <rule name="wph-block_other_wp_files" stopProcessing="true">  
                                    <match url="^wp-([a-z-])+.php" />  
                                    <conditions>  
                                        <add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="true" />
                                        <add input="{REQUEST_FILENAME}" pattern="wp-activate.php" ignoreCase="true" negate="true" />
                                        <add input="{REQUEST_FILENAME}" pattern="wp-cron.php" ignoreCase="true" negate="true" />
                                        <add input="{REQUEST_FILENAME}" pattern="wp-signup.php" ignoreCase="true" negate="true" />
                                        <add input="{REQUEST_FILENAME}" pattern="wp-comments-post.php" ignoreCase="true" negate="true" />
                                        <add input="{REQUEST_FILENAME}" pattern="wp-login.php" ignoreCase="true" negate="true" />
                                    </conditions>  
                                    <action type="Rewrite" url="'.  $this->wph->default_variables['site_relative_path'] .'index.php" />  
                                </rule>
                                                            ';
                               
                    $processing_response['rewrite'] = $text;    
                                                    
                    return  $processing_response;   
                }
        }
?>