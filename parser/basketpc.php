<?php
/**
 * file basketpc.php
 *
 * @author kujaomega
 */
include ('db.php');

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
  pase INT,
  dribling INT,
  rebote INT,
  ficha INT,
  clausula INT,
  contrato INT

)

SQL;

        $this->db->prepare( $query );
         $this->db->execute();
        var_dump( $this->db);
        echo 'fet';


    }

    public function addPlayers()
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
  pase,
  dribling,
  rebote,
  ficha,
  clausula,
  contrato
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
  :pase,
  :dribling,
  :rebote,
  :ficha,
  :clausula,
  :contrato
   )
SQL;



    }
}
