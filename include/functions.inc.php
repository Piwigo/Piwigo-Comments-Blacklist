<?php
defined('COMM_BLACKLIST_PATH') or die('Hacking attempt!');

function comm_blacklist_user_comment_check($comment_action, $comm)
{
  global $conf;
  
  if ($comment_action==$conf['comments_blacklist']['action'] or $comment_action=='reject')
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
  
  if (preg_match('#\b('.$blacklist.')\b#i', $comm['author']) or
      preg_match('#\b('.$blacklist.')\b#i', $comm['content'])
    )
  {
    return $conf['comments_blacklist']['action'];
  }
  
  return $comment_action;
}

?>