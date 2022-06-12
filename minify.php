<?php
/*
    Minify a file and return the result after writing 
    it to another file.

    $filein     - handle for input file
    $fileout    - handle for output file
    $url        - URL of API to perform minification

    Returns:
        Length of minified content (>0) - if successful
        0 - not successful
*/
function minify($filein, $fileout, $url) {
    $content = file_get_contents($filein);

    $postdata = ['http' => [
        'ignore_errors' => true,
        'method' => "POST",
        'header' => "Content-type: application/x-www-form-urlencoded\r\n"
        ,'content' => http_build_query(['input' => $content]), ]];

    $minstuff = file_get_contents($url, false, stream_context_create($postdata));
    preg_match('{HTTP\/\S*\s(\d{3})}', $http_response_header[0], $match);
    if($match[1] !== '200') return 0;
    else {
        $fileid = fopen($fileout, 'w');
        fwrite($fileid, $minstuff);
        fflush($fileid);
        fclose($fileid);
        return strlen($minstuff);
    }
}

/*
    Insert ".min" into the output file names, this function 
    expects "name.extension" to be passed in and it will 
    return "name.min.extension".

    $namein     - file name, a path can be included

    Returns:
        "name.min.extension"
*/
function insertMin($namein) {
    $pos = strrpos($namein, '.');
    return substr($namein, 0, $pos) . '.min' . substr($namein, $pos);;
}
?>