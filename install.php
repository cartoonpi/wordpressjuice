<?php
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

	//require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
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
//register_activation_hook( __FILE__, 'pressjuice_install_data' );
// end activation

// Delete table when deactivate

?>