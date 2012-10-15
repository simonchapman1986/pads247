<?php
# class Image Manager
#
# class_imageman.php
#
# created by Simon Chapman - 12/07/2012
#
#
class class_test {
	
	public function __construct() {
            $out = '<form action="" method="post"><input type="text" name="text" value="" /><input type="submit" /></form>';
            $this->data = $out;
            
            if(isset($_POST['text'])) $this->edit();
        }
        
        private function edit(){
            new db;
            if(mysql_query("UPDATE test_module SET text='".$_POST['text']."' WHERE id='0'")) $this->data = 'EDITED<br>'.$this->data;
            else $this->data = 'FAILED<br>'.$this->data;
        }
	
	public function __toString() {
		return (string) $this->data;
	}
	
}