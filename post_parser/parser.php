<?php
/*
$opciones = array(
    'http'=>array(
        'method'=>"POST",
        'header'=>"Accept-language: en\r\n" .
        "Cookie: foo=bar\r\n"
    )
);

$contexto = stream_context_create($opciones);


$fp = fopen('http://www.basketpc.com', 'r', false, $contexto);
fpassthru($fp);
fclose($fp);
?>
*/
$url = 'http://www.basketpc.com/index.php?mod=plantilla';
/*
$data = array('campo_login' => 'matraca', 'campo_password' => '1234567');

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
*/
$result = file_get_contents($url);
var_dump($result);
/*
use HttpRequest;

$r = new HttpRequest('http://www.basketpc.com/index.php?mod=ver_buzon', HttpRequest::METH_POST);
$r->setOptions(array('cookies' => array('lang' => 'de')));
$r->addPostFields(array('campo_login' => 'matraca', 'campo_password' => '1234567'));
//$r->addPostFile('image', 'profile.jpg', 'image/jpeg');
try {
    echo $r->send()->getBody();
} catch (HttpException $ex) {
    echo $ex;
}
?>
*/
$postdata = http_build_query(
    array(
        'campo_login' => 'matraca',
        'campo_password' => '1234567'
    )
);

$opts = array('http' =>
array(
    'method'  => 'POST',
    'header'  => 'Content-type: application/x-www-form-urlencoded',
    'content' => $postdata
)
);

$context  = stream_context_create($opts);

$result = file_get_contents('http://www.basketpc.com/index.php?mod=autentificacion', false, $context);

var_dump( $result );