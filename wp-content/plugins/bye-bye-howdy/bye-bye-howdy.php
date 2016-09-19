<?php
/*
Plugin Name: Bye Bye Howdy
Description: Replaces the Howdy text and underlying profile menu with a simple logout link.
Author: Ruud Evers
Author URI: http://www.ruudevers.net/
Plugin URI: http://wordpress.org/plugins/bye-bye-howdy/
License: GPLv2
Version: 2.0
*/

/*
 * Replace 'Howdy' with 'Hello' in English versions of WordPress.
 * Source: TM Replace Howdy (https://wordpress.org/plugins/tm-replace-howdy/)
 */
function bbh_replace_howdy( $wp_admin_bar ) {
	$account  = $wp_admin_bar->get_node( 'my-account' );
	$new_title = str_replace( 'Howdy, ', 'Hello, ', $account->title );
	$wp_admin_bar->add_node(
		array(
			'id'    => 'my-account',
			'title' => $new_title,
		)
	);
}
add_filter( 'admin_bar_menu', 'bbh_replace_howdy', 25 );

/**
 * Remove the My Account drop down items and add a top level log-out button.
 */
function bbh_clean_adminbar() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_node('user-info');
	$wp_admin_bar->remove_node('edit-profile');
	$wp_admin_bar->remove_node('logout');
	$wp_admin_bar->add_node( array(
		'parent' => 'top-secondary',
		'id' => 'logout',
		'title' => '<span class="ab-icon"></span><span class="ab-label">' . __('Log Out') . '</span>',
		'href' => wp_logout_url( ),
		'meta' => false
	));
}
add_action( 'wp_before_admin_bar_render', 'bbh_clean_adminbar', 50 );

/**
* Apply the necessary CSS styling.
*/
function bbh_admin_css() {
  echo '<style>
    #wp-admin-bar-top-secondary {display:flex;}
    #wp-admin-bar-my-account {padding-right:10px;order:1;}
    #wpadminbar li#wp-admin-bar-logout {display:block;order:2;}
    @media screen and (max-width: 782px) {
    	#wpadminbar li#wp-admin-bar-logout {display:block;}
    	#wpadminbar li#wp-admin-bar-logout .ab-icon:before {content:"\f310";}
    	#wpadminbar li#wp-admin-bar-logout .ab-icon:before {-moz-osx-font-smoothing:grayscale;display:block;font:400 32px/1 dashicons;text-align:center;text-indent:0;top:7px;width:52px;}
    }
    @media screen and (min-width: 783px) {
    	#wp-admin-bar-my-account .avatar, #wp-admin-bar-my-account > .ab-item::before {display:none!important;}
    	#wp-admin-bar-my-account > .ab-item::after {content:"\f110";}
    	#wpadminbar li#wp-admin-bar-logout .ab-icon:after {content:"\f310";padding-top:2px!important;}
    	#wpadminbar li#wp-admin-bar-logout .ab-icon {margin-right:0!important;}
    	#wp-admin-bar-my-account > .ab-item::after, #wpadminbar li#wp-admin-bar-logout .ab-icon:after {color:rgba(240, 245, 250, 0.6);position:relative;transition:all 0.1s ease-in-out 0s;-moz-osx-font-smoothing:grayscale;background-image:none!important;float:left;font:400 20px/1 dashicons;margin-right:6px;padding:4px 0;}
    }
  </style>';
}
add_action('admin_head', 'bbh_admin_css');

?>