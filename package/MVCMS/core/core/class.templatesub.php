<?php
# class template
#
# class.template.php
#
# created by Simon Chapman - 29/09/2010

class templatesub {
	
var $err;

	public function __construct($err=false) {
		
		$e=new error;
		
		if (SUBPAGE=='sitemap') $err=false;
		
		# first find out if we are on home or sub
		define('FILE',( $err==true?CMS_404:( SUBPAGE=='home'?CMS_MAIN:( $this->template_file(PAGE .'/'. SUBPAGE)!=''?$this->template_file(PAGE .'/'. SUBPAGE):CMS_404 ) ) ));
		
		if (FILE==CMS_404) {
			if (SUBPAGE!='404') {
				header('Location: '.DOMAIN.'/404');
			}
		}
		
		$template = file_get_contents( DOMAIN.TEMPLATES.FILE );
		$header = file_get_contents( DOMAIN.TEMPLATES.CMS_HEADER ); 
		$footer = file_get_contents( DOMAIN.TEMPLATES.CMS_FOOTER ); 
		
		$group = array($template,$header,$footer);
		
		$k=true;		
		foreach ( $group as $g ) {
			if ( $g === false ) { 
				# treat error
				$error = $e->err('113');
				$k=false;
			}
		}
			
		if ( $k = true ) {	
			
			$pre = new preparser;
			$par = new parsersub;
			
			$out = $header . $template . $footer;
			$ret = $pre->initiate($out);
			$ret = $par->initiate($out);
			
			if (isset($error)) echo $error;

			echo $ret;
		}
	}
	
	function template_file($page) {
		
		new db;
		
		#die($page);
		
		$q = mysql_query("SELECT * FROM pages WHERE url='".$page."' LIMIT 1");
		
		while ($qs = mysql_fetch_array($q)) {
			$a[]=$qs;
		}
		$b=$a[0]['template'];

		return $b;
		
	}

}

?>
