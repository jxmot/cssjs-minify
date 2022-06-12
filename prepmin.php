<?php
/*
    This script will utilize an API to minimize CSS and 
    JS source files.

    The intended use is with "minimize-prep", a PHP utility 
    that reads <link> and <script> tags and collects the 
    resource files it finds. It will create files that are 
    the concatenation of the CSS and JS files.

    Then this script is used for minimizing the files that were 
    created by "minimize-prep".
*/
if(file_exists($minifycfg->minprepcfg) === false) {
    echo "\nERROR {$minifycfg->minprepcfg} does not exist!\n\n";
    die();
}
$minprep = json_decode(file_get_contents($minifycfg->minprepcfg));

// minimize-prep was already used for concatenating the CSS and JS files, 
// use its JSON config file to locate the files to be minimized.
// See: https://github.com/jxmot/minimize-prep for additional details.
$cssin = $minprep->fileroot.$minprep->cssout;
if(file_exists($cssin) === false) {
    echo "\nERROR {$cssin} does not exist!\n\n";
    die();
}

$jsin  = $minprep->fileroot.$minprep->jsout;
if(file_exists($jsin) === false) {
    echo "\nERROR {$jsin} does not exist!\n\n";
    die();
}

// insert ".min" into the output file names
$cssout = insertMin($cssin);
$jsout  = insertMin($jsin);

// CSS minify...
if($_dbgcjm) echo $cssin . " - length = ".filesize($cssin)."\n";
if(minify($cssin, $cssout, $minifycfg->api->cssmin) > 0)
    if($_dbgcjm) echo $cssout . " - length = ".filesize($cssout)."\n";
else echo "\nERROR minifiying {$cssin} - {$http_response_header[0]}\n";

// JS minify...
if($_dbgcjm) echo $jsin . " - length = ".filesize($jsin)."\n";
if(minify($jsin, $jsout, $minifycfg->api->jsmin) > 0)
    if($_dbgcjm) echo $jsout . " - length = ".filesize($jsout)."\n";
else echo "\nERROR minifiying {$jsin} - {$http_response_header[0]}\n";
?>