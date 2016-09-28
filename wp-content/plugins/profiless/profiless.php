<?php
/*
Plugin Name: Profiless
Plugin URI: http://www.lautre-monde.fr/profiless/
Description: Profiless is a plugin that removes access to the profile page based on user role.
Author: Olivier
Version: 1.8
Author URI: http://www.lautre-monde.fr
*/

/*
1/ License terms :
- You are free to use Profiless.
- Release 1.0 of Profiless is free of use (this doesn't mean that it will be the case for the entire life of this plugin, you never know what can happen in life).
- I won't assume any responsibility for any problem that could happen as part of the use of this plugin so use it at your own risk.
- The plugin has been designed and tested under Wordpress 2.7.1. It may work under others releases but I haven't tested so I cannot commit on it.

2/ Release history :
- 1.8 (22/08/2012) : fixed a bug with old WP installations
- 1.7 (15/08/2012) : added an option page to allow selection of user role to be locked out
- 1.6 (06/10/2010) : removed usage of deprecated user_level
- 1.5 (20/06/2010) : added WP 3.0 compatibility
- 1.4 (27/11/2009) : fixed php error (wrong closing tag)
- 1.3 (03/09/2009) : modified server variables access to ensure maximum compatibility
- 1.2 (29/06/2009) : updated for WP 2.8 compatibility, improved page access test logic
- 1.1 (29/03/2009) : prevented access through alternate url
- 1.0 (10/03/2009) : original release

3/ Plugin description :
This plugin is very simple! It removes the menu icon to access the profile page for the selected user roles in wordpress admin pannel. It also redirects the locked user to the admin
homepage if it tries to access directly the profile page (as the menuitem has been removed).
*/

$profiless_version = '1.8';

function profiless_options()
{
	global $profiless_version, $profiless_settings;

	if (!current_user_can('administrator'))
		die('-1');
	
	$profiless_settings = get_option('profiless');
	
	echo '<h1 style="text-align:center">Profiless '.$profiless_version.'</h1>';
	
	if (isset($_POST['profiless_save_options']))
	{
		check_admin_referer('save-profiless-options');
		echo '<div id="message" class="updated fade"><p>Options successfully updated!<br /></p></div><br />';
		
		$locked_profile_roles = array();
		$roles = get_editable_roles();
		foreach ($roles as $role)
		{
			$locked_profile_roles[strtolower(before_last_bar($role['name']))] = isset($_POST['locked_profile_roles_'.strtolower(before_last_bar($role['name']))]) ? 1 : 0;
		}
		$profiless_settings['locked_profile_roles'] = $locked_profile_roles;
		update_option('profiless', $profiless_settings);
	}

	$profiless_settings = get_option('profiless');	
	
	echo '<form action="" method="post">';
	wp_nonce_field('save-profiless-options');
	
	echo 'Lock profile page access for the following user roles :<br />';
	echo '<table class="wats-form-table"><tr><td>';
	$roles = get_editable_roles();
	foreach ($roles AS $role)
	{
		$role_name = strtolower(before_last_bar($role['name']));
		echo '<input type="checkbox" name="locked_profile_roles_'.$role_name.'"';
		if (is_array($profiless_settings) && isset($profiless_settings['locked_profile_roles'][$role_name]) && $profiless_settings['locked_profile_roles'][$role_name] == 1)
			echo ' checked';
		echo '> '.translate_user_role($role['name']).'<br />';
	}
	echo '</td></tr><tr><td></table>';
	
	echo '<input class="button-primary" type="submit" id="profiless_save_options" name="profiless_save_options" value="Save the options" /></form>';

	return;
}

function profiless_remove_profile_access()
{
	global $menu, $current_user, $wp_version, $profiless_settings;

	$profiless_settings = get_option('profiless');
	add_options_page('Profiless','Profiless','administrator','profiless','profiless_options');	
	
	$plugin_url = trailingslashit(get_option('siteurl')) . 'wp-content/plugins/' . basename(dirname(__FILE__)) .'/';
    if (isset($GLOBALS["HTTP_SERVER_VARS"]["REQUEST_URI"]))
		$requesteduri = $GLOBALS["HTTP_SERVER_VARS"]["REQUEST_URI"];
	else
        $requesteduri = getenv('REQUEST_URI');

    $destpage = get_option('siteurl') . '/wp-admin/index.php';
	$result = strpos($requesteduri, '/wp-admin/profile.php');
	$result2 = strpos($requesteduri, '/wp-admin/user-edit.php');
	
	$locked = 0;
	if (is_array($profiless_settings) && isset($profiless_settings['locked_profile_roles']))
	{
		$locked_profile_roles = $profiless_settings['locked_profile_roles'];
		foreach ($locked_profile_roles AS $key => $value)
		{
			if ($value == 1 && current_user_can($key))
				$locked = 1;
		}
    }
	
	if ($locked == 1)
	{
		if ($wp_version >= '2.8')
			unset($menu[70]);
		else
			unset($menu[50]);
	}

    if ((($result !== false) || ($result2 !== false)) && $locked == 1)
        wp_safe_redirect($destpage);

	return;
}

function profiless_init()
{ 
	add_action('admin_menu', 'profiless_remove_profile_access');
}

add_action('plugins_loaded','profiless_init');
?>