-- ------------------------------------------------------
-- ------------------------------------------------------
--
-- WPBackItUp Database Export 
--
-- Created: 2016/09/13 on 11:13
--
-- Database : XD8NoLuX
--
-- Backup   Table  : wp_usermeta
-- Snapshot Table  : wp_usermeta
--
-- SQL    : SELECT * FROM wp_usermeta LIMIT 0,10000
-- Offset : 0
-- Rows   : 75
-- ------------------------------------------------------
-- ------------------------------------------------------
use XD8NoLuX;
SET AUTOCOMMIT = 0 ;
SET FOREIGN_KEY_CHECKS=0 ;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Table structure for table `wp_usermeta`
--
DROP TABLE  IF EXISTS `wp_usermeta`;
CREATE TABLE `wp_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



--
-- Data for table `wp_usermeta`
-- Number of rows: 75
--
INSERT INTO `wp_usermeta` VALUES 
(1,1,'nickname','owen'),
 (78,1,'sex',''),
 (3,1,'last_name',''),
 (4,1,'description',''),
 (5,1,'rich_editing','true'),
 (6,1,'comment_shortcuts','false'),
 (7,1,'admin_color','fresh'),
 (8,1,'use_ssl','0'),
 (9,1,'show_admin_bar_front','true'),
 (10,1,'wp_capabilities','a:1:{s:13:"administrator";b:1;}'),
 (11,1,'wp_user_level','10'),
 (12,1,'dismissed_wp_pointers',''),
 (13,1,'show_welcome_panel','1'),
 (31,1,'session_tokens','a:4:{s:64:"c3d8d420d5f9ac2a0cc899ca2cdf612922795dbefb01f1934762f4122c126509";a:4:{s:10:"expiration";i:1474688807;s:2:"ip";s:15:"159.203.228.184";s:2:"ua";s:110:"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36";s:5:"login";i:1473479207;}s:64:"3a4ecc17b1b9194814fdfef7d3e100adf83f69549637bff8a38392a013d45c84";a:4:{s:10:"expiration";i:1474769677;s:2:"ip";s:15:"166.111.163.204";s:2:"ua";s:129:"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.10240";s:5:"login";i:1473560077;}s:64:"39776025f527804a2078801670085be1b77126364da2767111793f76d8d4f579";a:4:{s:10:"expiration";i:1474853130;s:2:"ip";s:15:"159.203.228.184";s:2:"ua";s:110:"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36";s:5:"login";i:1473643530;}s:64:"114de1da46179b0e0376ce313ea32699a57eef0017199638a3a3dad7a94a1b94";a:4:{s:10:"expiration";i:1473910382;s:2:"ip";s:15:"159.203.228.184";s:2:"ua";s:110:"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36";s:5:"login";i:1473737582;}}'),
 (66,2,'meta-box-order_dashboard','a:4:{s:6:"normal";s:16:"dashboard_widget";s:4:"side";s:12:"rwp_meta_id1";s:7:"column3";s:0:"";s:7:"column4";s:0:"";}'),
 (67,2,'closedpostboxes_dashboard','a:0:{}'),
 (68,2,'metaboxhidden_dashboard','a:0:{}'),
 (15,1,'wp_dashboard_quick_press_last_post_id','3'),
 (16,1,'wp_user-settings','libraryContent=browse&editor=tinymce&mfold=o'),
 (17,1,'wp_user-settings-time','1473733024'),
 (19,2,'nickname','291477321-nicheng'),
 (20,2,'first_name','291477321'),
 (21,2,'last_name',''),
 (22,2,'description','sfaasdffds'),
 (23,2,'rich_editing','true'),
 (24,2,'comment_shortcuts','false'),
 (25,2,'admin_color','fresh'),
 (26,2,'use_ssl','0'),
 (27,2,'show_admin_bar_front','true'),
 (28,2,'wp_capabilities','a:1:{s:6:"author";b:1;}'),
 (29,2,'wp_user_level','2'),
 (30,2,'default_password_nag',''),
 (32,1,'managenav-menuscolumnshidden','a:5:{i:0;s:11:"link-target";i:1;s:11:"css-classes";i:2;s:3:"xfn";i:3;s:11:"description";i:4;s:15:"title-attribute";}'),
 (33,1,'metaboxhidden_nav-menus','a:2:{i:0;s:12:"add-post_tag";i:1;s:15:"add-post_format";}'),
 (34,3,'nickname','liujiahe'),
 (35,3,'first_name',''),
 (36,3,'last_name',''),
 (37,3,'description',''),
 (38,3,'rich_editing','true'),
 (39,3,'comment_shortcuts','false'),
 (40,3,'admin_color','fresh'),
 (41,3,'use_ssl','0'),
 (42,3,'show_admin_bar_front','true'),
 (43,3,'wp_capabilities','a:1:{s:6:"author";b:1;}'),
 (44,3,'wp_user_level','2'),
 (45,3,'default_password_nag',''),
 (53,3,'session_tokens','a:2:{s:64:"62219fce6c97af0c476f43c2bbb19cee49a359467a135f818a78ef2f03d40b30";a:4:{s:10:"expiration";i:1474708335;s:2:"ip";s:15:"159.203.228.184";s:2:"ua";s:110:"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36";s:5:"login";i:1473498735;}s:64:"0a9e3c8fea624e8020332d124527da14ba0bd42dc1d3b319c0843d436f9b0a0a";a:4:{s:10:"expiration";i:1473834247;s:2:"ip";s:13:"101.5.104.125";s:2:"ua";s:129:"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.10240";s:5:"login";i:1473661447;}}'),
 (47,2,'wp_user-settings','mfold=o&editor=tinymce&post_dfw=off&libraryContent=browse'),
 (48,2,'wp_user-settings-time','1473764249'),
 (50,2,'wp_dashboard_quick_press_last_post_id','11'),
 (51,2,'bp_xprofile_visibility_levels','a:1:{i:1;s:6:"public";}'),
 (52,2,'last_activity','2016-09-10 09:10:10'),
 (54,3,'last_activity','2016-09-10 09:42:28'),
 (58,3,'wp_user-settings','mfold=o'),
 (55,3,'wp_dashboard_quick_press_last_post_id','39'),
 (56,1,'last_activity','2016-09-10 09:44:21'),
 (61,1,'sb_we_last_sent','1473644601'),
 (59,3,'wp_user-settings-time','1473560541'),
 (60,1,'profile_image_id','6'),
 (63,2,'wp_media_library_mode','list'),
 (64,1,'hu_last_tgmpa_notice','a:2:{s:7:"version";s:5:"3.2.2";s:13:"dismiss_count";i:0;}'),
 (69,2,'session_tokens','a:3:{s:64:"615ffa7fa5ab30563eb5a6065b7c175cf232d1c1e3474c1c067926158f93e021";a:4:{s:10:"expiration";i:1473903728;s:2:"ip";s:15:"166.111.163.204";s:2:"ua";s:129:"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.10240";s:5:"login";i:1473730928;}s:64:"c82b2aabdd79933ecf864ff2289e4fa16b772427425d69ed19b62e7bdbe20814";a:4:{s:10:"expiration";i:1473910359;s:2:"ip";s:15:"159.203.228.184";s:2:"ua";s:110:"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36";s:5:"login";i:1473737559;}s:64:"ecfbe08e3bb9fb042f30bd919b15cbcafa32a87af8e48d2c0b66fb20cf325d6b";a:4:{s:10:"expiration";i:1474966104;s:2:"ip";s:15:"166.111.163.204";s:2:"ua";s:129:"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.10240";s:5:"login";i:1473756504;}}'),
 (70,1,'closedpostboxes_acf','a:0:{}'),
 (71,1,'metaboxhidden_acf','a:1:{i:0;s:7:"slugdiv";}'),
 (72,1,'meta-box-order_acf','a:3:{s:4:"side";s:9:"submitdiv";s:6:"normal";s:43:"acf_fields,acf_location,acf_options,slugdiv";s:8:"advanced";s:0:"";}'),
 (73,1,'screen_layout_acf','2'),
 (74,2,'性别','男'),
 (75,2,'_性别','field_57d76f2d8c355'),
 (76,2,'属性',''),
 (77,2,'_属性','field_57d770a7a8d5f'),
 (79,2,'sex','1'),
 (80,3,'sex',''),
 (81,2,'wp_metronet_post_id','87'),
 (82,2,'wp_metronet_image_id','64'),
 (83,2,'wp_metronet_avatar_override','on');

SET FOREIGN_KEY_CHECKS = 1 ; 
COMMIT ; 
SET AUTOCOMMIT = 1 ; 
