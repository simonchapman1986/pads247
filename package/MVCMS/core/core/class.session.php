<?
# class sessions
#
# class.session.php
#
# created by Simon Chapman - 29/09/2010

class sessions {
	
	function check_session(){
		//check if user is logged in?
		if(isset($_SESSION['username'])){
			return true;
		} else return false;
	}
}
?>
