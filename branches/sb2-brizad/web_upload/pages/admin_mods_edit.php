<?php
require_once 'api.php';

$phrases  = SBConfig::getEnv('phrases');
$userbank = SBConfig::getEnv('userbank');
$page     = new Page(ucwords($phrases['edit_mod']), !isset($_GET['nofullpage']));

try
{
  if(!$userbank->HasAccess(array('OWNER', 'EDIT_MODS')))
    throw new Exception($phrases['access_denied']);
  if($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    try
    {
      SB_API::editMod($_POST['id'], $_POST['name'], $_POST['folder'], $_POST['icon']);
      
      exit(json_encode(array(
        'redirect' => Util::buildUrl(array(
          '_' => 'admin_mods.php'
        ))
      )));
    }
    catch(Exception $e)
    {
      exit(json_encode(array(
        'error' => $e->getMessage()
      )));
    }
  }
  
  $mod = SB_API::getMod($_GET['id']);
  
  $page->assign('mod_name',   $mod['name']);
  $page->assign('mod_folder', $mod['folder']);
  $page->assign('mod_icon',   $mod['icon']);
  $page->display('page_admin_mods_edit');
}
catch(Exception $e)
{
  $page->assign('error', $e->getMessage());
  $page->display('page_error');
}
?>