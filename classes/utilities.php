<?php
/**
 * Description of utilities
 * @author rchan
 */
function sec_session_start() 
{
    $session_name = 'sec_session_id'; // Set a custom session name
    $secure = false; // Set to true if using https.
    $httponly = true; // This stops javascript being able to access the session id. 

    ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies. 
    $cookieParams = session_get_cookie_params(); // Gets current cookies params.
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
    session_name($session_name); // Sets the session name to the one set above.
    session_start(); // Start the php session
    session_regenerate_id(); // regenerated the session, delete the old one.  
}


/*
 * Checks if the user session is valid. This is to prevent session hijacking.
 * It is unlikely a user to change their browser mid-session.
 * @param resource  mysql_dblink    Database connection link
 */
function login_check($mysql_dblink) 
{
    // Check if all session variables are set
    if(isset($_SESSION['userkey'], $_SESSION['username'], $_SESSION['hashed_password'])) 
    {
        $userkey = $_SESSION['userkey'];
        $hashed_password = $_SESSION['hashed_password'];
        $username = $_SESSION['username'];
        $original_browser = $_SESSION['browser'];
        $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.

        $validate_user_query = "SELECT 1 FROM users WHERE 
            usr_key = '$userkey' AND password = '$hashed_password'
            AND username = '$username'";
        
        $result = mysql_query($validate_user_query, $mysql_dblink);
        $numrows = mysql_num_rows($result);
        
        //Validate user session
        if($numrows == 1 && $original_browser == $user_browser) 
        {
            return true;
        }
    }
     
    return false; 
}

/*
 * Generates the tree lines inorder to render the
 * tree in HTML
 * @param int  num_child_nodes    number of children node current node has
 * @param int  colspan            space distance; should be precalculated
 * @return array 
 *      tree_line => HTML_CONTENT
 *      tree_down => HTML CONTENT
 */
function generateTreeLinesHTML($num_child_nodes, $colspan)
{
    $html_tree_lines = array();
    
    //Generate HTML tree lines     
    $line_down = "";
    $tree_lines = "";

    //More than two child nodes
    if($num_child_nodes >= 2)
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
    else if($num_child_nodes == 1)
    {
        $line_down = "<tr><td colspan=\"$colspan\"><div class=\"line down\"></div></td></tr>";
        $tree_lines = "<tr><td class=\"line left\">&nbsp;</td><td class=\"line right\">&nbsp;</td></tr>";
    }
    
    $html_tree_lines['line_down'] = $line_down;
    $html_tree_lines['tree_lines'] = $tree_lines;
    
    return $html_tree_lines;
}

/*
 * @param array  paths       contains partial paths in a tree
 * @param int    currentId   Chapter Id of current node
 * @parm  int    parentId    Parent Id of the current node
 * @return array newly constructed $paths
 */
function prependParentIdToPath(&$paths, $currentId, $parentId)
{
    //directly modify array elements within the loop precede $value with &
    foreach($paths as &$value)
    {
        $pattern = "#^$currentId/#"; //Pound sign is used as a delimiter to indicate start and end of a regular expression
        $match = preg_match($pattern, $value);  //preg_match() returns 1 if the pattern matches given subject, 0 if it does not, or FALSE if an error occurred.
        
        //Check if currentId is in the current path by inspecting the first element of each constructed path
        if($match)
        {
            $value = $parentId . '/' .$value;
        }
    }
    
    unset($value); // break the reference with the last element; Reference of a $value and the last array element remain even after the foreach loop. It is recommended to destroy it by unset().
}

/*
 * Generate the HTML for each storyline in a tree.
 * @param  HashMap (int => String)  paths           contains all the paths in a tree. Each leaf node has an index (key). The value is in a specific String format (E.g Value 1/2/7) 
 * @param  HashMap (int => HashMap) chapters_map    conatins all the chapters. The chapter_id is used as the index. The value is a record of the resultset in hashmap form.
 * @return html of each storyline including each chapter info
 */
function generateHTMLStorylines($paths, $chapter_map)
{
    $delimiter = "/"; // Delimiter to tokenize
    $counter  = 1; // Keep track of next storyline
    $storylines_html = "";
    $atLeastOneElement = !empty($paths);
    
    //Iterate each path
    foreach($paths as $leaf_node_key => $single_path) 
    {
        $storylines_html = $storylines_html . "<hr /><h3>Storyline $counter (Leaf Node: $leaf_node_key)</h3>";
        
        //tokenize the path
        $token = strtok($single_path, $delimiter);
        
        //Inspect each node in a path
        while($token !== false) //This function may return Boolean FALSE, but may also return a non-Boolean value which evaluates to FALSE.
        {
            $chapter_node = $chapter_map[$token];
            $chapter_id = $chapter_node['chapter_id'];
            $title = $chapter_node['title'];
            $author = $chapter_node['author'];
            $create_date = $chapter_node['create_date'];
            $storylines_html = $storylines_html ."Chapter Id: $chapter_id <br />
                Title: $title <br />
                Author: $author <br />
                Create Date: $create_date <br /><br />";
            $token = strtok($delimiter); // Move internal pointer to next token
        }
        
        $counter++;
    }
    
    return $storylines_html . ($atLeastOneElement?"<hr />":"");
}

/*
 * Generates the HTML Tree Structure of a StoryTree.
 * This also builts an associtive containing all the possible paths in  a tree
 * and a hashmap containing each chapter record.
 * @param resource             dblink               connection yo database
 * @param int                  book_id              id of current book to generate the tree for
 * @param hashmap(int:String)  paths                constructs all possible paths; Leaf Nodes are keys. Path are constructed through tree level by level tranversal. (E.g. String format 1/5/7)   
 * @param hashMap(int:HashMap) $chapter_node_map    constructs a hashmap containing each chapter's attributes
 * @return HTML Tree
 * 
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
 * and the current node's content will be merge with its sibling nodes, which are nodes with
 * the same parent, that have aldready been inspected. The html content (determined by how jOrgChart plugin works) 
 * of each node will be store in a HashMap using its parent's key. Thus, sibling nodes' content
 * will merge together and should not interfere with cousin nodes. Eventually 
 * the content of every node will merge together and converge to the root node.
 * Each node will only be visited once. 
 * 
 * Example: This constructs an HTML Tree used in the jOrgChart Plugin. This
 * algorithm can be applied to generate the raw HTML that jOrgChart Plugin
 * generates for you.
 * 
 *         1                    H = 0
 *   ______|_________
 *   \     \    \    \
 *   2     3     4    5         H = 1
 * __|__       __|__
 * \    \      \    \
 *  6    7      8    9          H = 2
 * 
 * Data Structure: 
 *  HashMap: ParentId -> Constructed Content
 * 
 * Defintion:
 *  Leaf Node = has no children node
 *  Merger Node = has children node
 * 
 * Possible Cases:
 * 1. Empty tree
 * 2. Only the Root node exists
 * 3. Root Node with children node
 * 
 * Possiable Subcases:
 * 1. Leaf Node + First Apperance of its Parent Node in HashMap
 * 2. Leaf Node + Parent Node in HashMap exists (Merge with already inspected sibling node)
 * 3. Merger Node + First Apperance of its Parent Node in HashMap
 * 4. Merger Node + Parent Node in HashMap exists 
 * 
 * BEGIN: 
 *  OUTER LOOP: Iterate each node at height 2.
 *      First iteration at node 6 (Subcase 1):
 *      Insert 6's parent key into HashMap since it does not exist
 *          2 -> <li>6</li>
 *  
 *      Second iteration at node 7 (Subcase 2):
 *      Check if parent key exist in hashmap => Yes it does, so construct current node content and merge
 *          2 -> <li>6</li><li>7</li>
 * 
 *      Third iteration at node 8 (Subcase 1):
 *      Insert 8's parent key
 *          2 -> <li>6</li><li>7</li>
 *          4 -> <li>8</li>
 * 
 *      Fourth iteration at node 9 (Subcase 2):
 *      Merge content of 9 into 5.
 *          2 -> <li>6</li><li>7</li>
 *          4 -> <li>8</li><li>9</li>
 * 
 *  OUTER LOOP: Iterate each node at height 1.
 *      First iteration at node 2 (Subcase 3):
 *      Construct <li> tag with node 2 content and
 *      embed <ul> tag inside the <li> tag. The embedded <ul>
 *      contains all the descendent nodes includes children node and
 *      its child's children. For simplicity, HashMap[2] = <li>6</li><li>7</li>.
 *          2 -> <li>6</li><li>7</li>
 *          4 -> <li>8</li><li>9</li>
 *          1 -> <li>2<ul>HashMap[2]</ul></li>
 * 
 *      Second iteration at node 3 (Subcase 2)
 *          2 -> <li>6</li><li>7</li>    
 *          4 -> <li>8</li><li>9</li>
 *          1 -> <li>2<ul>HashMap[2]</ul></li><li>3</li>
 *      
 *      Third iteration at node 4 (Subcase 4):
 *      Wrap HashMap[4] = <li>8</li><li>9</li>
 *      with <ul> tags and after enclose with <li> 
 *      tags with current content inside. Append current content
 *      to the existing content in its parent node.
 *          2 -> <li>6</li><li>7</li>
 *          4 -> <li>8</li><li>9</li>
 *          1 -> <li>2<ul>HashMap[2]</ul></li><li>3</li><li>4<ul>HashMap[4]</ul></li>
 * 
 *      Fourth iteration at node 5 (Subcase 2) 
 *          2 -> <li>6</li><li>7</li>
 *          4 -> <li>8</li><li>9</li>
 *          1 -> <li>2<ul>HashMap[2]</ul></li><li>3</li><li>4<ul>HashMap[4]</ul></li><li>5</li>
 * 
 *  OUTER LOOP: Iterate each node at height 0.
 *      First iteration at Root Node:
 *          2 -> <li>6</li><li>7</li>
 *          4 -> <li>8</li><li>9</li>
 *          1 -> <ul><li>1<ul><li>2<ul>HashMap[2]</ul></li><li>3</li><li>4<ul>HashMap[4]</ul></li><li>5</li></ul></li></ul>   
 *  
 * Final HTML
 *  <ul>
 *      <li> 1
 *          <ul>
 *             <li>2<ul><li>6</li><li>7</li></ul></li>
 *             <li>3</li>
 *             <li>4<ul><li>8</li><li>9</li></ul></li>
 *             <li>5</li>
 *          </ul>
 *      </li>
 *  </ul>
 * END:
 * 
 * The tree paths are build during the HTML Tree generation.
 * The leaf node id is used as the index.
 * $paths:
 *      [6]: 1/2/6
 *      [7]: 1/2/7
 *      [3]: 1/3
 *      [8]: 1/4/8
 *      [9]: 1/4/9
 *      [5]: 1/5
 * Leaf node are added to array along with it parent.
 * When the parent node is detected, prepend its parent to
 * string.
 * 
 */   
function generateHTMLTree($dblink, $book_id, &$paths, &$chapter_node_map)
{
    $tree_html = null; //Store the HTML Tree
    
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

    //Iterate tree level by level starting at the highest height
    while($tree_height >= 0)
    {   
        //Retrieve all the chapters at a specific level for the selected book
        $chapters_query = "SELECT chapter_id, parent_id, height, title, author, 
            description, create_date, last_modified 
            FROM chapters WHERE book_id = $book_id AND height = $tree_height";
        $chapters_result_set = mysql_query($chapters_query, $dblink);

        //Case 1: Root Node
        if($tree_height == 0)
        {
            $row = mysql_fetch_assoc($chapters_result_set);
            $chapter_id = $row['chapter_id'];
            $parent_id = $row['parent_id'];
            $title = $row['title'];
            $isLeafNode = array_key_exists($chapter_id, $isLeafNodeMap); //Determines if current node is a leaf
            $chapter_node_map[$chapter_id] = $row; //Store chapter properties

            //Only the root node exists in tree
            if($isLeafNode)
            {
                 $tree_html = "<div align=\"center\" class=\"jOrgChart\">
                     <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                     <tbody><tr class=\"node-cells\"> <td class=\"node-cell\" colspan=\"2\">
                     <div class=\"node\" style=\"cursor: n-resize;\"><a href=\"#\" 
                     target=\"_blank\">$title</a><br /><font size=\"1px\">
                     <i>Chapter Id: $chapter_id</i></font></div></td></tr></tbody></table></div>";
                 $paths[$chapter_id] = $chapter_id; //Construct path
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
                $paths[$chapter_id] =  $parent_id . '/' . $chapter_id; //Construct path starting from the end
                $chapter_node_map[$chapter_id] = $row; //Store chapter properties

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
                $key_exists = array_key_exists($parent_id, $merger_node_data); //Determines if the parent node has been seen already
                $isLeafNode = array_key_exists($chapter_id, $isLeafNodeMap); //Determines if the current node is a leaf
                $chapter_node_map[$chapter_id] = $row; //Store chapter properties

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
                        $paths[$chapter_id] =  $parent_id . '/' . $chapter_id;
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

                        //Construct Tree paths
                        prependParentIdToPath($paths, $chapter_id, $parent_id);
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
                        $paths[$chapter_id] =  $parent_id . '/' . $chapter_id;
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

                        //Construct Tree paths
                        prependParentIdToPath($paths, $chapter_id, $parent_id);
                    }  
                }
            } 
        }

        $tree_height--; //Decrement the height
    }//End While: Height Check
    
    return $tree_html;
}
?>
