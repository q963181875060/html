<?php
// Before removing this file, please verify the PHP ini setting `auto_prepend_file` does not point to this.

if (file_exists('/usr/share/nginx/html/wp-content/plugins/wordfence/waf/bootstrap.php')) {
	define("WFWAF_LOG_PATH", '/usr/share/nginx/html/wp-content/wflogs/');
	include_once '/usr/share/nginx/html/wp-content/plugins/wordfence/waf/bootstrap.php';
}
?>