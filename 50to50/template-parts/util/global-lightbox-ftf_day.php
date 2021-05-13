<?php
  define('LIGHTBOX_COOKIE_NAME', 'STYXKEY_lightbox');

  global $lightbox_seen;  
  if(empty($_COOKIE[LIGHTBOX_COOKIE_NAME])) {
    setcookie(LIGHTBOX_COOKIE_NAME, 1, time() + (86400 * 30), '/', $_SERVER['HTTP_HOST']);
    $lightbox_seen = false;
  } else {
    $lightbox_seen = true;
  }
?>