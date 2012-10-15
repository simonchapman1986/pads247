<?
##########################################################
# class seoman
#
# class.seoman.php
#
# created by Simon Chapman - 26/04/2011 <>


class class_seoman {
	
	function __construct() {
		new db;
		if(isset($_POST['add'])) $this->add();
		elseif(isset($_POST['edit'])) $this->edit();
		elseif(isset($_POST['edit_db'])) $this->edit_db();
		elseif(isset($_POST['id'])) $this->remove();
		else $this->start();
	}
	
	private function start() {
		$this->data = '<h2>Add Redirect</h2>';
		
		$this->data .= '
			<form action="" method="post">
				<table>
					<tr><td>Custom uri to redirect from:</td><td><input type="text" name="from" /></td></tr>
					<tr><td>Page to goto:</td><td><input type="text" name="to" /></td></tr>
					<tr><td colspan="2"><input type="submit" name="add" value="add" /></td></tr>
				</table>
			</form>
			';
		
		
		$query = mysql_query("SELECT * FROM redirects ORDER BY id DESC");
		$this->data .= '<h2>Current Redirects</h2>';
		$this->data .= '<table><tr><td><b>Redirect From</b></td><td><b>Redirect To</b></td><td></td></tr>';
		if(mysql_num_rows($query)>0) {
			while($row = mysql_fetch_array($query)) {
				$this->data .= '
				<tr>
					<td>'.$row['from'].'</td><td>'.$row['to'].'</td>
					<td>
						<form action="" method="post">
							<input type="image" src="http://adetize.com/images/buttons/editbutton.png" width="42px" name="" />
							<input type="hidden" value="'.$row['id'].'" name="edit" />
						</form>
					</td>
					<td>
						<form action="" method="post">
							<input type="image" src="http://png.findicons.com/files/icons/49/button/300/button_delete.png" width="30px" name="remove" />
							<input type="hidden" value="'.$row['id'].'" name="id" />
						</form>
					</td>
				</tr>
				';
			}
		} else $this->data .= '<tr><td colspan="3">No redirects in place</td></tr>';
		
		$this->data .='</table>';
		
	}
	
	private function edit() {
		$query = mysql_query("SELECT * FROM redirects WHERE id='".$_POST['edit']."'");
		$data = mysql_fetch_array($query);
		
		$this->data = '<h2>Edit Redirect</h2>';
		$this->data .= '
			<form action="" method="post">
				<table>
					<tr><td>Custom uri to redirect from:</td><td><input type="text" name="from" value="'.$data['from'].'" /></td></tr>
					<tr><td>Page to goto:</td><td><input type="text" name="to" value="'.$data['to'].'" /></td></tr>
					<tr><td colspan="2"><input type="submit" name="edit_db" value="confirm changes" /><input type="hidden" name="id" value="'.$_POST['edit'].'" /></td></tr>
				</table>
			</form>
			';
	}
	
	private function edit_db() {
		if(isset($_POST['from'])&&isset($_POST['to'])) {
			if(mysql_query("UPDATE redirects SET `from`='".$_POST['from']."', `to`='".$_POST['to']."' WHERE id='".$_POST['id']."'")) $this->data = 'added'; else $this->data=mysql_error();
			
		}
		$this->start();
	}
	
	private function add(){
		if(isset($_POST['from'])&&isset($_POST['to'])) {
			if(mysql_query("INSERT INTO redirects (`from`, `to`) VALUES ('".$_POST['from']."', '".$_POST['to']."')")) $this->data = 'added'; else $this->data=mysql_error();	
		}
		$this->start();
	}
	
	private function remove() {
		if(isset($_POST['id'])) {
			if(mysql_query("DELETE FROM redirects WHERE id='".$_POST['id']."'")) $this->data = 'removed'; else $this->data = mysql_error();
		}
		$this->start();
	}
	
	function __toString() {
		return (string) $this->data;
	}
	
}

?>