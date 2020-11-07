<?php 
/**
 * =============================================================================
 * Page header
 * 
 * @author SteamFriends Development Team
 * @version 1.0.0
 * @copyright SourceBans (C)2007 SteamFriends.com.  All rights reserved.
 * @package SourceBans
 * @link http://www.sourcebans.net
 * 
 * @version $Id: header.php 190 2008-12-30 02:06:27Z peace-maker $
 * =============================================================================
 */

global $userbank, $theme, $xajax,$user,$start;
$time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$start = $time;
ob_start(); 

if(!defined("IN_SB"))
{
	echo "You should not be here. Only follow links!";
	die();
}

if(isset($_GET['c']) && $_GET['c']  == "settings")
{
	$theme->assign('tiny_mce_js', '<script type="text/javascript" src="./includes/tinymce/tinymce.min.js"></script>
					<script language="javascript" type="text/javascript">
					tinyMCE.init({
						selector: "textarea",
						branding: false,
						height: 500,
						plugins: "advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table directionality emoticons template paste textpattern imagetools codesample toc",
					});
					</script>');
} else
	$theme->assign('tiny_mce_js', '');

$theme->assign('xajax_functions',  $xajax->printJavascript("scripts", "xajax.js"));
$theme->assign('header_title', $GLOBALS['config']['template.title']);
$theme->assign('header_logo', $GLOBALS['config']['template.logo']);
$theme->assign('username', $userbank->GetProperty("user"));
$theme->assign('logged_in', $userbank->is_logged_in());
$theme->assign('theme_name', isset($GLOBALS['config']['config.theme'])?$GLOBALS['config']['config.theme']:'default');
$theme->display('page_header.tpl');
?>        
