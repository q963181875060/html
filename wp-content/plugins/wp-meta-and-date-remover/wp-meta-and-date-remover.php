<?php
/*
Plugin Name: WP Meta and date remover
Plugin URI: mailto:prasadkirpekar@outlook.com
Description: Remove Meta information such as Author and Date from posts and pages.
Version: 1.2.2
Author: Prasad Kirpekar
Author URI: http://twitter.com.com/kirpekarprasad
License: GPL v2
Copyright: Prasad Kirpekar

	This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function extra_links($links){
	$fiverr="<a href='http://bit.ly/2bzAUb6'>More Customization</a>";
$setting_link = '<a href="../wp-admin/options-general.php?page=wp-meta-and-date-remover.php">Settings</a>';
  $five_star='<a href="https://wordpress.org/support/view/plugin-reviews/wp-meta-and-date-remover?rate=5#postform">Vote up</a>';
  array_unshift($links, $setting_link);
  array_unshift($links, $five_star);
  array_unshift($links, $fiverr);
  return $links;
}
$plugin = plugin_basename(__FILE__);

//Removal using css

function inline_style_(){
	if(get_option('wpmdr_disable_css')=="0"){
		echo "<style>/* CSS added by WP Meta and Date Remover*/".get_option('wpmdr_css')."</style>";
	}
}
function wp_meta_settings()
{
	$css=get_option('wpmdr_css');
	$disable_php=get_option('wpmdr_disable_php');
	$disable_css=get_option('wpmdr_disable_css');
	if(isset($_POST['submitted']))
	{
		if(isset($_POST['wpmdr_css']))
		{
			$css=$_POST['wpmdr_css'];
		}
		if(isset($_POST['wpmdr_disable_php']))
		{
			$disable_php="1";
		}
		else{
			$disable_php="0";
		}
		if(isset($_POST['wpmdr_disable_css']))
		{
			$disable_css="1";
		}
		else{
			$disable_css="0";
		}
		
		update_option('wpmdr_css',$css);
		update_option('wpmdr_disable_php',$disable_php);
		update_option('wpmdr_disable_css',$disable_css);
		echo '<div class="updated fade"><p>Settings Saved! </p></div>';
	}
	$action_url = $_SERVER['REQUEST_URI'];
	include "admin/options.php";
}
function register_wpmdr_settings()
{
	register_setting( 'wpmdr_css', 'wpmdr_css' );
  
}
function admin_settings()
{
			add_options_page('WP Meta and Date Remover', 'WP Meta and Date Remover', 10, basename(__FILE__), 'wp_meta_settings');
	
}
function init_option(){
	$css="/* Remove meta from post */
.entry-meta {
display:none !important;
}

/* Remove meta from home page */
.home .entry-meta { 
display: none; 
}
/* WPTheme 2015 Metadata Removal */
.entry-footer {
display:none !important;
}

/* WPTheme 2015 Metadata Removal */
.home .entry-footer { 
display: none; 
}";
	add_option('wpmdr_css',$css);
	add_option('wpmdr_disable_php',"0");
	add_option('wpmdr_disable_css',"0");
}


// removal using php.
//some times css removal don't work for every theme.
function remove_meta_php() {
	if(get_option('wpmdr_disable_php')=="0"){
		
		add_filter('the_date', '__return_false');
		add_filter('the_author', '__return_false');
		add_filter('the_time', '__return_false');
		add_filter('the_modified_date', '__return_false');
		add_filter('get_the_date', '__return_false');
		add_filter('get_the_author', '__return_false');
		add_filter('get_the_title', '__return_false');
		add_filter('get_the_time', '__return_false');
		add_filter('get_the_modified_date', '__return_false');
	}
} 

//do everything 
register_activation_hook(__FILE__, 'init_option');
	
add_action('wp_head','inline_style_');
add_filter("plugin_action_links_$plugin", 'extra_links' );
add_action('loop_start', 'remove_meta_php');
add_action('admin_menu','admin_settings');