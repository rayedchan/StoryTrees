<?php
/*This is a test driver*/
//phpinfo();
$userkey = '2dsuhjk1sdhskdjds';
$user_id = preg_replace("/[^0-9]+/", "", $userkey);
echo $user_id;
?>
