<?php
/*This is a test driver*/
//phpinfo();
//$userkey = '2dsuhjk1sdhskdjds';
//$user_id = preg_replace("/[^0-9]+/", "", $userkey);
//echo $user_id;
?>

<?php
/*$needle = '7';
$haystack = '1 -> 21 -> 207';
$result = strpos($haystack, $needle);
echo $result;*/

/*$currentId = 55;
$value = '5/7/8';
$pattern = "#^$currentId/#";
$match = preg_match($pattern, $value);
echo $match;*/

$path = '5/7/8';
$delimiter = '/';
$token = strtok($path, $delimiter);

while($token !== false)
{
    echo $token . '<br />';
    $token = strtok($delimiter); 
}


?>

 <!--<ul>
   <li> 1
        <ul>
            <li>2<ul><li>6</li><li>7</li></ul></li>
           <li>3</li>
         <li>4<ul><li>8</li><li>9</li></ul></li>
           <li>5</li>
         </ul>
     </li>
 </ul>-->