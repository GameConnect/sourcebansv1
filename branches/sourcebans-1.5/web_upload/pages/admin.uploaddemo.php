<?php
/**
 * Upload a demo
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

if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN|ADMIN_EDIT_OWN_BANS|ADMIN_EDIT_GROUP_BANS|ADMIN_EDIT_ALL_BANS))
{
    $log = new CSystemLog("w", "Hacking Attempt", $userbank->GetProperty('user') . " tried to upload a demo, but doesn't have access.");
	echo 'You don\'t have access to this!';
	die();
}

$message = "";

if(isset($_POST['upload']))
{
	if(CheckExt($_FILES['demo_file']['name'], "zip") || CheckExt($_FILES['demo_file']['name'], "rar") || CheckExt($_FILES['demo_file']['name'], "dem"))
	{
		$filename = md5(time().rand(0, 1000));
		move_uploaded_file($_FILES['demo_file']['tmp_name'],SB_DEMOS."/".$filename);
		$message =  "<script>window.opener.demo('" . $filename . "','" . $_FILES['demo_file']['name'] . "');self.close()</script>";
        $log = new CSystemLog("m", "Demo Uploaded", "A new demo has been uploaded: ".htmlspecialchars($_FILES['demo_file']['name']));
	}
	else 
	{
		$message =  "<b> File must be zip, rar or dem filetype.</b><br><br>";
	}
}

$theme->assign("title", "Upload Demo");
$theme->assign("message", $message);
$theme->assign("input_name", "demo_file");
$theme->assign("form_name", "demup");
$theme->assign("formats", "a ZIP, RAR, or DEM");

$theme->display('page_uploadfile.tpl');
?>

