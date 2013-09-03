<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            require('navigation.html');
            require('classes/dbconnection.php');
            echo '<br />';
            
            //Check if the book id exist and is an numerical
            if(isset($_GET['bid']) && is_numeric($_GET['bid']))
            {
                $book_id = $_GET['bid'];
                echo "Book Id: " . $book_id . "<br /> <br />";
                
                //Database connection
                $dblink = quickMySQLConnect();
                
                //Retrieve all the chapters in the selected book
                $chapters_query = "SELECT chapter_id, parent_id, title
                   FROM chapters WHERE book_id = $book_id";
                
                $chapters_result_set = mysql_query($chapters_query, $dblink);
                
                while($row = mysql_fetch_assoc($chapters_result_set))
                {
                    $chapter_id = $row['chapter_id'];
                    $parent_id = $row['parent_id'];
                    $title = $row['title'];
                    
                    echo "Chapter Id: $chapter_id <br />Parent Id: $parent_id <br />Title: $title<br />";
                    echo '<br />';
                    
                }
                
                //Close database connection
                mysql_close($dblink);
            }
            
            //redirect to book page
        ?>
    </body>
</html>
