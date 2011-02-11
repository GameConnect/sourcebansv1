<?php
require_once 'api.php';

$db       = SBConfig::getEnv('db');
$config   = SBConfig::getEnv('config');
$phrases  = SBConfig::getEnv('phrases');
$userbank = SBConfig::getEnv('userbank');
$page     = new Page('Lost Password', !isset($_GET['nofullpage']));

try
{
  if($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    try
    {
      $admins = SB_API::getAdmins();
      
      foreach($admins['list'] as $id => $admin)
      {
        if($admin['email'] != $_POST['email'])
          continue;
        
        $validation = md5(time());
        
        $db->Execute('UPDATE ' . SBConfig::getEnv('prefix') . '_admins
                      SET    validate = ?
                      WHERE  email    = ?',
                      array($validation, $_POST['email']));
        
        Util::mail($_POST['email'], 'noreply@' . $_SERVER['HTTP_HOST'], 'SourceBans Password Reset',
                   'Hello ' . $admin['name'] . ',\n\n' .
                   'You have requested to have your password reset for your SourceBans account.\n' .
                   'To complete this process, please click the following link.\n\n' .
                   Util::buildUrl('lostpassword.php?email=' . $_POST['email'] . '&validation=' . $validation) . '\n\n' .
                   'NOTE: If you didn\'t request this reset, then simply ignore this email.');
        
        break;
      }
      
      exit(json_encode(array(
        'redirect' => Util::buildQuery()
      )));
    }
    catch(Exception $e)
    {
      exit(json_encode(array(
        'error' => $e->getMessage()
      )));
    }
  }
  if(isset($_GET['email'], $_GET['validation']) && !empty($_GET['email']) && !empty($_GET['validation']))
  {
    $admins = SB_API::getAdmins();
    
    foreach($admins['list'] as $id => $admin)
    {
      if($admin['email'] != $_GET['email'] || $admin['validate'] != $_GET['validation'])
        continue;
      
      $password = Util::generate_salt($config['config.password.minlength'] + 1);
      
      $db->Execute('UPDATE ' . SBConfig::getEnv('prefix') . '_admins
                    SET    password = ?,
                           validate = NULL
                    WHERE  email    = ?',
                    array($userbank->encrypt_password($password), $_POST['email']));
      
      $page->assign('password', $password);
      
      break;
    }
  }
  
  $page->display('page_lostpassword');
}
catch(Exception $e)
{
  $page->assign('error', $e->getMessage());
  $page->display('page_error');
}
?>