<?
## log watch for updates and amendments to class content
#
# 01/11/2010
# Added to class - level 2 integration with core upgrade for second level pages 
#
# 01/11/2010
# Fixed bug relating to null detection of parent page
#
#
#
#
#
#
#
##
##########################################################
# class class_content
#
# class.content.php
#
# created by Simon Chapman - 04/10/2010

class class_content {
	
	function __construct() {
		new db;
		
		$out = '';
		
		if(isset($_POST['delete'])) {
			mysql_query("DELETE FROM pages WHERE id='".$_POST['row_id']."' LIMIT 1");
			mysql_query("DELETE FROM objectstore WHERE page_id='".$_POST['row_id']."'");
			mysql_query("DELETE FROM keywords WHERE page_id='".$_POST['row_id']."'");
			
			# now to maintain the ordering hierarchy
			if($ords = mysql_query("SELECT * FROM pages ORDER BY ordering ASC")){
				$i=0;
				while ($ods = mysql_fetch_array($ords)){
					mysql_query("UPDATE pages SET ordering='".$i."' WHERE id='".$ods['id']."'");
					$i++;
				}
			}
		}
		
		if(isset($_POST['up'])) {
			$id = $_POST['up'];
			
			$s = mysql_query("SELECT * FROM pages WHERE id='".$id."' AND editable='1' AND status='1' ORDER BY ordering ASC LIMIT 1");
			$ss = mysql_fetch_array($s);
			// $ss = this row
			$our_order = $ss['ordering'];
			$next_order = $our_order-1;
			
			
			$t = mysql_query("SELECT * FROM pages WHERE ordering='".$next_order."' AND editable='1' AND status='1' ORDER BY ordering ASC LIMIT 1");
			$tt = mysql_fetch_array($t);
			// $tt = next row
			$next_id = $tt['id'];
			
			mysql_query("UPDATE pages SET ordering='".$next_order."' WHERE id='".$id."'");
			mysql_query("UPDATE pages SET ordering='".$our_order."' WHERE id='".$next_id."'");
		}
		
		
		if(isset($_POST['down'])) {
			$id = $_POST['down'];
			
			$s = mysql_query("SELECT * FROM pages WHERE id='".$id."' AND editable='1' AND status='1' ORDER BY ordering ASC LIMIT 1");
			$ss = mysql_fetch_array($s);
			// $ss = this row
			$our_order = $ss['ordering'];
			$next_order = $our_order+1;
			
			
			$t = mysql_query("SELECT * FROM pages WHERE ordering='".$next_order."' AND editable='1' AND status='1' ORDER BY ordering ASC LIMIT 1");
			$tt = mysql_fetch_array($t);
			// $tt = next row
			$next_id = $tt['id'];
			
			mysql_query("UPDATE pages SET ordering='".$next_order."' WHERE id='".$id."'");
			mysql_query("UPDATE pages SET ordering='".$our_order."' WHERE id='".$next_id."'");
		}
		
		
		$out .= '
					<h3><a href="">Page Management</a></h3> 
					<table style="background-color: #262626; border-color: #262626;" width="100%"> 
							<tr class="tabletd"> 
									<th align="left">Page Title</th>
									<th align="left">Page URL</th>
									<th align="left">Template</th>
									<th align="left">Order</th>
									<th align="left">Edit</th>
									<th align="left">Delete</th>
							</tr>';
		
		$q = mysql_query("SELECT id, title, url, template, ordering FROM pages WHERE status='1' AND editable='1' ORDER BY ordering ASC");
		
		$num = mysql_num_rows($q);
		$num = $num-1;
		
		$i=0;
		while ($row = mysql_fetch_array($q)){
			$out .= '		<tr class="tabletd">
									<td align="left">'.$row['title'] .'</td>
									<td align="left">'.$row['url'].'</td>
									<td align="left">'.$row['template'].'</td>
									'.
									($row['ordering']=='0'?'<td align="center"><small></small>':
									($i==$num?'<td align="left"><span style="float:left; width:16px; height:16px;"></span>':'
									<td align="left">
									<span style="float:left;">
										<form name="down_order_'.$row['id'].'" action="#start_form" method="post">
											<a href="javascript: document.down_order_'.$row['id'].'.submit()">
												<img src="/core/mvcms/templates/images/arrow_down.png" alt="down" />
											</a>
											<input type="hidden" name="down" value="'.$row['id'].'" />
										</form>
									</span>
									')).'
									'.($i<2?'':'
									<span style="float:left;">
										<form name="up_order_'.$row['id'].'" action="#start_form" method="post">
											<a href="javascript: document.up_order_'.$row['id'].'.submit()">
												<img src="/core/mvcms/templates/images/arrow_up.png" alt="up" />
											</a>
											<input type="hidden" name="up" value="'.$row['id'].'" />
										</form>
									</span>
									').'
									</td>
									<td align="left">
										<form name="edit_'.$row['id'].'" action="#start_form" method="post">
										<input type="submit" name="edits" value="edit" />
										<input type="hidden" name="edit" value="'.$row['id'].'" />
										<input type="hidden" name="row_id" value="'.$row['id'].'" />
										</form>
									</td>
									<td align="left">
										<form name="delete_'.$row['id'].'" action="#start_form" method="post">
											<input type="submit" name="delete" value="delete" />
											<input type="hidden" name="row_id" value="'.$row['id'].'" />
										</form>
									</td>
							</tr>';
			$i++;
		}
							
	
							
		$out .= '	</table>
					<br />
					<hr />
					<br />
				';
		
		if(isset($_POST['submit_page_2'])) {
						
			# first lets find the last in order
			$a = mysql_query("SELECT ordering FROM pages ORDER BY ordering DESC LIMIT 1");
			
			$b = mysql_fetch_array($a);
					
			$c = $b['ordering'];
			
			$c++;
			
			# give us our order number // $c
			
			# now lets make our url
			# first lets check if we are a sub page or not
			if($_POST['parent']>0) {
				# then we are a sub page
				$url_2 = seo_link($_POST['title']);
				
				$url1 = mysql_query("SELECT url FROM pages WHERE id='".$_POST['parent']."'");
				$url_1 = mysql_result($url1,0);
				
				$url = $url_1 . '/' . $url_2;
			} else $url = seo_link($_POST['title']);
			
			if(isset($_POST['edit'])){
				
				if (mysql_query("UPDATE pages SET title='".addslashes($_POST['title'])."', url='".$url."', template='".$_POST['template']."', description='".addslashes($_POST['description'])."'".(isset($_POST['navigation'])?($_POST['navigation']=='on'?", navigation='1'":", navigation='0'"):", navigation='0'").", parent='".$_POST['parent']."' WHERE id='".$_POST['edit']."'")) {
					$out .= '<h3>Page Updated</h3>';
				}
				
			} else {
			
				if (mysql_query("INSERT INTO pages (title, url, template, description, parent, ".($_POST['navigation']!=''?($_POST['navigation']=='on'?"navigation, ":"navigation, "):"")."ordering) VALUES ('".addslashes($_POST['title'])."', '".$url."', '".$_POST['template']."', '".addslashes($_POST['description'])."', '".$_POST['parent']."', '".($_POST['navigation']!=''?($_POST['navigation']=='on'?"1":"0"):"0")."' '".$c."')")){
					$out .= '<h3>Page Inserted</h3>';
				}
			
			}
			
			$d = mysql_query("SELECT id FROM pages WHERE title='".addslashes($_POST['title'])."' AND url='".$url."'");
			
			$e = mysql_fetch_array($d);
			
			$f = $e['id'];
						
			#$f = str_replace(' ', '',$f);
			
			$keys = explode(',',$_POST['keywords']);
			
			# first delete all current keywords for updating
			mysql_query("DELETE FROM keywords WHERE page_id='".$f."'");
			
			foreach($keys as $k) {
				$k = str_replace(' ', '', $k);
				if (mysql_query("INSERT INTO keywords (page_id, keyword) VALUES ('".$f."', '".addslashes($k)."')")){
					$out .= 'Keyword: <b>'.$k.'</b> added<br />';
				}
			}
			
			
			# now we put the data into the db
			$number_of_objects = $_POST['number_of_objects'];
			
			for ($counter=1; $counter<=$number_of_objects; $counter++) {
				
				$content[$counter]=$_POST['content_'.$counter];
				
				
				if (mysql_query("INSERT INTO objectstore (page_id, content, type, editable_area, user) VALUES ('".$f."', '".addslashes($content[$counter])."', 'content', '".$counter."', '".$_SESSION['username']."')")) {
					$out .= '<h3>Objectstore Updated</h3>';
				}
				
				
				$title_content[$counter]=$_POST['title_'.$counter];
				
				
				if (mysql_query("INSERT INTO objectstore (page_id, content, type, editable_area, user) VALUES ('".$f."', '".addslashes($title_content[$counter])."', 'title', '".$counter."', '".$_SESSION['username']."')")) {
					$out .= '<h3>Objectstore Updated</h3>';
				}
				
			}
		
			
			
			header("Location: /admin/content");
		
		} elseif(isset($_POST['submit_page_1'])){
		
		$vals = array();
		$objects = '';
		$n=true;
		foreach($_POST as $post => $val) {
			if ($post!='submit_page_1') {
			$vals[$post]=$val;
			$objects .= '<input type="hidden" name="'.$post.'" value="'.$val.'" />';
			}
			
			if ($val=='') $n=false;
		}

		$xmlstr = file_get_contents('../templates/templates.xml');
		
		$var=$vals['template'];
		
		$xml = new SimpleXMLElement($xmlstr);
		$found = 0;
		
		foreach ($xml->template as $tem) {
			if ($tem->file==$var)
				$found=$tem->editableareas;
		}
		
		$objects .= '<input type="hidden" name="number_of_objects" value="'.$found.'" />';

		if ($n==false) {
			$out .= '<a name="start_form"></a><form name="add_page" method="post" action="/admin/content#start_form"> 
					<h3><a href="">Error</a></h3> 
					<table style="background-color: #262626; border-color: #262626;" width="100%"> 
							<tr class="tabletr"> 
									<td align="left"><input type="submit" name="back" value="Back">
									';
			$out .= $objects;
			$out .= '				</td>
									<td align="center" width="250">please go back and complete all fields marked with an *</td>
							</tr>
					</table>
				</form>
							
				';
		} else {
			
			if(isset($_POST['edit'])) {
				
				# now lets limit the editable areas from the newest so we can retain the object store for edits
				$xmlstr = file_get_contents('../templates/templates.xml');
				$xml = new SimpleXMLElement($xmlstr);
				
				foreach ($xml->template as $temp) {
					if($temp->file==$_POST['template']) {
						$areas = $temp->editableareas;
					}
				}
				
				$content = mysql_query("SELECT * FROM objectstore WHERE page_id='".$_POST['edit']."' AND type='content' ORDER BY dateCreated DESC, editable_area ASC LIMIT ".$areas);

				while ($r = mysql_fetch_array($content)){
					$bob[$r['editable_area']] = $r['content'];
				}
				
				$title = mysql_query("SELECT * FROM objectstore WHERE page_id='".$_POST['edit']."' AND type='title' ORDER BY dateCreated DESC, editable_area ASC LIMIT ".$areas);

				while ($s = mysql_fetch_array($title)){
					$bob_title[$s['editable_area']] = $s['content'];
				}
				
				

			}
			
			$buf = '';
			for ($counter=1; $counter<=$found; $counter++) {
				$buf .= '<b>Title '.$counter.'</b><br />';
				$buf .= '<input type="text" name="title_'.$counter.'" value="'.(isset($bob_title)?stripslashes($bob_title[$counter]):'').'" />';
				$buf .= '<br /><b>Content Area '.$counter.'</b>';
				$buf .= '<textarea name="content_'.$counter.'" cols="30" rows="10">'.(isset($bob)?stripslashes($bob[$counter]):'').'</textarea><br /><br />';
			}
			
			$sel = mysql_query("SELECT * FROM modules WHERE activated='1'");
			$mods = '';
			while ($row = mysql_fetch_array($sel)) {
				$mods .= '<em>'.$row['name'].' --- [module name="'.$row['name'].'" /]</em><br />';
			}
                        
			$out .= '<a name="start_form"></a><form name="add_page" method="post" action="/admin/content#start_form"> 
						<h3><a href="">Add Page Content</a></h3> 
						<table style="background-color: #262626; border-color: #262626;" width="100%"> 
								<tr class="tabletd">  
									<td colspan="2">'.$buf.'</td>
								</tr>	
								<tr class="tabletr"> 
									<td colspan="2" align="left"><input type="submit" name="back" value="Back"><input type="submit" name="submit_page_2" value="Save">
                                                                        '.$objects.'
                                    					</td>
									<td align="right" width="250">please fill in all fields marked with an *</td>
								</tr>
						</table>
					</form>
								
					';
                        $out .= '<div id="floatingMenu">
                                <i style="colour:green; cursor:move;">I\'m draggable, you can move me :)</i>
                                <br /><hr /><br />
                                <b>Modules Available:</b><br />'.$mods.'
                                <br /><hr /><br />
                                <b>Images:</b><br />
                                </div>';
                    }
		} else {
		
		
		if(isset($_POST['edit'])) {
			
			$edit = mysql_query("SELECT * FROM pages WHERE id='".$_POST['edit']."' LIMIT 1");
			$edits = mysql_fetch_array($edit);
			
			$keywords = mysql_query("SELECT * FROM keywords WHERE page_id='".$_POST['edit']."'");
			$words = '';
			while($r = mysql_fetch_array($keywords)){
				$words .= $r['keyword'].',';
			}
			
			$words = substr($words,0,-1);
		}
		
		
		$xmlstr = file_get_contents('../templates/templates.xml');
		# lets get the templates	
		$templates= array();
		$xml = new SimpleXMLElement($xmlstr);
		
		foreach ($xml->template as $tem) {
		$selected=NULL;
			if(isset($_POST['edit'])){
				$checker=$edits['template'];
			} elseif(isset($_POST['back'])) {
				$checker=$_POST['template'];
			}
			
			if(isset($checker)){
				if($tem->file==$checker){
					$selected=' selected="selected" ';
				}
			}
			
		$templates[] = '<option'.(isset($selected)?$selected:' ').'value="'.$tem->file.'">'.$tem->name.'</option>';
		}
		
		# now we will generate our keywords from the content
		$keywords = 'hello this is some random bla bla bla, i am trying to repeat some words here meh meh meh ha ha ha ha to see if it works :)';
		
		$keywords = ereg_replace("[^A-Za-z0-9]", " ", $keywords );
		
		$tags = explode(" ", $keywords);
		$a=array();
		foreach($tags as $t) {
			if (strlen($t)>3) {
				if(array_key_exists($t, $a)) {
					$a[$t]++;
				} else {
					$a[$t] = 1;
				}
			}
		}
		
		arsort($a);
		
		$b = array_slice($a, 0, 10);
		
		$buf = 'Keywords:<br /><br />';
		foreach($b as $c => $d)
			$buf .= '<b>' . $c . '</b> occurances: ' . $d . '<br />';
			
			
		$out .= '<a name="start_form"></a><form name="add_page" method="post" action="/admin/content#start_form"> 
					<h3><a href="">'.(isset($_POST['edit'])?'Edit Page - '.$edits['title']:'Add Page').'</a></h3> 
					<table style="background-color: #262626; border-color: #262626;" width="100%"> 
							<tr class="tabletd"> 
									<th align="left" width="200px">* Page Title</th> 
									<td><input type="text" name="title" value="'.(isset($_POST['back'])?$_POST['title']:(isset($_POST['edit'])?$edits['title']:'')).'" /></td> 
							</tr> 
							<tr class="tabletd"> 
									<th align="left" width="200px">* Description</th> 
									<td><input type="text" name="description" value="'.(isset($_POST['back'])?$_POST['description']:(isset($_POST['edit'])?$edits['description']:'')).'" /></td> 
							</tr> 
							<tr class="tabletd"> 
									<th align="left" width="200px">* Keywords<br /><em><small>use this to override the auto-generator</small></em></th> 
									<td><input type="text" name="keywords" value="'.(isset($_POST['back'])?$_POST['keywords']:(isset($_POST['edit'])?$words:'')).'" /></td> 
							</tr>
							<tr class="tabletd"> 
									<td colspan="2">&nbsp;</td> 
							</tr>
							<tr class="tabletd"?
									<th align="left" width="200px">* Parent Page</th>
									<td>
										<select name="parent">
											<option value="0">-- No Parent --</option>
											';
		
		$parents = mysql_query("SELECT * FROM pages WHERE parent='0'");
		
		while ($par = mysql_fetch_array($parents)) {
			if(isset($_POST['edit'])){
				if ($edits['parent']==$par['id']) { $sel = ' selected="selected" '; } else { $sel = ' '; }
			} else $sel = ' ';
			$out .= '<option'.$sel.'value="'.$par['id'].'">'.$par['title'].'</option>';
		}	
					$out .=	'
										</select>
									</td>
							</tr>
							<tr class="tabletd"> 
									<th align="left" width="200px">* Template</th> 
									<td>
										<select name="template" onchange="call_script();">
											<option value="">--please select a template--</option>
											';
		# add the template options from the xml									
		foreach($templates as $m) {
			$out .= $m;
		}
							
		$out .= '
										</select>
										<span id="template_file">
										<a onclick="call_script()" target="_blank"><img  src="'.DOMAIN.'/core/mvcms/templates/images/preview.png" alt="Preview Template" title="Preview Template" /></a>
										</span>
									</td> 
							</tr>
							<tr class="tabletd">
								<th align="left" width="200px">* Add to Nav</th>
								<td><input type="checkbox" '.(isset($_POST['back'])?(isset($_POST['navigation'])?($_POST['navigation']=='on'?'checked="checked"':''):''):(isset($_POST['edit'])?($edits['navigation']=='1'?'checked="checked"':''):'')).' name="navigation" /></td>
							</tr>
							<tr class="tabletr"> 
									<td align="left"><input type="submit" name="submit_page_1" value="Next">'.(isset($_POST['edit'])?'<input type="hidden" name="edit" value="'.$_POST['edit'].'" />':'').'</td>
									<td align="right" width="250">please fill in all fields marked with an *</td>
							</tr> 
					</table> 
					</form>
					<br />
					<hr />
					<br />
					';
		
		}
		
		$this->data = $out;
	}
	
	function __toString() {
		return (string) $this->data;
	}
	
}