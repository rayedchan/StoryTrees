<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Log In</title>
        <link rel="stylesheet" href="css/loginbox_style.css" />
    </head>

    <body>
        <a href="index.php">Home</a> | <a href="registration.php">Sign up</a>
        <form method="post" id="login" name="login" action="">
            <div align="center">
                <h2>Log In</h2>
                <input type="text" class="text-field" id="username" name="username" maxlength="30" value="" placeholder="Username" /><br />
                <input type="password" class="text-field" placeholder="Password"  maxlength="128" value="" /><br />
                <span><a href="">Reset Password</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input type="button" value="Log In" class="button" /><br />
            </div>
        </form>
    </body>
</html>
