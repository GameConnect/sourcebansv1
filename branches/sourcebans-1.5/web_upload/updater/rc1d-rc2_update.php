<?php
/**
 * =============================================================================
 * Update the database structure from RC1d -> RC2
 * 
 * @author SteamFriends Development Team
 * @version 1.0.0
 * @copyright SourceBans (C)2007 SteamFriends.com.  All rights reserved.
 * @package SourceBans
 * @link http://www.sourcebans.net
 * 
 * @version $Id$
 * =============================================================================
 */
define('IN_SB', true);
define('ROOT', dirname(__FILE__) . '/../');
define('INCLUDES_PATH', ROOT . 'includes/');

require_once ROOT . '../config.php';
include_once INCLUDES_PATH . 'adodb/adodb.inc.php';

echo '- Starting <b>SourceBans</b> database update from RC1d to RC2 -<br />';
$db = ADONewConnection('mysql://' . DB_USER . ':' . DB_PASS . '@' . DB_HOST . ':' . DB_PORT . '/' . DB_NAME);

$db->Execute('INSERT INTO ' . DB_PREFIX . '_settings (setting, value)
              VALUES      ("config.dateformat", "m-d-y H:i")');
$db->Execute('INSERT INTO ' . DB_PREFIX . '_settings (setting, value)
              VALUES      ("config.timezone", "Europe/London")');
$db->Execute('INSERT INTO ' . DB_PREFIX . '_settings (setting, value)
              VALUES      ("config.theme", "default")');

echo 'Done updating. Please delete this file.<br />';