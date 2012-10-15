<?
# class admin_404
#
# class.admin404.php
#
# created by Simon Chapman - 01/10/2010

class admin_management {
	
	public function start() {
		$out ='';
		
		$e=new error;
		new db;
				
		# now we check if we have submitted something
		if (isset($_POST['add_user'])) {
			# then we have submitted the adding a user form
			# this is where we process the edit user info :)
			$chk = array(
							$_POST['username'], 
							$_POST['password'],
							$_POST['password_confirm'],
							$_POST['first_name'],
							$_POST['last_name'],
							$_POST['email']
						);
						
			$i=true;
			
			foreach($chk as $c)
				if ($c=='') $i=false;
				
			
			if ($i==true) {
				# then we have all relevant data! Now lets check for the passwords
				$pw=true;
				
				if($_POST['password']!=$_POST['password_confirm']) {
					$out .= $e->err('120');
					$pw=false;
				}
				
				if ($pw!=false) {
					# then we insert/update into db
					if(isset($_POST['admin']))
						if($_POST['admin']=='on')
							$admin = '1';
						else $admin = '0';
					else $admin = '0';
					
					$up = mysql_query("INSERT INTO admin_users 
									   (username, password, first_name, last_name, email, phone, mobile, admin)
									   VALUES 
									   ('".$_POST['username']."', 
									    '".md5($_POST['password'])."', 
										'".$_POST['first_name']."', 
										'".$_POST['last_name']."', 
										'".$_POST['email']."', 
										'".$_POST['phone']."', 
										'".$_POST['mobile']."',
										'".$admin."')
									   ");
					
					$out .= '<p><span class="accept">User: "'.$_POST['username'].'" has been added!</span></p>';
									   
					
				}
			}
			
		} elseif (isset($_POST['edit_user'])) {
			# this is where we process the edit user info :)
			$chk = array(
							$_POST['username'], 
							$_POST['password'],
							$_POST['password_confirm'],
							$_POST['first_name'],
							$_POST['last_name'],
							$_POST['email']
						);
						
			$i=true;
			
			foreach($chk as $c)
				if ($c=='') $i=false;
				
			$pw = true;
			if ($i==true) {
				# then we have all relevant data! Now lets check for the passwords
				if($_POST['password']!=$_POST['password_confirm']) {
					$out .= $e->err('120');
					$pw=false;
				}
				
				if ($pw!=false) {
					# then we insert/update into db
					if(isset($_POST['admin'])) {
						if($_POST['admin']=='on') {
							$admin = 1;
							#die($admin);
						} else { 
							$admin = 0; 
							#die($admin);
						} 
					} else { 
						$admin = 0; 
						#die($admin);
					}
					
					$up = mysql_query("UPDATE admin_users 
									   SET 
									   username='".$_POST['username']."', 
									   password='".md5($_POST['password'])."', 
									   first_name='".$_POST['first_name']."', 
									   last_name='".$_POST['last_name']."',
									   email='".$_POST['email']."',
									   phone='".$_POST['phone']."',
									   mobile='".$_POST['mobile']."', 
									   admin='".$admin."' 
									   WHERE
									   id='".$_POST['edit_user']."'
									   ");
					
					$out .= '<p><span class="accept">User: "'.$_POST['username'].'" has been updated!</span></p>';
									   
					
				}
			}
					
			
			
		} elseif (isset($_GET['type'])) {
			
			
			if($_GET['type']=='edit') {
			
			
			
					# then we are editing a user
					$sel = mysql_query("SELECT * FROM admin_users WHERE id='".$_GET['id']."'");
					
					while ($u = mysql_fetch_array($sel)) {
					
				$out .='
					<form name="user_edit" method="post" action="/admin/admin-management"> 
					<h3><a href="">Edit User</a></h3> 
					<table style="background-color: #262626; border-color: #262626;" width="100%"> 
							<tr class="tabletd"> 
									<th>* Username</th> 
									<td><input type="text" name="username" value="'.$u['username'].'" /></td> 
							</tr> 
							<tr class="tabletd"> 
									<th>* Password</th> 
									<td><input type="password" name="password" value="" /></td> 
							</tr> 
							<tr class="tabletd"> 
									<th>* Confirm Password</th> 
									<td><input type="password" name="password_confirm" value="" /></td> 
							</tr> 
							<tr class="tabletd"> 
									<th>* Forename</th> 
									<td><input type="text" name="first_name" value="'.$u['first_name'].'" /></td> 
							</tr> 
							<tr class="tabletd"> 
									<th>* Surname</th> 
									<td><input type="text" name="last_name" value="'.$u['last_name'].'" /></td> 
							</tr> 
							<tr class="tabletd"> 
									<th>* Email Address</th> 
									<td><input type="text" name="email" value="'.$u['email'].'" /></td> 
							</tr> 
							<tr class="tabletd"> 
									<th>&nbsp;&nbsp;Telephone Number</th> 
									<td><input type="text" name="phone" value="'.$u['phone'].'" /></td> 
							</tr> 
							<tr class="tabletd"> 
									<th>&nbsp;&nbsp;Mobile Number</th> 
									<td><input type="text" name="mobile" value="'.$u['mobile'].'" /></td> 
							</tr>
							' . (isset($_SESSION['admin'])?($_SESSION['admin']=='1'?
							'<tr class="tabletd">
									<th>* Super Admin</th>
									<td><input type="checkbox"' . ($u['admin']=='1'?' checked="checked" ':' ') . 'name="admin" /></td>
							</tr>'
							:''):'') . '
							<tr class="tabletr"> 
									<td><input type="submit" name="submit" value="Save"><input type="hidden" name="edit_user" value="'.$_GET['id'].'" /></td>
									<td align="right" width="250">please fill in all fields marked with an *</td>
							</tr> 
					</table> 
					</form> 
					 
					<br> 
					 
					<hr> 
					 
					<br>
				';	
					}
				
				} elseif ($_GET['type']=='delete-confirm') {
					# confirm delete
					$del = mysql_query("DELETE FROM admin_users WHERE id='".$_GET['id']."'");
						
				} elseif ($_GET['type']=='delete') {
					# then we are deleting a user (must confirm first)
					$out .= '<br /><center><p>are you sure you want to delete this user?</p>';
					$out .= '<p><a href="/admin/admin-management/delete-confirm/'.$_GET['id'].'">Yes</a>';
					$out .= '&nbsp;or&nbsp;';
					$out .= '<a href="/admin/admin-management">No</a></p></center><br />';
					
			}
			
		} else {
		# form for adding a new user
		$out .='
			<form name="user_add" method="post" action="/admin/admin-management"> 
			<h3><a href="">Add User</a></h3> 
			<table style="background-color: #262626; border-color: #262626;" width="100%"> 
					<tr class="tabletd"> 
							<th>* Username</th> 
							<td><input type="text" name="username" value=""></td> 
					</tr> 
					<tr class="tabletd"> 
							<th>* Password</th> 
							<td><input type="password" name="password" value=""></td> 
					</tr> 
					<tr class="tabletd"> 
							<th>* Confirm Password</th> 
							<td><input type="password" name="password_confirm" value=""></td> 
					</tr> 
					<tr class="tabletd"> 
							<th>* Forename</th> 
							<td><input type="text" name="first_name" value=""></td> 
					</tr> 
					<tr class="tabletd"> 
							<th>* Surname</th> 
							<td><input type="text" name="last_name" value=""></td> 
					</tr> 
					<tr class="tabletd"> 
							<th>* Email Address</th> 
							<td><input type="text" name="email" value=""></td> 
					</tr> 
					<tr class="tabletd"> 
							<th>&nbsp;&nbsp;Telephone Number</th> 
							<td><input type="text" name="phone" value=""></td> 
					</tr> 
					<tr class="tabletd"> 
							<th>&nbsp;&nbsp;Mobile Number</th> 
							<td><input type="text" name="mobile" value=""></td> 
					</tr> 
					' . (isset($_SESSION['admin'])?($_SESSION['admin']=='1'?
					'<tr class="tabletd">
							<th>* Super Admin</th>
							<td><input type="checkbox" name="admin" /></td>
					</tr>'
					:''):'') . '
					<tr class="tabletr"> 
							<td><input type="submit" name="submit" value="Add User"><input type="hidden" name="add_user" value="add" /></td>
							<td align="right" width="250">please fill in all fields marked with an *</td>
					</tr> 
			</table> 
			</form> 
			 
			<br> 
			 
			<hr> 
			 
			<br>
		';
		
		}
		
		# now we bring in the list of current users
		
		$users = mysql_query("SELECT * FROM admin_users");
		
		$out .= '<table style="background-color: #262626;" width="100%">';
		$out .=	'	<tr class="tabletr"> 
						<th>Username</th> 
						<th>Full Name</th> 
						<th>Email</th> 
						<th></th> 
					</tr>
				';
		
		while ($row = mysql_fetch_array($users)) {
			$out .='
					<tr class="tabletd"> 
						<td>'.$row['username'].'</td> 
						<td>'.$row['first_name'].' '.$row['last_name'].'</td> 
						<td>'.$row['email'].'</td> 
						<td align="center" width="40px"> 
							<a href="/admin/admin-management/edit/'.$row['id'].'" style="text-decoration: none;">
								<img src="'.DOMAIN.'/core/'.SITE_ADMIN_FOLDER.'/templates/images/edit.gif" alt="Edit User" title="Edit" />
							</a>
							<a href="/admin/admin-management/delete/'.$row['id'].'" style="text-decoration: none;">
								<img src="'.DOMAIN.'/core/'.SITE_ADMIN_FOLDER.'/templates/images/delete.gif" alt="Delete User" title="Delete" />
							</a> 
						</td> 
					</tr>
					';
		}
		
		$out .= '		</td> 
					</tr> 
				</table>
				';
				
		return $out;
		
	}
	
}