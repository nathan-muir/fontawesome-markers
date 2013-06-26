#!/usr/bin/php-cli
<?php

/**
 * Quick a dirty script
 *
 * Reads the fontawesome svg file, and the font-awesome variables file to compile a mapping of
 * unicode-to-name and unicode-to-path
 *
 * it then transforms this into a name-to-path mapping and outputs a javascript file.
 */
if (PHP_SAPI !== 'cli'){
    die("Must be run from cli");
}

if ($argc != 4){
    die("please invoke as " . basename(__FILE__) . " file.svg variables.less output.js"  . PHP_EOL);
}
if (!is_file($argv[1]) || !is_readable($argv[1])){
    die("First file (.svg) wasn't readable" . PHP_EOL);
}

if (!is_file($argv[2]) || !is_readable($argv[2])){
    die("Second file (.less) wasn't readable" . PHP_EOL);
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
$elements = $xp->query('/s:svg/s:defs/s:font/s:glyph[@unicode][@d]');
//echo $elements->length, PHP_EOL;
for($i = 0, $I = $elements->length; $i < $I; $i++){
  $element = $elements->item($i);
  $char = $element->attributes->getNamedItem('unicode')->nodeValue;
  $path = $element->attributes->getNamedItem('d')->nodeValue;
  $charHex = bin2hex(iconv('UTF-8','UCS-2BE',$char));
  $paths[$charHex] = $path;
}


// get the name for each unicode value
$variables = file_get_contents($argv[2]);

if ($variables === false){
  die("Second file (.less) wasn't readable" . PHP_EOL);
}

if (preg_match_all('~@([\w\-]+)\s*:\s*[\'"]\\\\([a-f0-9]{4})[\'"];~', $variables, $m)){
  $names = array_combine($m[2],$m[1]);
}

// combine together! name = path
$output = array();
$failed = array();
foreach ($paths as $hex=>$path){
  if (isset($names[$hex])){
    $name = strtoupper(str_replace('-','_',$names[$hex]));
    $output[$name] = $path;
  } else {
    $failed[] = $hex;
  }
}

//var_export(array_diff(array_keys($names),array_keys($paths)));
//var_export(array_diff(array_keys($paths),array_keys($names)));

$tFailed = count($failed);
//var_export($failed);
$tOutput = count($output);
//var_export($output);
$tTotal = $tFailed + $tOutput;


echo <<<TEXT
Failed:       $tFailed
Successful:   $tOutput
------------------------
Total:        $tTotal


TEXT
;

file_put_contents($argv[3], 'var fontawesome={};fontawesome.markers=' . json_encode($output));
