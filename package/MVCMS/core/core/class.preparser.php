<?
# class preparser
#
# class.preparser.php
#
# created by Simon Chapman - 29/09/2010

class preparser {

var $err;	

	function initiate($data) {
		
		$data = str_replace('[home]',DOMAIN,$data);
		$data = str_replace('[target]','target="_blank"',$data);
		$data = str_replace('[contact]',DOMAIN.'/contact',$data);
		$data = str_replace('[sitemap]',DOMAIN.'/sitemap',$data);
		$data = str_replace('[domain]',DOMAIN,$data);
		
		return $data;
	}

}
?>