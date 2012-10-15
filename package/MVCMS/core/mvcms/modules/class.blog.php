<?php
# class Image Manager
#
# class_imageman.php
#
# created by Simon Chapman - 12/07/2012
#
#
class class_blog {
	
	public function __construct() {
            
            $form = new forms();
            $form->addInputField('text', 'title');
            $form->addTextArea('test', '10', '10', 'this is a test');
            $form->addSubmit('go go go');
            $form->constructHtml();
            $this->data = $form->html;
            
            
            if(isset($_POST['title'])) $this->edit();
        }
        
        private function edit(){
            new db;
            if(mysql_query("UPDATE test_module SET text='".$_POST['title']."' WHERE id='0'")) $this->data = 'EDITED<br>'.$this->data;
            else $this->data = 'FAILED<br>'.$this->data;
        }
	
	public function __toString() {
		return (string) $this->data;
	}
	
}