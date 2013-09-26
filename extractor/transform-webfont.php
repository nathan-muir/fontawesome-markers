#!/usr/bin/php-cli
<?php

define("BASE_SIZE_PX", 64);
/**
 *
 *
 * Converts the fontawesome webfont svg from glyphs with unicode values to paths with ID's.
 *
 * Applies transforms to the glyphs necessary to recover the icons
 *
 * NOTE: Must manually apply the transform operations to the svg file created from this output
 *
 * using github.com/nathan-muir/svgo:nathan-svgo
 * svgo webfont.paths.svg webfont.paths.min.svg --pretty --disable=mergePaths --disable=cleanupIDs
 * creates the necessary output
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
$font = $xp->query('/s:svg/s:defs/s:font[@id]')->item(0);
$fontFace = $xp->query('s:font-face[@units-per-em][@descent]', $font)->item(0);
$unitsPerEm = intval($fontFace->attributes->getNamedItem("units-per-em")->nodeValue);
$descent = intval($fontFace->attributes->getNamedItem("descent")->nodeValue);
$glyphs = $xp->query('s:glyph[@unicode][@d]', $font);
//echo $glyphs->length, PHP_EOL;
foreach($glyphs as $glyph){
  $char = $glyph->attributes->getNamedItem('unicode')->nodeValue;
  $path = $glyph->attributes->getNamedItem('d')->nodeValue;
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
$output ="";
foreach ($paths as $hex=>$path){
  if (!isset($names[$hex])) continue;
    $output .= <<<SVG

        <path id="{$names[$hex]}" transform="translate(0, {$descent}) rotate(180) scale(-1, 1)" d="{$path}" />
SVG;
}
$scaleFactor = round(BASE_SIZE_PX / $unitsPerEm, 12); // 1em = 16px - so lets convert them to 16px tall!

$svg = <<<SVG
<?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd" >
<svg xmlns="http://www.w3.org/2000/svg">
    <g transform="scale({$scaleFactor})">{$output}
    </g>
</svg>
SVG;

file_put_contents($argv[3], $svg);

