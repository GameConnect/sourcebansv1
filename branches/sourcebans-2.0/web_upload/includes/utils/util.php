<?php
/**
 * This file contains general Utils
 * 
 * @author InterWave Studios
 * @version 2.0.0
 * @copyright SourceBans (C)2007-2009 InterWaveStudios.com.  All rights reserved.
 * @package SourceBans
 * $Id$
 */

/**
 * This class contains general Utils for SourceBans
 */
class Util
{
  /**
   * Searches an array recursively
   *
   * @param mixed $needle   The value to find
   * @param array $haystack The array to search in
   */
  public static function array_search_recursive($needle, $haystack)
  {
    if(!is_array($haystack))
      return false;
    
    $path = array();
    foreach($haystack as $key => $val)
    {
      if(is_array($val) && $subPath = array_search_recursive($needle, $val))
        return array_merge($path, array($key), $subPath);
      else if($val == $needle)
      {
        $path[] = $key;
        return $path;
      }
    }
    return false;
  }
  
  
  /**
   * This will sort a collection based on the collection(array()) values
   *
   * @param array   $array  The array to sort
   * @param mixed   $column The column to sort by
   * @param integer $order  The order to sort the array (SORT_ASC, SORT_DESC)
   */
  public static function array_qsort(&$array, $column = 0, $order = SORT_ASC)
  {
    if($order == SORT_DESC)
      $code = "return !strnatcmp(\$a['" . $column . "'], \$b['" . $column . "']);";
    else
      $code = "return strnatcmp(\$a['" . $column . "'], \$b['" . $column . "']);";
    
    uasort($array, create_function('$a, $b', $code));
  }
  
  
  /**
   * Converts seconds into string format
   *
   * @param integer $secs    the amount of seconds
   * @param bool    $textual Should we show Mo, Wk, etc or just 00:00:00
   * @return string
   */
  public static function SecondsToString($secs, $textual = true)
  {
    if($textual)
    {
      $desc = array('mo', 'wk', 'd', 'hr', 'min', 'sec');
      $div  = array(2592000, 604800, 86400, 3600, 60, 1);
      $ret  = '';
      for($i = 0; $i < count($div); $i++)
      {
        if(($cou = round($secs / $div[$i])))
        {
          $ret  .= $cou . ' ' . $desc[$i] . ', ';
          $secs %= $div[$i];
        }
      }
      $ret  = substr($ret, 0, -2);
    }
    else
    {
      $hours = floor($secs / 60 / 60);
      $secs -= $hours * 60 * 60;
      $mins  = floor($secs / 60);
      $secs %= 60;
      $ret   = $hours . ':' . $mins . ':' . $secs;
    }
    return $ret;
  }
  
  
  /**
   * Truncate string if too long
   *
   * @param string  $string      The string to truncate
   * @param integer $length      The maximum length before truncating
   * @param string  $etc         The string to append
   * @param bool    $break_words Truncate to the last space
   * @param bool    $middle      Truncate in the middle of the string
   * @return string
   */
  public static function trunc($string, $length = 80, $etc = '...', $break_words = false, $middle = false) 
  {
    if(!$length)
      return '';
    
    if(utf8_strlen($string) <= $length)
      return $string;
    
    $length -= min($length, utf8_strlen($etc));
    
    if(!$break_words && !$middle)
      $string = preg_replace('/\s+?(\S+)?$/', '', utf8_substr($string, 0, $length + 1));
    if($middle)
      return utf8_substr($string, 0, $length / 2) . $etc . utf8_substr($string, -$length / 2);
    else
      return utf8_substr($string, 0, $length)     . $etc;
  }
  
  
  /**
   * Get the size of a directory
   *
   * @param string $path The path to the directory
   * @return array
   */
  public static function getDirectorySize($path)
  {
    $size   = 0;
    $count  = 0;
    $dirs   = 0;
    if($dir = opendir($path))
    {
      while(($file = readdir($dir)) !== false)
      {
        $path     .= '/' . $file;
        if($file != '.' && $file != '..' && !is_link($path))
        {
          if(is_dir($path))
          {
            $dirsize = self::getDirectorySize($path);
            $size   += $dirsize['size'];
            $count  += $dirsize['count'];
            $dirs   += $dirsize['dirs'] + 1;
          }
          else if(is_file($path))
          {
            $size   += filesize($path);
            $count++;
          }
        }
      }
      closedir($dir);
    }
    
    return array('size'  => $size,
                 'count' => $count,
                 'dirs'  => $dirs);
  }
  
  
  /**
   * Formats a size in English units
   *
   * @param integer $size  The size in bytes
   * @param integer $round The amount of decimals to round to
   * @return string
   */
  public static function formatSize($size, $round = 2)
  {
    $sizes = array('bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    for($i = 0; $size > 1024 && $i < count($sizes) - 1; $i++) $size /= 1024;
    return round($size, $round) . ' ' . $sizes[$i];
  }
  
  
  /**
   * Sends an e-mail using PHPMailer
   *
   * @param mixed  $to      The e-mail address(es) to send the e-mail to. A string to specify a single address, or an array for multiple addresses
   * @param string $from    The e-mail address to send the e-mail from
   * @param string $subject The e-mail subject
   * @param string $message The e-mail message
   */
  public static function mail($to, $from, $subject, $message)
  {
    $config = Env::get('config');
    $mail   = new PHPMailer(true);
    
    // If SMTP is enabled
    if($config['email.smtp'])
    {
      $mail->IsSMTP();
      $mail->Host = $config['email.host'];
      $mail->Port = $config['email.port'];
      
      // If SMTP username and password have been filled in
      if(!empty($config['email.username']) && !empty($config['email.password']))
      {
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['email.username'];
        $mail->Password   = $config['email.password'];
      }
      // If SMTP secure option has been chosen
      if(!empty($config['email.secure']))
        $mail->SMTPSecure = $config['email.secure'];
    }
    // If $to is an array, add all addresses
    if(is_array($to))
    {
      foreach($to as $address)
        $mail->AddAddress($address);
    }
    else
      $mail->AddAddress($to);
    
    $mail->Subject = $subject;
    $mail->MsgHTML($message);
    $mail->SetFrom($from);
    $mail->Send();
  }
  
  
  /**
   * Parses an INI file with no interpretation of value content
   * 
   * @param  string $file The INI file to parse
   * @author Jean-Jacques Guegan (http://mach13.com/loose-and-multiline-parse_ini_file-function-in-php)
   */
  public static function parse_ini_file($file)
  {
    $matches =
    $result  = array();
    
    $a       = &$result;
    $s       = '\s*([[:alnum:]_\- \*]+?)\s*';
    
    preg_match_all('#^\s*((\[' . $s . '\])|(("?)' . $s . '\\5\s*=\s*("?)(.*?)\\7))\s*(;[^\n]*?)?$#ms', @file_get_contents($file), $matches, PREG_SET_ORDER);
    
    foreach($matches as $match)
    {
      if(empty($match[2]))
        $a[$match[6]] = $match[8];
      else
        $a            = &$result[$match[3]];
    }
    
    return $result;
  }
  
  
  /**
   * Redirects to the given URL
   *
   * @param string $url The URL to redirect to, or null to redirect to the previous page
   */
  public static function redirect($url = null)
  {
    header('Location: ' . (is_null($url) ? $_SERVER['HTTP_REFERER'] : $url));
    exit;
  }
  
  
  /**
   * Builds a query string
   *
   * @param  array  $formdata form data to use for the query string
   * @return string URL that was built
   */
  public static function buildQuery($formdata = array())
  {
    $file  = Env::get('active');
    $query = $_GET;
    
    foreach($formdata as $name => $value)
    {
      if(is_null($value))
        unset($query[$name]);
      else
        $query[$name] = $value;
    }
    
    ksort($query);
    $url       = dirname($_SERVER['SCRIPT_NAME']) . '/' . $file . '?' . http_build_query($query, '', '&amp;');
    list($url) = SBPlugins::call('OnBuildQuery', $url);
    
    return $url;
  }
  
  
  /**
   * Builds a URL
   *
   * @param  array  $formdata form data to use for the URL
   * @return string URL that was built
   */
  public static function buildUrl($formdata = array())
  {
    if(isset($formdata['_']))
    {
      $file = $formdata['_'];
      unset($formdata['_']);
    }
    else
      $file = Env::get('active');
    
    $url       = dirname($_SERVER['SCRIPT_NAME']) . '/' . $file;
    if(!empty($formdata))
    {
      ksort($formdata);
      $url .= '?' . http_build_query($formdata, '', '&amp;');
    }
    list($url) = SBPlugins::call('OnBuildUrl', $url);
    
    return $url;
  }
  
  
  /**
   * Clears the database and template caches
   */
  public static function clearCache()
  {
    $page = new Page();
    
    foreach(scandir(CACHE_DIR) as $file)
    {
      if(is_file(CACHE_DIR . $file))
        unlink(CACHE_DIR . $file);
    }
    
    foreach(scandir($page->compile_dir) as $file)
    {
      if(is_file($page->compile_dir . '/' . $file))
        unlink($page->compile_dir . '/' . $file);
    }
  }
}
?>