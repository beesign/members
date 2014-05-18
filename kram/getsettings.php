<?php require('../../../config.php');
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

$mod_dir = basename(dirname(__FILE__));

// Get id
if(!isset($_GET['section_id']) OR !is_numeric($_GET['section_id'])) {
  die("Location: ".ADMIN_URL."/pages/index.php");
} else {
  $section_id = $_GET['section_id'];  
}

// Get style
$style = 0;
if(isset($_GET['style']) AND is_numeric($_GET['style'])) { $style = $_GET['style'];}
// check if user has permissions to access the Members module
	require_once(WB_PATH.'/framework/class.admin.php');
	$admin = new admin('Modules', 'module_view', false, false);
	if (!($admin->is_authenticated() && $admin->get_permission($mod_dir, 'module'))) 
		{ die("Sorry, no access");} else {echo "//Starting Javascript\n";}

require('module_settings.default.php');
include('../module_settings.php');

// Get header and footer
	$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_members_settings WHERE section_id = '$section_id'");
	$fetch_content = $query_content->fetchRow();
	if ($style==1) {echo "\nthepresetsdescription = 'Short Description here';\n\n";}

	echo "document.edit.pic_loc.value = '".$fetch_content['pic_loc']."';\n";
	echo "document.edit.extensions.value = '".$fetch_content['extensions']."';\n";
	echo 'selectDropdownOption (document.edit.sort_grp_name, '.$fetch_content['sort_grp_name'].");\n";
	echo 'selectDropdownOption (document.edit.delete_grp_members, '.$fetch_content['delete_grp_members'].");\n";
		
	echo 'selectDropdownOption (document.edit.sort_mem_name, '.$fetch_content['sort_mem_name'].");\n";	
	echo 'selectDropdownOption (document.edit.sort_mem_desc, '.$fetch_content['sort_mem_desc'].");\n";
	echo 'selectDropdownOption (document.edit.hide_email, '.$fetch_content['hide_email'].");\n";	
	
	echo "document.edit.t_memberpage_id.value = '".$fetch_content['t_memberpage_id']."';\n";
	echo "document.edit.t_link.value = '".$fetch_content['t_link']."';\n";
	echo "document.edit.t_short1.value = '".$fetch_content['t_short1']."';\n";
	echo "document.edit.t_short2.value = '".$fetch_content['t_short2']."';\n";
	echo "document.edit.t_long1.value = '".$fetch_content['t_long1']."';\n";
	echo "document.edit.t_long2.value = '".$fetch_content['t_long2']."';\n";
	
	
	$longusage1 = 0; $longusage2 = 0;
	if ($html_allowed > 0) {$longusage1 = 1; $longusage2 = 1;}
	if ($html_allowed > 1) {$longusage1 = 2; $longusage2 = 0;}
	if ($html_allowed > 2) {$longusage1 = 2; $longusage2 = 2;}
	
	if ($fetch_content['various_values'] != '') {
		$vv = explode(',',$fetch_content['various_values']);
		$longusage1 = (int) $vv[0];
		$longusage2 = (int) $vv[1];					
	}
	echo 'selectDropdownOption (document.edit.longusage1, '.$longusage1.");\n";
	echo 'selectDropdownOption (document.edit.longusage2, '.$longusage2.");\n";
	
	
	$output = preg_replace("/\r|\n/s", "\\n", $fetch_content['header']);
	echo "document.edit.header.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
	
	$output = preg_replace("/\r|\n/s", "\\n", $fetch_content['footer']);
	echo "document.edit.footer.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
	
	
	$output = preg_replace("/\r|\n/s", "\\n", $fetch_content['grp_head']);
	echo "document.edit.grp_head.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
		
	$output = preg_replace("/\r|\n/s", "\\n", $fetch_content['member_loop']);
	echo "document.edit.member_loop.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
	
	$output = preg_replace("/\r|\n/s", "\\n", $fetch_content['grp_foot']);
	echo "document.edit.grp_foot.value = '".str_replace("\\n\\n", "\\n", $output)."';\n";
	
	if ($style==1) {
		echo "document.getElementById('presetsdescription').innerHTML = thepresetsdescription;\n";
		echo "alert('yepp!');\n";
	} else {
		echo "document.getElementById('getfromdescription').innerHTML = 'Check changed fields';\n";
		echo "alert('Done');\n";
	}
	?>