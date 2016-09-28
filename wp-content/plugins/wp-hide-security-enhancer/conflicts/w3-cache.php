<?php


    class WPH_conflict_handle_w3_cache
        {
                        
            function _construct_()
                {
                     
                }                        
            
            static function is_plugin_active()
                {
                    if(defined('W3TC_VERSION'))
                        return TRUE;
                        else
                        return FALSE;
                }
            
            static public function pagecache()
                {   
                    if( !   self::is_plugin_active())
                        return FALSE;
                    
                    //check if there's a pagecache callback
                    if(isset($GLOBALS['_w3tc_ob_callbacks'])    &&  isset($GLOBALS['_w3tc_ob_callbacks']['pagecache']))
                        {
                            $GLOBALS['WPH_w3tc_ob_callbacks']['pagecache'] =   $GLOBALS['_w3tc_ob_callbacks']['pagecache'];
                            
                            //hijackthe callback
                            $GLOBALS['_w3tc_ob_callbacks']['pagecache'] =   array('WPH_conflict_handle_w3_cache', 'pagecache_callback');   
                        }
                               
                }
                
            static function pagecache_callback(&$value)
                {
                    global $wph;
                    
                    //applay the replacements
                    $value  =   $wph->ob_start_callback($value);
                    
                    //allow the W3-Cache to continur the initial callback
                    $callback = $GLOBALS['WPH_w3tc_ob_callbacks']['pagecache'];
                    if (is_callable($callback)) 
                        {
                            $value = call_user_func($callback, $value);
                        }
                    
                    return $value;   
                }
            
                
        }


?>