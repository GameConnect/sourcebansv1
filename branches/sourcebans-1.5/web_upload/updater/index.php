<?php
/**
 * Updater
 * 
 * @author     SteamFriends, InterWave Studios, GameConnect
 * @copyright  (C)2007-2011 GameConnect.net.  All rights reserved.
 * @link       http://www.sourcebans.net
 * @package    SourceBans
 * @subpackage Updater
 * @version    $Id$
 */
define('IS_UPDATE', true);

require_once '../init.php';
require_once INCLUDES_PATH . '/CUpdate.php';

$updater = new CUpdater();
$theme->assign('theme_name',      isset($GLOBALS['config']['config.theme']) ? $GLOBALS['config']['config.theme'] : 'default');
$theme->assign('current_version', $updater->getCurrentVersion());
$theme->assign('latest_version',  $updater->getLatestVersion());

$updates = $updater->update();
// If updated, clear compiled templates
if(!empty($updates))
{
  $theme->clear_compiled_tpl();
}

$theme->assign('updates', $updates);
$theme->display('updater.tpl');