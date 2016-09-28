<?php

    error_reporting(0);
    
    $action     =   isset($_GET['action'])      ?   $_GET['action']     :   '';
    $file_path  =   isset($_GET['file_path'])   ?   $_GET['file_path']  :   '';
    
    if(empty($action)   ||  empty($file_path))
        die();
        
    //append doc root to path 
    $file_path  =   $_SERVER["DOCUMENT_ROOT"] .   $file_path;  
    
    //check if file exists
    if (!file_exists($file_path))
        die();
        
    $WPH_FileProcess  =   new WPH_FileProcess();
    
    $WPH_FileProcess->action        =   $action;
    $WPH_FileProcess->file_path     =   $file_path;
    
    $WPH_FileProcess->run();    

    class WPH_FileProcess
        {
            var $action;
            var $file_path;
            
            function __construct()
                {
                    ob_start("ob_gzhandler");
                }
            
            function __destruct()
                {
                    $out = ob_get_contents();
                    ob_end_clean();
                    
                    echo $out;
                }
                
            function run()
                {
                    switch($this->action)
                        {
                            case 'style-clean'  :   
                                                    $this->style_clean();
                                                    break;
                            
                        }
                }
                
                
            function style_clean()
                {
                    //output headers
                    $expires_offset = 31536000;                    
                    
                    header('Content-Type: text/css; charset=UTF-8');
                    header('Expires: ' . gmdate( "D, d M Y H:i:s", time() + $expires_offset ) . ' GMT');
                    header("Cache-Control: public, max-age=$expires_offset");
                    header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($this->file_path)).' GMT', true);
                    
                    $handle         = fopen($this->file_path, "r");
                    $file_data      = fread($handle, filesize($this->file_path));
                    fclose($handle);
                    
                    $file_data  =   preg_replace('!/\*.*?\*/!s', '', $file_data);
                    $file_data  =   preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $file_data);
                    
                    echo $file_data;
                    
                }
        }


?>