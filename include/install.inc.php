<?php
defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

function comm_blacklist_install() 
{
  global $conf;
  
  if (empty($conf['comments_blacklist']))
  {
    $comm_blacklist_default_config = serialize(array(
      'action' => 'reject',
      ));
  
    conf_update_param('comments_blacklist', $comm_blacklist_default_config);
    $conf['comments_blacklist'] = $comm_blacklist_default_config;
  }
  
  if ( file_exists(PWG_LOCAL_DIR) and !file_exists(PWG_LOCAL_DIR . 'comments_blacklist.txt') ) 
  {
    touch(PWG_LOCAL_DIR . 'comments_blacklist.txt');
  }

}

?>