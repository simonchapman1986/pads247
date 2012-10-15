<?php
# class template
#
# class.template.php
#
# created by Simon Chapman - 29/09/2010

class template {
	
var $err;

	public function __construct($err=false) {
		
		$e=new error;
		
		if (PAGE=='sitemap') $err=false;
		#die(PAGE);
		# first find out if we are on home or sub
		$ccc=false;
		$pagecheck = PAGE;
                #die(PAGE);
                define('FILE',( $err==true?CMS_404:( PAGE=='home'?CMS_MAIN:(PAGE=='sitemap'?'sitemap.html':( $this->template_file(PAGE)!=''?$this->template_file(PAGE):CMS_404 ) ) )));

                if (FILE==CMS_404) {
                        if (PAGE!='404') {
                                header('Location: '.DOMAIN.'/404');
                        }
		}


		
		if(isset($_POST)) {
		$gets='?';
		$i=0;
			$count=count($_POST);
			foreach($_POST as $post=>$id) {
				#if($id!='page'||$id!='page2'||$id!='admin'||$id!='type'||$id!='id') {
					$gets .=$id.'='.$post.($i==$count?'':'&');
					$i++;
				#}
			}
		}
		// add request uri string here to allow requests (post/get)
		$template =     file_get_contents( DOMAIN.TEMPLATES.FILE);
		$header = 	file_get_contents( DOMAIN.TEMPLATES.CMS_HEADER); 
		$footer = 	file_get_contents( DOMAIN.TEMPLATES.CMS_FOOTER); 
		
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
			if($ccc==true) $par = new seo_parser; else $par = new parser;
			
			$out = $header . $template . $footer;
			$ret = $pre->initiate($out);
			if($ccc==true) $ret = $par->initiate($out,$pagecheck); else $ret = $par->initiate($out); 
			
			if (isset($error)) echo $error;

			echo $ret;
		}
	}
	
	function template_file($page) {
		
		new db;

		$q = mysql_query("SELECT * FROM pages WHERE url='".$page."' LIMIT 1");
		
		while ($qs = mysql_fetch_array($q)) {
			$a[]=$qs;
		}
		$b=$a[0]['template'];
		
		return $b;
		
	}
        
        private function checkcountry() {
		$a=array();
		$query = mysql_query("SELECT * FROM country");
		while($row = mysql_fetch_array($query)) {
			$a[]=$this->slug_country($row['printable_name']);
		}
		return $a;
	}
	
	function slug_country($str) {
		$slug = strtolower(trim($str));
		$slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
		$slug = preg_replace('/-+/', "-", $slug);
		return $slug;
	}
}

?>
