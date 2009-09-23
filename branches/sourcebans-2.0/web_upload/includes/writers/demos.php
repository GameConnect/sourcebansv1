<?php
require_once READERS_DIR . 'demos.php';

class DemosWriter
{
  /**
   * Adds a demo
   *
   * @param  integer $ban_id   The ban/submission id of the demo
   * @param  integer $type     The type of the demo (BAN_TYPE, SUBMISSION_TYPE)
   * @param  string  $filename The filename of the demo
   * @param  string  $tmp_name The temporary filename of the demo
   * @return The id of the added demo
   */
  public static function add($ban_id, $type, $filename, $tmp_name)
  {
    $db      = Env::get('db');
    $phrases = Env::get('phrases');
    
    if(empty($ban_id)   || !is_numeric($ban_id))
      throw new Exception('Invalid ban ID supplied.');
    if(empty($type)     || !is_string($type))
      throw new Exception('Invalid type supplied.');
    if(empty($filename) || !is_string($filename))
      throw new Exception('Invalid filename supplied.');
    if(!in_array(pathinfo($filename, PATHINFO_EXTENSION), array('dem', 'rar', 'zip')))
      throw new Exception('Unsupported file format.');
    if(!move_uploaded_file($tmp_name, DEMOS_DIR . $type . $ban_id . '_' . $filename))
      throw new Exception('Unable to upload demo.');
    
    $db->Execute('INSERT INTO ' . Env::get('prefix') . '_demos (ban_id, type, filename)
                  VALUES      (?, ?, ?)',
                  array($ban_id, $type, $filename));
    
    $id                   = $db->Insert_ID();
    $demos_reader         = new DemosReader();
    $demos_reader->ban_id = $ban_id;
    $demos_reader->type   = $type;
    $demos_reader->removeCacheFile();
    
    SBPlugins::call('OnAddDemo', $id, $ban_id, $type, $filename);
    
    return $id;
  }
  
  
  /**
   * Deletes a demo
   *
   * @param integer $id The id of the demo to delete
   */
  public static function delete($id)
  {
    $db      = Env::get('db');
    $phrases = Env::get('phrases');
    
    if(empty($id) || !is_numeric($id))
      throw new Exception('Invalid ID supplied.');
    
    $demo = $db->GetRow('SELECT ban_id, type, filename
                         FROM   ' . Env::get('prefix') . '_demos
                         WHERE  id = ?',
                         array($id));
    
    if(empty($demo))
      throw new Exception('Invalid ID supplied.');
    
    unlink(DEMOS_DIR . $demo['type'] . $demo['ban_id'] . '_' . $demo['filename']);
    
    $db->Execute('DELETE FROM ' . Env::get('prefix') . '_demos
                  WHERE       id = ?',
                  array($id));
    
    $demos_reader         = new DemosReader();
    $demos_reader->ban_id = $demo['ban_id'];
    $demos_reader->type   = $demo['type'];
    $demos_reader->removeCacheFile();
    
    SBPlugins::call('OnDeleteDemo', $id);
  }
}
?>