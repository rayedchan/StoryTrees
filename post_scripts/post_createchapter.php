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
  
    //Check values for child or sibling node case
    if(isset($_POST['chapterid'],$_POST['createtype']))
    {
        $chapterid = intval($_POST['chapterid']);
        $createtype = intval($_POST['createtype']);
        
        //Make sure these fields are not empty
        if(empty($chapterid) || empty($createtype))
        {
            mysql_close($dblink);
            header("Location:../chapters.php?bid=$bookid&emptyfields2");
            exit();
        }
        
        //Validate values of createtype
        if($createtype != constant('PARALLEL_VALUE') && $createtype != constant('EXTEND_VALUE'))
        {
            mysql_close($dblink);
            header("Location:../chapters.php?bid=$bookid&invalidcreatetype");
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
        $selected_chapter_parent_id = $selected_chapter_record['parent_id'];
        $selected_chapter_height = $selected_chapter_record['height'];
        
        //Root node cannot have parallel nodes
        if($selected_chapter_height == 0 && $createtype == PARALLEL_VALUE)
        {
            mysql_close($dblink);
            header("Location:../chapters.php?bid=$bookid&rootnodecannothavesiblingnodes");
            exit();
        }
        
        //Extending the selected chapter: Add new node a level down
        else if($createtype == EXTEND_VALUE)
        {
            $new_node_height = $selected_chapter_height + 1;
            $add_new_child_node_query = "INSERT INTO chapters 
                (book_id, parent_id, height, title) VALUES 
                ('$bookid', '$chapterid', '$new_node_height', '$title')";
            mysql_query($add_new_child_node_query, $dblink) or die(mysql_error());
            mysql_close($dblink);
            header("Location:../chapters.php?bid=$bookid&childnodeadded");
            exit();
        }
        
        //Paralleling the selected chapter: Add new sibling node
        //Equivalent to extending the selected node's parent 
        else if($createtype == PARALLEL_VALUE)
        {
            $add_new_child_node_query = "INSERT INTO chapters 
                (book_id, parent_id, height, title) VALUES 
                ('$bookid', '$selected_chapter_parent_id' , '$selected_chapter_height', '$title')";
            mysql_query($add_new_child_node_query, $dblink);
            mysql_close($dblink);
            header("Location:../chapters.php?bid=$bookid&siblingnodeadded");
            exit();
        }
    }
    
    mysql_close($dblink);
    header("Location:../chapters.php?bid=$bookid&somethingwentwrong");
    exit();
}

 header("Location:../books.php");

?>
