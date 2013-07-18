<!DOCTYPE html>
<html>
    <head>
        <title>Registration Form</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <form method="post" id="createaccount" name="createaccount" action="" >
            <table>
                <tr>
                    <td><label>First Name</label></td>
                    <td><input type="text" id="firstname" name="firstname" maxlength="30" value="" spellcheck="false" autocomplete="off" /></td>
                </tr>
                <tr>
                    <td><label>Last Name</label></td>
                    <td><input type="text" id="lastname" name="lastname" maxlength="30" value="" spellcheck="false" autocomplete="off" /></td>
                </tr>
                <tr>    
                    <td><label>Email</label></td>
                    <td><input type="email" id="email" name="email" value="" maxlength="254" spellcheck="false" autocomplete="off" /></td>
                </tr>
                <tr>
                    <td><label>Confirm Email</label></td>
                    <td><input type="email" id="confirmemail" name="confirmemail" value="" maxlength="254" spellcheck="false" autocomplete="off" /></td>
                </tr>
                <tr>
                    <td><label>Password</label></td>
                    <td><input type="password" id="password" name="password" maxlength="128" value="" autocomplete="off" /></td>
                </tr>
                <tr>
                    <td><label>Confirm Password</label></td>
                    <td><input type="password" id="confirmpassword" name="confirmpassword" maxlength="128" value="" autocomplete="off" /></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="submit" id="signup" name="signup" value="Sign up" /></td>
                </tr>
            </table>
        </form>
    </body>
</html>