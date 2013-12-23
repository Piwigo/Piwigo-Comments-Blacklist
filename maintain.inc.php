<?php
defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

class comments_blacklist_maintain extends PluginMaintain
{
  private $installed = false;
  
  private $default_conf = array(
    'action' => 'reject',
    );
    
  private $file;
  
  function __contruct($plugin_id)
  {
    parent::_construct($plugin_id);
    $this->file = PHPWG_ROOT_PATH . PWG_LOCAL_DIR . 'comments_blacklist.txt';
  }

  function install($plugin_version, &$errors=array())
  {
    global $conf, $prefixeTable;

    if (empty($conf['comments_blacklist']))
    {
      $conf['comments_blacklist'] = serialize($this->default_conf);
      conf_update_param('comments_blacklist', $conf['comments_blacklist']);
    }
    
    if (!file_exists($this->file)) 
    {
      touch($this->file);
    }

    $this->installed = true;
  }

  function activate($plugin_version, &$errors=array())
  {
    if (!$this->installed)
    {
      $this->install($plugin_version, $errors);
    }
  }

  function deactivate()
  {
  }

  function uninstall()
  {
    conf_delete_param('comments_blacklist');

    @unlink($this->file);
  }
}
