<?php
defined('PHPWG_ROOT_PATH') or die('Hacking attempt!');

class comments_blacklist_maintain extends PluginMaintain
{
  private $default_conf = array(
    'action' => 'reject',
    );
    
  private $file;
  
  function __construct($plugin_id)
  {
    parent::__construct($plugin_id);
    $this->file = PHPWG_ROOT_PATH . PWG_LOCAL_DIR . 'comments_blacklist.txt';
  }

  function install($plugin_version, &$errors=array())
  {
    global $conf;

    if (empty($conf['comments_blacklist']))
    {
      conf_update_param('comments_blacklist', $this->default_conf, true);
    }
    
    if (!file_exists($this->file)) 
    {
      touch($this->file);
    }
  }

  function update($old_version, $new_version, &$errors=array())
  {
    $this->install($new_version, $errors);
  }

  function uninstall()
  {
    conf_delete_param('comments_blacklist');

    @unlink($this->file);
  }
}
