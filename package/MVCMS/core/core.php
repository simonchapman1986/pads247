<?
error_reporting(-1);
set_include_path('/var/www/html/core/core/lib');
# this is our site specific config details - edit per site
include '../config.php';
# this is the global config settings (no need to touch)
include 'config.php';
# include Zend
#foreach (glob("core/lib/Zend/*.php") as $zfile) if(!preg_match('/Loader/i',$zfile)) include $zfile;
include('core/lib/Zend/Application.php');
new Zend_Application('DEVELOPMENT');
# include main core classes
foreach (glob("core/class.*.php") as $filename) include $filename;
# basic functions lib
include 'core/functions.php';
# include library files
$files = ListIn('core/lib');
foreach($files as $f) {
    if(preg_match('/Zend/i',$f)) {} // we dont need this here
    elseif(preg_match('/mvcms/i',$f)) {} // or this.. these are dealt with seperatly :)
    else include('core/lib/'.$f);
}

# start site
if(isset($_GET['page'])) {
	new db;
        
	$query= mysql_query("SELECT * FROM redirects WHERE `from`='".$_GET['page']."'");
	if(mysql_num_rows($query)>0) {
            while($row=mysql_fetch_array($query)) {
                # altered to suit SEO module

                #header('HTTP/1.1 301 Moved Permanently');
                #header('Location: '.DOMAIN.'/'.$row['to']);
                $newpage = file_get_contents(DOMAIN.'/'.$row['to'].'&seopage=true');

                $keyword = mysql_query("SELECT * FROM redirects WHERE `from`='".$_GET['page']."'");
                $keywords = '';
                $keys = mysql_fetch_array($keyword);
                $keywords = $keys['keywords'];
                $pagetitle = $keys['title'];
                $description = $keys['description'];
                $data = str_replace('[seotitle]',$pagetitle,$newpage);
                $data = str_replace('[seokeywords]',$keywords,$data);
                $data = str_replace('[seodescription]',$description,$data);

                echo $data;
            }
	}
	else {
            if($_GET['page']=='admin') header('Location: '.DOMAIN.'/admin/login');
            elseif(isset($_GET['page2'])) new subpage;
            elseif(isset($_GET['page'])) new page;
        }
}
elseif(isset($_GET['admin'])) new admin;
else new page;

?>