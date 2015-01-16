<?php

$__version__    = '0.0.1';
$__pk__         = '1234567890123456';
$__iv__         = 'hehehahapapacaca';
$__key__        = 'k';
$__content_type__ = 'image/gif';
$__timeout__ = 20;
$__content__ = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    post();
} else {
    get();
}

function get() {
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
    $domain = preg_replace('/.*\\.(.+\\..+)$/', '$1', $host);
    if ($host && $host != $domain && $host != 'www'.$domain) {
        header('Location: http://www.' . $domain);
    } else {
        header('Location: https://www.google.com');
    }
}

function post() {
    //$s1 = urldecode(@file_get_contents('php://input'));
    $key = $GLOBALS['__key__'];
    $value = $_POST[$key];
    $decryptData = data_decrypt($value);
    $json = json_decode(trim($decryptData));
    var_dump($json);
}

function data_encrypt($plainText){
    $pk = $GLOBALS['__pk__'];
    $iv = $GLOBALS['__iv__'];
    $encryptedData = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $pk, $plainText, MCRYPT_MODE_CBC, $iv);
    //echo($encryptedData);
    $base64Encode = base64_encode($encryptedData);
    //echo($base64Encode);
    return $base64Encode;
}

function data_decrypt($cliperText){
    $pk = $GLOBALS['__pk__'];
    $iv = $GLOBALS['__iv__'];
    $encryptedData = base64_decode($cliperText);
    $decryptedData = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $pk, $encryptedData, MCRYPT_MODE_CBC, $iv);
    //echo $decryptedData;
    return mb_convert_encoding($decryptedData, "UTF-8");
}

function curl_get($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    if ($data){
        return $data;
    } else {
        return false;
    }
}

function curl_post($url, $vars) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1 );
    curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
    //disable https check
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
          //disable https check
    $data = curl_exec($ch);
    if ($data) {
         curl_close($ch);
        return $data;
    }  else {
         curl_close($ch);
        return false;
    }
}

function message_html($title, $banner, $detail) {
    $error = <<<MESSAGE_STRING
<html><head>
<meta http-equiv="content-type" content="text/html;charset=utf-8">
<title>${title}</title>
<style><!--
body {font-family: arial,sans-serif}
div.nav {margin-top: 1ex}
div.nav A {font-size: 10pt; font-family: arial,sans-serif}
span.nav {font-size: 10pt; font-family: arial,sans-serif; font-weight: bold}
div.nav A,span.big {font-size: 12pt; color: #0000cc}
div.nav A {font-size: 10pt; color: black}
A.l:link {color: #6f6f6f}
A.u:link {color: green}
//--></style>

</head>
<body text=#000000 bgcolor=#ffffff>
<table border=0 cellpadding=2 cellspacing=0 width=100%>
<tr><td bgcolor=#3366cc><font face=arial,sans-serif color=#ffffff><b>Error</b></td></tr>
<tr><td>&nbsp;</td></tr></table>
<blockquote>
<H1>${banner}</H1>
${detail}
</blockquote>
<table width=100% cellpadding=0 cellspacing=0><tr><td bgcolor=#3366cc><img alt="" width=1 height=4></td></tr></table>
</body></html>
MESSAGE_STRING;
    return $error;
}


?>
