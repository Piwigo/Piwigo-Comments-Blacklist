<?php
defined('COMM_BLACKLIST_PATH') or die('Hacking attempt!');

function comm_blacklist_user_comment_check($comment_action, $comm)
{
  global $conf;
  
  if ($comment_action == $conf['comments_blacklist']['action'])
  {
    return $comment_action;
  }
  
  $blacklist = file(COMM_BLACKLIST_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $blacklist = array_map('strtolower', $blacklist);
  $content = ' '.strtolower($comm['content']).' ';
  $author = ' '.strtolower($comm['author']).' ';
  
  foreach ($blacklist as $word)
  {
    $word = ' '.$word.' ';
    if ( strpos($content, $word)!==false or strpos($author, $word)!==false )
    {
      return $conf['comments_blacklist']['action'];
    }
  }
  
  return $comment_action;
}

?>