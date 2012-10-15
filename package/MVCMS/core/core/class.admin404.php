<?
# class admin_404
#
# class.admin404.php
#
# created by Simon Chapman - 30/09/2010

class admin_404 {
	
	public function __construct() {
		
		$set = new settings;
		
		$template = file_get_contents( DOMAIN.ADMIN_TEMPLATES.ADMIN_404 );
		
		$template = str_replace('[nav]',$this->nav(),$template);
		$template = str_replace('[copyright]',$set->grab('copyright'),$template);
		$template = str_replace('[loginout]',(isset($_SESSION['username'])?'hello '.ucwords($_SESSION['username']).' <a href="/admin/logout">Logout</a>':'<a href="/admin/login">Login</a>'),$template);
		
		echo $template;
	}
	
	function nav() {
		
		new db;
		
		$a = mysql_query("SELECT * FROM modules WHERE activated='1' AND navigation='1' ORDER BY ordering ASC");
		$c='';
		
		while($b = mysql_fetch_array($a)) {
		
		$c .='<a href="/admin/'.$b['name'].'">'.str_replace('_',' ',ucwords($b['name'])).'</a>';
		
		}
		$c .='<a href="/admin/admin-management">User Admin</a>';
		$c .=(isset($_SESSION['username'])?'<a href="/admin/logout">Logout</a>':'<a href="/admin/login">Login</a>');
			
		return $c;
		
	}
}