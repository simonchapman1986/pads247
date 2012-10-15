<?
class module_management {
	
	public function start() {
		
		if(isset($_POST['install'])) {
			# time to install a module
			new db;
			$name = $_POST['name'];
			mysql_query("INSERT INTO modules (activated, navigation, configurable, name) VALUES ('1', '0', '1', '".$name."')");
			header('Location: '.DOMAIN.'/admin/modules');
		}
		
		if(isset($_POST['deactivate'])) {
			# time to deactivate a module
			new db;
			$name = $_POST['name'];
			mysql_query("UPDATE modules SET activated='0' WHERE name='".$name."'");
			header('Location: '.DOMAIN.'/admin/modules');
		}
		
		if(isset($_POST['activate'])) {
			# time to deactivate a module
			new db;
			$name = $_POST['name'];
			mysql_query("UPDATE modules SET activated='1' WHERE name='".$name."'");
			header('Location: '.DOMAIN.'/admin/modules');
		}
		
		if(isset($_POST['nav'])) {
			# time to deactivate a module
			new db;
			$name = $_POST['name'];
			mysql_query("UPDATE modules SET navigation='1' WHERE name='".$name."'");
			header('Location: '.DOMAIN.'/admin/modules');
		}
		
		if(isset($_POST['del_nav'])) {
			# time to deactivate a module
			new db;
			$name = $_POST['name'];
			mysql_query("UPDATE modules SET navigation='0' WHERE name='".$name."'");
			header('Location: '.DOMAIN.'/admin/modules');
		}
		
		if(isset($_POST['delete'])) {
			# time to delete a module
			new db;
			$name = $_POST['name'];
			mysql_query("DELETE FROM modules WHERE name='".$name."'");
			header('Location: '.DOMAIN.'/admin/modules');
		}
		
		
		$a='';
		$count=0;
		$options = '';
		foreach (glob(SITE_ADMIN_FOLDER."/modules/class.*") as $filename){
			
			$count++;
			
			$a .= '<div style="width:100%; height:1px; border-bottom:1px dashed #111;"></div><br />';
			
			$b = explode('/', $filename);
			$b = explode('.', $b[2]);
			
			foreach (glob(SITE_ADMIN_FOLDER."/modules/".$b[1].".php") as $module){
				$c = explode('/',$module);
				$c = explode('.',$c[2]);
				$d = $module;				
			}
			$a .= (isset($c[0])?'<a name="'.$c[0].'"></a>':'');
			$a .= '<font color="#336699">class file:</font> '.$filename.'<br />';
			$a .= '<font color="#336699">module file:</font> '.(isset($d)?$d:'<font color="red">Could not detect corresponding module.</font>').'<br />';
			$a .= '<font color="#336699">class name:</font> class_'.$b[1].'<br />';
			$a .= '<font color="#336699">module name:</font> '.(isset($c[0])?$c[0]:'<font color="red">Could not detect corresponding module.</font>').'<br /><br />';
			
			$options .= (isset($c[0])?'<option value="#'.$c[0].'">'.str_replace('_',' ',$c[0]).'</option>':'');
			
			if (isset($d)){
				if (isset($b[1])) {
					# right we have a complete module
					# now we need to check if its installed
					$module_name = $c[0];
					
					new db;
					
					$chk = "SELECT * FROM modules WHERE name='".$module_name."' LIMIT 1";
					
					$chks = mysql_query($chk) or die(mysql_error());
					$found_module = mysql_num_rows($chks);
				
					if ($found_module) {
						while ($f = mysql_fetch_array($chks)) {
							
							if ($f['activated']=='1'){
								include $filename;
								$a .='<span class="success">MODULE INSTALLED</span>
									  <br /><br />
									  <form action="" method="post">
										<input type="hidden" name="name" value="'.$module_name.'" />
										<input type="submit" name="deactivate" value="deactivate" />
										<input type="submit" name="delete" value="delete" />
										'.($f['navigation']=='0'?'<input type="submit" name="nav" value="Add to Navigation" />':'<input type="submit" name="del_nav" value="Remove from Navigation" />').'
									  </form>
									  <br />
									  ';
								$a .= '<a href="#top">Back to Top</a><br />';
							} else {
								$a .='<span class="warning">MODULE DEACTIVATED</span>
									  <br /><br />
									  <form action="" method="post">
										<input type="hidden" name="name" value="'.$module_name.'" />
										<input type="submit" name="activate" value="activate" />
										<input type="submit" name="delete" value="delete" />
									  </form>
									  <br />
									  ';
								$a .= '<a href="#top">Back to Top</a><br />';
							}
						}
								
					} else {
						$a .='<span class="error">MODULE NOT INSTALLED: would you like to install now?</span>
							  <br /><br />
							  <form action="" method="post">
							  	<input type="hidden" name="name" value="'.$module_name.'" />
								<input type="submit" name="install" value="install" />
							  </form>
							  <br />
							  ';
						$a .= '<a href="#top">Back to Top</a><br />';
					}
				}
			}
		
		}
		
		$a .= '<div style="width:100%; height:1px; border-bottom:1px dashed #111;"></div>';
		
		$title = '<h4><b>'.($count>0?$count:'No').'</b> Modules have been detected...</h4><a name="#top"></a>';
		
		
		$buf = $title.'<br /><h3><a href="">Module Management</a>&nbsp;&nbsp;&nbsp;<select name="modules" onchange="window.open(this.options[this.selectedIndex].value,\'_top\')"><option value="#top">-- Jump to Module --</option>'.$options.'</select></h3><br /><br />'.$a;
		
		if (isset($_SESSION['admin'])) {
			if ($_SESSION['admin']=='1') {	
				return $buf;
			} else { 
				return '<br /><span class="error">You do not hold the correct permissions to be here</span>'; 
			}
		} else { 
			return '<br /><span class="error">You do not hold the correct permissions to be here</span>'; 
		}
	}
	
}