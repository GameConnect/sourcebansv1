<?php
/**
 * =============================================================================
 * Admin Servers page
 * 
 * @author SteamFriends Development Team
 * @version 1.0.0
 * @copyright SourceBans (C)2007 SteamFriends.com.  All rights reserved.
 * @package SourceBans
 * @link http://www.sourcebans.net
 * 
 * @version $Id: admin.servers.php 241 2009-03-22 19:31:41Z peace-maker $
 * =============================================================================
 */
?>

<div id="admin-page-content">
	<?php  
		if(!defined("IN_SB")){echo "You should not be here. Only follow links!";die();} 
		global $userbank, $theme;
		
			$servers = $GLOBALS['db']->GetAll("SELECT srv.ip ip, srv.port port, srv.sid sid, mo.icon icon, srv.enabled enabled FROM `" . DB_PREFIX . "_servers` AS srv
											   LEFT JOIN `" . DB_PREFIX . "_mods` AS mo ON mo.mid = srv.modid
											   ORDER BY modid");
			$server_count = $GLOBALS['db']->GetRow("SELECT COUNT(sid) AS cnt FROM `" . DB_PREFIX . "_servers`") ;

		
		// List mods
		$modlist = $GLOBALS['db']->GetAll("SELECT mid, name FROM `" . DB_PREFIX . "_mods` WHERE `mid` > 0 AND `enabled` = 1 ORDER BY name ASC");
		// List groups
		$grouplist = $GLOBALS['db']->GetAll("SELECT gid, name FROM `" . DB_PREFIX . "_groups` WHERE type = 3 ORDER BY name ASC");
		
		// Vars for server list
		$theme->assign('permission_list', $userbank->HasAccess(ADMIN_OWNER|ADMIN_LIST_SERVERS));
		$theme->assign('permission_config', $userbank->HasAccess(ADMIN_OWNER));
		$theme->assign('permission_rcon', $userbank->HasAccess(SM_RCON . SM_ROOT));
		$theme->assign('permission_editserver', $userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_SERVERS));
		$theme->assign('pemission_delserver', $userbank->HasAccess(ADMIN_OWNER|ADMIN_DELETE_SERVERS));
		$theme->assign('server_count', $server_count['cnt']);
		$theme->assign('server_list', $servers);
		
		// Vars for add server
		$theme->assign('permission_addserver', $userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_SERVER));
		$theme->assign('modlist', 	$modlist);
		$theme->assign('grouplist', $grouplist);
        // set vars from edit form
        $theme->assign('edit_server', false);
        $theme->assign('ip', 	'');
        $theme->assign('port', 	'');
        $theme->assign('rcon', 	'');
        $theme->assign('modid', '');
		
		$theme->assign('submit_text', "Add Server");
	?>
	
	
	<div id="0" style="display:none;">
		<?php $theme->display('page_admin_servers_list.tpl'); ?>
	</div>
	
	
	<div id="1" style="display:none;">
		<?php $theme->display('page_admin_servers_add.tpl'); ?>
	</div>

</div>
