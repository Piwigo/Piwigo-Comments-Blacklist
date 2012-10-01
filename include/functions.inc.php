<?php
defined('COMM_BLACKLIST_PATH') or die('Hacking attempt!');

function comm_blacklist_user_comment_check($comment_action, $comm)
{
  global $conf;
  
  if ( $comment_action==$conf['comments_blacklist']['action'] or $comment_action=='reject' )
  {
    return $comment_action;
  }
  
  $blacklist = file(COMM_BLACKLIST_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $blacklist = array_map(create_function('$w', 'return " ".preg_quote($w)." ";'), $blacklist);
  $blacklist = implode('|', $blacklist);

  $content = str_replace(array("\r\n","\n"), ' ', $comm['content']);
  
  if ( preg_match('#('.$blacklist.')#i', ' '.$comm['author'].' ') or preg_match('#('.$blacklist.')#i', ' '.$content.' ') )
  {
    return $conf['comments_blacklist']['action'];
  }
  
  return $comment_action;
}

?>