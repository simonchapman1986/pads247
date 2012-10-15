<?
# main config ## this don't change for each site
# useful vars
define('SUFFIX','html');
define('DOMAIN','http://'.$_SERVER['HTTP_HOST']);
define('TEMPLATES','/templates/');
define('IMAGES','/images/');
define('CMS_MAIN','main.'.SUFFIX);
define('CMS_SUB','sub.'.SUFFIX);
define('CMS_404','404.'.SUFFIX);
define('CMS_NAV','nav.'.SUFFIX);
define('CMS_HEADER','header.'./*SUFFIX*/'html');
define('CMS_FOOTER','footer.'./*SUFFIX*/'html');
define('PAGE',(isset($_GET['page'])?$_GET['page']:'home'));
define('SUBPAGE',(isset($_GET['page2'])?$_GET['page2']:''));
define('CSS_FOLDER','/public_html/css/');
define('CSS_GRAPHIC','main.css');
define('CSS_TEXT','text.css');
define('ADMIN_TEMPLATES','/core/'.SITE_ADMIN_FOLDER.'/templates/');
define('LOGIN','login.'.SUFFIX);
define('MODULE','module.'.SUFFIX);
define('ADMIN_404','404.'.SUFFIX);
?>