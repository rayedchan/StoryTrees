<?php
function connectToDB()
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

    pg_close($dbconnection_resource);//Close database connection
}
?>
