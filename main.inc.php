<?php 
/*
Plugin Name: Comments Blacklist
Version: auto
Description: Define a list of words which are not authorized in a comment.
Plugin URI: http://piwigo.org/ext/extension_view.php?eid=637
Author: Mistic
Author URI: http://www.strangeplanet.fr
*/

defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

defined('COMM_BLACKLIST_ID') or define('COMM_BLACKLIST_ID', basename(dirname(__FILE__)));
define('COMM_BLACKLIST_PATH' ,   PHPWG_PLUGINS_PATH . COMM_BLACKLIST_ID . '/');
define('COMM_BLACKLIST_ADMIN',   get_root_url() . 'admin.php?page=plugin-' . COMM_BLACKLIST_ID);
define('COMM_BLACKLIST_FILE',    PHPWG_ROOT_PATH . PWG_LOCAL_DIR . 'comments_blacklist.txt');
define('COMM_BLACKLIST_VERSION', 'auto');


add_event_handler('init', 'comm_blacklist_init');

if (defined('IN_ADMIN'))
{
  add_event_handler('get_admin_plugin_menu_links', 'comm_blacklist_admin_plugin_menu_links');
  
  function comm_blacklist_admin_plugin_menu_links($menu) 
  {
    array_push($menu, array(
      'NAME' => 'Comments Blacklist',
      'URL' => COMM_BLACKLIST_ADMIN,
    ));
    return $menu;
  }
}
else
{
  add_event_handler('user_comment_check', 'comm_blacklist_user_comment_check', EVENT_HANDLER_PRIORITY_NEUTRAL, 2);
  add_event_handler('user_comment_check_albums', 'comm_blacklist_user_comment_check', EVENT_HANDLER_PRIORITY_NEUTRAL, 2);
  add_event_handler('user_comment_check_guestbook', 'comm_blacklist_user_comment_check', EVENT_HANDLER_PRIORITY_NEUTRAL, 2);
  include_once(COMM_BLACKLIST_PATH . 'include/functions.inc.php');
}


/**
 * plugin initialization
 */
function comm_blacklist_init()
{
  global $conf, $pwg_loaded_plugins;
  
  // apply upgrade if needed
  if (
    COMM_BLACKLIST_VERSION == 'auto' or 
    $pwg_loaded_plugins[COMM_BLACKLIST_ID]['version'] == 'auto' or
    version_compare($pwg_loaded_plugins[COMM_BLACKLIST_ID]['version'], COMM_BLACKLIST_VERSION, '<')
  )
  {
    include_once(COMM_BLACKLIST_PATH . 'include/install.inc.php');
    comm_blacklist_install();
    
    if ( $pwg_loaded_plugins[COMM_BLACKLIST_ID]['version'] != 'auto' and COMM_BLACKLIST_VERSION != 'auto' )
    {
      $query = '
UPDATE '. PLUGINS_TABLE .'
SET version = "'. COMM_BLACKLIST_VERSION .'"
WHERE id = "'. COMM_BLACKLIST_ID .'"';
      pwg_query($query);
      
      $pwg_loaded_plugins[COMM_BLACKLIST_ID]['version'] = COMM_BLACKLIST_VERSION;
      
      if (defined('IN_ADMIN'))
      {
        $_SESSION['page_infos'][] = 'Comments Blacklist updated to version '. COMM_BLACKLIST_VERSION;
      }
    }
  }
  
  // load plugin language file
  load_language('plugin.lang', COMM_BLACKLIST_PATH);
  
  // prepare plugin configuration
  $conf['comments_blacklist'] = unserialize($conf['comments_blacklist']);
}


?>