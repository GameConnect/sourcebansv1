<?php
/**
 * =============================================================================
 * This file will setup our defs, and configure php
 * 
 * @author SteamFriends Development Team
 * @version 1.0.0
 * @copyright SourceBans (C)2007 SteamFriends.com.  All rights reserved.
 * @package SourceBans
 * @link http://www.sourcebans.net
 * 
 * @version $Id: init.php 117 2008-08-21 17:17:54Z peace-maker $
 * =============================================================================
 */
 
// ---------------------------------------------------
//  Directories
// ---------------------------------------------------
define('ROOT', dirname(__FILE__) . "/");
define('SCRIPT_PATH', ROOT . 'scripts');
define('TEMPLATES_PATH', ROOT . 'template');
define('INCLUDES_PATH', ROOT . 'includes');
define('IN_SB', true);
define('IN_INSTALL', true);


// ---------------------------------------------------
//  Fix some $_SERVER vars
// ---------------------------------------------------
// Fix for IIS, which doesn't set REQUEST_URI
if(!isset($_SERVER['REQUEST_URI']) || trim($_SERVER['REQUEST_URI']) == '') 
{ $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
    if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) 
    { $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING']; } 
} 
// Fix for Dreamhost and other PHP as CGI hosts
if(strstr($_SERVER['SCRIPT_NAME'], 'php.cgi')) unset($_SERVER['PATH_INFO']);
if(trim($_SERVER['PHP_SELF']) == '') $_SERVER['PHP_SELF'] = preg_replace("/(\?.*)?$/",'', $_SERVER["REQUEST_URI"]);

// ---------------------------------------------------
//  Initial setup
// ---------------------------------------------------
if(!defined('SB_VERSION')){
	define('SB_VERSION', '1.4.6 Installer');
}
define('LOGIN_COOKIE_LIFETIME', (60*60*24*7)*2);
define('COOKIE_PATH', '/');
define('COOKIE_DOMAIN', '');
define('COOKIE_SECURE', false);
define('SB_SALT', 'SourceBans');
// ---------------------------------------------------
//  Setup PHP
// ---------------------------------------------------
ini_set('date.timezone', 'GMT');

if(function_exists('date_default_timezone_set')){
    date_default_timezone_set('GMT');
}else if(!ini_get('safe_mode')) {
    putenv('TZ=GMT');
}
//if(defined('DEBUG') && DEBUG){
if (isset($_GET['debug']) && $_GET['debug'] == 1 || isset($_SESSION['debug']) && $_GET['debug'] != 0) {
	ini_set('display_errors', 1);
    error_reporting(E_ALL);
	$_SESSION['debug'] = 1;
}else if(isset($_GET['debug']) && $_GET['debug'] == 0) {
    ini_set('display_errors', 0);
	error_reporting(0);
	unset($_SESSION['debug']);
} 
    
// Create a blank config file
if(!file_exists("../config.php") && is_writable('../')) {
	$handle = fopen("../config.php", "w");
	fclose($handle);
}


// ---------------------------------------------------
//  Some defs
// ---------------------------------------------------
define('EMAIL_FORMAT', "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/");
define('URL_FORMAT', "/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}((:[0-9]{1,5})?\/.*)?$/i");
define('STEAM_FORMAT', "/^STEAM_[0-9]:[0-9]:[0-9]+$/");
define('STATUS_PARSE', '/#[ ]*([0-9]+) "(.+)" (STEAM_0:[0-1]:[0-9]+)[ ]{1,2}([0-9]+[:[0-9]+) ([0-9]+)[ ]([0-9]+) ([a-zA-Z]+) ([0-9.:]+)/');
 ?>