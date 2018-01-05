<?php
/**
*
*/
define('BASE_DIR', __DIR__.'/');
define('APP_PATH', __DIR__.'/app/');
define('SYS_PATH', __DIR__.'/sys/');

var $DeleteFiles = function($folder)
{
  foreach (glob($folder.'/*', GLOB_NOSORT) as $file) {
    if(stripos($file,'framework') !== false) return;
    if(is_dir($file)) return $DeleteFiles($file);
    if(stripos($file,'module-aop')) rename($file, APP_PATH.basename($file));
    if(stripos($file,'module-sys')) rename($file, SYS_PATH.basename($file));
  }
}
$DeleteFiles('vendor/frame-php');
