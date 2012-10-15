<?php
class statistics {
	
	public function start() {
		
		if(isset($_GET['type'])){
			if($_GET['type']=='clear') {
				if(isset($_SESSION['admin'])){
					if($_SESSION['admin']=='1'){
						mysql_query("DELETE FROM visits WHERE id!='1'");
						header("Location: ".DOMAIN."/admin/statistics");
					} else header("Location: ".DOMAIN."/admin/statistics");
				} else header("Location: ".DOMAIN."/admin/statistics");
			} else header("Location: ".DOMAIN."/admin/statistics");
		}
		
		date_default_timezone_set('GMT');
		$stats = new Count_visitors;
	
		$buf = '
		<h3><a href="#">Visitor statistics</a></h3>
		<table style="background-color: #262626;" width="100%">
		  <tr class="tabletd">
			<td width="20%">First visit at: </td>
			<td width="35%">'.$stats->first_last_visit("first").'</td>
			<td width="20%">Visits today: </td>
			<td>'.$stats->show_visits_today().'</td>
		  </tr>
		  <tr class="tabletd">
			<td>Last visit at: </td>
			<td>'.$stats->first_last_visit("last").'</td>
			<td>Total visits: </td>
			<td>'.$stats->show_all_visits().'</td>
		  </tr>
		</table>
		'.
		$stats->stats_country().
		$stats->stats_totals().
		$stats->stats_monthly(date('m'), date('Y')).
		'<br /><br />
		'.(isset($_SESSION['admin'])?($_SESSION['admin']=='1'?'<a href="'.DOMAIN.'/admin/statistics/clear">Reset All Statistics</a><br />':''):'');
		
		return $buf;
	}
}

//include(DOMAIN."/core/core/stats/config.php");

class Count_visitors {

	var $table_name = 'visits';
	var $referer;
	var $delay = 1;
	
	// niet vergeten visits ouder dan een jaar te verwijderen
	function Count_visitors() {
		$this->referer = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : "";
		new db;
	}
	/* made redundent
	function db_connect() {
		mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD) or die(mysql_error());
		mysql_select_db(DB_NAME);
	}
	*/
	function check_last_visit() {
		$check_sql = sprintf("SELECT time + 0 FROM %s WHERE visit_date = CURDATE() AND ip_adr = '%s' ORDER BY time DESC LIMIT 0, 1", $this->table_name, $_SERVER['REMOTE_ADDR']);
		$check_visit = mysql_query($check_sql);
		$check_row = mysql_fetch_array($check_visit);
		if (mysql_num_rows($check_visit) != 0) {
			$last_hour = date("H") - $this->delay; 
			$check_time = date($last_hour."is");
			if ($check_row[0] < $check_time) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
	function get_country() {
		$country_sql = sprintf("SELECT country FROM ip2nation WHERE ip < INET_ATON('%s') ORDER BY ip DESC LIMIT 0,1", $_SERVER['REMOTE_ADDR']);
		$country_res = mysql_query($country_sql);
		$country = mysql_result($country_res, 0, "country");
		return $country;
	}
	function insert_new_visit() {
		if ($this->check_last_visit()) {
			$insert_sql = sprintf("INSERT INTO %s (id, ip_adr, referer, country, client, visit_date, time, on_page) VALUES (NULL, '%s', '%s', '%s', '%s', CURDATE(), CURTIME(), '%s')", $this->table_name, $_SERVER['REMOTE_ADDR'], $this->referer, $this->get_country(), (isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:''), $_SERVER['PHP_SELF']);
			mysql_query($insert_sql);
		}
	}
	function show_all_visits() {
		$result = mysql_query(sprintf("SELECT COUNT(*) AS count FROM %s WHERE ip_adr!=''", $this->table_name));
		$visits = mysql_result($result, 0, "count");
		return $visits;
	}
	function show_visits_today() {
		$res_today = mysql_query(sprintf("SELECT COUNT(*) AS count FROM %s WHERE visit_date = NOW()", $this->table_name));
		$today = mysql_result($res_today, 0, "count");
		return $today;
	}
	function first_last_visit($type = "last") {
		$order_dir = ($type == "last") ? "DESC" : "ASC";
		$result = mysql_query(sprintf("SELECT visit_date, time FROM %s ORDER BY visit_date %s LIMIT 0,1", $this->table_name, $order_dir));
		$first_last = mysql_result($result, 0, "visit_date");
		$first_last .= " ".mysql_result($result, 0, "time");
		return $first_last;
	}
	function results_by_day($res_month, $res_year) {
		$sql = sprintf("SELECT DAYOFMONTH(visit_date) AS visit_day, COUNT(*) AS visits_count FROM %s WHERE MONTH(visit_date) = %s AND YEAR(visit_date) = %s GROUP BY visit_date", $this->table_name, $res_month, $res_year);
		$result = mysql_query($sql);
		$visits_daily = array();
		while ($obj = mysql_fetch_object($result)) {
			$visits_daily[$obj->visit_day] = $obj->visits_count;
		}
		return $visits_daily;
	}
	function results_by_month() {
		$sql = sprintf("SELECT MONTH(visit_date) AS visits_month, COUNT(*) AS month_count FROM %s GROUP BY MONTH(visit_date) ORDER BY visit_date LIMIT 0,12", $this->table_name);
		$result = mysql_query($sql);
		$visits_monthly = array();
		while ($obj = mysql_fetch_object($result)) {
			$visits_monthly[$obj->visits_month] = $obj->month_count;
		}
		return $visits_monthly;
	}
	function res_country_top() {
		$sql = sprintf("SELECT ip2nationCountries.country AS in_country, COUNT(*) AS visits_country FROM %s AS tbl LEFT JOIN ip2nationCountries ON ip2nationCountries.code = tbl.country WHERE tbl.country <> '' GROUP BY tbl.country ORDER BY 2 DESC LIMIT 0,10", $this->table_name);
		$result = mysql_query($sql);
		$country_top = array();
		while ($obj = mysql_fetch_object($result)) {
			$country_top[$obj->in_country] = $obj->visits_country;
		}
		return $country_top;
	}
	function get_days($from_month, $from_year) {
		$last_day = date("t", mktime(0,0,0,$from_month,1,$from_year));
		$day_count = 1;
		while ($day_count <= $last_day) {

			$days_array[] = $day_count;
			$day_count++;
		}
		return $days_array;
	}
	function create_date($month2, $year2) {
		$date_str = date ("M y", mktime (0,0,0,$month2,0,$year2)); 
		return $date_str;
	}
	function month_last_year() {
		$i = 0;
		while ($i < 12) {
			$twelve_month[$i] = date("n", mktime(0,0,0,date("n")-$i,15,date("Y")));
			$i++;
		}
		return $twelve_month;
	}	
	function build_rows_totals($array_labels, $array_values) {
		$all_values = array_sum($array_values);
		$row = "";
		foreach($array_labels as $label) {
			if (isset($array_values[$label])) {
				$row .= "  <tr>\n";
				$row .= "	   <td>".(is_numeric($label)?$this->month_name($label):$label)."</td>\n";			
				$width = ($array_values[$label]*100)/$all_values;
				$row .= "	   <td><img src=\"".DOMAIN."/core/core/stats/1px.png\" width=\"".round($width*3, 0)."\" height=\"10\"></td>\n";
				$row .= "	   <td>".$array_values[$label]."</td>\n";
				$row .= "  </tr>\n";
			}
		}
		return $row;
	}
	function stats_country() {
		$country_visits = $this->res_country_top();
		$country_array = array_keys($country_visits);
		$country_tbl = "<h3><a href=\"#\">Visits by country (Top ".count($country_array).")</a></h3>\n";
		$country_tbl .= "<table style=\"background-color: #262626;\" width=\"100%\">\n";
		$country_tbl .= "  <tr class=\"tabletd\">\n";
		$country_tbl .= "    <th>Country</th>\n";
		$country_tbl .= "    <th>&nbsp;</th>\n";
		$country_tbl .= "    <th>Visits</th>\n";
		$country_tbl .= "	 </tr>\n";
		$country_tbl .= $this->build_rows_totals($country_array, $country_visits);
		$country_tbl .= "</table>\n";
		return $country_tbl;
	}
	function stats_totals() {
		$month_array = $this->month_last_year();
		krsort($month_array);
		reset($month_array);
		$all_visits_month = $this->results_by_month();
		$total_tbl = "<h3><a href=\"#\">Visits last ".count($all_visits_month)." month".(count($all_visits_month)>1?"s":"")."</a></h3>\n";
		$total_tbl .= "<table style=\"background-color: #262626;\" width=\"100%\">\n";
		$total_tbl .= "  <tr class=\"tabletd\">\n";
		$total_tbl .= "    <th>Month</th>\n";
		$total_tbl .= "    <th>&nbsp;</th>\n";
		$total_tbl .= "    <th>Visits</th>\n";
		$total_tbl .= "	 </tr>\n";
		$total_tbl .= $this->build_rows_totals($month_array, $all_visits_month);
		$total_tbl .= "</table>\n";
		return $total_tbl;
	}
	function month_name($var) {
	
		$months = array ('1'=>'January','2'=>'Febuary','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December');
		
		return $months[$var];
	}
	function stats_monthly($month, $year) {
		$my_visits = $this->results_by_day($month, $year);
		$total_visits = array_sum($my_visits);
		$month_tbl = "<h3><a href=\"#\">Visits in ".$this->create_date($month, $year)." (total: ".$total_visits.")</a></h3>\n";
		$month_tbl .= "<table style=\"background-color: #262626;\" width=\"100%\">\n";
		$month_tbl .= "  <tr class=\"tabletd\">\n";
		foreach($this->get_days($month, $year) as $day) {
			if (isset($my_visits[$day])) {
				$month_tbl .= "	   <td>".$my_visits[$day]."</td>\n";
			} else {
				$month_tbl .= "    <td>-</td>\n";
			}
		}
		$month_tbl .= "	 </tr>\n";
		$month_tbl .= "  <tr class=\"tabletd\">\n";
		foreach($this->get_days($month, $year) as $day) {
			if (isset($my_visits[$day])) {
				$height = (($my_visits[$day]*100)/$total_visits)/4;	// must alter back to 100
				$month_tbl .= "	   <td align=\"center\" valign=\"bottom\"><img src=\"".DOMAIN."/core/core/stats/1px.png\" width=\"10\" height=\"".round($height*20, 0)."\"></td>\n";
			} else {
				$month_tbl .= "    <td>&nbsp;</td>\n";
			}
		}
		$month_tbl .= "	 </tr>\n";
		$month_tbl .= "  <tr class=\"tabletd\">\n";
		foreach($this->get_days($month, $year) as $day) {
			$month_tbl .= "	   <td>".$day."</td>\n";
		}
		$month_tbl .= "  </tr>\n";
		$month_tbl .= "</table>\n";
		return $month_tbl;
	}
}
?>