<?php
require('../classes/dbconnection.php');
require('../lib/BCrypt/password.php');
require('../classes/constants.php');
require('../classes/utilities.php');
sec_session_start();
    
//Post: Login 
if(isset($_POST['username'],$_POST['password']))
{
    //Database connection
    $dblink = quickMySQLConnect();
    
    //Cleanup form data
    $username = mysql_real_escape_string(trim($_POST['username']),$dblink);
    $password = mysql_real_escape_string(trim($_POST['password']),$dblink);
    
    //Validation after trimming 
    if(empty($username) || empty($password)) 
        return;
    
    //Get user information
    $query = "SELECT usr_key, username, email, password, login_attempts FROM users 
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
        $login_attempts = $row['login_attempts'];
        
        //Check if the user's account is locked from too many login attempts
        if($login_attempts > MAX_LOGIN_ATTEMPTS)
        {
            mysql_close($dblink);
            echo 'User exceeded login attempts';
            //redirect to login page
        }
        
        //Validate Password 
        if(password_verify($password, $hashed_password))
        {            
            $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
            $userkey = preg_replace("/[^0-9]+/", "", $userkey); // XSS protection as we might print this value
            $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); // XSS protection as we might print this value
            
            $_SESSION['userkey'] = $userkey;   
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['browser'] = $user_browser;
            $_SESSION['hashed_password'] = $hashed_password;
            
            mysql_close($dblink);
            header("Location:../userprofile.php");
            exit();
        }
        
        else
        {
            //User failed authentication
            $query_add_one_to_login_attempts = "UPDATE users SET login_attempts = 
                login_attempts + 1 WHERE usr_key = '$userkey'";
            mysql_query($query_add_one_to_login_attempts, $dblink);
            mysql_close($dblink);
            echo 'User Login failed';
        }
    }
}
?>
