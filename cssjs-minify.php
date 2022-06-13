<?php
/*
    Minify CSS or JS using an API provided 
    by... (see cssjs-minify.json). 

    This code *should be* usable with any API 
    that can be used for minifying CSS or JS 
    files. And that utilizes a POST to submit 
    content for minimization and gets a response 
    containing the minimized result.

    This code is dependent on the JSON config 
    file associated with minimize-prep. See:

    https://github.com/jxmot/minimize-prep#readme

*/
function usage($scrpt) {
    echo "Usage:\n";
    echo "    php {$scrpt} [default|minprep|fullmin]\n";
    die();
}

if($argc < 2) usage($argv[0]);

$choice = strtolower($argv[1]);

// there are 3 run-time choices:
//      "default" - the input files are specified in the JSON file 
//      "minprep" - "minimize-prep" is near by, and it has been run and 
//                  has concatenated the CSS and JS resources
//      "fullmin" - "minimize-prep" is near by, and this will run it 
//                  before minification starts
if(($choice !== 'default') && ($choice !== 'minprep') && ($choice !== 'fullmin')) {
    echo "\nERROR Invalid argument {$choice}\n\n";
    usage($argv[0]);
}

// minification functions
require_once 'minify.php';

if(file_exists("cssjs-minify-{$choice}.json") === false) {
    echo "\nERROR missing file: cssjs-minify-{$choice}.json\n";
    die;
}
$minifycfg = json_decode(file_get_contents("cssjs-minify-{$choice}.json"));

// when "true" be verbose
$_dbgcjm = $minifycfg->verbose;

if($_dbgcjm) echo "Starting Minimization...\n";
if($minifycfg->minprepcfg !== null) {
    if(!isset($minifycfg->minpreprun)) {
        // minimize files created by "minimize-prep"
        require_once 'prepmin.php';
    } else {
        if(count($minifycfg->minpreprun) === 2) {
            // we're going to run the script from 
            // within the folder where it's contained.
            // this will help insure that the paths 
            // are correct.
            $cwd = getcwd();
            if(is_dir($minifycfg->minpreprun[0])) {
                chdir($minifycfg->minpreprun[0]);
                require_once $minifycfg->minpreprun[1];
                chdir($cwd);
                // minimize files created by "minimize-prep"
                require_once 'prepmin.php';
            } else {
                $tmp = implode('', $minifycfg->minpreprun);
                echo "\nERROR {$tmp} does not exist\n";
                die();
            }
        } else {
            $tmp = implode('', $minifycfg->minpreprun);
            echo "\nERROR invalid minpreprun - {$tmp}\n";
            die();
        }
    }
} else {
    // minimize the files specified in the JSON file
    // CSS...
    if(count($minifycfg->cssin) > 0) {
        foreach($minifycfg->cssin as $cssin) {
            if(file_exists($cssin) === false) {
                echo "ERROR {$cssin} does not exist, skipping.\n";
            } else {
                // insert ".min" into the output file names
                $cssout = insertMin($cssin);
                // CSS minify...
                if($_dbgcjm) echo $cssin . " - length = ".filesize($cssin)."\n";
                if(minify($cssin, $cssout, $minifycfg->api->cssmin) > 0)
                    if($_dbgcjm) echo $cssout . " - length = ".filesize($cssout)."\n";
                else echo "ERROR minifiying {$cssin} - {$http_response_header[0]}\n";
            }
        }
    }
    // JS...
    if(count($minifycfg->jsin) > 0) {
        foreach($minifycfg->jsin as $jsin) {
            if(file_exists($jsin) === false) {
                echo "ERROR {$jsin} does not exist, skipping.\n";
            } else {
                // insert ".min" into the output file names
                $jsout = insertMin($jsin);
                // JS minify...
                if($_dbgcjm) echo $jsin . " - length = ".filesize($jsin)."\n";
                if(minify($jsin, $jsout, $minifycfg->api->jsmin) > 0)
                    if($_dbgcjm) echo $jsout . " - length = ".filesize($jsout)."\n";
                else echo "ERROR minifiying {$jsin} - {$http_response_header[0]}\n";
            }
        }
    }
}
if($_dbgcjm) echo "Minification Complete.\n\n";
?>