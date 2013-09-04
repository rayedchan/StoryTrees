<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Books</title>
    </head>
    <body>
        <?php
            require('navigation.html');
            echo '<br />';
            require('classes/dbconnection.php');
            
            //Database connection
            $dblink = quickMySQLConnect();
            
            //Query books 
            $book_query = "SELECT book_id, title, description,
                create_date, last_modified FROM books";
            $books_result_set = mysql_query($book_query, $dblink);
            
            //Display books
            while($row = mysql_fetch_assoc($books_result_set))
            {
                $book_id = $row['book_id'];
                $title = $row['title'];
                $description = $row['description'];
                $create_date = $row['create_date'];
                $last_modified = $row['last_modified'];
               
                echo "Book Id: $book_id <br />Title: $title <br />Description: 
                    $description <br />Date Created: $create_date 
                    <br />Date Modified: $last_modified <br />";
                
                //Place book_id in the URL
                echo "<a href=\"chapters.php?bid=$book_id\">Chapters</a>";
                echo "<br /> <br />";
            }
            
            //Close database connection
            mysql_close($dblink);
        ?>
    </body>
</html>
