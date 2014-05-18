<?php require('../../../config.php');
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

require_once(WB_PATH.'/framework/class.wb.php');
$wb = new wb;
if ($wb->is_authenticated()) {} else {die("Sorry, no access");}

$memp = '0';
if (isset($_POST['memp'])) { $memp = $_POST['memp']; }
if (!is_numeric($memp) OR $memp < 1) { die('<span style="color:red">This is no page_id!</span>');}



$query_pages = $database->query("SELECT link FROM ".TABLE_PREFIX."pages WHERE page_id = '$memp'");
if($query_pages->numRows() <> 1) { die('<span style="color:red">page_id <b>'.$memp.'</b> not found</span>'); }
$fetch_pages = $query_pages->fetchRow();


$thepage = $fetch_pages['link'];

echo '(found: '.$thepage.PAGE_EXTENSION.')';

?>