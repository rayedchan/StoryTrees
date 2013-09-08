<?php
/*
 * Process Create Chapter Form
 */
require('../classes/dbconnection.php');
require('../classes/constants.php');

if(isset($_POST['bookid'], $_POST['title']))
{
    //Database connection
    $dblink = quickMySQLConnect();
    
    $bookid = intval($_POST['bookid']);
    $title = mysql_real_escape_string(trim($_POST['title']),$dblink);
    
    //Make sure these fields are not empty
    if(empty($bookid) || empty($title))
    {
        mysql_close($dblink);
        header("Location:../chapters.php?bid=$bookid&emptyfields");
        exit();
    }
    
    //Determine if book exists
    $book_existence_query = "SELECT 1 FROM books WHERE book_id = $bookid";
    $book_existence_result = mysql_query($book_existence_query, $dblink);
    $book_num_row = mysql_num_rows($book_existence_result);

    //Validate book existence
    if($book_num_row != 1)
    {
        mysql_close($dblink);
        header('Location:../books.php?invalidbook');
        exit();
    }
    
    //Determine if any chapters exits for book
    $chapters_query = "SELECT 1 FROM chapters WHERE book_id = $bookid";
    $chapters_result = mysql_query($chapters_query, $dblink);
    $chapters_num_rows = mysql_num_rows($chapters_result);
    
    //Root node is being added
    if($chapters_num_rows == 0)
    {
        $root_node_add_query = "INSERT INTO chapters (book_id, parent_id, height, title)
             VALUES ('$bookid', NULL, 0, '$title')";
        mysql_query($root_node_add_query, $dblink);
        mysql_close($dblink);
        header("Location:../chapters.php?bid=$bookid&rootnodeadded");
        exit();
    }
  
    //Check values for child node case
    if(isset($_POST['chapterid']))
    {
        $chapterid = intval($_POST['chapterid']);
        
        //Make sure these fields are not empty
        if(empty($chapterid))
        {
            mysql_close($dblink);
            header("Location:../chapters.php?bid=$bookid&emptyfields2");
            exit();
        }

        //Determine if the selected chapter id exists
        $chapter_existence_query = "SELECT parent_id, height FROM chapters WHERE book_id = $bookid 
            AND chapter_id = $chapterid LIMIT 1";
        $chapter_existence_result = mysql_query($chapter_existence_query, $dblink);
        $chapter_exists = mysql_num_rows($chapter_existence_result);
        
        if($chapter_exists != 1)
        {
            mysql_close($dblink);
            header("Location:../chapters.php?bid=$bookid&selectedchapterdoesnotexist");
            exit();
        }
        
        $selected_chapter_record = mysql_fetch_assoc($chapter_existence_result);
        $selected_chapter_height = $selected_chapter_record['height'];
        
        $new_node_height = $selected_chapter_height + 1;
        $add_new_child_node_query = "INSERT INTO chapters 
            (book_id, parent_id, height, title) VALUES 
            ('$bookid', '$chapterid', '$new_node_height', '$title')";
        mysql_query($add_new_child_node_query, $dblink) or die(mysql_error());
        mysql_close($dblink);
        header("Location:../chapters.php?bid=$bookid&childnodeadded");
        exit();
    }
    
    mysql_close($dblink);
    header("Location:../chapters.php?bid=$bookid&somethingwentwrong");
    exit();
}

 header("Location:../books.php");
 exit();

?>
