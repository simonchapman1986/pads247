<?
# class login
#
# class.login.php
#
# created by Simon Chapman - 29/09/2010

class login {
	
	function __construct() {
		
		$err = '';
		$e=new error;
		
		if( isset( $_POST['username'] ) ) {
			if(getvarspost('username')=='') $err .= strval($e->err('114'));
			if(getvarspost('password')=='') $err .= strval($e->err('115'));
			if(getvarspost('username')!='') {
				
				new db;
				
				$login=sprintf("SELECT id, username, password, login_attempts, admin, first_name, last_name FROM admin_users WHERE status='1' AND username=%s AND password=%s",GetSQLValueString(getvarspost('username'), "text"), GetSQLValueString(md5(getvarspost('password')), "text"));
			
				$logins = mysql_query($login) or die(mysql_error());
				$found_user = mysql_num_rows($logins);
				
				if ($found_user) {
					while($chk = mysql_fetch_array($logins)) {
						$att = $chk['login_attempts'];
						$level = $chk['admin'];
						$first_name = $chk['first_name'];
						$last_name = $chk['last_name'];
						$id = $chk['id'];
					}
					if ($att<3) {
						$_SESSION['username'] = getvarspost('username');
						$_SESSION['admin'] = $level;
						$_SESSION['first_name'] = $first_name;
						$_SESSION['last_name'] = $last_name;
						$_SESSION['admin_id'] = $id;
						mysql_query("UPDATE admin_users SET last_ip='".$_SERVER['REMOTE_ADDR']."', login_attempts='0' WHERE username='".$_SESSION['username']."'");
						header("Location: /admin");
					} else {
						$err .= strval($e->err('116'));
					}
				} else {
					$attempt=sprintf("SELECT username, login_attempts FROM admin_users WHERE status='1' AND username=%s",GetSQLValueString(getvarspost('username'), "text"));
					$attempts = mysql_query($attempt) or die(mysql_error());
					$attempt_found_user = mysql_num_rows($attempts);
					if ($attempt_found_user) {
						while ($a = mysql_fetch_array($attempts)) {
							$b = $a['login_attempts'];
							$user = $a['username'];
						}
						if ($b>=3) {
							$err .= strval($e->err('116'));
						} else {
							$b++;
							$_SESSION['attempts'] = $b;
							mysql_query("UPDATE admin_users SET login_attempts='".$b."' WHERE username='".$user."'");
							$err .= strval($e->err('117'));
						}
					} else {
						$err .= strval($e->err('118'));
					}	
				}	
			}
		}
		
		$set = new settings;
		
		$template = file_get_contents( DOMAIN.ADMIN_TEMPLATES.LOGIN );
		$template = str_replace('[nav]',$this->nav(),$template);
		$template = str_replace('[copyright]',$set->grab('copyright'),$template);
		$template = str_replace('[loginout]',(isset($_SESSION['username'])?'<a href="/admin/logout">Logout</a>':'<a href="/admin/login">Login</a>'),$template);
		
		$k = true;
		
		if ( $template === false ) { 
			echo strval($e->err('113'));
			$k=false;
		}
		
		if ( $k = true ) {
			$pre = new preparser;
			$par = new parser;
			$ret = $pre->initiate($template);
			$ret = $par->initiate($template);
			
			$ret = str_replace('[error]',$err,$ret);

			echo $ret; 
		}
		
	}
	
	function nav() {
		
		$c =(isset($_SESSION['username'])?'<a href="/admin/logout">Logout</a>':'<a href="/admin/login">Login</a>');
			
		return $c;
		
	}

}
?>