<!DOCTYPE html>
<html>
    <head>
        <title>Registration Form</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Log In</title>
        <link rel="stylesheet" href="css/loginbox_style.css" />
    </head>
    <body>
        <?php require('navigation.html'); ?>
        <form method="post" id="createaccount" class="register" name="createaccount" action="registration.php" >
            <div align="center">
                <h2>Registration Form</h2>
                <input type="text" class="text-field" id="username" name="username" placeholder="Username" maxlength="30" value="" spellcheck="false" autocomplete="off" />  
                <input type="email" class="text-field" id="email" name="email" placeholder="Email" value="" maxlength="254" spellcheck="false" autocomplete="off" />
                <input type="password" class="text-field" id="password" name="password" placeholder="Password" maxlength="128" value="" autocomplete="off" /><br />
                <input type="submit" class="button" id="signup" name="signup" value="Sign up" />
            </div>
        </form>
    </body>
</html>

<?php
    require_once('dbconnection.php');
 
    //Process Registration Form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    //Checks if form fields exist and are not null
    if(!empty($username) && !empty($email) && !empty($password))
    {
        //Database connection
        $dblink = quickMySQLConnect();
                
        //Cleanup form data
        $username = mysql_real_escape_string(trim($username),$dblink);
        $email = mysql_real_escape_string(trim($email),$dblink);
        $password = mysql_real_escape_string(trim($password),$dblink);
        
        //Validation
        if(empty($username) || empty($email) || empty($password))
            return;
        
        //User creation
        $query = "INSERT INTO users (usr_key, username,email, password,
        firstname, lastname, birthdate, gender, create_date,
        last_modified) VALUES (NULL, '$username', '$email',
        '$password', NULL , NULL ,  NULL , 'M', CURRENT_TIMESTAMP, 
        CURRENT_TIMESTAMP)";
        mysql_query($query, $dblink) or die('Invalid query: ' . mysql_error());
        mysql_close($dblink);
        
        //Dialog box 
         echo "
            <script type=\"text/javascript\">
            alert(\"User $username has been created.\");
            </script>
        ";
    }
?>