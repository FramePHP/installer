<?php

namespace FramePHP\Cmd;

$global_vendors = realpath(__DIR__.'/../../../');
$module_vendors = realpath(__DIR__.'/../');

$AutoLoaders = array_filter([
  realpath("$global_vendors/autoload.php"),
  realpath("$module_vendors/autoload.php")
]);


if (!class_exists(Command::class)) {

  foreach ($AutoLoaders as $autoloader) {

    if(file_exists($autoloader)){
      require_once $autoloader;
      continue;
    }
  }
}

$argv = $argv ?: $GLOBALS['argv'] ?: $_SERVER['argv'];

$Commander = new Command($argv);
//
// use PhpParser\NodeTraverser;
// use PhpParser\ParserFactory;
// use PhpParser\PrettyPrinter;
//
// $parser        = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
// $traverser     = new NodeTraverser;
// $prettyPrinter = new PrettyPrinter\Standard;
//
// // add your visitor
// $traverser->addVisitor(new MyNodeVisitor);
//
// try {
//     $code = file_get_contents($fileName);
//
//     // parse
//     $stmts = $parser->parse($code);
//
//     // traverse
//     $stmts = $traverser->traverse($stmts);
//
//     // pretty print
//     $code = $prettyPrinter->prettyPrintFile($stmts);
//
//     echo $code;
// } catch (PhpParser\Error $e) {
//     echo 'Parse Error: ', $e->getMessage();
// }

return $Commander->run();
