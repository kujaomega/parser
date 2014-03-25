<?php
/**
 * file basketpc.php
 *
 * @author kujaomega
 */
include ('db.php');
include( 'pegCurlRequest.php');

/**
 * This is the class of basketpc, where we will make all the required needs for basketpc.
 *
 * class Basketpc
 */
class BasketPc
{
    public $db;
    /**
     * This is the constructor of basketpc.
     */
    public function __construct()
    {
        $host = 'localhost';
        $pass = 'P@@ssw0rd';
        $db = 'basketpc';
        $user = 'root';
        $this->db = new Db( $host, $db, $user, $pass );

    }


    /**
     * This si the method to create the players basket pc table.
     *
     */
    public function Bkpcplayers()
    {
        $query = <<<'SQL'
CREATE TABLE IF NOT EXISTS jugadores
(
  posicion CHAR(5),
  altura INT,
  nombre CHAR(50),
  id INT,
  edad INT,
  calidad INT,
  defensa INT,
  tiro3 INT,
  tiro2 INT,
  tiro1 INT,
  velocidad INT,
  resistencia INT,
  pase INT,
  dribling INT,
  rebote INT,
  media INT,
  ficha INT,
  clausula INT,
  tiempo_contrato INT

)

SQL;

        $this->db->prepare( $query );
         $this->db->execute();
        //var_dump( $this->db);
        //echo 'fet';
    }


    public function addPlayers( $posicion, $altura, $nombre, $id, $edad, $calidad, $defensa, $tiro3, $tiro2, $tiro1, $velocidad, $resistencia, $pase, $dribling, $rebote, $media, $ficha, $clausula, $tiempo_contrato)
    {
        $query =<<<'SQL'
INSERT INTO
  jugadores
  ( posicion,
  altura,
  nombre,
  id,
  edad,
  calidad,
  defensa,
  tiro3,
  tiro2,
  tiro1,
  velocidad,
  resistencia,
  pase,
  dribling,
  rebote,
  media,
  ficha,
  clausula,
  tiempo_contrato
   )
VALUES
  ( :posicion,
  :altura,
  :nombre,
  :id,
  :edad,
  :calidad,
  :defensa,
  :tiro3,
  :tiro2,
  :tiro1,
  :velocidad,
  :resistencia,
  :pase,
  :dribling,
  :rebote,
  :media,
  :ficha,
  :clausula,
  :tiempo_contrato
   )
SQL;
        $this->db->prepare( $query );
        $this->db->bindParam( ':posicion', $posicion, PDO::PARAM_STR );
        $this->db->bindParam( ':altura', $altura, PDO::PARAM_INT );
        $this->db->bindParam( ':nombre', $nombre, PDO::PARAM_STR );
        $this->db->bindParam( ':id', $id, PDO::PARAM_INT );
        $this->db->bindParam( ':edad', $edad, PDO::PARAM_INT );
        $this->db->bindParam( ':calidad', $calidad, PDO::PARAM_INT );
        $this->db->bindParam( ':defensa', $defensa, PDO::PARAM_INT );
        $this->db->bindParam( ':tiro3', $tiro3, PDO::PARAM_INT );
        $this->db->bindParam( ':tiro2', $tiro2, PDO::PARAM_INT );
        $this->db->bindParam( ':tiro1', $tiro1, PDO::PARAM_INT );
        $this->db->bindParam( ':velocidad', $velocidad, PDO::PARAM_INT );
        $this->db->bindParam( ':resistencia', $resistencia, PDO::PARAM_INT );
        $this->db->bindParam( ':pase', $pase, PDO::PARAM_INT );
        $this->db->bindParam( ':dribling', $dribling, PDO::PARAM_INT );
        $this->db->bindParam( ':rebote', $rebote, PDO::PARAM_INT );
        $this->db->bindParam( ':media', $media, PDO::PARAM_INT );
        $this->db->bindParam( ':ficha', $ficha, PDO::PARAM_INT );
        $this->db->bindParam( ':clausula', $clausula, PDO::PARAM_INT );
        $this->db->bindParam( ':tiempo_contrato', $tiempo_contrato, PDO::PARAM_INT );
        $this->db->execute();
    }

    public function getPlayers()
    {
        $query =<<<'SQL'
SELECT
  posicion,
  altura,
  nombre,
  id,
  edad,
  calidad,
  defensa,
  tiro3,
  tiro2,
  tiro1,
  velocidad,
  resistencia,
  pase,
  dribling,
  rebote,
  media,
  ficha,
  clausula,
  tiempo_contrato
VALUES
  ( :posicion,
  :altura,
  :nombre,
  :id,
  :edad,
  :calidad,
  :defensa,
  :tiro3,
  :tiro2,
  :tiro1,
  :velocidad,
  :resistencia,
  :pase,
  :dribling,
  :rebote,
  :media,
  :ficha,
  :clausula,
  :tiempo_contrato
   )
SQL;
    }

    public function newPlayers( $user, $pass)
    {
        $username = $user;
        $password = $pass;
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
//4547

//intancia basketpc
//1-1000


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
                $posicion = trim(strip_tags(html_entity_decode($array_datos_jugador[0])));
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
                $resistencia = trim(strip_tags(html_entity_decode($array_datos_jugador[9])));

                $pase = trim(strip_tags(html_entity_decode($array_datos_jugador[10])));
                $dribling = trim(strip_tags(html_entity_decode($array_datos_jugador[11])));
                $rebote = trim(strip_tags(html_entity_decode($array_datos_jugador[12])));
                $media = trim(strip_tags(html_entity_decode($array_datos_jugador[13])));

                $preficha = trim(strip_tags(html_entity_decode($array_datos_jugador[14])));
                $preficha = explode('K', $preficha);
                $ficha = $preficha[0];
                //$clausula = trim(strip_tags(html_entity_decode($array_datos_jugador[13])));
                $pre_clausula_tiempo_contrato = trim(strip_tags(html_entity_decode($array_datos_jugador[15])));
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
                var_dump( $resistencia);
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

                $this->addPlayers( $posicion, $altura, $nombre, $id, $edad, $calidad, $defensa, $tiro3, $tiro2, $tiro1, $velocidad, $resistencia, $pase, $dribling, $rebote, $media, $ficha, $clausula, $tiempo_contrato );
            }
            //var_dump($array_jugadores);
            //usleep(500000);
            echo 'Iteracio:'.$i.', '.$total.'<br>';
        }
        /*
        curl_setopt($ch, CURLOPT_URL, 'http://www.basketpc.com/index.php?mod=busqueda_jugador&pagina=2&orden=&sentido=asc');

        //execute the request
        $content = curl_exec($ch);
        var_dump( $content );
        */
    }

    public function getcracks()
    {
        $query =<<<'SQL'
SELECT
  posicion,
  nombre,
  edad,
  media,
  resistencia,
  ((calidad+defensa+velocidad)*0.5/3+(dribling+pase)*0.3/2+(tiro1+tiro2+tiro3+resistencia+rebote)*0.2/5)*10-(16.5*(edad-18)-1.56*((edad-19)*(edad+1-19)/2))
   as formula,
  clausula/(37-edad)
FROM
  jugadores
WHERE
  media-edad>51
AND
  clausula/(37-edad)<1
AND
  edad>=25
ORDER BY
  ((calidad+defensa+velocidad)*0.5/3+(dribling+pase)*0.3/2+(tiro1+tiro2+tiro3+resistencia+rebote)*0.2/5)*10-(16.5*(edad-18)-1.56*((edad-19)*(edad+1-19)/2))
DESC
LIMIT
  0,50;
SQL;
    }


    public function newyplayers()
    {
        $query = <<<'SQL'
CREATE TABLE IF NOT EXISTS jovenes
(
  posicion CHAR(5),
  altura INT,
  nombre CHAR(50),
  id INT,
  edad INT,
  semanas INT,
  calidad INT,
  defensa INT,
  tiro3 INT,
  tiro2 INT,
  tiro1 INT,
  velocidad INT,
  resistencia INT,
  pase INT,
  dribling INT,
  rebote INT,
  media INT,
  ficha INT,
  clausula INT,
  tiempo_contrato INT

)

SQL;

        $this->db->prepare( $query );
        $this->db->execute();
        //var_dump( $this->db);
        //echo 'fet';
    }

    public function addyPlayers( $posicion, $altura, $nombre, $id, $edad, $semanas, $calidad, $defensa, $tiro3, $tiro2, $tiro1, $velocidad, $resistencia, $pase, $dribling, $rebote, $media, $ficha, $clausula, $tiempo_contrato)
    {
        $query =<<<'SQL'
INSERT INTO
  jovenes
  ( posicion,
  altura,
  nombre,
  id,
  edad,
  semanas,
  calidad,
  defensa,
  tiro3,
  tiro2,
  tiro1,
  velocidad,
  resistencia,
  pase,
  dribling,
  rebote,
  media,
  ficha,
  clausula,
  tiempo_contrato
   )
VALUES
  ( :posicion,
  :altura,
  :nombre,
  :id,
  :edad,
  :semanas,
  :calidad,
  :defensa,
  :tiro3,
  :tiro2,
  :tiro1,
  :velocidad,
  :resistencia,
  :pase,
  :dribling,
  :rebote,
  :media,
  :ficha,
  :clausula,
  :tiempo_contrato
   )
SQL;
        $this->db->prepare( $query );
        $this->db->bindParam( ':posicion', $posicion, PDO::PARAM_STR );
        $this->db->bindParam( ':altura', $altura, PDO::PARAM_INT );
        $this->db->bindParam( ':nombre', $nombre, PDO::PARAM_STR );
        $this->db->bindParam( ':id', $id, PDO::PARAM_INT );
        $this->db->bindParam( ':edad', $edad, PDO::PARAM_INT );
        $this->db->bindParam( ':semanas', $semanas, PDO::PARAM_INT );
        $this->db->bindParam( ':calidad', $calidad, PDO::PARAM_INT );
        $this->db->bindParam( ':defensa', $defensa, PDO::PARAM_INT );
        $this->db->bindParam( ':tiro3', $tiro3, PDO::PARAM_INT );
        $this->db->bindParam( ':tiro2', $tiro2, PDO::PARAM_INT );
        $this->db->bindParam( ':tiro1', $tiro1, PDO::PARAM_INT );
        $this->db->bindParam( ':velocidad', $velocidad, PDO::PARAM_INT );
        $this->db->bindParam( ':resistencia', $resistencia, PDO::PARAM_INT );
        $this->db->bindParam( ':pase', $pase, PDO::PARAM_INT );
        $this->db->bindParam( ':dribling', $dribling, PDO::PARAM_INT );
        $this->db->bindParam( ':rebote', $rebote, PDO::PARAM_INT );
        $this->db->bindParam( ':media', $media, PDO::PARAM_INT );
        $this->db->bindParam( ':ficha', $ficha, PDO::PARAM_INT );
        $this->db->bindParam( ':clausula', $clausula, PDO::PARAM_INT );
        $this->db->bindParam( ':tiempo_contrato', $tiempo_contrato, PDO::PARAM_INT );
        $this->db->execute();
    }

    public function parseyoung($user, $pass)
    {
        $username = $user;
        $password = $pass;
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
//4547

//intancia basketpc
//1-1000

        for( $i =1; $i <=75; ++$i)
        {
            //echo'<h1> '.$i.'</h1>';
            $busqUrl='http://www.basketpc.com/index.php?mod=busqueda_jugador&pagina='.$i;
            curl_setopt($ch, CURLOPT_URL, $busqUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'max_edad=18');
            curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie2.txt');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

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
                /*
                $posicion = trim(strip_tags(html_entity_decode($array_datos_jugador[0])));
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
                $resistencia = trim(strip_tags(html_entity_decode($array_datos_jugador[9])));

                $pase = trim(strip_tags(html_entity_decode($array_datos_jugador[10])));
                $dribling = trim(strip_tags(html_entity_decode($array_datos_jugador[11])));
                $rebote = trim(strip_tags(html_entity_decode($array_datos_jugador[12])));
                $media = trim(strip_tags(html_entity_decode($array_datos_jugador[13])));
                $preficha = trim(strip_tags(html_entity_decode($array_datos_jugador[14])));
                $preficha = explode('K', $preficha);
                $ficha = $preficha[0];
                //$clausula = trim(strip_tags(html_entity_decode($array_datos_jugador[13])));
                $pre_clausula_tiempo_contrato = trim(strip_tags(html_entity_decode($array_datos_jugador[15])));
                $pre_clausula_tiempo_contrato = explode('M', $pre_clausula_tiempo_contrato);
                $clausula = htmlspecialchars($pre_clausula_tiempo_contrato[0]);
                $tiempo_contrato = trim($pre_clausula_tiempo_contrato[1]);
                */
                $posicion = trim(strip_tags(html_entity_decode($array_datos_jugador[0])));
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
                $resistencia = trim(strip_tags(html_entity_decode($array_datos_jugador[9])));

                $pase = trim(strip_tags(html_entity_decode($array_datos_jugador[10])));
                $dribling = trim(strip_tags(html_entity_decode($array_datos_jugador[11])));
                $rebote = trim(strip_tags(html_entity_decode($array_datos_jugador[12])));
                $media = trim(strip_tags(html_entity_decode($array_datos_jugador[13])));

                $preficha = trim(strip_tags(html_entity_decode($array_datos_jugador[14])));
                $preficha = explode('K', $preficha);
                $ficha = $preficha[0];
                //$clausula = trim(strip_tags(html_entity_decode($array_datos_jugador[13])));
                $pre_clausula_tiempo_contrato = trim(strip_tags(html_entity_decode($array_datos_jugador[15])));
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
                var_dump( $resistencia);
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
                $semanas = $this->getSemanas( $id , $ch);

                $this->addyPlayers( $posicion, $altura, $nombre, $id, $edad, $semanas, $calidad, $defensa, $tiro3, $tiro2, $tiro1, $velocidad, $resistencia, $pase, $dribling, $rebote, $media, $ficha, $clausula, $tiempo_contrato );
            }
            //var_dump($array_jugadores);
            //usleep(500000);
            echo 'Iteracio:'.$i.', '.$total.'<br>';
        }
        /*
        curl_setopt($ch, CURLOPT_URL, 'http://www.basketpc.com/index.php?mod=busqueda_jugador&pagina=2&orden=&sentido=asc');

        //execute the request
        $content = curl_exec($ch);
        var_dump( $content );
        */
        //Set the URL to work with

    }

    public function getSemanas( $id, $ch)
    {
        $semanasUrl='http://www.basketpc.com/lib/jugador/ficha_jugador/ver_ficha_jugador.php?id='.$id;
        curl_setopt($ch, CURLOPT_URL, $semanasUrl);
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie3.txt');


//execute the request
        $content = curl_exec($ch);

        $result = explode ('Semana:', $content );

        //var_dump($result);
        $result2 = explode ('</li>', $result[1]);
        $result2=strip_tags($result2[0]);
        return $result2;


        //$array_jugadores = strip_tags( $result2[0], '<tr></tr><td></td><a></a>');
        //var_dump($array_jugadores);
        //echo '<br>';
        //$array_jugadores = htmlspecialchars($array_jugadores, ENT_COMPAT,'ISO-8859-1', true);

    }

    public function dropYPlayers()
    {
        $query =<<<'SQL'
DROP TABLE IF EXISTS
  jovenes
SQL;
        $this->db->prepare( $query );
        $this->db->execute();
    }

    public function dropPlayers()
    {
        $query =<<<'SQL'
DROP TABLE IF EXISTS
  jugadores
SQL;
        $this->db->prepare( $query );
        $this->db->execute();
    }

}
