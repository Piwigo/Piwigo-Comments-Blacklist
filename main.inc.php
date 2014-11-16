<?php 
/*
Plugin Name: Comments Blacklist
Version: auto
Description: Define a list of words which are not authorized in a comment.
Plugin URI: auto
Author: Mistic
Author URI: http://www.strangeplanet.fr
*/

defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

if (basename(dirname(__FILE__)) != 'comments_blacklist')
{
  add_event_handler('init', 'comments_blacklist_error');
  function comments_blacklist_error()
  {
    global $page;
    $page['errors'][] = 'Comments Blacklist folder name is incorrect, uninstall the plugin and rename it to "comments_blacklist"';
  }
  return;
}

global $conf;

define('COMM_BLACKLIST_PATH' , PHPWG_PLUGINS_PATH . 'comments_blacklist/');
define('COMM_BLACKLIST_ADMIN', get_root_url() . 'admin.php?page=plugin-comments_blacklist');
define('COMM_BLACKLIST_FILE',  PHPWG_ROOT_PATH . PWG_LOCAL_DIR . 'comments_blacklist.txt');


$conf['comments_blacklist'] = safe_unserialize($conf['comments_blacklist']);


if (defined('IN_ADMIN'))
{
  add_event_handler('get_admin_plugin_menu_links', 'comm_blacklist_admin_plugin_menu_links');
}
else
{
  add_event_handler('user_comment_check', 'comm_blacklist_user_comment_check', EVENT_HANDLER_PRIORITY_NEUTRAL, 2);
}


/**
 * admin link
 */
function comm_blacklist_admin_plugin_menu_links($menu) 
{
  $menu[] = array(
    'NAME' => 'Comments Blacklist',
    'URL' => COMM_BLACKLIST_ADMIN,
    );
  return $menu;
}

/**
 * comment check
 */
function comm_blacklist_user_comment_check($comment_action, $comm)
{
  global $conf;
  
  if ($comment_action==$conf['comments_blacklist']['action']
      or $comment_action=='reject' or !isset($comm['content'])
    )
  {
    return $comment_action;
  }
  
  $blacklist = @file(COMM_BLACKLIST_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  
  if (empty($blacklist))
  {
    return $comment_action;
  }
  
  $blacklist = array_map(create_function('$w', 'return preg_quote($w);'), $blacklist);
  $blacklist = implode('|', $blacklist);
  
  if (preg_match('#\b('.$blacklist.')\b#i', $comm['content']) or
      (isset($comm['author']) and preg_match('#\b('.$blacklist.')\b#i', $comm['author']))
    )
  {
    return $conf['comments_blacklist']['action'];
  }
  
  return $comment_action;
}
