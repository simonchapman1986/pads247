<?php
# class error
#
# class.error.php
#
# created by Simon Chapman - 29/09/2010

class error {
	
	public function err($id) {
		switch ( $id ) {
			default:
				$out = 'ERROR 112: unknown code';
			break;
			
			case '113':
				$out = 'ERROR 113: Template file not found';
			break;
			
			case '114':
				$out = 'ERROR 114: Please enter a username';
			break;
			
			case '115':
				$out = 'ERROR 115: Please enter a password';
			break;
			
			case '116':
				$out = 'ERROR 116: You have used all your attempts - please reset your password';
			break;
			
			case '117':
				$out = 'ERROR 117: Attempt number '.$_SESSION['attempts'].'/3 has been used';
			break;
			
			case '118':
				$out = 'ERROR 118: This user does not exist!';
			break;
			
			case '119':
				$out = 'ERROR 119: This module has not been setup correctly.';
			break;
			
			case '120':
				$out = 'ERROR 120: The passwords did not match! Please check and try again.';
			break;
		}
		
		return '<p><span class="error">'.$out.'</span></p>';
	}
	
}
?>