<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.10.3.min.js"></script>
        <script type="text/javascript" src="js/jquery.jOrgChart.js"></script>
        <link rel="stylesheet" href="css/jquery.jOrgChart.css"/>
        <link rel="stylesheet" href="css/custom.css"/>
        <link rel="stylesheet" href="css/mycustom.css"/>
        <title>Chapters</title>
        <script type="text/javascript">
            /*Call method to built the story tree diagram*/
            /*jQuery(document).ready(function() {
                 $("#org").jOrgChart();
            });*/
        </script>
    </head>
    
    <body>
        <div>
            <img class="background" src="images/Facebook_in_the_dark_widewall_by_will_yen.jpg">
        </div>
        
        <?php
            require('navigation.html');
            require('classes/dbconnection.php');
            echo '<br /> <br />';
            
            $book_id  = null;
            $chapters_num_rows = null;

            //Check if the book id exist and is an numerical
            if(isset($_GET['bid']) && is_numeric($_GET['bid']))
            {
                $book_id = intval($_GET['bid']); //force integer conversion
                
                //Database connection
                $dblink = quickMySQLConnect();
                
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
                
                //Determine if any chapters exits for book
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
                    $tree_height = $height_row['max_height'];
                    $max_height = $tree_height;
                    //echo "Tree Height: " . $tree_height;
                    //echo "<br />";

                    //Retrieve all the leaf node in a given tree
                    $leaf_node_query = "SELECT c1.chapter_id as chapter_id, c1.title as title FROM chapters AS c1
                        LEFT JOIN chapters AS c2 ON
                        c1.chapter_id = c2.parent_id
                        WHERE c2.parent_id IS NULL AND c1.book_id = $book_id";
                    $leaf_node_result = mysql_query($leaf_node_query, $dblink);
                    $num_leaf_nodes = mysql_numrows($leaf_node_result);
                    //echo "Number of Leaf Nodes: $num_leaf_nodes <br />";
                    $isLeafNodeMap = array(); //Associative Array to store leaf nodes; ChapterId(NodeId) => Title
                    for($i = 1; $i <= $num_leaf_nodes; $i++)
                    {
                        $leaf_node_record = mysql_fetch_assoc($leaf_node_result);
                        $nodeId = $leaf_node_record['chapter_id'];
                        $title = $leaf_node_record['title'];
                        $isLeafNodeMap[$nodeId] = $title;
                    }
                    //print_r($isLeafNodeMap);
                    //echo "<br /> <br />";

                    //HashMap to store the merged results of nodes; Use ParentId as the index
                    $merger_node_data = array();
                    
                    //Stores the number of children of each parent node; Use ParentId as the index
                    $merger_node_child_num = array();

                    //Store the HTML Tree
                    $tree_html = null;
                    
                    //Keep track of nodes on each level of tree
                    $previous_level_num_nodes = 0;
                    $current_level_num_nodes = 0;

                    //Iterate tree level by level starting at the highest height
                    while($tree_height >= 0)
                    {   
                        //Store num nodes on previous level; Only works for root case
                        $previous_level_num_nodes = $current_level_num_nodes;
                        
                        //Retrieve all the chapters at a specific level for the selected book
                        $chapters_query = "SELECT chapter_id, parent_id, height, title 
                            FROM chapters WHERE book_id = $book_id AND height = $tree_height";
                        $chapters_result_set = mysql_query($chapters_query, $dblink);
                        $current_level_num_nodes = mysql_num_rows($chapters_result_set);

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

                            else
                            {
                                 $colspan = 2 * $previous_level_num_nodes;
                                 
                                 //Merge root node with the entire descendent subtree
                                 $child_node_content = $merger_node_data[$chapter_id];
                                 $parent_node_content = "<tr class=\"node-cells\">
                                     <td class=\"node-cell\" colspan=\"$colspan\"><div class=\"node\" style=\"cursor: n-resize;\">
                                     <a href=\"#\" target=\"_blank\">$title</a><br><font size=\"1px\">
                                     <i>Chapter Id: $chapter_id</i></font></div></td></tr>";

                                //Generate HTML tree lines     
                                $line_down = "";
                                $tree_lines = "";
                                
                                //More than two child nodes
                                if($previous_level_num_nodes >= 2)
                                {
                                    $line_down = "<tr><td colspan=\"$colspan\"><div class=\"line down\"></div></td></tr>";
                                    
                                    for($j = 2; $j <= $colspan; $j = $j + 2)
                                    {
                                        //First Node
                                        if($j == 2)
                                        {
                                            $tree_lines = "<td class=\"line left\">&nbsp;</td> <td class=\"line right top\">&nbsp;</td>";
                                        }

                                        //Last Node
                                        else if($j == $colspan)
                                        {
                                            $tree_lines = "<tr>" . $tree_lines . "<td class=\"line left top\">&nbsp;</td><td class=\"line right\">&nbsp;</td></tr>";
                                        }

                                        //Middle Node
                                        else
                                        {
                                            $tree_lines = $tree_lines . "<td class=\"line left top\">&nbsp;</td><td class=\"line right top\">&nbsp;</td>";
                                        }
                                    }
                                }
                                
                                //Exactly one child node
                                else if($previous_level_num_nodes == 1)
                                {
                                    $line_down = "<tr><td colspan=\"$colspan\"><div class=\"line down\"></div></td></tr>";
                                    $tree_lines = "<tr><td class=\"line left\">&nbsp;</td><td class=\"line right\">&nbsp;</td></tr>";
                                }
                                
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
                        //Need to handle merger nodes and remaining leaf nodes
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

                                        //Generate HTML tree lines     
                                        $line_down = "";
                                        $tree_lines = "";
                                
                                        //More than two child nodes
                                        if($current_node_child_num >= 2)
                                        {
                                            $line_down = "<tr><td colspan=\"$colspan\"><div class=\"line down\"></div></td></tr>";

                                            for($j = 2; $j <= $colspan; $j = $j + 2)
                                            {
                                                //First Node
                                                if($j == 2)
                                                {
                                                    $tree_lines = "<td class=\"line left\">&nbsp;</td> <td class=\"line right top\">&nbsp;</td>";
                                                }

                                                //Last Node
                                                else if($j == $colspan)
                                                {
                                                    $tree_lines = "<tr>" . $tree_lines . "<td class=\"line left top\">&nbsp;</td><td class=\"line right\">&nbsp;</td></tr>";
                                                }

                                                //Middle Node
                                                else
                                                {
                                                    $tree_lines = $tree_lines . "<td class=\"line left top\">&nbsp;</td><td class=\"line right top\">&nbsp;</td>";
                                                }
                                            }
                                        }
                                
                                        //Exactly one child node
                                        else if($current_node_child_num == 1)
                                        {
                                            $line_down = "<tr><td colspan=\"$colspan\"><div class=\"line down\"></div></td></tr>";
                                            $tree_lines = "<tr><td class=\"line left\">&nbsp;</td><td class=\"line right\">&nbsp;</td></tr>";
                                        }
                                        

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
                                        
                                        //Generate HTML tree lines     
                                        $line_down = "";
                                        $tree_lines = "";
                                
                                        //More than two child nodes
                                        if($current_node_child_num >= 2)
                                        {
                                            $line_down = "<tr><td colspan=\"$colspan\"><div class=\"line down\"></div></td></tr>";

                                            for($j = 2; $j <= $colspan; $j = $j + 2)
                                            {
                                                //First Node
                                                if($j == 2)
                                                {
                                                    $tree_lines = "<td class=\"line left\">&nbsp;</td> <td class=\"line right top\">&nbsp;</td>";
                                                }

                                                //Last Node
                                                else if($j == $colspan)
                                                {
                                                    $tree_lines = "<tr>" . $tree_lines . "<td class=\"line left top\">&nbsp;</td><td class=\"line right\">&nbsp;</td></tr>";
                                                }

                                                //Middle Node
                                                else
                                                {
                                                    $tree_lines = $tree_lines . "<td class=\"line left top\">&nbsp;</td><td class=\"line right top\">&nbsp;</td>";
                                                }
                                            }
                                        }
                                
                                        //Exactly one child node
                                        else if($current_node_child_num == 1)
                                        {
                                            $line_down = "<tr><td colspan=\"$colspan\"><div class=\"line down\"></div></td></tr>";
                                            $tree_lines = "<tr><td class=\"line left\">&nbsp;</td><td class=\"line right\">&nbsp;</td></tr>";
                                        }

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
                        //Decrement the height
                        $tree_height--;
                    }//End While: Height Check
                    
                    //Display HTML story tree
                    echo $tree_html; 
                }//End If: Chapters Check 
                
                //Close database connection
                mysql_close($dblink);
            }//End If: Post Process Check
            
            //redirect to book page
            else
            {        
                header('Location:books.php?unset');
                exit();
            }
            
            //Create Chapter Form
            require('createchapter.php');
        ?>
    </body>
</html>
