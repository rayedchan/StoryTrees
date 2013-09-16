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
?>
