#!/usr/bin/php-cli
<?php

/**
 * Quick and dirty script
 *
 * Reads the fontawesome svg file, and the font-awesome variables file to compile a mapping of
 * unicode-to-name and unicode-to-path
 *
 * it then transforms this into a name-to-path mapping and outputs a javascript file.
 */
if (PHP_SAPI !== 'cli'){
    die("Must be run from cli");
}

if ($argc != 3){
    die("please invoke as " . basename(__FILE__) . " webfont.paths.min.svg output.js"  . PHP_EOL);
}
if (!is_file($argv[1]) || !is_readable($argv[1])){
    die("First file (.svg) wasn't readable" . PHP_EOL);
}

// get the path for each unicode value
$doc = new DomDocument();
$loaded = $doc->load($argv[1]);

if ($loaded === false){
    die("First file (.svg) wasn't parsed as xml" . PHP_EOL);
}
$xp = new DOMXPath($doc);
$xp->registerNamespace('s','http://www.w3.org/2000/svg');
$paths = array();
$paths = $xp->query('/s:svg/s:path[@id][@d]');
$output = array();
foreach($paths as $path){
  $id = $path->attributes->getNamedItem('id')->nodeValue;
  $d = $path->attributes->getNamedItem('d')->nodeValue;
  $name = strtoupper(str_replace('-','_',$id));
  $output[$name] = $d;
}

file_put_contents($argv[2], 'var fontawesome={};fontawesome.markers=' . json_encode($output));
