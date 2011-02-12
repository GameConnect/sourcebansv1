<?php
/**
 * =============================================================================
 * Main functions for user here
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

/**
 * Checks the database for any identical
 * rows, username, email etc
 *
 * @param string $table the table to lookup
 * @param string $field The feild to check
 * @param string $value The value to check against
 * @return true if the value already exists in that field is found, else false
 */
function is_taken($table, $field, $value)
{
    $query = $GLOBALS['db']->GetRow("SELECT * FROM `" . DB_PREFIX . "_$table` WHERE `$field` = '$value'");
    return (count($query) > 0);
}


/**
 * Generates a random string to use as the salt
 *
 * @param integer $length the length of the salt
 * @return string of random chars in the length specified
 */
function generate_salt($length=5)
{
	return (substr(str_shuffle('qwertyuiopasdfghjklmnbvcxz0987612345'), 0, $length));
}

/**
 * Logs out the admin by removing cookies and killing the session
 *
 * @param string $username The username of the admin
 * @param string $password The password of the admin
 * @param boolean $cookie Should we create a cookie
 * @return true.
 */
function logout() {
	setcookie('aid', '', time()-86400);
	setcookie('password', '', time()-86400);
	$_SESSION = array();
	session_destroy();
	return true;
}

/**
 * Changes the admins data
 *
 * @param integer $aid The admin id to change the details of
 * @param string $username The new username of the admin
 * @param string $name The new realname of the admin
 * @param string $email The email of the admin
 * @param string $authid the STEAM of the admin
 * @return true on success.
 */
function edit_admin($aid, $username, $name, $email, $authid)
{
    $query = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_admins` SET `user` = ?,  `authid` = ?, `email` = ? WHERE `aid` = ?", array($username, $authid, $email, $aid));
    if($query)
    {
        return true;
    }
    else
    {
        return false;
    }
}

/**
 * Removes an admin from the system
 *
 * @param integer $aid The admin id of the admin to delete
 * @return true on success.
 */
function delete_admin($aid)
{
    $aid = (int)$aid;
    $query = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_admins` WHERE `aid` = '$aid'");
    if($query)
    {
        return true;
    }
    else
    {
        return false;
    }
}

/**
 * Creates an array to store the admin's data in
 *
 * @param integer $aid The admin id of the user get details for
 * @param string $pass The admins password for security
 * @return array.
 */
function userdata($aid, $pass)
{
    global $userbank;
    if(!$userbank->CheckLogin($userbank->encrypt_password($pass), $aid))
    {
        //Fill array with guest data
      $_SESSION['user'] = array('aid' => '-1',
        			'user' => 'Guest',
        			'password' => '',
        			'steam' => '',
        			'email' => '',
        			'gid' => '',
        			'flags' => '0');

    }
    else
    {
        $query = $GLOBALS['db']->GetRow("SELECT * FROM `" . DB_PREFIX . "_admins` WHERE `aid` = '$aid'");
        $_SESSION['user'] = array('aid' => $aid,
        			'user' => $query['user'],
        			'password' => $query['password'],
        			'steam' => $query['authid'],
        			'email' => $query['email'],
        			'gid' => $query['gid'],
        			'flags' => get_user_flags($aid),
        			'admin' => get_user_admin($query['authid']));
        $GLOBALS['aid'] = $aid;
        $GLOBALS['user'] = new CUser($aid);
        $GLOBALS['user']->FillData();
    }
}

/**
 * Returns the current flags associated with the user
 *
 * @param integer The admin id to check
 * @return integer.
 */
function get_user_flags($aid)
{
	if(empty($aid))
		return 0;

	$admin = $query = $GLOBALS['db']->GetRow("SELECT `gid`, `extraflags` FROM `" . DB_PREFIX . "_admins` WHERE aid = '$aid'");
	if(intval($admin['gid']) == -1)
	{
		return intval($admin['extraflags']);
	}
	else
	{
		$query = $GLOBALS['db']->GetRow("SELECT `flags` FROM `" . DB_PREFIX . "_groups` WHERE gid = (SELECT gid FROM " . DB_PREFIX . "_admins WHERE aid = '$aid')");
		return (intval($query['flags']) | intval($admin['extraflags']));
	}

}

/**
 * Returns the current server flags associated with the user
 *
 * @param string The admin to check
 * @return string.
 */
function get_user_admin($steam)
{
	if(empty($steam))
		return 0;
	$admin = $GLOBALS['db']->GetRow("SELECT * FROM " . DB_PREFIX . "_admins WHERE authid = '" . $steam . "'");
	if(strlen($admin['srv_group']) > 1)
	{
		$query = $GLOBALS['db']->GetRow("SELECT flags FROM " . DB_PREFIX . "_srvgroups WHERE name = (SELECT srv_group FROM " . DB_PREFIX . "_admins WHERE authid = '" . $steam . "')");
		return $query['flags'] . $admin['srv_flags'];
	}
	else
	{
		return $admin['srv_flags'];
	}
}

/**
 * Returns the current server flags associated with the user
 *
 * @param string The admin to check
 * @return string.
 */
function get_non_inherited_admin($steam)
{
	if(empty($steam))
		return 0;
	$admin = $GLOBALS['db']->GetRow("SELECT * FROM `" . DB_PREFIX . "_admins` WHERE authid = '$steam'");
	return $admin['srv_flags'];
}

/**
 * Checks if user is logged in.
 *
 * @return boolean.
 */
function is_logged_in()
{
	if($_SESSION['user']['user'] == "Guest" || $_SESSION['user']['user'] == "")
		return false;
	else
		return true;
}

/**
 * Checks if user is an admin.
 *
 * @return boolean.
 */
function is_admin($aid)
{
	if (check_flags($aid, ALL_WEB))
		return true;
	else
		return false;
}

/**
 * Checks which admin type the admin is
 * using the given mask
 *
 * @return integer.
 */
function check_group($mask)
{
	if ($mask &
	(ADMIN_WEB_BANS|ADMIN_WEB_ADMINS|ADMIN_WEB_AGROUPS|
	ADMIN_SERVER_ADMINS|ADMIN_SERVER_AGROUPS|ADMIN_SERVER_SETTINGS|
	ADMIN_SERVER_ADD|ADMIN_SERVER_REMOVE|ADMIN_SERVER_GROUPS|ADMIN_WEB_SETTINGS|
	ADMIN_OWNER|ADMIN_MODS != 0 && $mask &
	SM_RESERVED_SLOT|SM_GENERIC|SM_KICK|SM_BAN|SM_UNBAN|SM_SLAY|
	SM_MAP|SM_CVAR|SM_CONFIG|SM_CHAT|SM_VOTE|SM_PASSWORD|SM_RCON|
	SM_CHEATS|SM_ROOT|SM_DEF_IMMUNITY|SM_GLOBAL_IMMUNITY == 0))
		return GROUP_WEB_A;
	else if($mask == 0)
		return GROUP_NONE_A;
	else
		return GROUP_SERVER_A;
}



/**
 * Removes all flags and replaces with new flag
 *
 * @param integet $aid the admin id to change the flags of
 * @param integer $flag the new flag to apply to the user
 * @return noreturn
 */
function set_flag($aid, $flag)
{
	$aid = (int)$aid;
	$query = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_groups` SET `flags` = '$flag' WHERE gid = (SELECT gid FROM " . DB_PREFIX . "_admins WHERE aid = '$aid')");
	userdata($aid, $_SESSION['user']['password']);
}

/**
 * Adds a new flag to the current bitmask
 *
 * @param integet $aid the admin id to change the flags of
 * @param integer $flag the flag to apply to the user
 * @return noreturn
 */
function add_flag($aid, $flag)
{
	$aid = (int)$aid;
	$flagd = get_user_flags($aid);
	$flagd |= $flag;
	$query = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_groups` SET `flags` = '$flagd' WHERE gid = (SELECT gid FROM " . DB_PREFIX . "_admins WHERE aid = '$aid')");
	userdata($aid, $_SESSION['user']['password']);
}

/**
 * Removes a flag from the bitmask
 *
 * @param integet $aid the admin id to change the flags of
 * @param integer $flag the flag to remove from the user
 * @return noreturn
 */
function remove_flag($aid, $flag)
{
	$aid = (int)$aid;
	$flagd = get_user_flags($aid);
	$flagd &= ~($flag);
	$query = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_groups` SET `flags` = '$flagd' WHERE gid = (SELECT gid FROM " . DB_PREFIX . "_admins WHERE aid = '$aid')");
	userdata($aid, $_SESSION['user']['password']);
}

/**
 * Checks if the admin has ALL the specified flags
 *
 * @param integet $aid the admin id to check the flags of
 * @param integer $flag the flag to check
 * @return boolean
 */
function check_all_flags($aid, $flag)
{
	$mask = get_user_flags($aid);
	return ($mask & $flag) == $flag;
}

/**
 * Checks if the admin has ANY the specified flags
 *
 * @param integet $aid the admin id to check the flags of
 * @param integer $flag the flag to check
 * @return boolean
 */
function check_flags($aid, $flag)
{
	$mask = get_user_flags($aid);
	if(($mask & $flag) !=0)
		return true;
	else
		return false;
}

/**
 * Checks if the mask contains ANY the specified flags
 *
 * @param integet $aid the admin id to check the flags of
 * @param integer $flag the flag to check
 * @return boolean
 */
function check_flag($mask, $flag)
{
	if(($mask & $flag) !=0)
		return true;
	else
		return false;
}

function validate_steam($steam)
{
	return preg_match(STEAM_FORMAT, $steam) ? true : false;
}

function validate_email($email)
{
	return preg_match(EMAIL_FORMAT, $email) ? true : false;
}
function validate_ip($ip)
{
	return preg_match(IP_FORMAT, $ip) ? true : false;
}
?>
