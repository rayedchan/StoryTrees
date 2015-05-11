<?php

class DbConnection
{
    const PATH_TO_SSL_CLIENT_KEY_FILE = 'certs/b65aaecc03af97-key.pem';
    const PATH_TO_SSL_CLIENT_CERT_FILE = 'certs/b65aaecc03af97-cert.pem';
    const PATH_TO_CA_CERT_FILE = 'certs/cleardb-ca.pem';
    private $connection_resource;
        
    /*
     * Constructor
     */
    public function __construct($hostname, $username, $password, $dbname, $port, $socket ,$flags)
    {
        $this->connection_resource = $this->connectToMySQLDB($hostname,$username, $password, $dbname, $port, $socket, $flags);  
    }
    
    /*
     * Establish a connection to MySQL Database
     */
    private function connectToMySQLDB($hostname,$username, $password, $dbname, $port, $socket ,$flags)
    {
        $link = mysql_connect($hostname.":".$port, $username, $password);
        if (!$link) 
            die('Could not connect: ' . mysql_error());
        
        $db_selected = mysql_select_db($dbname, $link); //make the current db
        if (!$db_selected) 
            die ('Cannot use selected db : ' . mysql_error());
        
        return $link;
    }
    
    /*
     * Close MySQL Database Connection
     */
    public function closeMySQLConnection($link)
    {
        mysql_close($link);
    }
    
    /*
     * Test querying from database
     */
    public function testQuery($link)
    {
        //MySQLi Version
        /*$result = mysqli_query($link, 'SELECT * FROM USER');
        while($row = mysqli_fetch_array($result))
        {
            echo $row['username'];
            echo "<br>";
        }*/
        
        $result = mysql_query("SELECT * FROM USER", $link);
        while($row = mysql_fetch_array($result))
        {
            echo $row['username'];
            echo "<br />";
        }
    }
    
    /*
     * Establish an SSL connection to MySQL Database
     * Not support in heroku yet
     */
    private function connectToMySQLDBSSL($hostname,$username, $password, $dbname, $port, $socket ,$flags)
    {
        //$link =  new mysqli($hostname,$username, $password, $dbname, $port);
        $link = mysqli_init();
        mysqli_options($link, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
        mysqli_ssl_set($link, self::PATH_TO_SSL_CLIENT_KEY_FILE , self::PATH_TO_SSL_CLIENT_CERT_FILE, self::PATH_TO_CA_CERT_FILE, null, null);
        mysqli_real_connect($link, $hostname,$username, $password, $dbname, $port, $socket ,$flags);
        if($link->connect_error) 
            die('Connect Error (' . $link->connect_errno . ') ' . $link->connect_error);

        return $link;
    }
    
    /*
     * Close MySQL Database SSL connection 
     */
    public function closeMySQLConnectionSSL($resource)
    {
        $resource->close();
    }
    
    /*
     * Get connection resource
     */
    public function getMySQLConnectionResource()
    {
        return $this->connection_resource;
    }
}

function quickMySQLConnect()
{
    //Use on Heroku Server to eliminate hard-coded values
    /*$url=parse_url(getenv("CLEARDB_DATABASE_URL"));
    $server = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $db = substr($url["path"],1);
    echo "$server " . "$username " . "$db ";*/

    $hostname = 'us-cdbr-east-04.cleardb.com';
    $username = 'b65aaecc03af97';
    $password = 'c79a70b5';
    $dbname = 'heroku_1b0f41c846188ed';
    $port = '3306';
    $socket = null;
    $dbconnection = new DbConnection($hostname, $username, $password, $dbname, $port, $socket, MYSQLI_CLIENT_SSL);
    $link = $dbconnection->getMySQLConnectionResource();
    return $link;
}

?>
