<?php
require_once READER;
require_once READERS_DIR . 'server_query.php';

class ServersReader extends SBReader
{
  public function prepare()
  {  }
  
  public function &execute()
  {
    $db      = SBConfig::getEnv('db');
    
    // Fetch servers
    $servers = $db->GetAssoc('SELECT    se.id, se.ip, se.port, se.rcon, se.mod_id, se.enabled, mo.name AS mod_name, mo.folder AS mod_folder, mo.icon AS mod_icon,
                                        GROUP_CONCAT(DISTINCT sg.group_id ORDER BY sg.group_id) AS groups
                              FROM      ' . SBConfig::getEnv('prefix') . '_servers           AS se
                              LEFT JOIN ' . SBConfig::getEnv('prefix') . '_servers_srvgroups AS sg ON sg.server_id = se.id
                              LEFT JOIN ' . SBConfig::getEnv('prefix') . '_mods              AS mo ON mo.id        = se.mod_id
                              GROUP BY  id
                              ORDER BY  mod_name');
    
    // Parse server groups and fetch server info, players and rules
    foreach($servers as &$server)
    {
      $server_query_reader       = new ServerQueryReader();
      $server_query_reader->ip   = $server['ip'];
      $server_query_reader->port = $server['port'];
      $server_query_reader->type = SERVER_INFO;
      $server_info               = $server_query_reader->executeCached(ONE_MINUTE);
      
      $server                    = array_merge($server, $server_info);
      
      $server_query_reader->type = SERVER_PLAYERS;
      $server['players']         = $server_query_reader->executeCached(ONE_MINUTE);
      
      $server_query_reader->type = SERVER_RULES;
      $server['rules']           = $server_query_reader->executeCached(ONE_MINUTE);
      
      $server['groups']          = explode(',', $server['groups']);
    }
    
    list($servers) = SBPlugins::call('OnGetServers', $servers);
    
    return $servers;
  }
}
?>