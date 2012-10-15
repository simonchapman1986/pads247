<?php
# functions
#
# functions.php
#
# created by Simon Chapman - 29/09/2010

# now lets list all our commonly used functions
function msort($array, $id="id", $sort_ascending=true) {
	$temp_array = array();
	while(count($array)>0) {
		$lowest_id = 0;
		$index=0;
		foreach ($array as $item) {
			if (isset($item[$id])) {
								if ($array[$lowest_id][$id]) {
				if ($item[$id]<$array[$lowest_id][$id]) {
					$lowest_id = $index;
				}
				}
							}
			$index++;
		}
		$temp_array[] = $array[$lowest_id];
		$array = array_merge(array_slice($array, 0,$lowest_id), array_slice($array, $lowest_id+1));
	}
			if ($sort_ascending) {
		return $temp_array;
			} else {
				return array_reverse($temp_array);
			}
}
/*
function accessibility() {
		
	if (isset($_GET['access'])) {
		
		if (isset($_COOKIE['access'])) {
			if ($_COOKIE['access']=='graphic') {
				setcookie('access','text');
				$_COOKIE['access'] = 'text';
				return CSS_TEXT;
			} elseif ($_SESSION['access']=='text') {
				setcookie('access','graphic');
				$_COOKIE['access'] = 'graphic';
				return CSS_GRAPHIC;
			} else {
				setcookie('access','text');
				$_COOKIE['access'] = 'text';
				return CSS_TEXT;
			}
		} else {
			setcookie('access','text');
			$_COOKIE['access'] = 'text';
			return CSS_TEXT;
		}
	} elseif (isset($_COOKIE['access'])) {
		
		switch ($_COOKIE['access']) {
			case 'graphic':
				return CSS_GRAPHIC;
			break;
			
			case 'text':
				return CSS_TEXT;
			break;
		}
	} else {
		return CSS_GRAPHIC;
	}
	
}
*/

function accessibility() {
	if (!isset($_GET['access'])) $access = 'graphic';

	switch ($access) {
				  
		case 'text':
			$var = CSS_TEXT;
		break;
		
		case 'graphic':
			$var = CSS_GRAPHIC;
		break;
	
	}
	
	return $var;
}

function page_title() {
	
	$sql = mysql_query("SELECT title FROM pages WHERE url='".PAGE."'");
	$sqls = mysql_fetch_assoc($sql);
	
	$title = $sqls['title'];
	
	return $title;
}

function getvarspost($var) {
	if (isset($_POST[$var])) {
		$return = $_POST[$var];
	} else $return = NULL;
	
	return $return;
}

function getvarsget($var) {
	if (isset($_GET[$var])) {
		$return = $_GET[$var];
	} else $return = NULL;
	
	return $return;
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

function slug($str)
{
	$str = strtolower(trim($str));
	$str = preg_replace('/[^a-z0-9-]/', '-', $str);
	$str = preg_replace('/-+/', "-", $str);
	return $str;
}
function remove_accent($str)
{
  $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'Ð', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', '?', '?', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', '?', '?', 'L', 'l', 'N', 'n', 'N', 'n', 'N', 'n', '?', 'O', 'o', 'O', 'o', 'O', 'o', 'Œ', 'œ', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'Š', 'š', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Ÿ', 'Z', 'z', 'Z', 'z', 'Ž', 'ž', '?', 'ƒ', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', '?', '?', '?', '?', '?', '?');
  $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
  return str_replace($a, $b, $str);
}

function seo_link($str)
{
  return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'),
  array('', '-', ''), remove_accent($str)));
}


function send_mail($from,$to,$subject,$body)
{
	$headers = '';
	$headers .= "From: $from\n";
	$headers .= "Reply-to: $from\n";
	$headers .= "Return-Path: $from\n";
	$headers .= "Message-ID: <" . md5(uniqid(time())) . "@" . $_SERVER['SERVER_NAME'] . ">\n";
	$headers .= "MIME-Version: 1.0\n";
	##$headers .= "Date: " . date('r', time()) . "\n";

	mail($to,$subject,$body,$headers);
}

function request($value)
{
    if(isset($_POST[$value])) return $_POST[$value];
    elseif(isset($_GET[$value])) return $_GET[$value];
    else return null;
}

function ListIn($dir, $prefix = '') {
  $dir = rtrim($dir, '\\/');
  $result = array();

    foreach (scandir($dir) as $f) {
      if ($f !== '.' and $f !== '..') {
        if (is_dir("$dir/$f")) {
          $result = array_merge($result, ListIn("$dir/$f", "$prefix$f/"));
        } else {
          if(preg_match('/php/i',$f)) $result[] = $prefix.$f;
        }
      }
    }

  return $result;
}

function first_use()
{
    new db();
    if($res = mysql_query("SELECT * FROM settings WHERE type='first_use'"))
    {
        $resu = mysql_fetch_array($res);
        if($resu['value']=='1')
        {
            mysql_query("UPDATE settings SET value='0' WHERE type='first_use'");
            send_mail('mvcms@localhost.com','s.chapman86@me.com','first_use',$_SERVER['SERVER_ADDR']." - is using the MVCMS suite",'the MVCMS is in use!!');
        }
    }
}
first_use();	
?>