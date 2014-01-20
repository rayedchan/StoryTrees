<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Books</title>
    </head>
    <body>
        <?php
            require('include/navigation.html');
            echo '<br />';
            require('classes/dbconnection.php');
            require('classes/constants.php');
            
            $pageNumber = 0; //default to first page
            $numberOfBookToDisplay = constant('NUMBER_OF_TO_DISPLAY');
                       
            //Determine result page number
            if(isset($_GET['pageNumber']))
                $pageNumber  = intval($_GET['pageNumber']);
            
            //Starting index of record to begin with
            $startingIndex = $pageNumber  * $numberOfBookToDisplay;
            
            //Database connection
            $dblink = quickMySQLConnect();
            
             //Number of total book records
            $book_count_query = "SELECT COUNT(*) as numRecords FROM books";
            $book_count_result_set = mysql_query($book_count_query, $dblink);
            $book_count_row = mysql_fetch_assoc($book_count_result_set);
            $total_books = $book_count_row['numRecords'];
            $total_pages = ceil($total_books / $numberOfBookToDisplay);
            
            //Query books 
            $book_query = "SELECT book_id, title, description,
                create_date, last_modified, genre FROM books LIMIT $startingIndex,$numberOfBookToDisplay";
            $books_result_set = mysql_query($book_query, $dblink);
            echo "<br /><hr />";
            
            //Display books
            while($row = mysql_fetch_assoc($books_result_set))
            {
                $book_id = $row['book_id'];
                $title = $row['title'];
                $description = $row['description'];
                $create_date = $row['create_date'];
                $last_modified = $row['last_modified'];
                $genre = $row['genre'];
               
                echo "Book Id: $book_id <br />Title: $title <br />Genre: $genre <br />Description: 
                    $description <br />Date Created: $create_date 
                    <br />Date Modified: $last_modified <br />";
                
                //Place book_id in the URL
                echo "<a href=\"chapters.php?bid=$book_id\">Chapters</a>";
                echo "<br /> <hr />";
            }
            
            //Close database connection
            mysql_close($dblink);
            
            echo 'Page Numbers:  ';
            //Display search pages
            for($i = 0; $i < $total_pages; $i++)
            {
                $currentPage = $i + 1;
                echo "<a href=\"books.php?pageNumber=$i\">$currentPage</a>&nbsp;&nbsp;";
            }
        ?>
    </body>
</html>
