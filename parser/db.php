<?php
/**
 * File ex1.php
 * @author david.sole
 */
//namespace applications\db;


/**
 * Class for Db
 */
class Db
{


    /**
    * This variable contains the history of the querys and the time.
    *
    * @var string $data_base.
    */
    protected $history = array();

    /**
     * This variable contains the pdo statement from prepare.
     *
     * @var PDOStatement|bool $prepare.
     */
    protected $pdo_statement;

    /**
     * This variable contains the pdo Object of the connection.
     *
     * @var PDO $pdo.
     */
    protected $pdo;

    /**
     * This variable contains the profiler state.
     *
     * @var Bool $profiler_state.
     */
    protected $profiler_state;

    /**
    * The constructor from db and creates de PDO object, sets the profiler_state to true.
    *
    * @param string|int $host Is the host we will connect.
    * @param string $data_base Is the name of the database.
    * @param string $user Is the name of the user.
    * @param string $password Is the password of the user.
    */
    public function __construct( $host, $data_base, $user, $password )
    {
        $this->CheckHost( $host );
        $dsn = 'mysql:dbname='.$data_base.';host='.$host;
        $this->profiler_state = true;
        try
        {
            $this->pdo = new PDO( $dsn, $user, $password );
            var_dump( $this->pdo );
        }
        catch ( PDOException $e )
        {
            return $e->getMessage();
        }
    }

    /**
     * It will prepare a sentence to be executed.
     *
     * @param string $statement the query we will set.
     * @throws \PDOException
     */
    public function prepare( $statement )
    {
        try
        {
            $this->pdo_statement = $this->pdo->prepare( $statement );
        }
        catch ( PDOException $e )
        {
            throw new PDOException ( 'Take care the error: '.$e->getMessage()."<br />\r\n" );
        }
    }

    /**
     * It will bind the parameters in a query.
     *
     * @param $limit
     * @param int|string $value Is the value we will set.
     * @param in|string $data_type Specifies the data type.
     * @throws \PDOException
     * @throws \InvalidArgumentException .
     */
    public function bindParam( $limit, $value, $data_type )
    {
        if ( get_class( $this->pdo_statement ) == 'PDOStatement' )
        {
            try
            {
                $this->pdo_statement->bindParam( $limit, $value, $data_type );
            }
            catch ( PDOException $e )
            {
                throw new PDOException ('Take care the error: '.$e->getMessage()."<br />\r\n" );
            }
        }
        else
        {
            throw new InvalidArgumentException('First prepare the statement ');
        }
    }

    /**
     * It will execute a sentence and take que historial from the querys.
     *
     * @throws \InvalidArgumentException
     * @return bool|PDOException|InvalidArgumentException
     */
    public function execute()
    {
        if ( get_class( $this->pdo_statement )== 'PDOStatement' )
        {
            try
            {
                if($this->profiler_state)
                {
                    $micro_time = microtime( true );
                    $this->pdo_statement->execute();
                    $micro_time = microtime( true ) - $micro_time;
                    var_dump( $this->pdo_statement );
                    $query = ob_get_clean();
                    $this->history = $query.' Takes :'.$micro_time.' ms';
                }
                else
                {
                    $this->pdo_statement->execute();
                }
            }
            catch ( PDOException $e )
            {
                print( "Caught the error: ".$e->getMessage()."<br />\r\n" );
            }

        }
        else
        {
            throw new InvalidArgumentException( 'First prepare the statement ' );
        }
    }

    /**
     * It will prepare a sentence to be executed.
     *
     * @internal param string $statement the query we will set.
     * @throws \InvalidArgumentException
     * @return bool|array
     */
    public function fetchAll()
    {
        if ( get_class( $this->pdo_statement)== 'PDOStatement')
        {
            try
            {
                return $this->pdo_statement->fetchAll();
            }
            catch ( PDOException $e )
            {
                print("Caught the error: ".$e->getMessage()."<br />\r\n" );
            }
        }
        else
        {
            throw new InvalidArgumentException( 'First prepare the statement ' );
        }


    }

    /**
     * It will get all the history of querys.
     *
     * @internal param string $statement the query we will set.
     * @throws \InvalidArgumentException
     * @return bool|array
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * It can set the profiler to track or not the querys we do.
     *
     * @param boolean $state.
     */
    public function setProfiler(boolean $state)
    {
        $this->profiler_state  = $state;
    }

    /**
     * It will check if the $host is correct.
     *
     * @param int|string $host the host we will check.
     * @throws \InvalidArgumentException.
     * @return int|string
     */
    protected function CheckHost( $host )
    {
        if( filter_var( $host, FILTER_VALIDATE_IP ) )
        {
            return $host;
        }
        elseif( is_string( $host ) )
        {
            return $host;
        }
        else
        {
            throw new InvalidArgumentException( 'Introduce a valid host ' );
        }
    }


}

?>