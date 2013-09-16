<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.10.3.min.js"></script>
        <link rel="stylesheet" href="css/jquery.jOrgChart.css"/>
        <link rel="stylesheet" href="css/jquery.jOrgChart.customizable.css"/>
        <link rel="stylesheet" href="css/mycustom.css"/>
        <title>Chapters</title>
    </head>
    
    <body>
        <div>
            <img class="background" src="images/Facebook_in_the_dark_widewall_by_will_yen.jpg">
        </div>
        
        <?php
        
        /*
         * Goal: Extract Story Tree data from database and render the data
         *      as a tree structure in HTML.
         * Data Structures:
         *      HashMap: ParentId => Content of all of its descendents
         * Definition: Story Tree represent a multi-storyline book.
         *      Each node represents a chapter from a book.
         *      Each level of the tree represent the chapter number. Ex.
         *      The root node is chapter one. The children nodes of the root node
         *      are Chapter 2.
         * Algorithm:
         * Iterate the tree structure starting at the max height of the tree which
         * is at the bottom most of the tree. At each level, each node will be inspected
         * and the content will be merge with its sibling nodes, which are nodes with
         * the same parent. The html content (determined by how jOrgChart plugin works) 
         * of each node will be store in a HashMap using its parent's key. Sibling nodes
         * will merge together and should not interfere with cousin nodes.   
         */
        
            require('include/navigation.html');
            require('classes/dbconnection.php');
            require('classes/utilities.php');
            echo '<br /> <br />';
            
            $book_id  = null; //book id of the current book being viewed
            $chapters_num_rows = null; //number of chapters in current book

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

                //Chapter exists 
                if($chapters_num_rows > 0)
                {
                    //Retrieve the max height of the tree
                    $tree_height_query = "SELECT MAX(height) as max_height
                        FROM chapters WHERE book_id = $book_id LIMIT 1";
                    $height_result = mysql_query($tree_height_query, $dblink) or die(mysql_error());
                    $height_row = mysql_fetch_assoc($height_result);
                    $tree_height = $height_row['max_height']; //keep of the current height being inspected
                    $max_height = $tree_height; //this will be fixed as the max height

                    //Retrieve all the leaf node in a given tree
                    $leaf_node_query = "SELECT c1.chapter_id as chapter_id, c1.title as title FROM chapters AS c1
                        LEFT JOIN chapters AS c2 ON
                        c1.chapter_id = c2.parent_id
                        WHERE c2.parent_id IS NULL AND c1.book_id = $book_id";
                    $leaf_node_result = mysql_query($leaf_node_query, $dblink);
                    $num_leaf_nodes = mysql_numrows($leaf_node_result);
                    $isLeafNodeMap = array(); //Associative Array to store leaf nodes; ChapterId(NodeId) => Title
                    for($i = 1; $i <= $num_leaf_nodes; $i++)
                    {
                        $leaf_node_record = mysql_fetch_assoc($leaf_node_result);
                        $nodeId = $leaf_node_record['chapter_id'];
                        $title = $leaf_node_record['title'];
                        $isLeafNodeMap[$nodeId] = $title;
                    }

                    $merger_node_data = array(); //HashMap to store the merged results of nodes; Use ParentId as the index
                    $merger_node_child_num = array(); //Stores the number of children of each parent node; Use ParentId as the index
                    $tree_html = null; //Store the HTML Tree
                    
                    //Iterate tree level by level starting at the highest height
                    while($tree_height >= 0)
                    {   
                        //Retrieve all the chapters at a specific level for the selected book
                        $chapters_query = "SELECT chapter_id, parent_id, height, title 
                            FROM chapters WHERE book_id = $book_id AND height = $tree_height";
                        $chapters_result_set = mysql_query($chapters_query, $dblink);

                        //Case 1: Root Node
                        if($tree_height == 0)
                        {
                            $row = mysql_fetch_assoc($chapters_result_set);
                            $chapter_id = $row['chapter_id'];
                            $parent_id = $row['parent_id'];
                            $title = $row['title'];
                            $isLeafNode = array_key_exists($chapter_id, $isLeafNodeMap);

                            //Only the root node exists in tree
                            if($isLeafNode)
                            {
                                 $tree_html = "<div align=\"center\" class=\"jOrgChart\">
                                     <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                                     <tbody><tr class=\"node-cells\"> <td class=\"node-cell\" colspan=\"2\">
                                     <div class=\"node\" style=\"cursor: n-resize;\"><a href=\"#\" 
                                     target=\"_blank\">$title</a><br /><font size=\"1px\">
                                     <i>Chapter Id: $chapter_id</i></font></div></td></tr></tbody></table></div>";
                            }

                            //Merge current processed content, which includes all the descendents, with the root node
                            else
                            {
                                 $root_num_child_nodes = $merger_node_child_num[$chapter_id]; //number of child node the root node has
                                 $colspan = 2 * $root_num_child_nodes;
                                 
                                 //Merge root node with the entire descendent subtree
                                 $child_node_content = $merger_node_data[$chapter_id];
                                 $parent_node_content = "<tr class=\"node-cells\">
                                     <td class=\"node-cell\" colspan=\"$colspan\"><div class=\"node\" style=\"cursor: n-resize;\">
                                     <a href=\"#\" target=\"_blank\">$title</a><br><font size=\"1px\">
                                     <i>Chapter Id: $chapter_id</i></font></div></td></tr>";

                                 //Get the lines for the HTML Tree structure
                                 $html_tree_lines = generateTreeLinesHTML($root_num_child_nodes, $colspan);
                                 $line_down = $html_tree_lines['line_down'];
                                 $tree_lines = $html_tree_lines['tree_lines'];
                                 
                                 //Final Step for entire HTML tree
                                 $tree_html = "<div align=\"center\" class=\"jOrgChart\">
                                     <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                                     <tbody>$parent_node_content $line_down $tree_lines <tr>$child_node_content</tr></tbody></table></div>";
                            }
                        }

                        //Case 2: Handle when starting at the max height
                        //All nodes here are li elements. They are also leaf nodes.
                        else if($tree_height == $max_height)
                        {
                            //Iterate each chapter from result set
                            while($row = mysql_fetch_assoc($chapters_result_set))
                            {
                                $chapter_id = $row['chapter_id'];
                                $parent_id = $row['parent_id'];
                                $title = $row['title'];
                                $key_exists = array_key_exists($parent_id, $merger_node_data);

                                //Sibling Merge Case
                                if($key_exists)
                                {                      
                                    $old_content = $merger_node_data[$parent_id];
                                    $new_content =  "<td class=\"node-container\" colspan=\"2\">
                                        <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tbody>
                                        <tr class=\"node-cells\"><td class=\"node-cell\" colspan=\"2\">
                                        <div class=\"node\"><a href=\"#\" target=\"_blank\">$title</a><br />
                                        <font size=\"1px\"><i>Chapter Id: $chapter_id</i></font></div></td>
                                        </tr></tbody></table></td>";
                                    $merged_content = $old_content . $new_content;
                                    $merger_node_data[$parent_id] = $merged_content;
                                    $merger_node_child_num[$parent_id]++;
                                }

                                //First Child Node Case
                                else
                                {
                                    $merger_node_data[$parent_id] = "<td class=\"node-container\" colspan=\"2\">
                                        <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tbody>
                                        <tr class=\"node-cells\"><td class=\"node-cell\" colspan=\"2\">
                                        <div class=\"node\"><a href=\"#\" target=\"_blank\">$title</a><br />
                                        <font size=\"1px\"><i>Chapter Id: $chapter_id</i></font></div></td>
                                        </tr></tbody></table></td>";
                                    $merger_node_child_num[$parent_id]++;
                                }
                            }
                        }

                        //Case 3: Middle layer of the tree
                        //Handles merger nodes and remaining leaf nodes
                        else 
                        {
                            //Iterate each chapter from result set
                            while($row = mysql_fetch_assoc($chapters_result_set))
                            {
                                $chapter_id = $row['chapter_id'];
                                $parent_id = $row['parent_id'];
                                $title = $row['title'];
                                $key_exists = array_key_exists($parent_id, $merger_node_data);
                                $isLeafNode = array_key_exists($chapter_id, $isLeafNodeMap);

                                //Sibling Merge Case
                                if($key_exists)
                                {     
                                    //Current Node is a Leaf
                                    if($isLeafNode)
                                    {
                                        //Merge current node content with its sibling nodes which already have been processed
                                        $current_constructed_content = $merger_node_data[$parent_id];
                                        $new_node_content = "<td class=\"node-container\" colspan=\"2\">
                                            <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tbody>
                                            <tr class=\"node-cells\"><td class=\"node-cell\" colspan=\"2\">
                                            <div class=\"node\"><a href=\"#\" target=\"_blank\">$title</a><br />
                                            <font size=\"1px\"><i>Chapter Id: $chapter_id</i></font></div></td>
                                            </tr></tbody></table></td>";
                                        $merged_content = $current_constructed_content . $new_node_content;
                                        $merger_node_data[$parent_id] = $merged_content;
                                        $merger_node_child_num[$parent_id]++;
                                    }

                                    //Merger Node Case
                                    else
                                    {
                                        $current_node_child_num = $merger_node_child_num[$chapter_id];  //Determine current node's children
                                        $colspan = 2 * $current_node_child_num;
                                        
                                        //Merge child nodes of the current node content with current node's content (parent)
                                        $child_node_content = $merger_node_data[$chapter_id];
                                        $parent_node_content = "<tr class=\"node-cells\">
                                            <td class=\"node-cell\" colspan=\"$colspan\"><div class=\"node\" style=\"cursor: n-resize;\">
                                            <a href=\"#\" target=\"_blank\">$title</a><br><font size=\"1px\">
                                            <i>Chapter Id: $chapter_id</i></font></div></td></tr>";                                  

                                        //Get the lines for the HTML Tree structure
                                        $html_tree_lines = generateTreeLinesHTML($current_node_child_num, $colspan);
                                        $line_down = $html_tree_lines['line_down'];
                                        $tree_lines = $html_tree_lines['tree_lines'];

                                        $merged_descendant_content = "<td class=\"node-container\" colspan=\"2\"> 
                                            <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tbody>
                                            $parent_node_content $line_down $tree_lines <tr>$child_node_content</tr>
                                            </tbody></table></td>";

                                        //Merge with sibling nodes which already have been processed
                                        $current_constructed_content = $merger_node_data[$parent_id];
                                        $merger_node_data[$parent_id] = $current_constructed_content . $merged_descendant_content;
                                        $merger_node_child_num[$parent_id]++;
                                    }  
                                }

                                //First Child Node(currentNode) of New Parent Case
                                else
                                {
                                    //Leaf Node Case
                                    if($isLeafNode)
                                    {
                                        $merger_node_data[$parent_id] = "<td class=\"node-container\" colspan=\"2\">
                                            <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tbody>
                                            <tr class=\"node-cells\"><td class=\"node-cell\" colspan=\"2\">
                                            <div class=\"node\"><a href=\"#\" target=\"_blank\">$title</a><br />
                                            <font size=\"1px\"><i>Chapter Id: $chapter_id</i></font></div></td>
                                            </tr></tbody></table></td>";
                                        $merger_node_child_num[$parent_id]++;
                                    }

                                    //Merger Node Case
                                    else
                                    {
                                        //Determine current node's children
                                        $current_node_child_num = $merger_node_child_num[$chapter_id];
                                        $colspan = 2 * $current_node_child_num;
                                        
                                        //Merge current node's child nodes content with current node's content (parent)
                                        $child_node_content = $merger_node_data[$chapter_id];
                                        $parent_node_content = "<tr class=\"node-cells\">
                                            <td class=\"node-cell\" colspan=\"$colspan\"><div class=\"node\" style=\"cursor: n-resize;\">
                                            <a href=\"#\" target=\"_blank\">$title</a><br><font size=\"1px\">
                                            <i>Chapter Id: $chapter_id</i></font></div></td></tr>";//The parent node is currently being inspected
                                        
                                        //Get the lines for the HTML Tree structure
                                        $html_tree_lines = generateTreeLinesHTML($current_node_child_num, $colspan);
                                        $line_down = $html_tree_lines['line_down'];
                                        $tree_lines = $html_tree_lines['tree_lines'];

                                        $merged_content = "<td class=\"node-container\" colspan=\"2\"> 
                                            <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tbody>
                                            $parent_node_content $line_down $tree_lines <tr>$child_node_content</tr>
                                            </tbody></table></td>";
                                        $merger_node_data[$parent_id] = $merged_content; //Store merged content into its parent
                                        $merger_node_child_num[$parent_id]++; //increment current node's parent child counter
                                    }  
                                }
                            } 
                        }
                        
                        $tree_height--; //Decrement the height
                    }//End While: Height Check
                    
                    echo $tree_html;  //Display HTML story tree
                }//End If: Chapters Check 
                
                mysql_close($dblink); //Close database connection
            }//End If: Post Process Check
            
            //redirect to book page
            else
            {        
                header('Location:books.php?unset');
                exit();
            }
            
            //Create Chapter Form
            require('forms/createchapter.php');
        ?>
    </body>
</html>
