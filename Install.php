<?php
/**
*
*/

class Install
{
    const BASE = __DIR__.'/';
    const VNDR = __DIR__.'/vendor/frame-php/';
    const APP  = __DIR__.'/module-sys/';
    const SYS  = __DIR__.'/module-app/';

    public function __construct()
    {

    }

    public static function PostCreateCMD()
    {
        if(realpath(self::VNDR.'application')){
           self::CopyDir(realpath('vendor/frame-php/application'), '.');
        }
        if(realpath(self::VNDR.'module-app')){
           self::CopyDir(realpath('vendor/frame-php/module-app'), 'app');
        }
        if(realpath(self::VNDR.'module-sys')){
           self::CopyDir(realpath('vendor/frame-php/module-sys'), 'sys');
        }
    }

    public static function CopyDir($source, $dest)
    {
        $director = new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($director, \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterator as $item ) {
            if ($item->isDir()) {
                mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            } else {
                rename($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
            rmdir($item->getPathName());
        }
    }
}
