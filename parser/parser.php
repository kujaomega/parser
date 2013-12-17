<?php
include( 'pegCurlRequest.php');

$username = 'matraca';
$password = '****';
$loginUrl = 'http://www.basketpc.com/index.php?mod=autentificacion';

//init curl
$ch = curl_init();

//Set the URL to work with
curl_setopt($ch, CURLOPT_URL, $loginUrl);

// ENABLE HTTP POST
curl_setopt($ch, CURLOPT_POST, 1);

//Set the post parameters
curl_setopt($ch, CURLOPT_POSTFIELDS, 'campo_login='.$username.'&campo_password='.$password);

//Handle cookies for the login
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');

//Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
//not to print out the results of its query.
//Instead, it will return the results as a string return value
//from curl_exec() instead of the usual true/false.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//execute the request (the login)
$store = curl_exec($ch);

//the login is now done and you can continue to get the
//protected content.

echo '<table>';
//set the URL to the protected file
//5649
for( $i =0; $i <2; ++$i)
{
    //echo'<h1> '.$i.'</h1>';

    curl_setopt($ch, CURLOPT_URL, 'http://www.basketpc.com/index.php?mod=busqueda_jugador&pagina='.$i.'&orden=&sentido=asc');

//execute the request
    $content = curl_exec($ch);
    $result = explode ('<tbody>', $content );

    //var_dump($result);
    $result2 = explode ('</tbody>', $result[1]);
    var_dump($result2[0]);
    //explode$result2[0];

    //usleep(50000);
}
echo '</table>';

/*
curl_setopt($ch, CURLOPT_URL, 'http://www.basketpc.com/index.php?mod=busqueda_jugador&pagina=2&orden=&sentido=asc');

//execute the request
$content = curl_exec($ch);
var_dump( $content );
*/
