<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.10.3.min.js"></script>
        <script type="text/javascript" src="js/jquery.jOrgChart.js"></script>
        <link rel="stylesheet" href="css/jquery.jOrgChart.css"/>
        <link rel="stylesheet" href="css/custom.css"/>
        <title>Chapters</title>
        <script type="text/javascript">
            /*Call method to built the story tree diagram*/
            jQuery(document).ready(function() {
                 $("#org").jOrgChart();
            });
        </script>
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
                echo "Book Id: " . $book_id;
                echo "<br />";
                
                //Database connection
                $dblink = quickMySQLConnect();
                
                //Retrieve the max height of the tree
                $tree_height_query = "SELECT MAX(height) as max_height
                    FROM chapters WHERE book_id = $book_id LIMIT 1";
                $height_result = mysql_query($tree_height_query, $dblink) or die(mysql_error());
                $height_row = mysql_fetch_assoc($height_result);
                $tree_height = $height_row['max_height'];
                $max_height = $$tree_height;
                echo "Tree Height: " . $tree_height;
                echo "<br />";
                
                //Retrieve all the leaf node in a given tree
                $leaf_node_query = "SELECT c1.chapter_id as chapter_id, c1.title as title FROM chapters AS c1
                    LEFT JOIN chapters AS c2 ON
                    c1.chapter_id = c2.parent_id
                    WHERE c2.parent_id IS NULL AND c1.book_id = $book_id";
                $leaf_node_result = mysql_query($leaf_node_query, $dblink);
                $num_leaf_nodes = mysql_numrows($leaf_node_result);
                echo "Number of Leaf Nodes: $num_leaf_nodes <br />";
                $isLeafNodeMap = array(); //Associative Array to store leaf nodes; ChapterId(NodeId) => Title
                for($i = 1; $i <= $num_leaf_nodes; $i++)
                {
                    $leaf_node_record = mysql_fetch_assoc($leaf_node_result);
                    $nodeId = $leaf_node_record['chapter_id'];
                    $title = $leaf_node_record['title'];
                    $isLeafNodeMap[$nodeId] = $title;
                }
                //print_r($isLeafNodeMap);
                echo "<br /> <br />";
                
                //HashMap to store the merged results of nodes; Use ParentId as the Index
                $merger_node_data = array();
                
                //Store the HTML Tree
                $tree_html = null;

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
                             $tree_html = "<ul id=\"org\" style=\"display:none\"><li><a href=\"#\">$title</a><br /><font size=\"1px\"><i>Chapter Id: $chapter_id</i></font></li></ul>";
                        }
                        
                        else
                        {
                             //Merge root node with the entire descendent subtree
                             $child_node_content = $merger_node_data[$chapter_id];
                             $parent_node_content = "<a href=\"#\">$title</a><br /><font size=\"1px\"><i>Chapter Id: $chapter_id</i></font>";
                             $tree_html = "<ul id=\"org\" style=\"display:none\"><li>$parent_node_content<ul>$child_node_content</ul></li></ul>";
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
                                $new_content = "<li><a href=\"#\">$title</a><br /><font size=\"1px\"><i>Chapter Id: $chapter_id</i></font></li>";
                                $merged_content = $old_content . $new_content;
                                $merger_node_data[$parent_id] = $merged_content;
                            }
                            
                            //First Child Node Case
                            else
                            {
                                $merger_node_data[$parent_id] = "<li><a href=\"#\">$title</a><br /><font size=\"1px\"><i>Chapter Id: $chapter_id</i></font></li>";
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
                                    $new_node_content = "<li><a href=\"#\">$title</a><br /><font size=\"1px\"><i>Chapter Id: $chapter_id</i></font></li>";
                                    $merged_content = $current_constructed_content . $new_node_content;
                                    $merger_node_data[$parent_id] = $merged_content;
                                }
                                              
                                //Merger Node Case
                                else
                                {
                                    //Merge child nodes of the current node content with current node's content (parent)
                                    $child_node_content = $merger_node_data[$chapter_id];
                                    $parent_node_content = "<a href=\"#\">$title</a><br /><font size=\"1px\"><i>Chapter Id: $chapter_id</i></font>";
                                    $merged_descendant_content = "<li>$parent_node_content<ul>$child_node_content</ul></li>";
                                    
                                    //Merge with sibling nodes which already have been processed
                                    $current_constructed_content = $merger_node_data[$parent_id];
                                    $merger_node_data[$parent_id] = $current_constructed_content . $merged_descendant_content;
                                }  
                            }
                            
                            //First Child Node of New Parent Case
                            else
                            {
                                //Leaf Node Case
                                if($isLeafNode)
                                {
                                    $merger_node_data[$parent_id] = "<li><a href=\"#\">$title</a><br /><font size=\"1px\"><i>Chapter Id: $chapter_id</i></font></li>";
                                }
                                
                                //Merger Node Case
                                else
                                {
                                    //Merge current node's child nodes content with current node's content (parent)
                                    $child_node_content = $merger_node_data[$chapter_id];
                                    $parent_node_content = "<a href=\"#\">$title</a><br /><font size=\"1px\"><i>Chapter Id: $chapter_id</i></font>";//The parent node is currently being inspected
                                    $merged_content = "<li>$parent_node_content<ul>$child_node_content</ul></li>";
                                    $merger_node_data[$parent_id] = $merged_content;
                                }  
                            }
                        } 
                    }
                    
                    //Decrement the height
                    $tree_height--;
                }
                
                //Display HTML story tree
                echo $tree_html;
                
                //Close database connection
                mysql_close($dblink);
            }
            
            //redirect to book page
        ?>
    </body>
</html>
