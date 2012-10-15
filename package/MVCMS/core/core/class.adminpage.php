<?
# class admin page
#
# class.adminpage.php
#
# created by Simon Chapman - 30/09/2010

class adminpage {
	
	public function __construct() {
		
		$set = new settings;
		
		$template = file_get_contents( DOMAIN.ADMIN_TEMPLATES.MODULE );
		
		# replace the tags
		$template = str_replace('[nav]',$this->nav(),$template);
		$template = str_replace('[copyright]',$set->grab('copyright'),$template);
		$template = str_replace('[setup]',(isset($_SESSION['admin'])?($_SESSION['admin']=='1'?'<li><a href="/admin/setup">Setup &amp; Log</a></li>':''):''),$template);
		$template = str_replace('[loginout]',(isset($_SESSION['username'])?'hello ' . ucwords($_SESSION['first_name']) . ' ' . ucwords($_SESSION['last_name']) . ' (' .ucwords($_SESSION['username']).') <a href="/admin/logout">Logout</a>':'<a href="/admin/login">Login</a>'),$template);
		
		$m=new module_management;
		$ad=new admin_management;
		$stat=new statistics;
		
		if ($_GET['admin']=='modules') {
			$template = str_replace('[content]',$m->start(),$template);
			$template = str_replace('[domain]',DOMAIN,$template);
			echo $template;
		} elseif ($_GET['admin']=='admin-management') {
			$template = str_replace('[content]',$ad->start(),$template);
			$template = str_replace('[domain]',DOMAIN,$template);
			echo $template;
                } elseif ($_GET['admin']=='statistics') {
			$template = str_replace('[content]',$stat->start(),$template);
			$template = str_replace('[domain]',DOMAIN,$template);
			echo $template;
		} elseif ($_GET['admin']=='setup') {
			if(isset($_SESSION['admin'])) {
				if($_SESSION['admin']=='1') {
					$help_file = file_get_contents( DOMAIN.'/core/core/setup.txt' );
					$template = str_replace('[content]',$help_file,$template);
					$template = str_replace('[domain]',DOMAIN,$template);
					echo $template;
				} else { header('Location: /admin/404'); }
			} else { header('Location: /admin/404'); }
		} elseif ($_GET['admin']=='template') {
			if (isset($_GET['type'])) {
				# then we start template preview
				$header = file_get_contents( DOMAIN.'/templates/header.html' );
				$template = file_get_contents( DOMAIN.'/templates/'.$_GET['type'].'.html' );
				$footer = file_get_contents ( DOMAIN.'/templates/footer.html' );
				
				echo $header.$template.$footer;
			} else {
				echo '<h3>Could not find the selected template</h3>';	
			}
		} else {
			$template = str_replace('[domain]',DOMAIN,$template);
			if($_GET['admin']!='home') {
				# main call for live modules	
				$glob = $_GET['admin'];
				
				$class_name = 'class_'.$glob;
				$class_file_name = SITE_ADMIN_FOLDER."/modules/class.".$glob.".php";
				
				include $class_file_name;
				
				if (class_exists($class_name)) {
					$content = new $class_name;
				
					if ($content instanceof $class_name) {						
						$template = explode('[content]',$template);
						
						echo $template[0] , '<a name="top"></a>' , $content , '<br /><br /><a href="#top">Back To Top</a>' , $template[1];
					}
				} else {
					$e=new error;
					
					$template = explode('[content]',$template);
						
					echo $template[0] , '<br /><br />' , strval($e->err('119')) , $template[1];
					
				}
			} else {
				
				$template = str_replace('[content]',$this->home(),$template);
				
				echo $template;
			}
		}
		
		
	}
	
	function home() {
		
		$out  = '<h3><a href="">Administration Home</a></h3><br />';
		$out .= 'Welcome to the administration area for the MVCMS. Please navigate through the admin area using the links at the top of the page.';	
		
		return $out;
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