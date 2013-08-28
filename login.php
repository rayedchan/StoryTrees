<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Log In</title>
        <link rel="stylesheet" href="css/loginbox_style.css" />
    </head>

    <body>
        <?php require('navigation.html'); ?>
        <form method="post" id="login" class="login" name="login" action="post_scripts/post_login.php">
            <div align="center">
                <h2>Log In</h2>
                <input type="text" class="text-field" id="username" name="username" maxlength="30" value="" placeholder="Username" /><br />
                <input type="password" class="text-field" id="password" name="password" placeholder="Password"  maxlength="128" value="" /><br />
                <span><a href="">Reset Password</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input type="submit" value="Log In" class="button" /><br />
            </div>
        </form>
    </body>
</html>
