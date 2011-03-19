<?php
/**
 * Your account page
 * 
 * @author    SteamFriends, InterWave Studios, GameConnect
 * @copyright (C)2007-2011 GameConnect.net.  All rights reserved.
 * @link      http://www.sourcebans.net
 * @package   SourceBans
 * @version   $Id$
 */
global $userbank, $theme;

if(!defined("IN_SB")){echo "You should not be here. Only follow links!";die();}
if($userbank->GetAid() == -1){echo "You shoudnt be here. looks like we messed up ><";die();}
		
$groupsTabMenu = new CTabsMenu();
$groupsTabMenu->addMenuItem("View Permissions", 0);
$groupsTabMenu->addMenuItem("Change Password", 1);
$groupsTabMenu->addMenuItem("Server Password", 2);
$groupsTabMenu->addMenuItem("Change Email", 3);
$groupsTabMenu->outputMenu();

$res = $GLOBALS['db']->Execute("SELECT srv_password, email FROM " . DB_PREFIX . "_admins WHERE aid = '".$userbank->GetAid()."'");
$srvpwset = (!empty($res->fields['srv_password'])?true:false);

$theme->assign('srvpwset',				$srvpwset);
$theme->assign('email',					$res->fields['email']);
$theme->assign('user_aid',				$userbank->GetAid());
$theme->assign('web_permissions',		BitToString($userbank->GetProperty("extraflags")));
$theme->assign('server_permissions',	SmFlagsToSb($userbank->GetProperty("srv_flags")));
$theme->assign('min_pass_len',			MIN_PASS_LENGTH);

$theme->left_delimiter = "-{";
$theme->right_delimiter = "}-";
$theme->display('page_youraccount.tpl');
$theme->left_delimiter = "{";
$theme->right_delimiter = "}";
?>