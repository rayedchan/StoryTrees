<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.10.3.min.js"></script>
        <script type="text/javascript" src="js/jquery.jOrgChart.js"></script>
        <link rel="stylesheet" href="css/jquery.jOrgChart.css"/>
        <link rel="stylesheet" href="css/custom.css"/>
        <title>Story Tree</title>
        <script type="text/javascript">
            /*Call method to built the story tree diagram*/
            jQuery(document).ready(function() {
                 $("#org").jOrgChart();
            });
        </script>
    </head>

    <body>
        
        <?php
            //require_once('dbconnection.php');
            //connectToDB();
            //phpinfo();
            //echo '<a href="registration.php">Register</a>';
        ?>
        
        <a href="registration.php">Sign up</a> | <a href="login.php">Login</a>
        <!--Story Tree diagram -->
        <ul id="org" style="display:none">
            <li><a href="#" target="_blank">Chapter 1</a><br /><font size="1px"><i>Author: Ray</i></font>     
                <ul> 
                    <li><a href="#" target="_blank">Chapter 2a</a></li>
                    <li><a href="#" target="_blank">Chapter 2b</a>
                        <ul>
                          <li><a href="#" target="_blank">Chapter 3a</a></li>
                          <li><a href="#" target="_blank">Chapter 3b</a></li>
                        </ul>
                    </li>
                    <li><a href="#" target="_blank">Chapter 2c</a><br /><font size="1px"><i>Author: Anonymous</i></font></li>
                    <li><a href="#" target="_blank">Chapter 2d</a>
                        <ul>
                          <li><a href="#" target="_blank">Chapter 3c</a></li>
                          <li><a href="#" target="_blank">Chapter 3d</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </body>
</html>
