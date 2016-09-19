=== Plugin Name ===
Contributors: nsp-code
Donate link: http://www.nsp-code.com/donate.php
Tags: hide, security, improve security, hacking, wp hide, wordpress hide, custom login url, wp-loging.php, ap-admin
Requires at least: 2.8
Tested up to: 4.6
Stable tag: 1.3.5.1
License: GPLv2 or later

Hide and increase Security for your WordPress website instance using smart techniques. No files are changed on your server.

== Description ==

The **easy way to completely hide your WordPress** core files, theme and plugins path from being show on front side. This is a huge improvement over Site Security, no one will know you actually run a WordPress. Provide a simple way to clean up html by removing all WordPress fingerprints.

Change the default WordPress login urls from wp-admin and wp-login.php to something totally arbitrary. No one will ever know where to try to guess a login and hack into your site. Totally invisible !!

When testing with WordPress theme and plugins detector services/sites, any setting change may not reflect right away on their reports, since they use cache. So you may want to check again later, or try a different inner url, homepage url usage is not mandatory.

Being the best content management system, widely used, WordPress is susceptible to a large range of hacking attacks including brute-force, SQL injections, XSS, XSRF etc. Despite the fact the WordPress core is a very secure code maintained by a team of professional enthusiast, the additional plugins and themes makes the vulnerable spot of every website. In many cases, those are created by pseudo-developers who do not follow the best coding practices or simply do not own the experience to create a secure plugin. 
Statistics reveal that every day new vulnerabilities are discovered, many affecting hundreds of thousands of WordPress websites. 
Over 99,9% of hacked WordPress websites are target of automated malware scripts, who search for certain WordPress fingerprints. This plugin hide or replace those traces, making the hacking boots attacks useless.

Works fine with custom WordPress directory structures e.g. custom plugins, themes, uplaods folder.

Once configured, you need to **clear server cache data and/or any cache plugins** (e.g. W3 Cache), for a new html data to be created. If use CDN this should be cache clear as well.

**Main plugin functionality:**

* Custom Admin Url
* Block default admin Url
* Block any direct folder access to completely hide the structure
* Custom wp-login.php filename
* Block default wp-login.php
* Block default wp-signup.php
* Block XML-RPC API
* New XML-RPC path
* Adjustable theme url
* New child Theme url
* Change theme style file name
* Clean any headers for theme style file
* Custom wp-include 
* Block default wp-include paths
* Block defalt wp-content
* Custom plugins urls
* Individual plugin url change 
* Block default plugins paths
* New upload url
* Block default upload urls
* Remove wordpress version
* Meta Generator block
* Disble the emoji and required javascript code
* Remove pingback tag
* Remove wlwmanifest Meta
* Remove rsd_link Meta
* Remove wpemoji

and many more.

**No other plugins functionality is being blocked or interfered in any way, everything will function the same**

This plugin allow to change default Admin Url's from **wp-login.php** and **wp-admin** to something else. All original links return default theme 404 Not Found page, like nothing exists there. Beside the huge security advantage, this save lots of server processing time by reducing php code and MySQL usage since brute-force attacks trigger wrong urls.

**Important:** Compared to all other similar plugins which mainly use redirects, this plugin return a default theme 404 error page for all **block url** functionality, so is not revealing at all the link existence.

Since version 1.2 Change individual plugin urls which make them unrecognizable, for example change default WooCommerce plugin urls and dependencies from domain.com/wp-content/plugins/woocommerce/ to domain.com/ecommerce/cdn/ or anything customized.

= Plugin Sections =

**Rewrite > Theme**

* New Theme Path - Change default theme path
* New Style File Path - Change default style file name and path
* Remove description header from Style file - Replace any WordPress metadata informations (like theme name, version etc) from style file
* Child - New Theme Path - Change default child theme path
* Child - New Style File Path - Change child theme stylesheed file path and name
* Child - Remove description header from Style file - Replace any WordPress metadata informations (like theme name, version etc) from style file

**Rewrite > WP includes**

* New Includes Path - Change default wp-includes path / url
* Block wp-includes URL - Block default wp-includes url

**Rewrite > WP content**

* New Content Path - Change default wp-content path / url
* Block wp-content URL - Block default content url

**Rewrite > Plugins**

* New Plugins Path - Change default wp-content/plugins path / url
* Block plugins URL - Block default wp-content/plugins url
* New path / url for Every Active Plugin
* Custom path and name for any active plugins

**Rewrite > Uploads**

* New Uploads Path - Change default media files path / url
* Block uploads URL - Block default media files url

**Rewrite > XML-RPC**

* New XML-RPC Path - Change default XML-RPC path / url
* Block default xmlrpc.php - Block default XML-RPC url
* Disable XML-RPC authentication - Filter whether XML-RPC methods requiring authentication
* Remove pingback - Remove pingback link tag from theme

**Rewrite > JSON REST**

* Disable JSON REST V1 service - Disable an API service for WordPress which is active by default.
* Disable JSON REST V2 service - Disable an API service for WordPress which is active by default.
* Block any JSON REST calls - Any call for JSON REST API service will be blocked.
* Disable output the REST API link tag into page header
* Disable JSON REST WP RSD endpoint from XML-RPC responses
* Disable Sends a Link header for the REST API

**Rewrite > Root Files**

* New wp-comments-post.php Path
* Block wp-comments-post.php
* Block license.txt - Block access to license.txt root file
* Block readme.html - Block access to readme.html root file
* Block wp-activate.php - Block access to wp-activate.php file
* Block wp-cron.php -  Block access to wp-cron.php file
* Block wp-signup.php - Block default wp-signup.php file
* Block other wp-*.php files - Block other wp-*.php files within WordPress Root

**Rewrite > URL Slash**

* URL's add Slash - Add a slash to any links without. This disguise any existing uppon a file, folder or a wrong url, they all be all slashed.


**General / Html > Meta**

* Remove Generator Meta
* Remove wlwmanifest Meta
* Remove feed_links Meta
* Remove rsd_link Meta
* Remove adjacent_posts_rel Meta
* Remove profile link
* Remove canonical link

**General / Html > Emoji**

* Disable Emoji
* Disable TinyMC Emoji

**General / Html > Styles**

* Remove Version
* Remove ID from link tags

**General / Html > Scripts**

* Remove Version

**General / Html > Headers**

* Remove X-Powered-By Header
* Remove X-Pingback Header

**General / Html > HTML**

* Remove HTML Comments
* Remove general classes from body tag
* Remove ID from Menu items
* Remove class from Menu items
* Remove general classes from post
* Remove general classes from images

**Admin > wp-login.php**

* New wp-login.php - Map a new wp-login.php instead default
* Block default wp-login.php - Block default wp-login.php file from being accesible

**Admin > Admin URL**

* New Admin Url - Create a new admin url instead default /wp-admin. This also apply for admin-ajax.php calls
* Block default Admin Url - Block default admin url and files from being accesible

<br />Something is wrong with this plugin on your site? Just use the forum or get in touch with us at <a target="_blank" href="http://www.wp-hide.com">Contact</a> and we'll check it out.

<br />A website example can be found at <a target="_blank" href="http://nsp-code.com/demo/wp-hide/">http://nsp-code.com/demo/wp-hide/</a>

<br />Plugin homepage at <a target="_blank" href="http://www.wp-hide.com/">WordPress Hide and Security Enhancer</a>

<br />
<br />This plugin is developed by <a target="_blank" href="http://www.nsp-code.com">Nsp-Code</a>

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wp-hide-security-enhancer` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the WP Hide menu screen to configure the plugin.

== Frequently Asked Questions ==

Feel free to contact us at electronice_delphi@yahoo.com

= Something is wrong, what can i do? =

* First, stay calm. There will be no harm, guaranteed :)
* Go to admin and change some of plugin options to see which one cause the problem. Then report it to forum or get in touch with us to fix it.
* If you can't login to admin, use the Recovery Link which has been sent to your e-mail. This will reset the login to default.
* If for some reason the site appear broken, you should back-up then **replace the .htaccess file** located on your WordPress root. Then **remove the wp-hide-security-enhancer** from your plugin directory.

* At this point the site should run as before. If for some reason still not working, you missed something, please get in touch with us at electronice_delphi@yahoo.com and we'll fix it for you in no time!

= I have no PHP knowledge at all, is this plugin for me? =

There's no requirements on php knowledge. All plugin features and functionality are applied automatically, controlled through a descriptive admin interface.

= I can't find a functionality that i'am looking for =

Please get in touch with us and we'll do our best to include it for a next version.

== Screenshots ==

1. Admin Interface.
2. Sample front html code.

== Changelog ==

= 1.3.5.1 =
* Fix the Remove general classes from images component when within admin dashboard

= 1.3.5 =
* New component element : Remove general classes from body tag
* New component element : Remove general classes from post
* New component element : Remove general classes from images
* New component: JSON REST
* Disable JSON REST V1 service
* Disable JSON REST V2 service
* Block any JSON REST calls
* Disable output the REST API link tag into page header
* Disable JSON REST WP RSD endpoint from XML-RPC responses
* Disable Sends a Link header for the REST API
* Improved Styles and Scripts version remove
* Speed improvements
* Set Last-Modified header attribute for routed files
* Moved the plugins custom paths from 'plugins_url' filter to class init to allow replacements to occur when HTML has been created.
* Removed 'admin_url' and 'wp_default_scripts' filters to allow replacement at the end, within the buffer
* Updated PO / MO translation files

= 1.3.4 =
* Improved replacement code for Uploads, when "Block uploads URL" is set for "No" it use default media urls within the admin editor, to ensure links are still functional if plugin is disabled.
* Priority (high, normal, low) for replacement urls
* Replacement Urls for gziped buffer
* Fix rule name for child_style_file_clean on web.config IIS
* bbPress Compatibility
* BuddyPress Compatibility
* Prevent replacements on wp_redirect filter if is_404()
* Updated PO / MO translation files
* Removed in line components filters which changed the urls, leave for end buffering to make all changes.
* Fix for mod_rewrite line on child theme when router is turned Off, append the default style.css filename
* Improvements for Templates default variables to match customized themes like Sage
* Compatibility fix for Super Cache plugin ob callback
* Add IfModule mod_env.c before set nSetEnv HTTP_MOD_REWRITE On to prevent server internal error in case mod_env module is not available
* Check for Empty $saved_field_data within new plugin path component, to avoid creating rewrite rule if empty and existent path
* Improved get_home_path()
* Replacements for Relative URL's

= 1.3.3.2 =
* DOMDocument encoding fix for "Remove the autogenerated meta generator"

= 1.3.3.1 =
* DOMDocument encoding fix for "remove styles links attribute"

= 1.3.3 =
* Improve Remove Generator Meta - Use DOMDocument to remove any meta generator tag
* New Component Item - Remove X-Pingback Header
* New functionality, Remove ID from Menu items, Remove class from Menu items
* Add short default replacement for wp-login.php
* Filter all email content (message argument) through wp_mail for any require replacements
* New action wp-hide/add_default_replacements
* New functionality - Remove ID attribute from all link tags which include a stylesheet.
* Separate tabs for Styles and Scripts
* Update engine improvements
* Fix for apache_mod_loaded function not being loaded on plugin update
* Replace spaces within paths for theme rewrite component

= 1.3.1 =
* Moved the Disable XML-RPC authentication within Rewrite -> XML-RPC
* HTML Comments strip out will trigger only on front side, no need for admin
* wp-cron.php block / allow access new setting
* New style file name now include default / new theme path to avoid 404 resource loading when using internally relative urls.
* Modules Menu order fix
* Writable check notification improvements for htaccess / web.config file
* Alternative request headers when apache_response_headers  for LEMP / PHP-FPM
* IIS windows server type compatibility
* Rewrite rules for IIS servers with web.config set-up
* apache_response_headers and headers_list PHP functions check if available within the server
* Code Version add and updater class structure update
* WriteCheckString check fix when .htaccess not exists
* Remove description header from Style file
* Router Engine - files post-processing
* Separate theme, style, style proxy setting for parent and child

= 1.2.9 =
* Load plugin styles and scripts only when one of plugin admin menus
* Use default_value when input field is empty
* Reset All Settings button for reverting all options to default
* Fix - double slash in plugin path when usee plugins_url filter
* Individual plugins path processing before general plugin path
* New component - URL Slash
* Update - New Style File Path - apply when theme path already changed
* Fix: Plugins path module, check if $path variable is not "/" instead empty
* Default add backslash rule rule, check if not redirect to prevent infinite loops

= 1.2.6 =
* New Component - HTML Comments replace
* New Component - Headers
* Conflict Handle with W3 Cache plugin when pagecache is active
* W3 Cache plugin buffer use when active
* Show notice when rules could not be delivered to htaccess file
* Disable include filters and leave the buffering urls replacements to allow other plug ins to use default urls for compatibility purpose(e.g. W3-Cache Minify)
* Fix: plugin folder / textdomain change
* Early Buffering start, before any other code
* Recovery link code functionality improvements
* New wp-comments-post.php Path
* Fix: Decrease the processing order index for wp-content module to allow others to run earlier than wp-content
* Add mod_rewrite rules monitor system
* Check if the mod_rewrite rules where successfully written to .htaccess file or disable any component run

= 1.2.2 =
* New Content Path
* New Component : Root Files
* Block license.txt
* Block readme.html
* Block wp-activate.php
* Block wp-signup.php
* Block other wp-*.php files
* licence.txt and readme.html block
* PO translations update

= 1.2 =
* New Feature Change individual plugin url path
* Admin layout improvments
* Fix for Admin canonical filter remove if remove canonical option set
* PO translations update
* Translation

= 1.1.7 =
* Remove profile link meta tag within head.
* Remove canonical link meta tag within head
* New XML-RPC Path
* Block default xmlrpc.php
* Remove pingback tag
* Recovery link for default wp-login.php and admin urls
* Css changes and warning messages update
* PO translations update
* TinyMCE emojicons callback fix

= 1.1.2 =
* Add a custom url for login_url filter
* Better description and warning for wp-login.php change
* Add default replacement for uploads
* conflict handle - Security Firewall (WordPress Security Firewall) >  Login Protection > Rename WP Login Page functionality
* wp-includes block when not logged-in
* wp-content block when not logged-in
* readme update

= 1.1 =
* Po / Mo localisation files update
* Update class to process the further structure changes and current components fields name change.
* New Component : Wp-content folder access block
* New Component : Block default wp-signup.php file from being accesible.
* Fix: New admin url save when permalinks disable. keep on default admin url instead redirect.
* Rewrite Default mod_rewrite code, append slashes to all urls to avoid actual directory reveal
* Send e-mail notification when admin e-mail change, to prevent url forget / lose
* New Component Disable Emoji
* New Component Disable TinyMC Emoji
* Structure change on the modules, split into chunks called components
* Code Clean-up
* Set processing order for component settings to allow mod_rewrite rules placement at certain position related to another line
* Improved Template dir when child theme is active
* Allow parent theme / child theme rewrite
* mod_rewrite change for 404 error, set for WordPress internal 404 error page instead default server

= 1.0.4 =
* Text Domain fix from wp-hide to wp-hide-security-enhancer

= 1.0.3 =
* Certain sections improvments and code redo
* Admin module cleanup
* removed block for wp-include
* Removed router functionality
* Created Change relative urls within load-style block, load the tyles on a separate file to change the links

= 1.0 =
* Initial release.

== Upgrade Notice ==

Always keep plugin up to date.


== Localization ==
Please help and translate this plugin to your language at https://translate.wordpress.org/projects/wp-plugins/wp-hide-security-enhancer

Please help by promoting this plugin with an article on your site or any other place. If you liked this code or helped on your your project, consider to leave a 5 star review on this board.