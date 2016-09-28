<?php

    class WPH_module_general_html extends WPH_module_component
        {
            function get_component_title()
                {
                    return "HTML";
                }
                                    
            function get_module_settings()
                {
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'remove_html_comments',
                                                                    'label'         =>  'Remove HTML Comments',
                                                                    'description'   =>  __('Remove all HTML Comments which usualy specify Plugins Name and Versio. Any Internet Exploreer conditional tags are preserved.', 'wp-hide-security-enhancer'),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  80
                                                                    );
                                                                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'clean_body_classes',
                                                                    'label'         =>  'Remove general classes from body tag',
                                                                    'description'   =>  __('Remove general classes from body tag.', 'wp-hide-security-enhancer')
                                                                                        . ' ' . __('More details can be found at',    'wp-hide-security-enhancer') .' <a href="http://www.wp-hide.com/remove-classes-html/" target="_blank">Remove classes from HTML</a>', 
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  81
                                                                    );
                                                                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'clean_menu_items_id',
                                                                    'label'         =>  'Remove ID from Menu items',
                                                                    'description'   =>  __('Remove ID attribute from all menu items.', 'wp-hide-security-enhancer')
                                                                                        . ' ' . __('More details can be found at',    'wp-hide-security-enhancer') .' <a href="http://www.wp-hide.com/remove-classes-html/" target="_blank">Remove classes from HTML</a>', 
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  81
                                                                    );
                                                                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'clean_menu_items_classes',
                                                                    'label'         =>  'Remove class from Menu items',
                                                                    'description'   =>  __('Remove class attribute from all menu items. Any classes which include a "current" prefix or contain "has-children" will be preserved.', 'wp-hide-security-enhancer')
                                                                                        . ' ' . __('More details can be found at',    'wp-hide-security-enhancer') .' <a href="http://www.wp-hide.com/remove-classes-html/" target="_blank">Remove classes from HTML</a>', 
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  81
                                                                    );
                                                                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'clean_post_classes',
                                                                    'label'         =>  'Remove general classes from post',
                                                                    'description'   =>  __('Remove general classes from post.', 'wp-hide-security-enhancer')
                                                                                        . ' ' . __('More details can be found at',    'wp-hide-security-enhancer') .' <a href="http://www.wp-hide.com/remove-classes-html/" target="_blank">Remove classes from HTML</a>', 
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  81
                                                                    );
                                                                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'clean_image_classes',
                                                                    'label'         =>  'Remove general classes from images',
                                                                    'description'   =>  __('Remove general classes from media tags.', 'wp-hide-security-enhancer')
                                                                                        . ' ' . __('More details can be found at',    'wp-hide-security-enhancer') .' <a href="http://www.wp-hide.com/remove-classes-html/" target="_blank">Remove classes from HTML</a>', 
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  81
                                                                    );
                                                                    
                    return $this->module_settings;   
                }
                
                
                
            function _init_remove_html_comments($saved_field_data)
                {
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                        
                    
                    add_filter('wph/ob_start_callback', array($this, 'remove_html_comments'));
                    
                }
                
                
            function remove_html_comments($buffer)
                {
                    //do not run when within admin
                    if(defined('WP_ADMIN'))
                        return $buffer;    
                    
                    //replace any comments 
                    $buffer =   preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->)(.|\n))*-->/sm', "" , $buffer);
                    
                    return $buffer;
                    
                }
            
            function _init_clean_body_classes( $saved_field_data )
                {
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                        
                    add_filter('body_class', array(&$this, 'body_class'), 9, 2);
                    
                }
                
            
            function body_class( $classes, $class )
                {
                    $preserve_classes   =   array(
                                                    'home',
                                                    'archive',
                                                    'single',
                                                    'blog',
                                                    'attachment',
                                                    'search',
                                                    'category',
                                                    'tag',
                                                    'rtl',
                                                    'author'
                                                    );
                    
                    if(!empty( $class ))
                        $preserve_classes =   array_merge($preserve_classes, (array) $class );
                    
                    $preserve_classes   =   apply_filters('wp-hide/components/general-html/body_class/preserve', $preserve_classes);;
                    
                    $keep_classes   =   array_intersect($preserve_classes, $classes);
                    
                    //reindex the array
                    $keep_classes   =   array_values($keep_classes);
                       
                    return $keep_classes;
                        
                }
                
                
            function _init_clean_menu_items_id( $saved_field_data )
                {
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                           
                    add_filter('nav_menu_item_id', array(&$this, 'nav_menu_item_id'), 999);
                    
                }   
                
            
            function nav_menu_item_id($item_id)
                {
                    $item_id    =   '';
                       
                    return $item_id;
                        
                }
                
                
            function _init_clean_menu_items_classes( $saved_field_data )
                {
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                        
                    add_filter('nav_menu_css_class', array(&$this, 'nav_menu_css_class'), 999);   
                    
                }
                
                
            function nav_menu_css_class( $classes )
                {
                    foreach($classes    as  $key    =>  $class_name)
                        {
                            if(strpos($class_name, 'current-')  === 0   ||  strpos($class_name, 'current_')  === 0  || strpos($class_name, 'has-children')  !== FALSE)
                                continue;
                                
                            unset($classes[$key]);
                            
                        }
                        
                    $classes    =   array_values($classes);
                    
                        
                    return $classes;
                }
                
                
            function _init_clean_post_classes( $saved_field_data )
                {
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                        
                    add_filter('post_class', array(&$this, 'post_class'), 999, 2);   
                    
                }
                
                
            function post_class( $classes, $class )
                {
                    return $classes;
                    $preserve_classes   =   array(
                                                    'sticky'
                                                    );
                                                    
                    if(!empty( $class ))
                        $preserve_classes =   array_merge($preserve_classes, (array) $class );
                    
                    //preserve post types
                    $post_types =   get_post_types();
                    foreach($post_types as  $post_type)
                        {
                            $preserve_classes[] =   $post_type;
                        }
                        
                    //preserve taxonomies
                    $taxonomies = get_taxonomies( );
                    foreach($taxonomies as  $taxonomy)
                        {
                            $preserve_classes[] =   $taxonomy;
                        }
                    
                    //preserve formats classes
                    foreach( $classes   as  $class)
                        {
                            if(strpos($class, 'format-')   === 0)
                                $preserve_classes[] =   $class;
                        }
                    
                    $preserve_classes   =   apply_filters('wp-hide/components/general-html/post_class/preserve', $preserve_classes);;
                    
                    $keep_classes   =   array_intersect($preserve_classes, $classes);
                    
                    //reindex the array
                    $keep_classes   =   array_values($keep_classes);
                       
                    return $keep_classes;    
                }
                
            
            
            function _init_clean_image_classes( $saved_field_data )
                {
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                      
                    add_filter( 'wph/ob_start_callback',         array(&$this, 'ob_start_callback_clean_image_classes'));
                    
                }
                
            
            function ob_start_callback_clean_image_classes( $buffer )
                {

                    if(is_admin())
                        return $buffer;

                    
                    if ( ! class_exists( 'DOMDocument', false ) )
                        return $buffer;

                    $doc = new DOMDocument();
                    $doc->preserveWhiteSpace    = true;
                                        
                    if ( @$doc->loadHTML(mb_convert_encoding($buffer, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD) === false )
                        return $buffer;
                    
                    $doc->encoding              = 'UTF-8';
                    $doc->formatOutput          = true;
                        
                    $xpath = new DOMXPath($doc);
                    
                    $nodes = $xpath->query('//img[@class]');
                    if($nodes->length < 1)
                        return $buffer;
                        
                    foreach ($nodes as $node) 
                        {
                            $classes    =   $node->getAttribute('class');
                            
                            if(empty($classes))
                                continue;
                                
                            $classes_array  =   explode(" ", $classes);
                            $classes_array  =   array_filter( $classes_array );
                            
                            foreach($classes_array  as  $key    =>  $class)
                                {
                                    //only wp-image- at the momment
                                    if(strpos($class, 'wp-image-')  === 0)
                                        {
                                            unset( $classes_array[$key] );
                                        }
                                }
                            
                            $classes_array  =   array_values($classes_array);
                            
                            $node->setAttribute( "class", implode( " ", $classes_array ) );
                        }
                    
                    $doc->normalizeDocument();
                    
                    $buffer =   $doc->saveHTML( $dom->documentElement );
                        
                    return $buffer;
                        
                }    
            

        }
?>