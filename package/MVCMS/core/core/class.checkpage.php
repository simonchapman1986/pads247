<?php
# class check
#
# class.checkpage.php
#
# created by Simon Chapman - 29/09/2010

# this is a class to check to see if this page does in fact exist in the database, if not we return a 404 error!

class check {
	
	public function __construct() {
            
		$check = mysql_query("SELECT * FROM pages WHERE url = '".PAGE."' LIMIT 1");
		$result = mysql_fetch_assoc($check);
		
		if (empty($result)) {
			# then this page does exist
			new template(true);
		} else {
			# register stat
			date_default_timezone_set('GMT');
                        #include(DOMAIN."/core/core/stats/count_visitors_class.php"); //classes is the map where the class file is stored
			# create a new instance of the count_visitors class.
			$my_visitors = new Count_visitors; 
			
			$my_visitors->delay = 1; // how often (in hours) a visitor is registered in the database (default = 1 hour)
			$my_visitors->insert_new_visit(); // That's all, the validation is with this method, too.
			# we return our result as being false for a 404 error and we can then proceed to call the data later
			new template;
		}
		
	}
	
	private function checkcountry($page) {
		$query = mysql_query("SELECT * FROM country WHERE iso");
		while($row = mysql_fetch_array($query)) {
			$slugged = $this->slug_country($row['printable_name']);
			if($page==$slugged) {
				return true;
				exit;
			}
		}
	}
	
	private function slug_country($str) {
		$slug = strtolower(trim($str));
		$slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
		$slug = preg_replace('/-+/', "-", $slug);
		return $slug;
	}

}

?>