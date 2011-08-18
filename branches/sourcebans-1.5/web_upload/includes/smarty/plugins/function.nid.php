<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     nid
 * Version:  1.0
 * Date:     Janurary 15, 2008
 * Author:   Brad Smith <brizad@stompfest.com>
 * Purpose:  so tired of doing name and id for form objects
 * Input:    id
 * 
 * Examples: {nid id="UserName"}
 * -------------------------------------------------------------
 */
function smarty_function_nid($params, &$smarty)
{
  extract($params);
  
  if(empty($id))
  {
    $smarty->trigger_error('nid: missing "id" parameter');
    return;
  }
  
  return 'id="' . $id . '" name="' . $id . '"';
}
?>