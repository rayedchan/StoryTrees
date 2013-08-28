<?php
require('../classes/dbconnection.php');
require('../lib/BCrypt/password.php');
    
//Post: Login 
$username = $_POST['username'];
$password = $_POST['password'];

//Validate fields are not empty
if(!empty($username) && !empty($password))
{
    //Database connection
    $dblink = quickMySQLConnect();
    
    //Cleanup form data
    $username = mysql_real_escape_string(trim($username),$dblink);
    $password = mysql_real_escape_string(trim($password),$dblink);
    
    //Validation after trimming 
    if(empty($username) || empty($password)) 
        return;
    
    //Get user information
    $query = "SELECT usr_key, username, email, password FROM users 
        WHERE username = '$username'";
    
    $user_result = mysql_query($query, $dblink) or die('Invalid query: ' . mysql_error());
    $numrow = mysql_num_rows($user_result);
    
    //Must only be one row to retrieved
    if($numrow == 1)
    {
        $row = mysql_fetch_array($user_result);
        $userkey = $row['usr_key'];
        $hashed_password = $row['password'];
        $email = $row['email'];
        
        //Validate Password 
        if(password_verify($password, $hashed_password))
        {
            echo "Login successfully. <br />";
            echo "User Key: $userkey <br />";
            echo "Username: $username <br />";
            echo "Password: $password <br />";
            echo "Hash: $hashed_password <br />";
            echo "Email: $email <br />";
        }
        
        else
        {
            echo "Failed to authenticate.";
        }
    }
    
    mysql_close($dblink);
}
?>
