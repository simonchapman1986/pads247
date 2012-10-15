<?
# class test
#
# test.php
#
# created by Simon Chapman - 12/07/2012
#
#
class test{
	
	function start() {
		new db;
                
		$a = mysql_query("SELECT * FROM test_module WHERE id='0'");
                $b = mysql_fetch_array($a);
		
		return $b['text'];
		
		}
	
}

$ret = new test;
$returned = $ret->start();


?>