<?
# class content
#
# content.php
#
# created by Simon Chapman - 01/10/2010
#
#
# this is an example of bringing data into the site, this is a simple call to the database, extracted how wanted and the returned in the way desired.
#
# basically all html styling etc of the module is done in here

class content {
	
	function start() {
		
		$bla = 'bla bla bla';
		return $bla;
	}
	
}

$ret = new content;
$returned = $ret->start();