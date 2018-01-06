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
        print "Moving into project root, app and sys folders ... ".PHP_EOL;
        if(realpath(self::VNDR.'application')){
            self::CopyDir(realpath('vendor/frame-php/application'), self::BASE);
        }
        if(realpath(self::VNDR.'module-app')){
            self::CopyDir(realpath('vendor/frame-php/module-app'), self::APP);
        }
        if(realpath(self::VNDR.'module-sys')){
            self::CopyDir(realpath('vendor/frame-php/module-sys'), self::SYS);
        }
        print "Done!".PHP_EOL;

    }

    public static function PostUpdateCMD()
    {
        print "Deleting duplicate files from vendor folder ... ".PHP_EOL;
        if($application = realpath(BASE.'vendor/frame-php/application')){
            self::RmvDir($application);
        }
        if($module_app = realpath(BASE.'vendor/frame-php/module-app')){
            self::RmvDir($module_app);
        }
        if($module_sys = realpath(BASE.'vendor/frame-php/module-sys')){
            self::RmvDir($module_sys);
        }
        print "Done!".PHP_EOL;

    }

    public static function CopyDir($source, $dest)
    {
        $director = new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($director, \RecursiveIteratorIterator::SELF_FIRST);
        
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

    }
    public static function RmvDir($path)
    {

        $i = new DirectoryIterator($path);
        
        foreach($i as $f) {
            chmod($f->getRealPath(), 0777);
            if($f->isDot()) continue;

            if($f->isFile()) {
                unlink($f->getRealPath());
            }
            elseif($f->isDir()) {
                static::RmvDir($f->getRealPath());
            }
            else{
                var_dump($f);
            }
        }
        if($i->valid()) static::RmvDir($path);
        
    }
    
    public function RmvDir($target)
    {
        if(is_dir($target)){
            $files = glob( $target . '*', GLOB_MARK ); 

            foreach( $files as $file ) {
                static::RmvDir( $file );      
            }
            rmdir( $target );
        } 
        elseif(is_file($target)) {
            unlink( $target );  
        }
    }
}
