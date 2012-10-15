<?php
/*
 * 
 * $form = new forms($name,$action,$method,$breaker_between_elements);
 * $form->addInputField(---); // add relevant inputs etc
 * $form->addSubmit(---); // add submit
 * $form->constructHtml(); // complete and form html
 * $form->html; // use public var to export
 * 
 */
class forms {
    private $formName;
    private $fields = array();
    private $action;
    private $method;
    private $breaker;
    public $html = "";
    
    public function __construct($formName=null,$formAction=null,$formMethod="POST",$breaker="<br />") 
    {
        $this->formName = $formName;
        $this->action = $formAction;
        $this->method = $formMethod;
        $this->breaker = $breaker;
    }
    
    public function addInputField($type,$name,$value=null, $options=array())
    {
        if($type===null||$name===null)
        {
            return null;
        }
        $inputoptions = $this->generateOptions($options);
        
        $input = '<input type="'.$type.'" name="'.$name.'" value="'.$value.'" '.$inputoptions.'/>';
        $this->add($input);
    }
    
    public function addTextArea($name='', $cols='5', $rows='5', $text='', $options = array())
    {
        $textoptions = $this->generateOptions($options);
        
        $textarea = '<textarea name="'.$name.'" cols="'.$cols.'" rows="'.$rows.'" '.$textoptions.'>'.$text.'</textarea>';
        $this->add($textarea);
    }
    
    public function addSubmit($value='submit', $name=null, $options=array())
    {
        $submitoptions = $this->generateOptions($options);
        $submit = '<input type="submit" name="'.$name.'" value="'.$value.'" />';
        $this->add($submit);
    }
    
    private function add($field)
    {
        $this->fields[count($this->fields)+1] = $field;
    }
    
    private function generateOptions($options)
    {
        $inputoptions = '';
        if(count($options>0))
        {
            
            foreach($options as $type => $value)
            {
                $inputoptions .= $type.'= "'.$value.'" ';
            }
        }
        return $inputoptions;
    }
    
    public function constructHtml()
    {
        $this->html = '<form name="'.$this->formName.'" action="'.$this->action.'" method="'.$this->method.'">';
        foreach($this->fields as $fields)
        {
            $this->html .= $fields.$this->breaker;
        }
        $this->html .= '</form>';
    }
    
}
?>
