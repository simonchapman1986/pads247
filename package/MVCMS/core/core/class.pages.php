<?php
# class pages
#
# class.pages.php
#
# created by Simon Chapman - 29/09/2010

class page {

	function __construct() {
				
		# this connect us to the db
		new db;
		
		$page = mysql_query("SELECT * FROM pages WHERE status='1' ORDER BY ordering ASC");
		
		$i = 0;
		$pages = array();
		
		while ( $row = mysql_fetch_array($page) ) {
			
			$pages[$i]['id'] = $row ['id'];
			$pages[$i]['title'] = $row ['title'];
			$pages[$i]['url'] = $row['url'];
			$pages[$i]['order'] = $row['ordering'];
			
			$i++;
		
		}
		
		$content = mysql_query("SELECT * FROM objectstore");
		
		$i = 0;
		while ( $row = mysql_fetch_array($content) ) {
			
			foreach ( $pages as $page ) {
				
				if ( $page['id'] == $row['page_id'] ) {
					
					$pg[$i]['id'] = $page['id'];
					$pg[$i]['page_id'] = $row['page_id'];
					$pg[$i]['title'] = $page['title'];
					$pg[$i]['url'] = $page['url'];
					$pg[$i]['content'] = $row['content'];
					$pg[$i]['order'] = $page['order'];
					
				}
			
			}
		
		$i++;
		
		}		
		
		# now lets check what type of page we are on -- grab our db info and return our template
		$page = new check;
		
	}

}


?>