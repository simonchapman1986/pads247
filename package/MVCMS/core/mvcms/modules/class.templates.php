<?php
# class Image Manager
#
# class_imageman.php
#
# created by Simon Chapman - 12/07/2012
#
#
class class_templates {
    
    private $data = '';
	
    public function __construct() {
        $form = new forms();
        $form->addTextArea('test', '100', '1000', $this->getFile('main'));
        $form->addSubmit('save','submit');
        $form->constructHtml();
        $this->data = '<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />'.$form->html;

        if(request('submit')) $this->save();
        if(request('submit')) $this->edit();
    }

    private function getFile($file)
    {
        if(file_exists('../templates/'.$file.'.html')){
            return file_get_contents('../templates/'.$file.'.html');
        } else return 'template not found';
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