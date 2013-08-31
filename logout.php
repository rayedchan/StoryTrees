<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            require('classes/utilities.php');
            sec_session_start();
            
            // Unset all session values
            $_SESSION = array();
            
            // get session parameters 
            $params = session_get_cookie_params();
            
            // Delete the actual cookie.
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
            
            // Destroy session
            session_destroy();
            header('Location:index.php');
            exit();
        ?>
    </body>
</html>
