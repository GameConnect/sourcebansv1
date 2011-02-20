<?php
/**
 * Update an icon
 * 
 * @author    SteamFriends, InterWave Studios, GameConnect
 * @copyright (C)2007-2011 GameConnect.net.  All rights reserved.
 * @link      http://www.sourcebans.net
 * @package   SourceBans
 * @version   $Id$
 */

include_once("../init.php");
include_once("../includes/system-functions.php");
global $theme, $userbank;

if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_MODS|ADMIN_ADD_MODS))
{
    $log = new CSystemLog("w", "Hacking Attempt", $userbank->GetProperty('user') . " tried to upload a mod icon, but doesn't have access.");
	echo 'You don\'t have access to this!';
	die();
}

$message = "";
if(isset($_POST['upload']))
{
	if(CheckExt($_FILES['icon_file']['name'], "gif") || CheckExt($_FILES['icon_file']['name'], "jpg") || CheckExt($_FILES['icon_file']['name'], "png"))
	{
		move_uploaded_file($_FILES['icon_file']['tmp_name'],SB_ICONS."/".$_FILES['icon_file']['name']);
		$message =  "<script>window.opener.icon('" . $_FILES['icon_file']['name'] . "');self.close()</script>";
        $log = new CSystemLog("m", "Mod Icon Uploaded", "A new mod icon has been uploaded: ".htmlspecialchars($_FILES['icon_file']['name']));
	}
	else 
	{
		$message =  "<b> File must be gif, jpg or png filetype.</b><br><br>";
	}
}

$theme->assign("title", "Upload Icon");
$theme->assign("message", $message);
$theme->assign("input_name", "icon_file");
$theme->assign("form_name", "iconup");
$theme->assign("formats", "a GIF, PNG or JPG");

$theme->display('page_uploadfile.tpl');
?>
