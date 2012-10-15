<?php
# class subpages
#
# class.pages.php
#
# created by Simon Chapman - 29/09/2010

class subpage {

	function __construct() {
				
		# this connect us to the db
		new db;
		
		$parent_id = mysql_query("SELECT id FROM pages WHERE url='".PAGE."' LIMIT 1");
		$parent_ids = mysql_result($parent_id,0);
		
		$page = mysql_query("SELECT * FROM pages WHERE status='1' AND parent='".$parent_ids."' ORDER BY ordering ASC");
		
		$i = 0;
		$pages = array();
		
		while ( $row = mysql_fetch_array($page) ) {
			
			$pages[$i]['id'] = $row ['id'];
			$pages[$i]['title'] = $row ['title'];
			$pages[$i]['url'] = $row['url'];
			$pages[$i]['order'] = $row['ordering'];
			
			$i++;
		
		}
		
		$content = mysql_query("SELECT * FROM objectstore WHERE status=1");
		
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
		$page = new checksub;
		
	}

}


?>