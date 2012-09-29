<?php
defined('COMM_BLACKLIST_PATH') or die('Hacking attempt!');
 
global $template, $page;

// save config
if (isset($_POST['save_config']))
{
  $conf['comments_blacklist'] = array(
    'action' => $_POST['action'],
    );
    
  file_put_contents(COMM_BLACKLIST_FILE, $_POST['content']);
  conf_update_param('comments_blacklist', serialize($conf['comments_blacklist']));
  array_push($page['infos'], l10n('Information data registered in database'));
}
  
// template vars
$template->assign($conf['comments_blacklist']);
$template->assign(array(
  'blacklist' => file_get_contents(COMM_BLACKLIST_FILE),
  'COMM_BLACKLIST_PATH'=> get_root_url() . COMM_BLACKLIST_PATH,
  'COMM_BLACKLIST_ADMIN' => COMM_BLACKLIST_ADMIN,
  ));
  
// send page content
$template->set_filename('comm_blacklist_content', realpath(COMM_BLACKLIST_PATH . 'admin.tpl'));
$template->assign_var_from_handle('ADMIN_CONTENT', 'comm_blacklist_content');

?>