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
        <form method="post" id="createaccount" class="register" name="createaccount" action="post_scripts/post_registration.php" >
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