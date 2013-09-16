<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            require('classes/dbconnection.php');
            require('classes/utilities.php');
            require('include/navigation.html');
            echo '<br />';
            sec_session_start();
            
            //Database connection
            $dblink = quickMySQLConnect();
            
            //Validate user session
            if(login_check($dblink) == true)
            {
                $userkey = $_SESSION['userkey'];   
                $username = $_SESSION['username'];
                $email = $_SESSION['email'];
                $user_browser = $_SESSION['browser'];
                $hashed_password = $_SESSION['hashed_password'];
                
                echo "Login successfully. <br />";
                echo "User Key: $userkey <br />";
                echo "Username: $username <br />";
                echo "Hash: $hashed_password <br />";
                echo "Email: $email <br />";
                echo "User browser: $user_browser <br />";
            }
            
            else
            {
                 echo 'You are not authorized to access this page, please login. <br/>';
            }
            
            mysql_close($dblink);
        ?>
    </body>
</html>
