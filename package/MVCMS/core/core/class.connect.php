<?
# class db
#
# class.connect.php
#
# created by Simon Chapman - 29/09/2010

class db {

	function __construct() {
		$hostname_admin = HOST;
		$database_admin = DATABASE;
		$username_admin = USER;
		$password_admin = PASS;
		$admin = mysql_connect( $hostname_admin, $username_admin, $password_admin) or trigger_error(mysql_error(),E_USER_ERROR );

		mysql_select_db( $database_admin, $admin ) or die( mysql_error() );
		
	}

}
?>
