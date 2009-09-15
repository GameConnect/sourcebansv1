<?php
require_once 'init.php';
require_once READERS_DIR . 'comments.php';
require_once WRITERS_DIR . 'comments.php';

$phrases  = Env::get('phrases');
$userbank = Env::get('userbank');
$page     = new Page('Edit Comment');

try
{
  if(!$userbank->HasAccess(array('OWNER')))
    throw new Exception($phrases['access_denied']);
  if($_SERVER['REQUEST_METHOD'] == 'POST')
  {
    try
    {
      CommentsWriter::edit($_POST['id'], $_POST['message']);
      
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
  
  $comments_reader = new CommentsReader();
  $comments        = $comments_reader->executeCached(ONE_MINUTE * 5);
  
  if(!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($comments[$_GET['id']]))
    throw new Exception('Invalid ID specified.');
  
  $comment         = $comments[$_GET['id']];
  
  $page->assign('comment_message', $comment['message']);
  $page->assign('comments',        $comments);
  $page->display('page_comments_edit');
}
catch(Exception $e)
{
  $page->assign('error', $e->getMessage());
  $page->display('page_error');
}
?>