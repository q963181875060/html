<?php

    class WPH_module_general_styles extends WPH_module_component
        {
            function get_component_title()
                {
                    return "Styles";
                }
                                    
            function get_module_settings()
                {
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'styles_remove_version',
                                                                    'label'         =>  'Remove Version',
                                                                    'description'   =>  __('Remove version number from enqueued style files.', 'wp-hide-security-enhancer'),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower')
                                                                    
                                                                    );
                                                                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'styles_remove_id_attribute',
                                                                    'label'         =>  'Remove ID from link tags',
                                                                    'description'   =>  __('Remove ID attribute from all link tags which include a stylesheet.', 'wp-hide-security-enhancer'),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower')
                                                                    
                                                                    );
                                                                    
                    return $this->module_settings;   
                }
                
                
                
            function _init_styles_remove_version($saved_field_data)
                {
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                        
                    add_filter( 'style_loader_src',         array(&$this, 'remove_file_version'), 999 );
                    
                }
                
            function remove_file_version($src)
                {
                    if( empty($src) )   
                        return $src;
                        
                    $parse_url  =   parse_url( $src );
                    
                    if(empty($parse_url['query']))
                        return $src;
                    
                    parse_str( $parse_url['query'], $query );
                    
                    if(!isset( $query['ver'] ))
                        return $src;
                    
                    unset($query['ver']);    
                    
                    $parse_url['query'] =   http_build_query( $query );
                    if(empty($parse_url['query']))
                        unset( $parse_url['query'] );
                    
                    $url    =   $this->wph->functions->build_parsed_url( $parse_url );
                    
                    return $url;
                    
                }
                
                
            function _init_styles_remove_id_attribute($saved_field_data)
                {
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                    
                    //run only on front syde
                    if(is_admin())
                        return FALSE;
                        
                    add_filter( 'wph/ob_start_callback',         array(&$this, 'ob_start_callback_remove_id'));
                    
                }
                
            
            /**
            * Replace all ID's attribute for link tags
            * 
            * @param mixed $buffer
            */
            function ob_start_callback_remove_id($buffer)
                {
                    if ( ! class_exists( 'DOMDocument', false ) )
                        return $buffer;

                    $doc = new DOMDocument();
                    $doc->preserveWhiteSpace    = true;
                                        
                    if ( @$doc->loadHTML(mb_convert_encoding($buffer, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD) === false )
                        return $buffer;
                    
                    $doc->encoding              = 'UTF-8';
                    $doc->formatOutput          = true;
                        
                    $xpath = new DOMXPath($doc);
                    
                    $nodes = $xpath->query('//link[@id]');
                    if($nodes->length < 1)
                        return $buffer;
                        
                    foreach ($nodes as $node) 
                        {
                            $node->removeAttribute('id');
                        }
                    
                    $doc->normalizeDocument();
                    
                    $buffer =   $doc->saveHTML( $dom->documentElement );
                        
                    return $buffer;
                       
                }
                
            
            

        }
?>