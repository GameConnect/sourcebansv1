<?php
require_once READERS_DIR . 'comments.php';

class CommentsWriter
{
  /**
   * Adds a comment
   *
   * @param  integer $ban_id  The id of the ban/protest/submission to comment to
   * @param  integer $type    The type of the comment (BAN_TYPE, PROTEST_TYPE, SUBMISSION_TYPE)
   * @param  string  $message The message of the comment
   * @return The id of the added comment
   */
  public static function add($ban_id, $type, $message)
  {
    $db       = Env::get('db');
    $phrases  = Env::get('phrases');
    $userbank = Env::get('userbank');
    
    if(empty($ban_id)  || !is_numeric($ban_id))
      throw new Exception('Invalid ban ID supplied.');
    if(empty($type)    || !is_string($type))
      throw new Exception('Invalid type supplied.');
    if(empty($message) || !is_string($message))
      throw new Exception('Invalid message supplied.');
    
    $db->Execute('INSERT INTO ' . Env::get('prefix') . '_comments (type, ban_id, admin_id, message, time)
                  VALUES      (?, ?, ?, ?, UNIX_TIMESTAMP())',
                  array($type, $ban_id, $userbank->GetID(), $message));
    
    $id                    = $db->Insert_ID();
    $comments_reader       = new CommentsReader();
    $comments_reader->bid  = $ban_id;
    $comments_reader->type = $type;
    $comments_reader->removeCacheFile();
    
    SBPlugins::call('OnAddComment', $id, $ban_id, $type, $message);
    
    return $id;
  }
  
  
  /**
   * Deletes a comment
   *
   * @param integer $id The id of the comment to delete
   */
  public static function delete($id)
  {
    $db      = Env::get('db');
    $phrases = Env::get('phrases');
    
    if(empty($id) || !is_numeric($id))
      throw new Exception('Invalid ID supplied.');
    
    $comment = $db->GetRow('SELECT ban_id, type
                            FROM   ' . Env::get('prefix') . '_comments
                            WHERE  id = ?',
                            array($id));
    
    $db->Execute('DELETE FROM ' . Env::get('prefix') . '_comments
                  WHERE       id = ?',
                  array($id));
    
    $comments_reader       = new CommentsReader();
    $comments_reader->bid  = $comment['ban_id'];
    $comments_reader->type = $comment['type'];
    $comments_reader->removeCacheFile();
    
    SBPlugins::call('OnDeleteComment', $id);
  }
  
  
  /**
   * Edits a comment
   *
   * @param integer $id      The id of the comment to edit
   * @param string  $message The message of the comment
   */
  public static function edit($id, $message)
  {
    $db       = Env::get('db');
    $phrases  = Env::get('phrases');
    $userbank = Env::get('userbank');
    
    if(empty($id)      || !is_numeric($id))
      throw new Exception('Invalid ID supplied.');
    if(empty($message) || !is_string($message))
      throw new Exception('Invalid message supplied.');
    
    $comment = $db->GetRow('SELECT ban_id, type
                            FROM   ' . Env::get('prefix') . '_comments
                            WHERE  id = ?',
                            array($id));
    
    $db->Execute('UPDATE ' . Env::get('prefix') . '_comments
                  SET    message       = ?,
                         edit_admin_id = ?,
                         edit_time     = UNIX_TIMESTAMP()
                  WHERE  id            = ?',
                  array($message, $userbank->GetID(), $id));
    
    $comments_reader       = new CommentsReader();
    $comments_reader->bid  = $comment['ban_id'];
    $comments_reader->type = $comment['type'];
    $comments_reader->removeCacheFile();
    
    SBPlugins::call('OnEditComment', $id, $message);
  }
}
?>