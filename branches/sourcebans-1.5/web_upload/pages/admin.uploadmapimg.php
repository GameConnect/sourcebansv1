<?php
/**
 * Upload a map image
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

if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_SERVER))
{
    $log = new CSystemLog("w", "Hacking Attempt", $userbank->GetProperty('user') . " tried to upload a mapimage, but doesn't have access.");
	echo 'You don\'t have access to this!';
	die();
}

$message = "";
if(isset($_POST['upload']))
{
	if(CheckExt($_FILES['mapimg_file']['name'], "jpg"))
	{
		move_uploaded_file($_FILES['mapimg_file']['tmp_name'],SB_MAP_LOCATION."/".$_FILES['mapimg_file']['name']);
		$message =  "<script>window.opener.mapimg('" . $_FILES['mapimg_file']['name'] . "');self.close()</script>";
		$log = new CSystemLog("m", "Map Image Uploaded", "A new map image has been uploaded: ".htmlspecialchars($_FILES['mapimg_file']['name']));
	}
	else 
	{
		$message =  "<b> File must be jpg filetype.</b><br><br>";
	}
}

$theme->assign("title", "Upload Mapimage");
$theme->assign("message", $message);
$theme->assign("input_name", "mapimg_file");
$theme->assign("form_name", "mapimgup");
$theme->assign("formats", "a JPG");

$theme->display('page_uploadfile.tpl');
?>
