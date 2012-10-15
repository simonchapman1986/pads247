<?
# class parser
#
# class.parser.php
#
# created by Simon Chapman - 29/09/2010

class parser {
	
	function initiate($data) {
		 
						
			
		$page = mysql_query("SELECT id, template, title, description FROM pages WHERE url='".PAGE."' LIMIT 1");
		$pages = mysql_fetch_assoc($page);
		$pageid = $pages['id'];
		$template = $pages['template'];
		$pagetitle = $pages['title'];
		$description = $pages['description'];
		
		// seo addition //
		if($pagetitle=='home') {
			// then we are on the home page and want our specified key tag line //
			$tagline = mysql_query("SELECT `value` FROM settings WHERE `type`='main_tag_line'");
			$tag = mysql_fetch_array($tagline);
			$pagetitle = $tag['value'];
		}
		
		
		$keyword = mysql_query("SELECT * FROM keywords WHERE page_id='".$pageid."'");
		$keywords = '';
		while($keys = mysql_fetch_array($keyword)){
			$keywords .= $keys['keyword'].', ';
		}
		$keywords = substr($keywords,0,-2);
		
		# now lets limit the editable areas from the newest so we can retain the object store for edits
		#include('../templates/xml.templates.php');
		$xmlstr = file_get_contents('../templates/templates.xml');
                $xml = new SimpleXMLElement($xmlstr);
		
		foreach ($xml->template as $temp) {
			if($temp->file==$template) {
				$areas = $temp->editableareas;
			}
		}
		
		$content = mysql_query("SELECT * FROM objectstore WHERE page_id='".$pageid."' AND type='content' ORDER BY dateCreated DESC LIMIT ".(isset($areas)?$areas:"0"));
		if($content!=NULL){
			while ($contents = mysql_fetch_assoc($content)) {
				$con[$contents['editable_area']]=$contents['content'];
			}
		}
		if(isset($con)) ksort($con);
		
		if(isset($con)) $cons = array_slice($con, 0, (isset($areas)?$areas:0));
		
		
		$title_con = mysql_query("SELECT * FROM objectstore WHERE page_id='".$pageid."' AND type='title' ORDER BY dateCreated DESC LIMIT ".(isset($areas)?$areas:"0"));
		if($title_con!=NULL){
			while ($title_cons = mysql_fetch_assoc($title_con)) {
				$titles[$title_cons['editable_area']]=$title_cons['content'];
			}
		}
		if(isset($titles)) ksort($titles);
		
		if(isset($titles)) $titles = array_slice($titles, 0, (isset($areas)?$areas:0));
						
		# page content
		switch(PAGE) {
			case 'sitemap':	
				$data = str_replace('[editable1]',$this->sitemap(),$data);
			break;
			
			default:
				if(isset($cons)) {
					foreach ($cons as $blaaa=>$da) {
							$bb = $blaaa+1;
							$data = str_replace('[editable'.$bb.']',$da,$data);
					}
				}
				
				if(isset($titles)) {
					foreach ($titles as $blaaa=>$da) {
							$bb = $blaaa+1;
							$data = str_replace('[title'.$bb.']',$da,$data);
					}
				}
			break;
		}
		
		$data = str_replace('[meta]',$this->get_meta(),$data);
		$data = str_replace('[nav]',$this->get_nav(),$data);
		$data = str_replace('[title]',$pagetitle,$data);
		$data = str_replace('[keywords]',$keywords,$data);
		$data = str_replace('[description]',$description,$data);
		
		# now we check for any modules in place
		# first lets grab all modules
		$module = mysql_query("SELECT * FROM modules WHERE activated='1'");
		
		while ($modules = mysql_fetch_array($module)) {
			
			if (strlen(strstr($data,'[module name="'.$modules['name'].'" /]'))>0) {
				include ('../core/' . SITE_ADMIN_FOLDER . '/modules/' . $modules['name'] . '.php');
                                #$ret = new $modules['name']();
                                #$returned = $ret->start();
				$data = str_replace('[module name="'.$modules['name'].'" /]',$returned,$data);
			}
			
		}
		
		return $data;

	}
	
	function sitemap() {
		
		$out = '<h2>Sitemap</h2>';
		// lets construct our sitemap
		$map = mysql_query("SELECT * FROM pages WHERE status='1' ORDER BY ordering ASC");
		
		$out .= '<ul>';
		$i = 0;
		while ( $row = mysql_fetch_array($map) ) {
			$i++;	
			$out .= '<li><a href="'.$row['url'].'">'.$row['title'].'</a>';
		}
		
		$out .= '</ul>';
		$out .= '<br />Total pages indexed: '.$i;
		return $out;
	}
	
	function gen_slug($str) {
		$slug = strtolower(trim($str));
		$slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
		$slug = preg_replace('/-+/', "-", $slug);
		return $slug;
	}
	
	
	function get_nav() {
		if($nav = mysql_query("SELECT * FROM pages WHERE status=1 AND navigation=1 ORDER BY ordering ASC")){
			$out = '';
			while ($navs = mysql_fetch_array($nav) ) {
				$out .= '<li><a href="'.DOMAIN.'/'.$navs['url'].'"><span class="navs">'.$navs['title'].'</span></a></li>';
			}		
		}
		return $out;		
	}
	
	
	function get_meta() {

		$scripts =  '
		<script type="text/javascript"> 
			function clearText(thefield) {
				if (thefield.defaultValue == thefield.value) { 
					thefield.value = "";
				} else {
					thefield.value = thefield.defaultValue;
				}
			}
		</script>
		';
		
		
		$title = '<title>'.SITE_NAME.' - '.ucfirst(page_title()).'</title>';
		
		$out = $scripts.$title;
		
		return $out;
		
	}

}
?>