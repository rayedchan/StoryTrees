<?php
/*
 * Process Create Book Form
 */
require('../classes/dbconnection.php');

//Check if form is submitted
if(isset($_POST['title'], $_POST['description'], $_POST['genre']))
{
    //Database connection
    $dblink = quickMySQLConnect();
    
    $title = mysql_real_escape_string(trim($_POST['title']), $dblink);
    $description = mysql_real_escape_string(trim($_POST['description']),$dblink);
    $genre = mysql_real_escape_string(trim($_POST['genre']),$dblink);
    
    //Validate if fields are not empty
    if(empty($title) || empty($description) || empty($genre))
        return;
    
    //Create book query
    $query = "INSERT INTO books (title, description, genre) VALUES 
        ('$title', '$description', '$genre')";
    mysql_query($query, $dblink) or die(mysql_error());
    mysql_close($dblink);
    
    header("Location:../books.php");
    exit();
}

?>
