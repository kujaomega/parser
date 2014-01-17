<?php
include( 'pegCurlRequest.php');
include( 'basketpc.php');
$username = 'matraca';
$password = '1234567';
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

//echo '<table>';
//set the URL to the protected file
//5649

//intancia basketpc
//1-1000
$connection = new BasketPc();
$connection->Bkpcplayers();
for( $i =1; $i <=1000; ++$i)
{
    //echo'<h1> '.$i.'</h1>';

    curl_setopt($ch, CURLOPT_URL, 'http://www.basketpc.com/index.php?mod=busqueda_jugador&pagina='.$i.'&orden=ca&sentido=desc');

//execute the request
    $content = curl_exec($ch);

    $result = explode ('<tbody>', $content );

    //var_dump($result);
    $result2 = explode ('</tbody>', $result[1]);
    $array_jugadores = strip_tags( $result2[0], '<tr></tr><td></td><a></a>');
    //var_dump($array_jugadores);
    //echo '<br>';
    $array_jugadores = htmlspecialchars($array_jugadores, ENT_COMPAT,'ISO-8859-1', true);
    //var_dump($array_jugadores);
    $array_jugadores =explode('&lt;tr &gt;',$array_jugadores);
    //echo html_entity_decode($array_jugadores[2]);
    $total = count($array_jugadores)-1;
    //for($a)
    //echo $total."<br>";
    for($j = 1; $j<= $total; ++$j)
    {
        $jugador = $array_jugadores[$j] ;
        $limitador = '&lt;td&gt;';
        //echo $limitador.'<br>';
        $array_datos_jugador = explode($limitador, $jugador);
        //var_dump( $array_datos_jugador);
        //$array_datos_jugador=explode('&lt;td&gt;',$array_jugadores[$i]);

        //$array_datos_jugador[$j] = html_entity_decode($array_datos_jugador[$j]);
        $posicion = strip_tags(html_entity_decode($array_datos_jugador[0]));
        $prealtura = explode('&lt;/td&gt;', $array_datos_jugador[1]);
        $altura = $prealtura[0];
        $pre_nombre_id = explode('&quot;', $array_datos_jugador[1]);
        $nombre = $pre_nombre_id[5];
        $id = $pre_nombre_id[7];
        $edad = trim(strip_tags(html_entity_decode($array_datos_jugador[2])));
        $calidad = trim(strip_tags(html_entity_decode($array_datos_jugador[3])));
        $defensa = trim(strip_tags(html_entity_decode($array_datos_jugador[4])));
        $tiro3 = trim(strip_tags(html_entity_decode($array_datos_jugador[5])));
        $tiro2 = trim(strip_tags(html_entity_decode($array_datos_jugador[6])));
        $tiro1 = trim(strip_tags(html_entity_decode($array_datos_jugador[7])));
        $velocidad = trim(strip_tags(html_entity_decode($array_datos_jugador[8])));
        $pase = trim(strip_tags(html_entity_decode($array_datos_jugador[9])));
        $dribling = trim(strip_tags(html_entity_decode($array_datos_jugador[10])));
        $rebote = trim(strip_tags(html_entity_decode($array_datos_jugador[11])));
        $media = trim(strip_tags(html_entity_decode($array_datos_jugador[12])));
        $preficha = trim(strip_tags(html_entity_decode($array_datos_jugador[13])));
        $preficha = explode('K', $preficha);
        $ficha = $preficha[0];
        //$clausula = trim(strip_tags(html_entity_decode($array_datos_jugador[13])));
        $pre_clausula_tiempo_contrato = trim(strip_tags(html_entity_decode($array_datos_jugador[14])));
        $pre_clausula_tiempo_contrato = explode('M', $pre_clausula_tiempo_contrato);
        $clausula = htmlspecialchars($pre_clausula_tiempo_contrato[0]);
        $tiempo_contrato = trim($pre_clausula_tiempo_contrato[1]);

        /*
        var_dump( $posicion);
        echo '<br>';
        var_dump( $altura);
        echo '<br>';
        var_dump( $nombre);
        echo '<br>';
        var_dump( $id);
        echo '<br>';
        var_dump( $edad);
        echo '<br>';
        var_dump( $calidad);
        echo '<br>';
        var_dump( $defensa);
        echo '<br>';
        var_dump( $tiro3);
        echo '<br>';
        var_dump( $tiro2);
        echo '<br>';
        var_dump( $tiro1);
        echo '<br>';
        var_dump( $velocidad);
        echo '<br>';
        var_dump( $pase);
        echo '<br>';
        var_dump($dribling);
        echo '<br>';
        var_dump($rebote);
        echo '<br>';
        var_dump($media);
        echo '<br>';
        var_dump($ficha);
        echo '<br>';
        var_dump($clausula);
        echo '<br>';
        var_dump($tiempo_contrato);
        echo '<br>';
        */
        //var_dump($array_datos_jugador);

       $connection->addPlayers( $posicion, $altura, $nombre, $id, $edad, $calidad, $defensa, $tiro3, $tiro2, $tiro1, $velocidad, $pase, $dribling, $rebote, $media, $ficha, $clausula, $tiempo_contrato );
    }
    //var_dump($array_jugadores);
    //usleep(500000);
    echo 'Iteracio:'.$i.', '.$total.'<br>';
}
//echo '</table>';

/*
curl_setopt($ch, CURLOPT_URL, 'http://www.basketpc.com/index.php?mod=busqueda_jugador&pagina=2&orden=&sentido=asc');

//execute the request
$content = curl_exec($ch);
var_dump( $content );
*/
