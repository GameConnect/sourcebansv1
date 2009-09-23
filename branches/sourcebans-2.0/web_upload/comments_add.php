<?php
require_once 'init.php';
require_once WRITERS_DIR . 'comments.php';

$phrases  = Env::get('phrases');
$userbank = Env::get('userbank');
$page     = new Page(ucwords($phrases['add_comment']), !isset($_GET['nofullpage']));

try
{
  if(!$userbank->is_admin())
    throw new Exception($phrases['access_denied']);
  if($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    try
    {
      CommentsWriter::add($_POST['bid'], $_POST['type'], $_POST['message']);
      
      exit(json_encode(array(
        'redirect' => Env::get('active')
      )));
    }
    catch(Exception $e)
    {
      exit(json_encode(array(
        'error' => $e->getMessage()
      )));
    }
  }
  
  $page->display('page_comments_add');
}
catch(Exception $e)
{
  $page->assign('error', $e->getMessage());
  $page->display('page_error');
}
?>