<?php
/**
 * WordPress基础配置文件。
 *
 * 这个文件被安装程序用于自动生成wp-config.php配置文件，
 * 您可以不使用网站，您需要手动复制这个文件，
 * 并重命名为“wp-config.php”，然后填入相关信息。
 *
 * 本文件包含以下配置选项：
 *
 * * MySQL设置
 * * 密钥
 * * 数据库表名前缀
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/zh-cn:%E7%BC%96%E8%BE%91_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL 设置 - 具体信息来自您正在使用的主机 ** //
/** WordPress数据库的名称 */
//define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/yjdata/www/wordpress/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'XD8NoLuX');

/** MySQL数据库用户名 */
define('DB_USER', 'XD8NoLuX');

/** MySQL数据库密码 */
define('DB_PASSWORD', 'jb7DnmP3MqWr');

/** MySQL主机 */
define('DB_HOST', 'localhost');
define('WEB_HOST','www.xrzwg.cn');

/** 创建数据表时默认的文字编码 */
define('DB_CHARSET', 'utf8mb4');

/** 数据库整理类型。如不确定请勿更改 */
define('DB_COLLATE', '');

/**#@+
 * 身份认证密钥与盐。
 *
 * 修改为任意独一无二的字串！
 * 或者直接访问{@link https://api.wordpress.org/secret-key/1.1/salt/
 * WordPress.org密钥生成服务}
 * 任何修改都会导致所有cookies失效，所有用户将必须重新登录。
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '|q+;uRGh9)T&#sAs7u3O0h!+4nOw^#b#e2.,/e@~l@!]<Ag@mGsqtjs4sI&2Hf5!');
define('SECURE_AUTH_KEY',  'dS`uFJ])Os%1P V0#9GOsy_.j!:$dw.lnnL@+$ZHV9O2gEB(v::Pjv:975^+:D:w');
define('LOGGED_IN_KEY',    'k7KcQn:xp3OV,4dbr]n}s-1Cw{E`~q2!!(}D ^Q+!tU@:~j=a;RHL!zVNHh`k-h{');
define('NONCE_KEY',        'H#WiRs78i0MA4L7NnrU|+|kQA8DP{yZ9wQK{@Pw93vlVn!}-cU>YPi-GWR-;^ngr');
define('AUTH_SALT',        '>nb|a`b+|%}zU.egN^xDWiWw#8>b6}Bb/`yMEs);^^7=Xshq0iIV*MrPos@X~yDK');
define('SECURE_AUTH_SALT', '%0z-r2?;F{#R1 bbU%d: qdOMhJh)OJyy6[f~E-v#ggO0Fg=G0$ak&CQ:3_q:3iX');
define('LOGGED_IN_SALT',   'L6CYd`5Ot7=vy8{blrV+ek-Xa~2|~~cgmM eQ]-B;@Fkb_2yka8!*tb:r*AC8@;/');
define('NONCE_SALT',       '5xATR*Z-y[-#iyrCA2# }h+ynkpSr#lsDZo_,0Nxld[q+:lT2yNi?%^}}GL`U%/e');

/**#@-*/

/**
 * WordPress数据表前缀。
 *
 * 如果您有在同一数据库内安装多个WordPress的需求，请为每个WordPress设置
 * 不同的数据表前缀。前缀名只能为数字、字母加下划线。
 */
$table_prefix  = 'wp_';

/**
 * 开发者专用：WordPress调试模式。
 *
 * 将这个值改为true，WordPress将显示所有用于开发的提示。
 * 强烈建议插件开发者在开发环境中启用WP_DEBUG。
 *
 * 要获取其他能用于调试的信息，请访问Codex。
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);

/**
 * zh_CN本地化设置：启用ICP备案号显示
 *
 * 可在设置→常规中修改。
 * 如需禁用，请移除或注释掉本行。
 */
define('WP_ZH_CN_ICP_NUM', true);
define('DISABLE_WP_CRON', true);

/* 好了！请不要再继续编辑。请保存本文件。使用愉快！ */

/** WordPress目录的绝对路径。 */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** 设置WordPress变量和包含文件。 */
require_once(ABSPATH . 'wp-settings.php');
