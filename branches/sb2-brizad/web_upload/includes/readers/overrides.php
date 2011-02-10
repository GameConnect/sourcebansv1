<?php
require_once READER;

class OverridesReader extends SBReader
{
  public function prepare()
  {  }
  
  public function &execute()
  {
    $db        = Env::get('db');
    
    // Fetch overrides
    $overrides = $db->GetAll('SELECT type, name, flags
                              FROM   ' . Env::get('prefix') . '_overrides');
    
    list($overrides) = SBPlugins::call('OnGetOverrides', $overrides);
    
    return $overrides;
  }
}
?>