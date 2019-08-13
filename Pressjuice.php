<?php
/**
 * @package PressJuice
 * @version 0.0
 */
/*
Plugin Name: PressJuice
Plugin URI: www.hatwalne.com
Description: Pressjuice trackes every user everywhere.
Author: Piyush Hatwalne
Version: 0.0
Author URI: http://www.hatwalne.com
*/



if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }

include( plugin_dir_path( __FILE__ ) . 'admin.php');
include( plugin_dir_path( __FILE__ ) . 'adminajax.php');
//include( plugin_dir_path( __FILE__ ) . 'install.php');
global $pressjuice_db_version;
$pressjuice_db_version = '1.0';
function pressjuice_install() {
	global $wpdb;
	global $pressjuice_db_version;
// create url tracking table
	$table_name = $wpdb->prefix . 'pressjuice';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		username tinytext NOT NULL,
		userrole tinytext NOT NULL,
		ipaddess tinytext NOT NULL,
		url text DEFAULT '' NOT NULL,
		cookie mediumint(9),
		sessioncookie mediumint(15),
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	// create cookie table
	$table_name = $wpdb->prefix . 'pressjuice_cookie';

	$sql = "CREATE TABLE $table_name (
		cookie mediumint(9) NOT NULL AUTO_INCREMENT,
		username tinytext DEFAULT '' NOT NULL,
		PRIMARY KEY  (cookie)
	) $charset_collate;";
	//require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	dbDelta( $sql );

	$table_name = $wpdb->prefix . 'pressjuice_utm';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		username tinytext NOT NULL,
		userrole tinytext NOT NULL,
		ipaddess tinytext NOT NULL,
		url text DEFAULT '' NOT NULL,
		cookie mediumint(9),
		sessioncookie mediumint(15),
		utm_source tinytext NOT NULL,
		utm_medium tinytext,
		utm_campaign tinytext,
		utm_term tinytext,
		utm_content tinytext,
		PRIMARY KEY  (id)
	) $charset_collate;";

	//require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'pressjuice_db_version', $pressjuice_db_version );
	add_option( 'pressjuice_cookie_duration', 365);

	add_option('pressjuice_activation_date',current_time( 'mysql' ));
}

register_activation_hook( __FILE__, 'pressjuice_install' );

function my_plugin_remove_database() {
     global $wpdb;
     $table_name = $wpdb->prefix . 'pressjuice';
     $sql = "DROP TABLE IF EXISTS $table_name;";
     $wpdb->query($sql);
     $table_name = $wpdb->prefix . 'pressjuice_cookie';	
     $sql = "DROP TABLE IF EXISTS $table_name;";
     $wpdb->query($sql);
     $table_name = $wpdb->prefix . 'pressjuice_utm';	
     $sql = "DROP TABLE IF EXISTS $table_name;";
     $wpdb->query($sql);
     delete_option("pressjuice_db_version");
     delete_option( 'pressjuice_cookie_duration');
     delete_option('pressjuice_activation_date');
}    
register_deactivation_hook( __FILE__, 'my_plugin_remove_database' );
// gets user data and send it to database
function pressjuiceh1() {
	global $wpdb;
			$user1=$wpdb->get_var("SELECT username FROM wp2_pressjuice_cookie WHERE cookie = 2 ");

$current_user = wp_get_current_user();
	
    $user=$current_user->user_login;
    $userid=$current_user->ID;
   // echo "<h1>".$user1."</h1><br>";
    //echo "<h1>the admin id is ".is_null($user1)."awed</h1>";
    
$keywords = preg_split("/wp_corn/", "hypertextwp_cornlanguage, programming");
//echo "<h1>asd".$keywords[0]."asd</h1><br>";
	
 }
add_action('wp_footer', 'pressjuiceh1',999);

// function to get client ip address. 
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
// Creating the admin menue



function pressjuice_options_page()
{
    add_menu_page(
        'PressJuice',
        'Pressjuice Options',
        'manage_options',
        'Pressjuiceadminpage',
        'pressjuice_options_page_html',
        'PressJuice/icon.png',
        20
    );
}	

add_action('admin_menu', 'pressjuice_options_page');

function pressjuice_utm_page(){
	add_submenu_page(
		'Pressjuiceadminpage',
		'UTM Analysis',
		'UTM Analysis',
		'manage_options',
		'Pressjuiceadminpage/UTMAnalysis',
		'utm_admin_page'
	);
}

add_action('admin_menu','pressjuice_utm_page');

function utm_admin_page(){
include( plugin_dir_path( __FILE__ ) . 'utmAnalyze.php');

}

function find_utm($text, $url){// only pure text allowed special characters will create regex problems
	
	if (preg_match("/".$text."/", $url)) {

		$utm1 = preg_split("/".$text."\=/", $url);
		$utm = $utm1[1];
		if (preg_match("/&/", $utm)) {
			$utm3=preg_split("/&/", $utm);
			$utm = $utm3[0];
		}
		//echo "//".$utm."//";
	return $utm;
	}
	else{
		return null;
	}

}

// Cookie based tracking
function pressjuice_set_cookie() {
	global $wpdb;
	$current_user = wp_get_current_user();
	$cookie_name = 'pressjuice_cookie';
	$value = 'something from somewhere';
	$cookie_path = parse_url(site_url(),PHP_URL_PATH);
	$table_name = $wpdb->prefix . 'pressjuice_cookie';
	$table_name_press = $wpdb->prefix . 'pressjuice';
	$is_cookie = isset($_COOKIE[$cookie_name]);
	$sessioncookiename = 'pressjuice_sessioncookie';
	$issessioncookie = isset($_COOKIE[$sessioncookiename]);
	$table_name_utm =  $wpdb->prefix . 'pressjuice_utm';
	$ip= get_client_ip();
	$current_user = wp_get_current_user();
	global $wpdb;
   if(!empty($current_user->ID)){
    // echo '<h1>Username: ' . $current_user->user_login . '<br /></h1>';
    $user=$current_user->user_login;
    $role = ( array ) $current_user->roles;
	$userrole = $role[0];
	}
	else{
		$user='not log in';
		$userrole = 'not log in';
	}

	$current_url="//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

$is_utm = false;

	if(preg_match("/\?/", $current_url)){
		$utm_source = find_utm("utm_source",$current_url);
		$utm_medium  = find_utm("utm_medium",$current_url);
		$utm_campaign  = find_utm("utm_campaign",$current_url);
		$utm_term  = find_utm("utm_term",$current_url);
		$utm_content = find_utm("utm_content",$current_url);
		$keywords = preg_split("/\?/", $current_url);
		$current_url=$keywords[0];
		if (!is_null($utm_source)) {
			$is_utm = true;
		}
	}

	
	if(!$is_cookie&&$current_user->exists()){
	 // generate and assign a new cookie here.
	 	
	 
		$wpdb->insert( 
			$table_name, 
			array( 
				'username' => $current_user->user_login, 
			)
		);
		$cookie_value = $wpdb->get_var("SELECT MAX(cookie) FROM $table_name WHERE username = '$current_user->user_login' ");
		 setcookie($cookie_name, $cookie_value, time() + (86400 * 365), $cookie_path);
		 }
	
	elseif (!$is_cookie&&!$current_user->exists()) {
		
	
		$wpdb->insert( 
			$table_name, 
			array(
				'username' => '', 
			)
		);
		$cookie_value = $wpdb->get_var("SELECT MAX(cookie) FROM $table_name WHERE username = '' ");
		 setcookie($cookie_name, $cookie_value, time() + (86400 * 365), $cookie_path);
		}
	elseif ($is_cookie&&$current_user->exists()) {
		$cookie=$_COOKIE[$cookie_name];
		$user1=$wpdb->get_var("SELECT username FROM $table_name WHERE cookie = $cookie ");
		if (is_null($user1)) {
	
			$wpdb->insert( 
					$table_name, 
					array( 
						'username' => $current_user->user_login, 
						'cookie'=>$cookie,

					)
				);
		}
		if(!($user1==$current_user->user_login)){
			if ($user1=='') {
				$wpdb->update(
					$table_name,
					array(
						'username'=>$current_user->user_login
					),
					array(
						'cookie'=>$cookie
					)

				);
					$cookie_value=$_COOKIE[$cookie_name];
				setcookie($cookie_name, $cookie_value, time() + (86400 * 365), $cookie_path);
			}
			else{
				$wpdb->insert( 
					$table_name, 
					array( 
						'username' => $current_user->user_login, 
					)
				);
			$cookie_value = $wpdb->get_var("SELECT MAX(cookie) FROM $table_name WHERE username = '$current_user->user_login' ");
			 setcookie($cookie_name, $cookie_value, time() + (86400 * 365), $cookie_path);
		 	}
		}
		else{
			$cookie_value=$_COOKIE[$cookie_name];
			setcookie($cookie_name, $cookie_value, time() + (86400 * 365), $cookie_path);
			}
	}
	else{
		
		$cookie_value=$_COOKIE[$cookie_name];
		setcookie($cookie_name, $cookie_value, time() + (86400 * 365), $cookie_path);
	}



		if (!$issessioncookie) {
		$cookie_session_value = $wpdb->get_var("SELECT MAX(sessioncookie) FROM $table_name_press");
		 $cookie_session_value=$cookie_session_value+1;
		 setcookie($sessioncookiename, $cookie_session_value,null, $cookie_path);
		 }
		else{
			$cookie_session_value = $_COOKIE['pressjuice_sessioncookie'];
		}
		 	$wpdb->insert( 
			$table_name_press, 
			array( 
			'time' => current_time( 'mysql' ), 
			'username' => $user,
			'userrole' => $userrole,
			'ipaddess' => $ip,
			'url' => $current_url,
			'cookie' => $cookie_value,
			'sessioncookie' => $cookie_session_value,
			) 
		);
		
		if($is_utm){
		$wpdb->insert( 
			$table_name_utm, 
			array( 
			'time' => current_time( 'mysql' ), 
			'username' => $user,
			'userrole' => $userrole,
			'ipaddess' => $ip,
			'url' => $current_url,
			'cookie' => $cookie_value,
			'sessioncookie' => $cookie_session_value,
			'utm_source' => $utm_source, 
			'utm_medium' => $utm_medium,
			'utm_campaign' => $utm_campaign,
			'utm_term' => $utm_term,
			'utm_content' => $utm_content,
			) 
		);
	}

}
add_action('template_redirect','pressjuice_set_cookie');
?>