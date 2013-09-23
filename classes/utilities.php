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
 * Displays each storyline in a tree.
 * @param  HashMap (int => String)  paths           contains all the paths in a tree. Each leaf node has an index (key). The value is in a specific String format (E.g Value 1/2/7) 
 * @param  HashMap (int => HashMap) chapters_map    conatins all the chapters. The chapter_id is used as the index. The value is a record of the resultset in hashmap form.
 */
function displayAllStorylines($paths, $chapter_map)
{
    $delimiter = "/"; // Delimiter to tokenize
    $counter  = 1; // Keep track of next storyline
    $storylines_html = "";
    
    //Iterate each path
    foreach($paths as $leaf_node_key => $single_path) 
    {
        echo "<h3>Storyline $counter</h3>";
        
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
            echo "Chapter Id: $chapter_id <br />
                Title: $title <br />
                Author: $author <br />
                Create Date: $create_date <br />";
            $token = strtok($delimiter); // Move internal pointer to next token
        }
        
        echo '<br />';
        $counter++;
    }
}

?>
