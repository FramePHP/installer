<?php
/**
*
*/

class Command
{
    const BASE = __DIR__.'/';
    const VNDR = __DIR__.'/vendor/frame-php/';
    const APP  = __DIR__.'/app/site/';
    const SYS  = __DIR__.'/sys/';

    public function __construct()
    {

    }

    public static function PostCreateCMD()
    {
        if(realpath(self::VNDR.'application')){
            self::CopyDir(realpath('vendor/frame-php/application'), self::BASE);
        }
        if(realpath(self::VNDR.'module-app')){
            self::CopyDir(realpath('vendor/frame-php/module-app'), self::APP);
        }
        if(realpath(self::VNDR.'module-sys')){
            self::CopyDir(realpath('vendor/frame-php/module-sys'), self::SYS);
        }

    }

    public static function PostUpdateCMD()
    {
        if($application = realpath(BASE.'vendor/frame-php/application')){
            self::RmvDir($application);
        }
        if($module_app = realpath(BASE.'vendor/frame-php/module-app')){
            self::RmvDir($module_app);
        }
        if($module_sys = realpath(BASE.'vendor/frame-php/module-sys')){
            self::RmvDir($module_sys);
        }

    }

    public static function CopyDir($source, $dest)
    {
        $director = new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($director, \RecursiveIteratorIterator::SELF_FIRST);

        print "Moving framework files into this folder: $dest ...";
        foreach ($iterator as $item ) {

            $path = $item->getRealPath();
            $name = $dest.$iterator->getSubPathName();

            if(stripos($path,'.git') !== false) continue;//static::RmvDir($path);
            if(!file_exists($path) || file_exists($name) || is_dir($name)) continue;

            if ($item->isFile()){
                rename($path, $name);
            }
            if ($item->isDir()){
                mkdir($name, 0755, true);
            }
            if(!$iterator->valid()) rmdir($path);
        }
        static::RmvDir($source);
        print "Done!".PHP_EOL;

    }
    public static function RmvDir($path)
    {

        $i = new DirectoryIterator($path);

        foreach($i as $f) {
            if($f->isFile()) {
                unlink($f->getRealPath());
            } else if(!$f->isDot() && $f->isDir()) {
                static::RmvDir($f->getRealPath());
            }
        }
        rmdir($path);
    }
}
