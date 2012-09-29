<?php
defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

defined('COMM_BLACKLIST_ID') or define('COMM_BLACKLIST_ID', basename(dirname(__FILE__)));
include_once(PHPWG_PLUGINS_PATH . COMM_BLACKLIST_ID . '/include/install.inc.php');


function plugin_install() 
{
  comm_blacklist_install();
  define('comm_blacklist_installed', true);
}

function plugin_activate()
{
  if (!defined('comm_blacklist_installed'))
  {
    comm_blacklist_install();
  }
}

function plugin_uninstall() 
{
  @unlink(PWG_LOCAL_DIR . 'comments_blacklist.txt');
}

?>