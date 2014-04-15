<?php
/**
 * This is a test file
 *
 * File ex2_test.php
 *
 * @author david sole
 */
include_once ( 'basketpc2.php' );



$connection = new BasketPc();
//$connection->dropYPlayers();
$connection->dropPlayers();
$connection->Bkpcplayers();
//$connection->newyplayers();
$auth = $connection->getAuthentication("******","*******");
$connection->getActualWeek( $auth );
$connection->newPlayers( $auth );
$connection->parseyoung($auth);


?>