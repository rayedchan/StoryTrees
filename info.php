<?php
//phpinfo();

/*require('lib/BCrypt/password.php');
$password = 'Password1';
$hash = password_hash($password, PASSWORD_BCRYPT, array("cost" => 12));

echo "Hash: $hash";
echo "<br />";
echo "Hash Length: " . strlen($hash);
echo "<br />";
*/

//Validate Password 
/*if(password_verify('Password1', $hash))
{
    echo "Login successfully.";
}
else
{
    echo "Failed to authenticate.";
}*/
$userkey = '2dsuhjk1sdhskdjds';
$user_id = preg_replace("/[^0-9]+/", "", $userkey);
echo $user_id;
?>
