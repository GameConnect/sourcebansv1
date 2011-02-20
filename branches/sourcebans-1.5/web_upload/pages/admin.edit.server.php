<div id="admin-page-content">
<?php  
/**
 * Edit server
 * 
 * @author    SteamFriends, InterWave Studios, GameConnect
 * @copyright (C)2007-2011 GameConnect.net.  All rights reserved.
 * @link      http://www.sourcebans.net
 * @package   SourceBans
 * @version   $Id$
 */

if(!defined("IN_SB")){echo "You should not be here. Only follow links!";die();} 
global $theme;
if(!isset($_GET['id']))
{
	echo '<div id="msg-red" >
	<i><img src="./images/warning.png" alt="Warning" /></i>
	<b>Error</b>
	<br />
	No server id specified. Please only follow links
</div>';
	die();
}
$_GET['id'] = (int)$_GET['id'];

if(isset($_POST['address']))
{
	$grps = "";
	$sg = $GLOBALS['db']->GetAll("SELECT * FROM ".DB_PREFIX."_servers_groups WHERE server_id = {$_GET['id']}");
	foreach($sg AS $server)
	{
		$GLOBALS['db']->Execute("DELETE FROM ".DB_PREFIX."_servers_groups WHERE server_id = " . (int)$server['server_id'] . " AND group_id = " . (int)$server['group_id']);
	}
	if(!empty($_POST['groups'])) {
		foreach($_POST['groups'] as $t)
		{
			$addtogrp = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_servers_groups (`server_id`, `group_id`) VALUES (?,?)");
			$GLOBALS['db']->Execute($addtogrp,array($_GET['id'], $t));
		}
	}
	
	$enabled = (isset($_POST['enabled']) && $_POST['enabled'] == "on" ? 1 : 0);
	
    // don't change rcon password if not changed
    $rcon = "";
    if($_POST['rcon'] != '+-#*_')
        $rcon = "`rcon` = " . $GLOBALS['db']->qstr($_POST['rcon']) . ",";
        
	$edit = $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_servers SET
									`ip` = ?,
									`port` = " . (int)$_POST['port'] . ",
									" . $rcon . "
									`modid` = " . (int)$_POST['mod'] . ",
									`enabled` = " . (int)$enabled . "
									WHERE `sid` = '". (int)$_GET['id'] . "'", array($_POST['address']));

	
									
	echo "<script>ShowBox('Server updated', 'The server has been updated successfully', 'green', 'index.php?p=admin&c=servers');TabToReload();</script>";		
}

$server = $GLOBALS['db']->GetRow("SELECT * FROM ".DB_PREFIX."_servers WHERE sid = {$_GET['id']}");
if(!$server)
{
	$log = new CSystemLog("e", "Getting server data failed", "Can't find data for server with id '".$_GET['id']."'");
	echo '<div id="msg-red" >
	<i><img src="./images/warning.png" alt="Warning" /></i>
	<b>Error</b>
	<br />
	Error getting current data.
</div></div>';
	PageDie();
}


$modlist = $GLOBALS['db']->GetAll("SELECT mid, name FROM `" . DB_PREFIX . "_mods` WHERE `mid` > 0 AND `enabled` = 1 ORDER BY name ASC");
$grouplist = $GLOBALS['db']->GetAll("SELECT gid, name FROM `" . DB_PREFIX . "_groups` WHERE type = 3 ORDER BY name ASC");

$theme->assign('ip', 	$server['ip']);
$theme->assign('port', 	 $server['port']);
$theme->assign('rcon', 	'+-#*_'); // Mh, some random string
$theme->assign('modid', 	$server['modid']);


$theme->assign('permission_addserver', $userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_SERVER));
$theme->assign('modlist', 	$modlist);
$theme->assign('grouplist', $grouplist);

$theme->assign('edit_server', true);
$theme->assign('submit_text', "Update Server");

echo '<form action="" method="post" name="editserver">';
$theme->display('page_admin_servers_add.tpl');
echo '</form>';

echo "<script>";
$groups = $GLOBALS['db']->GetAll("SELECT group_id FROM `" . DB_PREFIX . "_servers_groups` WHERE server_id = {$_GET['id']}"); 
foreach($groups AS $g)
{
	if($g)
		echo "if($('g_" . $g[0] . "')) $('g_" . $g[0] . "').checked = true;";
}
?>

$('enabled').checked = <?php echo $server['enabled']; ?>;
if($('mod')) $('mod').value = <?php echo $server['modid']?>;
</script>

</div>
