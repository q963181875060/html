<?php
// Before removing this file, please verify the PHP ini setting `auto_prepend_file` does not point to this.

if (file_exists('/yjdata/www/wordpress/wp-content/plugins/wordfence/waf/bootstrap.php')) {
	define("WFWAF_LOG_PATH", '/yjdata/www/wordpress/wp-content/wflogs/');
	include_once '/yjdata/www/wordpress/wp-content/plugins/wordfence/waf/bootstrap.php';
}
?>