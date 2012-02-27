<?php
// Version: 0.9.1b
  $cwd = getcwd();
  $path = substr($cwd,0,strpos($cwd,'wp-content/'));
  require($path . 'wp-blog-header.php');
  dynamic_sidebar ('ESI Widget Sidebar');
?>
