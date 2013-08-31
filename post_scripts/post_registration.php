<?php
    require('../classes/dbconnection.php');
    require('../lib/BCrypt/password.php');

    //Process Registration Form
    if(isset( $_POST['username'],$_POST['email']))
    {
        //Database connection
        $dblink = quickMySQLConnect();
                
        //Cleanup form data
        $username = mysql_real_escape_string(trim( $_POST['username']),$dblink);
        $email = mysql_real_escape_string(trim($_POST['email']),$dblink);
        $password = mysql_real_escape_string(trim($_POST['password']),$dblink);
        
        //Validation after trimming 
        if(empty($username) || empty($email) || empty($password))
            return;
        
        //Generate password hash using BCrypt Algorithm 
        $hashed_password =  password_hash($password, PASSWORD_BCRYPT, array("cost" => 12));
        
        //User creation
        $query = "INSERT INTO users (usr_key, username,email, password,
        firstname, lastname, birthdate, gender, create_date,
        last_modified) VALUES (NULL, '$username', '$email',
        '$hashed_password', NULL , NULL ,  NULL , NULL , CURRENT_TIMESTAMP, 
        CURRENT_TIMESTAMP)";
        mysql_query($query, $dblink) or die('Invalid query: ' . mysql_error());
        mysql_close($dblink);
        
        //Redirect 
        header('Location:../index.php');
        exit();
        
        //Dialog box 
        /*echo "
            <script type=\"text/javascript\">
            alert(\"User $username has been created.\");
            </script>
        ";*/
        //echo "<br /> User $username has been created.";
    }
    
?>
