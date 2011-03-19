<?php 
/**
 * RCON window
 * 
 * @author    SteamFriends, InterWave Studios, GameConnect
 * @copyright (C)2007-2011 GameConnect.net.  All rights reserved.
 * @link      http://www.sourcebans.net
 * @package   SourceBans
 * @version   $Id$
 */

if(!defined("IN_SB")){echo "You should not be here. Only follow links!";die();} 

global $theme, $userbank;

$sid = (int)$_GET['id'];

// Access on that server?
$servers = $GLOBALS['db']->GetAll("SELECT server_id, srv_group_id FROM " . DB_PREFIX . "_admins_servers_groups WHERE admin_id = ". $userbank->GetAid());
$access = false;
foreach($servers as $server)
{
    if($server['server_id'] == $sid)
    {
        $access = true;
        break;
    }
    if($server['srv_group_id'] > 0)
    {
        $servers_in_group = $GLOBALS['db']->GetAll("SELECT server_id FROM " . DB_PREFIX . "_servers_groups WHERE group_id = ". (int)$server['srv_group_id']);
        foreach($servers_in_group as $servig)
        {
            if($servig['server_id'] == $sid)
            {
                $access = true;
                break 2;
            }
        }
    }
}

$theme->assign('id', $sid);
$theme->assign('permission_rcon', ($access && $userbank->HasAccess(SM_RCON . SM_ROOT)));
$theme->left_delimiter = '-{';
$theme->right_delimiter = '}-';

$theme->display('page_admin_servers_rcon.tpl');

$theme->left_delimiter = '{';
$theme->right_delimiter = '}';
?>

