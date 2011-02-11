<?php
require_once BASE_PATH . 'api.php';

class SB_SEO extends SBPlugin
{
  public $name = 'Search Engine Optimization';
  public $author = 'Tsunami';
  public $desc = 'Converts URLs into search engine optimized URLs.';
  public $version = SB_VERSION;
  public $url = 'http://www.sourcebans.net';

  public static function OnBuildQuery(&$url)
  {
    // Converts page_mode.php?name1=value1&name2=value2 to /page_mode/name1=value1/name2=value2
    list($path, $query) = explode('.php?', $url);
    $url                = dirname($path) . '/' . str_replace('_', '/', basename($path)) . '/' . str_replace('&amp;', '/', $query);
  }
  
  public static function OnBuildUrl(&$url)
  {
    // Converts page_mode.php?name1=value1&name2=value2 to /page_mode/name1=value1/name2=value2
    list($path, $query) = explode('.php',  $url);
    $url                = dirname($path) . '/' . basename($path);
    
    if(!empty($query))
      $url .= '/' . str_replace('&amp;', '/', substr($query, 1));
  }
}
