<?
class settings {
	
	public function grab($type) {
		
		new db;
		
		$query 	= mysql_query("SELECT value FROM settings WHERE type='".$type."' LIMIT 1");
		$querys	= mysql_fetch_assoc($query);
		
		$a = (string)$querys['value'];
		
		return $a;
		
	}
	
}