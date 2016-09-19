<?php
/*
Plugin Name: Remove Footer Credit
Version: 0.10
Plugin URI: https://upwerd.com/remove-footer-credit
Description: A simple plugin to remove footer credits
Author: Joe Bill
Author URI: https://upwerd.com
License: GPLv2 or later
Text Domain: remove-footer-credit
*/

/*
Copyright 2016 Joe Bill

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
* Add a submenu under Tools
*/
function jabrfc_admin_menu() {
	$page = add_submenu_page( 'tools.php', 'Remove Footer Credit', 'Remove Footer Credit', 'activate_plugins', 'remove-footer-credit', 'jabrfc_options_page' );
}

function jabrfc_options_page() {
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$_POST = stripslashes_deep( $_POST );

		$data = array(
			'find'  => explode("\n", str_replace("\r", "", $_POST['find'])),
			'replace'  => explode("\n", str_replace("\r", "", $_POST['replace']))
		);

		update_option( 'jabrfc_text', $data );

		echo '<div id="message" class="updated fade">';
			echo '<p><strong>Text/HTML Saved</strong></p>';
		echo '</div>';
	}
?>

<div class="wrap" style="padding-bottom:5em;">
	<h2>Remove Footer Credit</h2>

	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" style="float: left; width:65%;">
		<?php $data = get_option( 'jabrfc_text' ); ?>
		<p><label for="find"> Enter text/HTML to remove (one per line)</label></p>
		<p><textarea name="find" id="find" class="small-text code" rows="13" style="width: 100%;"><?php if ($data['find']) echo implode("\n",$data['find']); ?></textarea></p>
		<p><label for="replace"> Enter text/HTML to replace (one per line)</label></p>
		<p><textarea name="replace" id="replace" class="small-text code" rows="13" style="width:100%"><?php if ($data['replace']) echo implode("\n",$data['replace']); ?></textarea></p>
		<input type="submit" class="button" value="Save" />
	</form>
	<div style="float: right;-align: top; margin-top: 10px; margin-left: 1%;padding: 0 1.5% 1.5% 1.5%;width:30%;background-color: #eee; height: 100%; border: 1px solid #e4e4e4">
		<h3>Get Help</h3>
		<p>Need help using this plugin or want to report a bug? Contact me <a href="https://upwerd.com/#help">here</a>.</p>
		<hr>
		<h3>Learn</h3>
		<p><a href="https://upwerd.com/remove-footer-credit/">Click here</a> to view instructions on how to use this plugin.</p>
		<hr>
		<h3>Donate</h3>
		<p>If you have found this plugin useful, please support me by donating or linking to my website. Thank you for your support.</p>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="L2DY49TRE4BRY">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
		<hr>
		<h3>Link</h3>
		<p>Copy the text below and paste somewhere on your website</p>
		<textarea style="width:100%" rows="5">I <?php echo htmlentities('&hearts;') ?> the <a href="https://upwerd.com/remove-footer-credit">Remove Footer Credit Plugin</a></textarea>

	</div>
	<br>

</div>

<?php }

/*
* Apply find and replace rules
*/
function jabrfc_ob_call( $buffer ) { // $buffer contains entire page

	$data = get_option( 'jabrfc_text' );
	if ( is_array( $data['find']) ) {
		$i = 0;
		foreach ( $data['find'] as &$value ) {
			$buffer = str_replace( $value, (array_key_exists($i, $data['replace']) ? $data['replace'][$i] : ''), $buffer );
			$i++;
		}
	}
	return $buffer;
}

function jabrfc_template_redirect() {
	ob_start();
	ob_start( 'jabrfc_ob_call' );
}

//Add left menu item in admin
add_action( 'admin_menu', 'jabrfc_admin_menu' );

//Handles find and replace for public pages
add_action( 'template_redirect', 'jabrfc_template_redirect' );