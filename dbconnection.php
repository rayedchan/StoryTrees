<?php
class DbConnection
{
    private $connection_resource;
    
    /*
     * Constructor
     */
    public function __construct($hostname, $username, $password, $dbname, $port)
    {
        $this->connection_resource = $this->connectToMySQLDB($hostname,$username, $password, $dbname, $port);  
        //echo 'Connection successful';
    }
    
    /*
     * Establish a connection to MySQL Database
     */
    private function connectToMySQLDB($hostname,$username, $password, $dbname, $port)
    {
        $link =  new mysqli($hostname,$username, $password, $dbname, $port);
        if($link->connect_error) 
            die('Could not establish database connection');
        return $link;
    }
    
    /*
     * Close MySQL Database connection 
     */
    public function closeMySQLConnction($resource)
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
    
    /*
     * Connects to a PostgreSQL Database  
     */
    private function connectToPostgreSQLDB()
    {
        //Connection Settings for PostgreSQL DB; connectionString format KEYWORD=VALUE
        $hostname = 'host=ec2-54-235-134-222.compute-1.amazonaws.com';
        $port = 'port=5432';
        $dbname = 'dbname=dev2jctlvph8it';
        $dbuser = 'user=pbsvyowsqpmzzu';
        $dbpassword = 'password=hvTStiJa-pjszB6GfyxVbKQjfN';
        $mode = "sslmode=require";
        $connection_string = "$hostname $port $dbname $mode $dbuser $dbpassword";

        $dbconnection_resource = pg_connect($connection_string); //Connect to PostgreSQL database
        if(!dbconnection_resource)
             die("Could not connect: " . pg_last_error($dbconnection_resource));
        echo 'Connected Successfully.';
    }

    /*
     * Close PostgreSQL Database connection
     */
    public function closePostgreSQLConnection($dbconnection_resource)
    {
        pg_close($dbconnection_resource);//Close database connection
    }
}
?>
