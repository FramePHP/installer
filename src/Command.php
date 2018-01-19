<?php
/**
*
*/

class Command
{
    const BASE = __DIR__.'/../';
    const VNDR = __DIR__.'/../vendor/frame-php/';
    const APP  = __DIR__.'/../app/site/';
    const SYS  = __DIR__.'/../sys/';

    public function __construct()
    {

    }

    public static function CheckInstallCMD($reset = true)
    {
        # code...
        if(!realpath(self::APP) || !realpath(self::SYS) || !realpath(self::BASE.'data') || $reset){
            self::PostCreateCMD();
        }
        if(realpath(self::VNDR.'application') || realpath(self::VNDR.'module-app')){
            self::PostUpdateCMD();
        }
    }

    public static function PostCreateCMD()
    {
//         print "Rename install composer.json and README files...".PHP_EOL;
//         rename(self::BASE.'composer.json', self::BASE.'installer.json');
//         rename(self::BASE.'README.md', self::BASE.'installed.md');
//         print "Done!".PHP_EOL;
        
        print "Moving into project root, app and sys folders ... ".PHP_EOL;
        if(realpath(self::VNDR.'application')){
            self::CopyDir(realpath('vendor/frame-php/application'), SELF::BASE);
        }
        if(realpath(self::VNDR.'module-app')){
            self::CopyDir(realpath('vendor/frame-php/module-app'), SELF::APP);
        }
        if(realpath(self::VNDR.'module-sys')){
            self::CopyDir(realpath('vendor/frame-php/module-sys'), SELF::SYS);
        }
        print "Done!".PHP_EOL;

    }

    public static function PostUpdateCMD()
    {
        print "Deleting duplicate files from vendor folder ... ".PHP_EOL;
        if($application = realpath(self::BASE.'vendor/frame-php/application')){
            self::RmvDir($application, 'application');
        }
        if($module_app = realpath(self::BASE.'vendor/frame-php/module-app')){
            self::RmvDir($module_app, 'module-app');
        }
        if($module_sys = realpath(self::BASE.'vendor/frame-php/module-sys')){
            self::RmvDir($module_sys, 'module-sys');
        }
        print "Done!".PHP_EOL;

    }

    public static function CopyDir($source, $dest)
    {
        if(!$source) return;
        $director = new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($director, \RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $item ) {

            $path = $item->getRealPath();
            $name = $dest.$iterator->getSubPathName();
            
            if(strpos($path, ".git") !== false){
                continue;
            }
            if($file = realpath($name)){
                self::RmvDir($name);
            }
            if ($item->isFile()){
                rename($path, $name);
            }
            elseif ($item->isDir()){
                mkdir($name, 0755, true);
            }
            if(!$iterator->valid()) rmdir($path);
        }
        self::RmvDir($source);

    }
    public static function RmvDir($folder, $skip = null)
    {
        try {
            $i = new DirectoryIterator($folder); 
        } 
        catch (Exception $e) {
            return;
        }

        foreach($i as $f) {

            $path = $f->getRealPath();
            $name = $f->getBasename();

            if($f->isDot()){
                continue; 
            }

            chmod($path, 0777);

            if($f->isFile()) {
                unlink($path);
            }
            elseif($f->isDir() && !$i->valid()) {
                rmdir($path);                
            }
            else{
                static::RmvDir($name);
            }
        }

        if(is_array($skip) && in_array($name, $skip)) return;
        if(!is_array($skip) && $skip == $name) return;
        return rmdir($folder);
    }
}
