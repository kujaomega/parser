<?php
/**
 * This is a test file
 *
 * File ex2_test.php
 *
 * @author david sole
 */
include_once ( 'basketpc2.php' );


$parseyoung='N';
$parseall='Y';
$connection = new BasketPc();
$auth = $connection->getAuthentication("****","*******");
$connection->getActualWeek( $auth );
if( $parseyoung == 'Y' )
{
    $connection->dropYPlayers();
    $connection->newyplayers();
    $connection->parseyoung($auth);
}

if( $parseall == 'Y' )
{
    $connection->dropPlayers();
    $connection->Bkpcplayers();
    $connection->newPlayers( $auth );
}




?>