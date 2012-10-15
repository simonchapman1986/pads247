<?php
# class admin
#
# class.admin.php
#
# created by Simon Chapman - 29/09/2010

class admin {

	function __construct() {
				
		if (!isset($_SESSION)) {
		  session_start();
		}
		new db;
		
		$chk = new sessions;
		$chks = $chk->check_session();
		
		
		if ($chks == false) {
			if($_GET['admin']!='login') header('Location: '.DOMAIN.'/admin/login');
			new login;
		} else {
			if($_GET['admin']=='login') header('Location: '.DOMAIN.'/admin/home');
			if($_GET['admin']=='logout') $this->logout();
			
			if(isset($_GET['admin'])){
			
				$a = false;
				
				if($this->module_file()!=''){
				
					foreach($this->module_file() as $b) {
						if($_GET['admin']==$b['name']) {
							$a = true;
						}
					}
				}
				
				if($_GET['admin']=='home') $a = true;
				if(isset($_GET['page'])) $a = true;
				if($_GET['admin']=='login') $a = true;
				if($_GET['admin']=='modules') $a = true;
				if($_GET['admin']=='statistics') $a = true;
				if($_GET['admin']=='admin-management') $a = true;
				if($_GET['admin']=='setup') $a = true;
				if($_GET['admin']=='template') $a = true;
				
				if ($a==true){
					new adminpage;
				} else { 
					if($_GET['admin']!='404') header('Location: '.DOMAIN.'/admin/404');
					new admin_404;
				}
			}
		}	
	}
	
	function module_file() {
		$a = '';
		new db;
		$q = mysql_query("SELECT * FROM modules WHERE activated='1'");
		while ($qs = mysql_fetch_array($q)) {
			$a[]=$qs;
		}
		return $a;
	}
	
	function logout() {
		$logoutGoTo = DOMAIN.'/admin';
		if (!isset($_SESSION)) {
		  session_start();
		}
		$_SESSION['username'] = NULL;
		$_SESSION['admin'] = NULL;
		$_SESSION['first_name'] = NULL;
		$_SESSION['last_name'] = NULL;
		$_SESSION['attempts'] = NULL;
		#$_session['started']= NULL;
		unset($_SESSION['username']);
		unset($_SESSION['admin']);
		unset($_SESSION['first_name']);
		unset($_SESSION['last_name']);
		unset($_SESSION['attempts']);
		#unset($_SESSION['started']);
		if ($logoutGoTo != "") {header("Location: $logoutGoTo");
		exit;
		}
	}
}


?>