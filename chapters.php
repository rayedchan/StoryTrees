<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.10.3.min.js"></script>
        <script type="text/javascript" src="js/javascript-tabs.js"></script>
        <link rel="stylesheet" href="css/jquery.jOrgChart.css"/>
        <link rel="stylesheet" href="css/jquery.jOrgChart.customizable.css"/>
        <link rel="stylesheet" href="css/mycustom.css"/>
        <link rel="stylesheet" href="css/javascript-tabs.css"/>
        <title>Chapters</title>
    </head>
    
    <body onload="init()">
        <!--<div>
            <img class="background" src="images/Facebook_in_the_dark_widewall_by_will_yen.jpg">
        </div>-->
        
        <?php
            require('include/navigation.html');
            require('classes/dbconnection.php');
            require('classes/utilities.php');
            echo '<br /> <br />';
            
            $book_id  = null; //book id of the current book being viewed
            $chapters_num_rows = null; //number of chapters in current book
            $paths = array(); //Stores all the paths in a tree
            $tree_html = null; //Store the HTML Tree
            $chapter_node_map = array(); //Stores each Node Object in tree; ChapterId => Array containing chapter properties (Key => Value)
            $html_storylines = null; //Store Linear View of HTML Tree
            
            //Check if the book id exist and is an numerical
            if(isset($_GET['bid']) && is_numeric($_GET['bid']))
            {
                $book_id = intval($_GET['bid']); //force integer conversion
                $dblink = quickMySQLConnect(); //database connection
                
                //Determine if book exists
                $book_existence_query = "SELECT 1 FROM books WHERE book_id = $book_id";
                $book_existence_result = mysql_query($book_existence_query, $dblink);
                $book_num_row = mysql_num_rows($book_existence_result);
                
                //Validate book existence
                if($book_num_row != 1)
                {
                    mysql_close($dblink);
                    header('Location:books.php?invalidbook');
                    exit();
                }
                
                //Determine if any chapters exists for book
                $chapters_query = "SELECT 1 FROM chapters WHERE book_id = $book_id";
                $chapters_result = mysql_query($chapters_query, $dblink);
                $chapters_num_rows = mysql_num_rows($chapters_result);

                //Validate chapter existence 
                if($chapters_num_rows > 0)
                {
                    //Call function to generate the HTML Tree; builts node and paths maps
                    $tree_html = generateHTMLTree($dblink, $book_id, $paths, $chapter_node_map);
                    
                    //Call function to generate a linear view of the tree by displaying each path
                    $html_storylines = generateHTMLStorylines($paths, $chapter_node_map);
                }
                
                mysql_close($dblink); //Close database connection
            }//End If: Post Process Check
            
            //redirect to book page
            else
            {        
                header('Location:books.php?unset');
                exit();
            }
             
            //Display Tabs
            echo "<ul id=\"tabs\"> 
                    <li><a href=\"#treeview\">Tree View</a></li>
                    <li><a href=\"#storylineview\">Storyline View</a></li>
                  </ul>";
            
            //Wrap each content in a tabContainer
            echo "<div class=\"tabContent\" id=\"treeview\">"; 
                require('forms/createchapter.php');  //Render Create Chapter Form
                echo "$tree_html";//Display HTML story tree
            echo "</div>";
            echo "<div class=\"tabContent\" id=\"storylineview\">
                Display every storyline in the current tree.<br />";
                print_r($paths);
                echo '<br /> <br />';
                echo $html_storylines;
            echo "</div>";      
        ?>
    </body>
</html>
