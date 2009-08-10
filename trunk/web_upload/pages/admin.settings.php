<?php 
/**
 * =============================================================================
 * Settings page
 * 
 * @author SteamFriends Development Team
 * @version 1.0.0
 * @copyright SourceBans (C)2007 SteamFriends.com.  All rights reserved.
 * @package SourceBans
 * @link http://www.sourcebans.net
 * 
 * @version $Id: admin.settings.php 219 2009-02-24 21:09:11Z peace-maker $
 * =============================================================================
 */

if(!defined("IN_SB")){echo "You should not be here. Only follow links!";die();}
	global $userbank, $theme;
	
	//Log stuff
	$logs = new CSystemLog();
	$page = 1;
	if (isset($_GET['page']) && $_GET['page'] > 0)
		$page = intval($_GET['page']);
		
	if(isset($_GET['log_clear']) && $_GET['log_clear'] == "true")
	{
		if($userbank->HasAccess(ADMIN_OWNER))
		{
			$result = $GLOBALS['db']->Execute("TRUNCATE TABLE `".DB_PREFIX."_log`");
		}
	}
	
	// search
	$where = "";
	if(isset($_GET['advSearch']))
	{
		$value = $_GET['advSearch'];
		$type = $_GET['advType'];
		switch($type)
		{
			case "admin":
				$where = " WHERE l.aid = '" . $value . "'";
			break;
			case "message":
				$where = " WHERE l.message LIKE '%" . $value . "%' OR l.title LIKE '%" . $value . "%'";
			break;
			case "date":
			$date = explode(",", $value);
			$time = mktime($date[3],$date[4],0,$date[1],$date[0],$date[2]);
			$time2 = mktime($date[5],$date[6],59,$date[1],$date[0],$date[2]);
			$where = "WHERE l.created > '$time' AND l.created < '$time2'";
		break;
			case "type":
				$where = " WHERE l.type = '" . $value . "'";
			break;
			default:
				$where = "";
			break;
		}
		$searchlink = "&advSearch=".$_GET['advSearch']."&advType=".$_GET['advType'];
	}
	else
		$searchlink = "";
	
	$list_start = ($page-1) * intval($GLOBALS['config']['banlist.bansperpage']);
	$list_end = $list_start + intval($GLOBALS['config']['banlist.bansperpage']);
	
	$log_count = $logs->LogCount($where);
	$log = $logs->getAll($list_start, intval($GLOBALS['config']['banlist.bansperpage']), $where);
	if(($page > 1))
		$prev = CreateLinkR('<img border="0" alt="prev" src="images/left.gif" style="vertical-align:middle;" /> prev',"index.php?p=admin&c=settings" . $searchlink . "&page=" .($page-1) . "#^2");
	else 
		$prev = "";
		
	if($list_end < $log_count)
		$next = CreateLinkR('next <img border="0" alt="prev" src="images/right.gif" style="vertical-align:middle;" />',"index.php?p=admin&c=settings" . $searchlink . "&page=" .($page+1)."#^2");
	else 
		$next = "";

		
	$pages = (round($log_count/intval($GLOBALS['config']['banlist.bansperpage']))==0)?1:round($log_count/intval($GLOBALS['config']['banlist.bansperpage']));
	if($pages>1)
		$page_numbers =  'Page ' . $page . ' of ' . $pages . " - " . $prev . " | " . $next;
	else
		$page_numbers = 'Page ' . $page . ' of ' . $pages;
		
		
	$pages = ceil($log_count/intval($GLOBALS['config']['banlist.bansperpage']));
	if($pages > 1) {
		if(!isset($_GET['advSearch']) || !isset($_GET['advType'])) {
			$_GET['advSearch'] = "";
			$_GET['advType'] = "";
		}
		$page_numbers .= '&nbsp;<select onchange="changePage(this,\'L\',\''.$_GET['advSearch'].'\',\''.$_GET['advType'].'\');">';
		for($i=1;$i<=$pages;$i++) {
			if(isset($_GET["page"]) && $i==$_GET["page"]) {
				$page_numbers .= '<option value="' . $i . '" selected="selected">' . $i . '</option>';
				continue;
			}
			$page_numbers .= '<option value="' . $i . '">' . $i . '</option>';
		}
		$page_numbers .= '</select>';
	}
	$log_list = array();
	foreach($log as $l)
	{
		$log_item = array();
		if($l['type'] == "m")
			$log_item['type_img'] = "<img src='themes/".SB_THEME."/images/admin/help.png' alt='Info'>"; 
		elseif($l['type'] == "w")
			$log_item['type_img'] = "<img src='themes/".SB_THEME."/images/admin/warning.png' alt='Warning'>"; 
		elseif($l['type'] == "e")
			$log_item['type_img'] = "<img src='themes/".SB_THEME."/images/admin/error.png' alt='Warning'>"; 
		$log_item['user'] = !empty($l['user'])?$l['user']:'Guest';
		$log_item['date_str'] = strftime("%d %B %y - %H:%M", $l['created']);
		$log_item = array_merge($l, $log_item);	
		array_push($log_list, $log_item);
	}
	
	// Theme stuff
	$dh  = opendir(SB_THEMES);
	while (false !== ($filename = readdir($dh))) {
	    $themes[] = $filename;
	}
	//$themes = scandir(SB_THEMES);
	$valid_themes = array();
	foreach($themes as $thm)
	{
		if(@file_exists(SB_THEMES . $thm . "/theme.conf.php"))
		{
			$file = file_get_contents(SB_THEMES . $thm . "/theme.conf.php");
			if($namesearch = preg_match_all('/define\(\'theme_name\',[ ]*\"(.+)\"\);/',$file,$thmname,PREG_PATTERN_ORDER))
				$thme['name'] = $thmname[1][0];
			else
				$thme['name'] = $thm;
			$thme['dir'] = $thm;
			array_push($valid_themes, $thme);
		}
	}
	require (SB_THEMES . SB_THEME . "/theme.conf.php");
		
?>
<div id="admin-page-content">
<?php if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_WEB_SETTINGS))
{
	echo '<div id="0" style="display:none;">Access Denied!</div>';
}
else
{
	if(isset($_POST['settingsGroup']))
	{
		$errors = "";

		if ($_POST['settingsGroup'] == "mainsettings")
		{
			if(!is_numeric($_POST['config_password_minlength']))
				$errors .= "Min password length must be a number<br />";
			if(!is_numeric($_POST['banlist_bansperpage']))
				$errors .= "Bans per page must be a number";
			if(empty($errors))
			{
				if(isset($_POST['enable_submit']) && $_POST['enable_submit'] == "on") {
					$submit = 1;
				} else {
					$submit = 0;
				}
				if(isset($_POST['enable_protest']) && $_POST['enable_protest'] == "on") {
					$protest = 1;
				} else {
					$protest = 0;
				}

				$lognopopup = (isset($_POST['dash_nopopup']) && $_POST['dash_nopopup'] == "on" ? 1 : 0);
				
				$debugmode = (isset($_POST['config_debug']) && $_POST['config_debug'] == "on" ? 1 : 0);
				
				$summertime = (isset($_POST['config_summertime']) && $_POST['config_summertime'] == "on" ? 1 : 0);
				
				$hideadmname = (isset($_POST['banlist_hideadmname']) && $_POST['banlist_hideadmname'] == "on" ? 1 : 0);
				
				$nocountryfetch = (isset($_POST['banlist_nocountryfetch']) && $_POST['banlist_nocountryfetch'] == "on" ? 1 : 0);
				
				$onlyinvolved = (isset($_POST['protest_emailonlyinvolved']) && $_POST['protest_emailonlyinvolved'] == "on" ? 1 : 0);
				
				$size = sizeof($_POST['bans_customreason']);
				for($i=0;$i<$size;$i++) {
					if(empty($_POST['bans_customreason'][$i]))
						unset($_POST['bans_customreason'][$i]);
					else
						$_POST['bans_customreason'][$i] = htmlspecialchars($_POST['bans_customreason'][$i]);
				}
				if(sizeof($_POST['bans_customreason'])!=0)
					$cureason = serialize($_POST['bans_customreason']);
				else
					$cureason = "";

				$tz_string = $_POST['timezoneoffset'];

				$edit = $GLOBALS['db']->Execute("REPLACE INTO ".DB_PREFIX."_settings (`value`, `setting`) VALUES
												(?, 'template.title'),
												(?,'template.logo'),
												(" . (int)$_POST['config_password_minlength'] . ", 'config.password.minlength'),
												(" . $debugmode . ", 'config.debug'),
												(?, 'config.dateformat'),
												(?, 'dash.intro.title'),
												(" . (int)$_POST['banlist_bansperpage'] . ", 'banlist.bansperpage'),
												(" . (int)$hideadmname . ", 'banlist.hideadminname'),
												(" . (int)$nocountryfetch . ", 'banlist.nocountryfetch'),
												(?, 'dash.intro.text'),
												(" . (int)$lognopopup . ", 'dash.lognopopup'),
												(" . (int)$protest . ", 'config.enableprotest'),
												(" . (int)$submit . ", 'config.enablesubmit'),
												(" . (int)$onlyinvolved . ", 'protest.emailonlyinvolved'),
												(?, 'config.timezone'),
												(?, 'config.summertime'),
												(?, 'bans.customreasons'),
												(" . (int)$_POST['default_page'] . ", 'config.defaultpage')", array($_POST['template_title'], $_POST['template_logo'], $_POST['config_dateformat'], $_POST['dash_intro_title'], $_POST['dash_intro_text'], $tz_string, $summertime, $cureason));

				?><script>ShowBox('Settings updated', 'The changes have been successfully updated', 'green', 'index.php?p=admin&c=settings');</script><?php 
			}else{
				CreateRedBox("Error", $errors); 
			}
		}
		
		if ($_POST['settingsGroup'] == "features")
		{
			$kickit = (isset($_POST['enable_kickit']) && $_POST['enable_kickit'] == "on" ? 1 : 0);

			$exportpub = (isset($_POST['export_public']) && $_POST['export_public'] == "on" ? 1 : 0);

			$groupban = (isset($_POST['enable_groupbanning']) && $_POST['enable_groupbanning'] == "on" ? 1 : 0);
			
			$friendsban = (isset($_POST['enable_friendsbanning']) && $_POST['enable_friendsbanning'] == "on" ? 1 : 0);
			
			$adminrehash = (isset($_POST['enable_adminrehashing']) && $_POST['enable_adminrehashing'] == "on" ? 1 : 0);
			
			$edit = $GLOBALS['db']->Execute("REPLACE INTO ".DB_PREFIX."_settings (`value`, `setting`) VALUES
											(" . (int)$exportpub . ", 'config.exportpublic'),
											(" . (int)$kickit . ", 'config.enablekickit'),
											(" . (int)$groupban . ", 'config.enablegroupbanning'),
											(" . (int)$friendsban . ", 'config.enablefriendsbanning'),
											(" . (int)$adminrehash . ", 'config.enableadminrehashing')");
											

			?><script>ShowBox('Settings updated', 'The changes have been successfully updated', 'green', 'index.php?p=admin&c=settings');</script><?php 
		}
	}

	$date_offs = $GLOBALS['config']['config.timezone'];
	
	#########[Settings Page]###############
	echo '<div id="0" style="display:none;">';
		$theme->assign('config_title',			$GLOBALS['config']['template.title']);
		$theme->assign('config_logo',			$GLOBALS['config']['template.logo']);
		$theme->assign('config_min_password', 	$GLOBALS['config']['config.password.minlength']);
		$theme->assign('config_dateformat', 	$GLOBALS['config']['config.dateformat']);
		$theme->assign('config_dash_title', 	$GLOBALS['config']['dash.intro.title']);
		$theme->assign('config_time', 			$date_offs);
		$theme->assign('config_dash_text', 		stripslashes($GLOBALS['config']['dash.intro.text']));
		$theme->assign('config_bans_per_page',	$GLOBALS['config']['banlist.bansperpage']);
		
		$theme->assign('bans_customreason', ((isset($GLOBALS['config']['bans.customreasons'])&&$GLOBALS['config']['bans.customreasons']!="")?unserialize($GLOBALS['config']['bans.customreasons']):array()));
		
		$theme->display('page_admin_settings_settings.tpl');	
	echo '</div>';
	#########/[Settings Page]###############

	#########[Features Page]###############
	echo '<div id="3" style="display:none;">';
		$theme->display('page_admin_settings_features.tpl');
	echo '</div>';
	#########/[Features Page]###############
	
	#########[Themes Page]###############
	echo '<div id="1" style="display:none;">';
		$theme->assign('theme_list',			$valid_themes);
		
		$theme->assign('theme_name',			strip_tags(theme_name));
		$theme->assign('theme_author',			strip_tags(theme_author));
		$theme->assign('theme_version',			strip_tags(theme_version));
		$theme->assign('theme_link',			strip_tags(theme_link));
		$theme->assign('theme_screenshot',		'<img width="250px" height="170px" src="themes/'.SB_THEME.'/'.strip_tags(theme_screenshot).'">');		
			
		$theme->display('page_admin_settings_themes.tpl');	
	echo '</div>';
	#########/[Settings Page]###############
	
	#########[Logs Page]###############
	echo '<div id="2" style="display:none;">';
		if($userbank->HasAccess(ADMIN_OWNER))
			$theme->assign('clear_logs', "( <a href='javascript:ClearLogs();'>Clear Log</a> )");
		$theme->assign('page_numbers', 			$page_numbers);
		$theme->assign('log_items',				$log_list);
				
		$theme->display('page_admin_settings_logs.tpl');	
	echo '</div>';
	#########/[Logs Page]###############
	
}
	
?>
<script>
$('config_debug').checked = <?php echo $GLOBALS['config']['config.debug']?>;
$('config_summertime').checked = <?php echo $GLOBALS['config']['config.summertime']?>;
$('enable_submit').checked = <?php echo $GLOBALS['config']['config.enablesubmit']?>;
$('enable_protest').checked = <?php echo $GLOBALS['config']['config.enableprotest']?>;
$('enable_kickit').checked = <?php echo $GLOBALS['config']['config.enablekickit']?>;
$('export_public').checked = <?php echo $GLOBALS['config']['config.exportpublic']?>;
$('dash_nopopup').checked = <?php echo $GLOBALS['config']['dash.lognopopup']?>;
$('default_page').value = <?php echo $GLOBALS['config']['config.defaultpage']?>;
$('protest_emailonlyinvolved').checked = <?php echo $GLOBALS['config']['protest.emailonlyinvolved']?>;
$('banlist_hideadmname').checked = <?php echo $GLOBALS['config']['banlist.hideadminname']?>;
$('banlist_nocountryfetch').checked = <?php echo $GLOBALS['config']['banlist.nocountryfetch']?>;
$('enable_groupbanning').checked = <?php echo $GLOBALS['config']['config.enablegroupbanning']?>;
$('enable_friendsbanning').checked = <?php echo $GLOBALS['config']['config.enablefriendsbanning']?>;
$('enable_adminrehashing').checked = <?php echo $GLOBALS['config']['config.enableadminrehashing']?>;
<?php
if(ini_get('safe_mode')==1) {
	print "$('enable_groupbanning').disabled = true;\n";
	print "$('enable_friendsbanning').disabled = true;\n";
	print "$('enable_friendsbanning.msg').setHTML('You can\'t use these features. You need to set PHP safe mode off.');\n";
	print "$('enable_friendsbanning.msg').setStyle('display', 'block');\n";
}
?>

function MoreFields()
{
	var t = document.getElementById("custom.reasons");
	var tr = t.insertRow("-1");
	var td = tr.insertCell("-1");
	var inp = document.createElement("input");
	inp.setAttribute("type","text");
	inp.className = "submit-fields";
	inp.setAttribute("name","bans_customreason[]");
	inp.setAttribute("id","bans_customreason[]");
	td.appendChild(inp);
}
</script>
</div>