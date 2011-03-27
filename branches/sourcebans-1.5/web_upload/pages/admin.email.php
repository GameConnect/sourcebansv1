<?php 
/**
 * Send an email
 * 
 * @author    SteamFriends, InterWave Studios, GameConnect
 * @copyright (C)2007-2011 GameConnect.net.  All rights reserved.
 * @link      http://www.sourcebans.net
 * @package   SourceBans
 * @version   $Id$
 */

if(!defined("IN_SB")){echo "You should not be here. Only follow links!";die();} 

global $theme, $userbank;

if(!isset($_GET['id']))
{
	echo '<div id="msg-red" >
	<i><img src="./images/warning.png" alt="Warning" /></i>
	<b>Error</b>
	<br />
	No submission or protest id specified. Please only follow links
</div>';
	PageDie();
}

if(!isset($_GET['type']) || ($_GET['type'] != 's' && $_GET['type'] != 'p'))
{
	echo '<div id="msg-red" >
	<i><img src="./images/warning.png" alt="Warning" /></i>
	<b>Error</b>
	<br />
	Invalid type. Please only follow links
</div>';
	PageDie();
}

// Submission
$email = "";
if($_GET['type'] == 's')
{
	$email = $GLOBALS['db']->GetOne('SELECT email FROM ' . DB_PREFIX . '_submissions WHERE subid = ?', array($_GET['id']));
}
// Protest
else if($_GET['type'] == 'p')
{
	$email = $GLOBALS['db']->GetOne('SELECT email FROM ' . DB_PREFIX . '_protests WHERE pid = ?', array($_GET['id']));
}

if(empty($email))
{
	echo '<div id="msg-red" >
	<i><img src="./images/warning.png" alt="Warning" /></i>
	<b>Error</b>
	<br />
	There is no email to send to supplied.
</div>';
	PageDie();
}

$theme->assign('email_addr', htmlspecialchars($email));
$theme->assign('email_js', "CheckEmail('".$_GET['type']."', ".(int)$_GET['id'].")");
?>

<div id="admin-page-content">
	<div id="1">
		<?php $theme->display('page_admin_bans_email.tpl'); ?>
	</div>
</div>
