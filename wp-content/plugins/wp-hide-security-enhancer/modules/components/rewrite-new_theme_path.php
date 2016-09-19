<?php

    class WPH_module_rewrite_new_theme_path extends WPH_module_component
        {
            
            function get_component_title()
                {
                    return "Theme";
                }
            
                                     
            function get_module_settings()
                {
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'new_theme_path',
                                                                    'label'         =>  __('New Theme Path',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('The default theme path is set to',    'wp-hide-security-enhancer') . ' <strong>'. str_replace(get_bloginfo('wpurl'), '' ,$this->wph->default_variables['template_url'])  .'</strong>
                                                                                            '. __('More details can be found at',    'wp-hide-security-enhancer') .' <a href="http://www.nsp-code.com" target="_blank">Link</a>',
                                                                    
                                                                    'value_description' =>  __('e.g. my_template',    'wp-hide-security-enhancer'),
                                                                    'input_type'    =>  'text',
                                                                    
                                                                    'sanitize_type' =>  array(array($this->wph->functions, 'sanitize_file_path_name')),
                                                                    'processing_order'  =>  10
                                                                    );
                    
                                        
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'new_style_file_path',
                                                                    'label'         =>  __('New Style File Path',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('The default theme style file style.css path is set to',    'wp-hide-security-enhancer') . ' <strong>'. str_replace(get_bloginfo('wpurl'), '' ,   $this->wph->default_variables['template_url'])  .'/style.css</strong>'
                                                                                        .'<div class="description"><div class="notice-error"><div alt="f534" class="dashicons dashicons-warning">warning</div> <span class="important">'.   __('If style file contain relative URLs it should not include additional path, just the actual filename.',    'wp-hide-security-enhancer')   .'. '. __('More details at',    'wp-hide-security-enhancer') .' <a href="http://www.wp-hide.com/new-style-file-path-along-relative-urls/" target="_blank">New Style File Path along with relative URLs</a></span></div></div>'
                                                                    ,
                                                                    
                                                                    'value_description' =>  __('e.g. custom-style-file.css',    'wp-hide-security-enhancer'),
                                                                    'input_type'    =>  'text',
                                                                    
                                                                    'sanitize_type' =>  array(array($this->wph->functions, 'sanitize_file_path_name')),
                                                                    
                                                                    'processing_order'  =>  5
                                                                    );
                                                                    
                    $this->module_settings[]                  =   array(
                                                                        'id'            =>  'style_file_clean',
                                                                        'label'         =>  __('Remove description header from Style file',    'wp-hide-security-enhancer'),
                                                                        'description'   =>  __('Strip out all meta data from style file e.g. Theme Name, Theme URI, Author etc. Those are important information to find out possible theme security breaches.',    'wp-hide-security-enhancer')
                                                                                            . '<br />' . __('This feature may not work if style file url not available on html (being concatenated).',    'wp-hide-security-enhancer'),
                                                                        
                                                                        'input_type'    =>  'radio',
                                                                        'options'       =>  array(
                                                                                                    'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                    'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                    ),
                                                                        'default_value' =>  'no',
                                                                        
                                                                        'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                        'processing_order'  =>  3
                                                                        
                                                                        );
                                                                        
                                                                        
                    if($this->wph->templates_data['use_child_theme'])                                                
                        {
                            $this->module_settings[]                  =   array(
                                                                                'type'            =>  'split'
                                                                                
                                                                                );
                            
                            $this->module_settings[]                  =   array(
                                                                            'id'            =>  'new_theme_child_path',
                                                                            'label'         =>  __('Child - New Theme Path',    'wp-hide-security-enhancer'),
                                                                            'description'   =>  __('The default theme path is set to',    'wp-hide-security-enhancer') . ' <strong>'. str_replace(get_bloginfo('wpurl'), '' , trailingslashit($this->wph->templates_data['themes_url']) . $this->wph->templates_data['child']['folder_name'])  .'</strong>
                                                                                                    '.__('More details can be found at',    'wp-hide-security-enhancer') .' <a href="http://www.nsp-code.com" target="_blank">Link</a>',
                                                                            
                                                                            'value_description' =>  __('e.g. my_child_template',    'wp-hide-security-enhancer'),
                                                                            'input_type'    =>  'text',
                                                                            
                                                                            'sanitize_type' =>  array(array($this->wph->functions, 'sanitize_file_path_name')),
                                                                            'processing_order'  =>  15
                                                                            );
                                                                            
                            $this->module_settings[]                  =   array(
                                                                            'id'            =>  'child_style_file_path',
                                                                            'label'         =>  __('Child - New Style File Path',    'wp-hide-security-enhancer'),
                                                                            'description'   =>  __('The default theme style file style.css path is set to',    'wp-hide-security-enhancer') . ' <strong>'. str_replace(get_bloginfo('wpurl'), '' ,   $this->wph->default_variables['stylesheet_uri'])  .'</strong>'
                                                                                                .'<div class="description"><div class="notice-error"><div alt="f534" class="dashicons dashicons-warning">warning</div> <span class="important">'.   __('If style file contain relative URLs it should not include additional path, just the actual filename.',    'wp-hide-security-enhancer')   .'. '. __('More details at',    'wp-hide-security-enhancer') .' <a href="http://www.wp-hide.com/new-style-file-path-along-relative-urls/" target="_blank">New Style File Path along with relative URLs</a></span></div></div>',
                                                                            
                                                                            'value_description' =>  __('e.g. custom-style-file.css',    'wp-hide-security-enhancer'),
                                                                            'input_type'    =>  'text',
                                                                            
                                                                            'sanitize_type' =>  array(array($this->wph->functions, 'sanitize_file_path_name')),
                                                                            
                                                                            'processing_order'  =>  5
                                                                            );
                                                                    
                            $this->module_settings[]                  =   array(
                                                                                'id'            =>  'child_style_file_clean',
                                                                                'label'         =>  __('Child - Remove description header from Style file',    'wp-hide-security-enhancer'),
                                                                                'description'   =>  __('Strip out all meta data from style file e.g. Theme Name, Theme URI, Author etc. Those are important information to find out possible theme security breaches.',    'wp-hide-security-enhancer')
                                                                                                    . '<br />' . __('This feature may not work if style file url not available on html (being concatenated).',    'wp-hide-security-enhancer'),
                                                                                
                                                                                'input_type'    =>  'radio',
                                                                                'options'       =>  array(
                                                                                                            'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                            'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                            ),
                                                                                'default_value' =>  'no',
                                                                                
                                                                                'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                                'processing_order'  =>  3
                                                                                
                                                                                );
                        }
                                                                    
                    return $this->module_settings;   
                }
                
                
                
                
            /**
            * New Theme Path
            *     
            * @param mixed $saved_field_data
            */
            function _init_new_theme_path($saved_field_data)
                {
                    if(empty($saved_field_data))
                        return FALSE;
                    
                     
                     /*
                    //check for child
                    if($this->wph->templates_data['use_child_theme']     === TRUE) 
                        {
                            $child_theme_saved_field_data   =   $this->wph->functions->get_module_item_setting('new_theme_child_path');
                            if(empty($child_theme_saved_field_data) &&  empty($saved_field_data))
                                return FALSE;
                        }
                        else
                        {
                            if(empty($saved_field_data))
                                return FALSE;
                        }
                    */
                        
                    //applay when not admin and not customize.php
                    /*
                    if(is_admin()   ||  $this->functions->is_theme_customize())
                        return;
                    */
                        
                   
                   //???? to remove?
                    //add_filter('stylesheet_directory_uri',      array(&$this, 'stylesheet_directory_uri'), 999, 1);
                    //add_filter('template_directory_uri',        array(&$this, 'template_directory_uri'), 999, 3);
                   
                   
                   
                    //add_filter('theme_root_uri',                array(&$this, 'theme_root_uri'), 999, 3);
                    
                    //add replacement url
                    $this->wph->functions->add_replacement( trailingslashit( $this->wph->default_variables['template_url']), trailingslashit(    site_url()  )   .   trailingslashit( $saved_field_data ));

                }
                
            function _callback_saved_new_theme_path($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    //check if the field is noe empty
                    if(empty($saved_field_data))
                        return  $processing_response; 
          
                    $theme_path =   $this->wph->functions->get_url_path( $this->wph->templates_data['themes_url'] . $this->wph->templates_data['main']['folder_name']    );
                    
                    $path           =   '';
                    /*
                    if(!empty($this->wph->default_variables['wordpress_directory']))
                        $path           =   trailingslashit($this->wph->default_variables['wordpress_directory']);
                    */
                    $path           .=  trailingslashit(   $saved_field_data   );
                    
                    $theme_path = str_replace(' ', '%20', $theme_path);
                               
                    if($this->wph->server_htaccess_config   === TRUE)
                        $processing_response['rewrite'] = "\nRewriteRule ^"    .   $path   .   '(.*) '. $theme_path .'$1 [L,QSA]';
                    
                    if($this->wph->server_web_config   === TRUE)
                        $processing_response['rewrite'] = '
                            <rule name="wph-new_theme_path" stopProcessing="true">
                                <match url="^'.  $path   .'(.*)"  />
                                <action type="Rewrite" url="'.  $theme_path .'{R:1}"  appendQueryString="true" />
                            </rule>
                                                            ';
                                
                    return  $processing_response;   
                }
                
                
                
            function stylesheet_directory_uri($url)
                {
                    if  (   $this->wph->disable_filters )   
                        return  $url;
                    
                    $template_slug   =   str_replace($this->wph->templates_data['themes_url'], "", $url);
                    return  $url;
                                   
                    if($this->wph->templates_data['_template_' . $template_slug]  ==  'main')                    
                        $new_theme_path     =   $this->wph->functions->get_module_item_setting('new_theme_path');
                        else
                        $new_theme_path     =   $this->wph->functions->get_module_item_setting('new_theme_child_path');
                    
                    if(empty($new_theme_path))
                        return $url;
                       
                    $template_dir_uri   =   trailingslashit(    home_url()  )   . ltrim(rtrim($new_theme_path, "/"),  "/");
                    
                    //add replacement
                    $this->wph->functions->add_replacement( $url, $template_dir_uri );
                       
                    return $template_dir_uri;    
                }
                
                
            function template_directory_uri($template_dir_uri, $template, $theme_root_uri)
                {
                    if  (   $this->wph->disable_filters )   
                        return  $template_dir_uri;
                                 
                    if($this->wph->templates_data['_template_' . $template]  ==  'main')                    
                        $new_theme_path     =   $this->wph->functions->get_module_item_setting('new_theme_path');
                        else
                        $new_theme_path     =   $this->wph->functions->get_module_item_setting('new_theme_child_path');
                        
                    if(empty($new_theme_path))
                        return  $template_dir_uri;
                    
                    $new_template_dir_uri   =   trailingslashit(    home_url()  )   . trim($new_theme_path, "/") ;
                    
                    //add replacement
                    $this->wph->functions->add_replacement( $template_dir_uri, $new_template_dir_uri );
                       
                    return $new_template_dir_uri;    
                }
                
            
            /*
            function theme_root_uri($theme_root_uri, $siteurl, $stylesheet_or_template)
                {   
                    if  (   $this->wph->disable_filters )   
                        return  $theme_root_uri;
                    
                    //only for current theme
                    $current_theme = get_stylesheet();
                    if($current_theme !=    $stylesheet_or_template)
                        return $theme_root_uri;
                    
                    $theme_root_uri     =   untrailingslashit(    site_url()  ) ;
                    
                    return $theme_root_uri;    
                }
            */
                
            
            function _init_new_theme_child_path($saved_field_data)
                {
                    if(empty($saved_field_data))
                        return FALSE;
                    
                     
                     /*
                    //check for child
                    if($this->wph->templates_data['use_child_theme']     === TRUE) 
                        {
                            $child_theme_saved_field_data   =   $this->wph->functions->get_module_item_setting('new_theme_child_path');
                            if(empty($child_theme_saved_field_data) &&  empty($saved_field_data))
                                return FALSE;
                        }
                        else
                        {
                            if(empty($saved_field_data))
                                return FALSE;
                        }
                    */
                        
                    //applay when not admin and not customize.php
                    /*
                    if(is_admin()   ||  $this->functions->is_theme_customize())
                        return;
                    */
                        
                   
                   //???? to remove?
                    //add_filter('stylesheet_directory_uri',      array(&$this, 'stylesheet_directory_uri'), 999, 1);
                    //add_filter('template_directory_uri',        array(&$this, 'template_directory_uri'), 999, 3);
                   
                   
                   
                    //add_filter('theme_root_uri',                array(&$this, 'theme_root_uri'), 999, 3);
                    
                    //add replacement url
                    $this->wph->functions->add_replacement( trailingslashit( $this->wph->default_variables['stylesheet_uri'] ) , trailingslashit(    site_url()  )   .   trailingslashit( $saved_field_data ) );

                }
                
            function _callback_saved_new_theme_child_path($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    //check if the field is noe empty
                    if(empty($saved_field_data))
                        return  $processing_response; 
          
                    $theme_path =   $this->wph->functions->get_url_path( $this->wph->templates_data['themes_url'] . $this->wph->templates_data['child']['folder_name']    );
                    
                    $path           =   '';
                    /*
                    if(!empty($this->wph->default_variables['wordpress_directory']))
                        $path           =   trailingslashit($this->wph->default_variables['wordpress_directory']);
                    */
                    $path           .=  trailingslashit(   $saved_field_data   );
                               
                    
                    $theme_path = str_replace(' ', '%20', $theme_path);
                    
                    if($this->wph->server_htaccess_config   === TRUE)
                        $processing_response['rewrite']     =   "\nRewriteRule ^"    .   $path   .   '(.*) '. $theme_path .'$1 [L,QSA]';
                        
                    if($this->wph->server_web_config   === TRUE)
                        $processing_response['rewrite'] = '
                            <rule name="wph-new_theme_child_path" stopProcessing="true">
                                <match url="^'.  $path   .'(.*)"  />
                                <action type="Rewrite" url="'.  $theme_path .'{R:1}"  appendQueryString="true" />
                            </rule>
                                                            ';
                                
                    return  $processing_response;   
                }
                
                
          
            function _init_new_style_file_path($saved_field_data)
                {
                    if(empty($saved_field_data))
                        return FALSE;
                    
                    if($this->wph->functions->is_theme_customize())
                        return;    
                    
                    $new_theme_path     =   $this->wph->functions->get_module_item_setting('new_theme_path');
                    
                    //add default replacements
                    $template_url           =   trailingslashit( $this->wph->default_variables['template_url'] );
                    $old_style_file_path    =   trailingslashit( $this->wph->default_variables['template_url'] )    .   'style.css';
                    
                    if(!empty($new_theme_path))
                        {
                            $new_style_file_path    =  trailingslashit(    site_url()  )   .   trailingslashit($new_theme_path) . $saved_field_data;
                            $this->wph->functions->add_replacement( $old_style_file_path ,  $new_style_file_path );
                        }
                        else
                        {
                            $new_style_file_path    =  $template_url    .   $saved_field_data;
                            $this->wph->functions->add_replacement( $old_style_file_path ,  $new_style_file_path );
                        }
                            
                    
           
                    //add replacement for style.css when already template name replaced
                    if(!empty($new_theme_path))
                        {
                            $old_style_file_path    =   trailingslashit(    site_url()  ) . trailingslashit( $new_theme_path ) . 'style.css';
                            $this->wph->functions->add_replacement( $old_style_file_path ,  $new_style_file_path );
                        }
                  
                }
                
            function _callback_saved_new_style_file_path($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    //check if the field is noe empty
                    if(empty($saved_field_data))
                        return  $processing_response; 
          
                    $current_stylesheet_uri     =   $this->wph->default_variables['template_url'];
                    $current_stylesheet_uri     =   $this->wph->functions->get_url_path( $current_stylesheet_uri );
                    $current_stylesheet_uri     =   trailingslashit( $current_stylesheet_uri ) . 'style.css';
                    
                    $path           =   '';
                    /*
                    if(!empty($this->wph->default_variables['wordpress_directory']))
                        $path           =   trailingslashit($this->wph->default_variables['wordpress_directory']);
                    */
                    
                    $new_theme_path     =   $this->wph->functions->get_module_item_setting('new_theme_path');
                    if(!empty($new_theme_path))
                        {
                            $path    .=  trailingslashit($new_theme_path) . $saved_field_data;
                        }
                        else
                        {
                            $template_relative_url  =   $this->wph->functions->get_url_path_relative_to_domain_root($this->wph->default_variables['template_url']);
                            $path    .=  trailingslashit($template_relative_url) . $saved_field_data;
                        }
                    
                    $current_stylesheet_uri = str_replace(' ', '%20', $current_stylesheet_uri);
                               
                    if($this->wph->server_htaccess_config   === TRUE)
                        $processing_response['rewrite'] = "\nRewriteRule ^"    .   $path   .   ' '. $current_stylesheet_uri .' [L,QSA]';            
                        
                    if($this->wph->server_web_config   === TRUE)
                        $processing_response['rewrite'] = '
                            <rule name="wph-new_style_file_path" stopProcessing="true">
                                <match url="^'.  $path   .'"  />
                                <action type="Rewrite" url="'.  $current_stylesheet_uri .'"  appendQueryString="true" />
                            </rule>
                                                            ';
                                
                    return  $processing_response;   
                }

            
            
            function _callback_saved_style_file_clean($saved_field_data)
                {
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                        
                    $processing_response    =   array();
                    
                    //actual style file path
                    $current_stylesheet_uri     =   $this->wph->default_variables['template_url'];
                    $current_stylesheet_uri     =   $this->wph->functions->get_url_path( $current_stylesheet_uri );
                    $current_stylesheet_uri     =   trailingslashit( $current_stylesheet_uri ) . 'style.css'; 
                                        
                    //current style file path
                    $path           =   '';
                    $new_theme_path         =  $this->wph->functions->get_module_item_setting('new_theme_path'); 
                    $new_style_file_path    =  $this->wph->functions->get_module_item_setting('new_style_file_path');
                    if(!empty($new_style_file_path))
                        {
                            /*
                            if(!empty($this->wph->default_variables['wordpress_directory']))
                                $path           =   trailingslashit($this->wph->default_variables['wordpress_directory']);
                            */
                            
                            
                            if(!empty($new_theme_path))
                                {
                                    $path    .=  trailingslashit($new_theme_path) . $new_style_file_path;
                                }
                                else
                                {
                                    $template_relative_url  =   $this->wph->functions->get_url_path_relative_to_domain_root($this->wph->default_variables['template_url']);
                                    $path    .=  trailingslashit($template_relative_url) . $new_style_file_path;
                                }
     
                        }
                        else if(!empty($new_theme_path))
                            {
                                $path           =  trailingslashit( $new_theme_path ) . 'style.css';   
                            }
                            else
                            {
                                //use the default
                                //  cont/themes/twentyfifteen/style.css
                                
                                $default_path   =   get_template_directory_uri();
                                   
                                //check for modified wp-content folder
                                $new_content_path =   $this->wph->functions->get_module_item_setting('new_content_path');
                                if(!empty($new_content_path))
                                    {
                                        $path   =   str_replace( trailingslashit( WP_CONTENT_URL ) , "/", $default_path);
                                        $path   =   $new_content_path . $path;
                                    }
                                    else
                                    {
                                        $path   =   str_replace( trailingslashit( WP_CONTENT_URL ) , "/", $default_path);
                                        
                                        $wp_content_folder      =   str_replace( site_url() , '' , WP_CONTENT_URL);
                                        $wp_content_folder      =   trim($wp_content_folder, '/');
                                        
                                        $path   =   $wp_content_folder . $path;
                                    }
                                
                                //$path       =   $this->wph->functions->get_url_path( get_template_directory_uri() );
                                $path       =  trailingslashit( $path ) . 'style.css';
                            }
                    
                    //plugin File Processor router path
                    $file_processor =   $this->wph->functions->get_url_path( WP_PLUGIN_URL    );
                    $file_processor =   trailingslashit( $file_processor ) . 'wp-hide-security-enhancer/router/file-process.php';
                    
                    $current_stylesheet_uri = str_replace(' ', '%20', $current_stylesheet_uri);
                    
                    if($this->wph->server_htaccess_config   === TRUE)                               
                        $processing_response['rewrite'] = "\nRewriteRule ^"    .   $path   .   ' '. $file_processor . '?action=style-clean&file_path=' . $current_stylesheet_uri .' [L,QSA]';
                        
                    if($this->wph->server_web_config   === TRUE)
                        $processing_response['rewrite'] = '
                            <rule name="wph-style_file_clean" stopProcessing="true">
                                <match url="^'.  $path   .'"  />
                                <action type="Rewrite" url="'.  $file_processor .'?action=style-clean&amp;file_path=' . $current_stylesheet_uri .'"  appendQueryString="true" />
                            </rule>
                                                            ';
                                      
                    return  $processing_response; 
                    
                }
            
                
                
            function _init_child_style_file_path($saved_field_data)
                {

                    if(empty($saved_field_data))
                        return FALSE;
                    
                    if($this->wph->functions->is_theme_customize())
                        return;
                        
                    $new_theme_path     =   $this->wph->functions->get_module_item_setting('new_theme_child_path');
                    
                    //add default replacements
                    $template_url           =   trailingslashit( $this->wph->default_variables['stylesheet_uri'] );
                    $old_style_file_path    =   trailingslashit( $this->wph->default_variables['stylesheet_uri'] )    .   'style.css';
                    
                    if(!empty($new_theme_path))
                        {
                            $new_style_file_path    =  trailingslashit(    site_url()  )   .   trailingslashit($new_theme_path) . $saved_field_data;
                            $this->wph->functions->add_replacement( $old_style_file_path , $new_style_file_path );
                        }
                        else
                        {
                            $new_style_file_path    =  $template_url    .   $saved_field_data;
                            $this->wph->functions->add_replacement( $old_style_file_path , $new_style_file_path );
                        }
                            
                    
           
                    //add replacement for style.css when already template name replaced
                    if(!empty($new_theme_path))
                        {
                            $old_style_file_path    =   trailingslashit(    site_url()  ) . trailingslashit( $new_theme_path ) . 'style.css';
                            $this->wph->functions->add_replacement( $old_style_file_path ,  $new_style_file_path );
                        }
                        
           
                }
                
            function _callback_saved_child_style_file_path($saved_field_data)
                {
                    
                    $processing_response    =   array();
                    
                    //check if the field is noe empty
                    if(empty($saved_field_data))
                        return  $processing_response; 
          
                    $current_stylesheet_uri     =   $this->wph->default_variables['stylesheet_uri'];
                    $current_stylesheet_uri     =   $this->wph->functions->get_url_path( $current_stylesheet_uri, TRUE );
                    $current_stylesheet_uri     =   trailingslashit( $current_stylesheet_uri ) . 'style.css';
                    
                    $path           =   '';
                    /*
                    if(!empty($this->wph->default_variables['wordpress_directory']))
                        $path           =   trailingslashit($this->wph->default_variables['wordpress_directory']);
                    */
                    
                    $new_theme_path     =   $this->wph->functions->get_module_item_setting('new_theme_child_path');
                    if(!empty($new_theme_path))
                        {
                            $path    .=  trailingslashit($new_theme_path) . $saved_field_data;
                        }
                        else
                        {
                            $template_relative_url  =   $this->wph->functions->get_url_path_relative_to_domain_root($this->wph->default_variables['template_url']);
                            $path    .=  trailingslashit($template_relative_url) . $saved_field_data;
                        }
                    
                    $current_stylesheet_uri = str_replace(' ', '%20', $current_stylesheet_uri);
                    
                    if($this->wph->server_htaccess_config   === TRUE)           
                        $processing_response['rewrite'] = "\nRewriteRule ^"    .   $path   .   ' '. $current_stylesheet_uri .' [L,QSA]';            
                    
                    if($this->wph->server_web_config   === TRUE)
                        $processing_response['rewrite'] = '
                            <rule name="wph-child_style_file_path" stopProcessing="true">
                                <match url="^'.  $path   .'"  />
                                <action type="Rewrite" url="'.  $current_stylesheet_uri .'"  appendQueryString="true" />
                            </rule>
                                                            ';
                                
                    return  $processing_response;   
                }
 

            function _callback_saved_child_style_file_clean($saved_field_data)
                {
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                        
                    $processing_response    =   array();
                    
                    //actual style file path
                    $current_stylesheet_uri     =   trailingslashit ( $this->wph->templates_data['themes_url'] ) . $this->wph->templates_data['child']['folder_name'];
                    $current_stylesheet_uri     =   $this->wph->functions->get_url_path( $current_stylesheet_uri );
                    $current_stylesheet_uri     =   trailingslashit( $current_stylesheet_uri ) . 'style.css'; 
                                        
                    //current style file path
                    $path           =   '';
                    $new_theme_path         =  $this->wph->functions->get_module_item_setting('new_theme_child_path'); 
                    $new_style_file_path    =  $this->wph->functions->get_module_item_setting('child_style_file_path');
                    if(!empty($new_style_file_path))
                        {
                            /*
                            if(!empty($this->wph->default_variables['wordpress_directory']))
                                $path           =   trailingslashit($this->wph->default_variables['wordpress_directory']);
                            */
                            
                            if(!empty($new_theme_path))
                                {
                                    $path    .=  trailingslashit($new_theme_path) . $new_style_file_path;
                                }
                                else
                                {
                                    $template_relative_url  =   $this->wph->functions->get_url_path_relative_to_domain_root($this->wph->default_variables['template_url']);
                                    $path    .=  trailingslashit($template_relative_url) . $new_style_file_path;
                                }
                        }
                        else if(!empty($new_theme_path))
                            {
                                $path           =  trailingslashit( $new_theme_path ) . 'style.css';   
                            }
                            else
                            {
                                //use the default
                                //  cont/themes/twentyfifteen/style.css
                                
                                $default_path   =   trailingslashit ( $this->wph->templates_data['themes_url'] ) . $this->wph->templates_data['child']['folder_name'];
                                   
                                //check for modified wp-content folder
                                $new_content_path =   $this->wph->functions->get_module_item_setting('new_content_path');
                                if(!empty($new_content_path))
                                    {
                                        $path   =   str_replace( trailingslashit( WP_CONTENT_URL ) , "/", $default_path);
                                        $path   =   $new_content_path . $path;
                                    }
                                    else
                                    {
                                        $path   =   str_replace( trailingslashit( WP_CONTENT_URL ) , "/", $default_path);
                                        
                                        $wp_content_folder      =   str_replace( site_url() , '' , WP_CONTENT_URL);
                                        $wp_content_folder      =   trim($wp_content_folder, '/');
                                        
                                        $path   =   $wp_content_folder . $path;
                                    }
                                
                                //$path       =   $this->wph->functions->get_url_path( get_template_directory_uri() );
                                $path       =  trailingslashit( $path ) . 'style.css';
                            }
                    
                    //plugin File Processor router path
                    $file_processor =   $this->wph->functions->get_url_path( WP_PLUGIN_URL    );
                    $file_processor =   trailingslashit( $file_processor ) . 'wp-hide-security-enhancer/router/file-process.php';
                    
                    $current_stylesheet_uri = str_replace(' ', '%20', $current_stylesheet_uri);
                    
                    if($this->wph->server_htaccess_config   === TRUE)                               
                        $processing_response['rewrite'] = "\nRewriteRule ^"    .   $path   .   ' '. $file_processor . '?action=style-clean&file_path=' . $current_stylesheet_uri .' [L,QSA]';            
                        
                    
                    if($this->wph->server_web_config   === TRUE)
                        $processing_response['rewrite'] = '
                            <rule name="wph-child-style_file_clean" stopProcessing="true">
                                <match url="^'.  $path   .'"  />
                                <action type="Rewrite" url="'.  $file_processor .'?action=style-clean&amp;file_path=' . $current_stylesheet_uri .'"  appendQueryString="true" />
                            </rule>
                                                            ';
                                
                    return  $processing_response; 
                    
                }
        }
?>