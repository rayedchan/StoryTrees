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
            //Use on Heroku Server to eliminate hard-coded values
            /*$url=parse_url(getenv("CLEARDB_DATABASE_URL"));
            $server = $url["host"];
            $username = $url["user"];
            $password = $url["pass"];
            $db = substr($url["path"],1);
            echo "$server " . "$username " . "$db ";*/
        
            require_once('dbconnection.php');
            $hostname = 'us-cdbr-east-04.cleardb.com';
            $username = 'b65aaecc03af97';
            $password = 'c79a70b5';
            $dbname = 'heroku_1b0f41c846188ed';
            $port = '3306';
            $socket = null;
            $dbconnection = new DbConnection($hostname, $username, $password, $dbname, $port, $socket, MYSQLI_CLIENT_SSL);
            $link = $dbconnection->getMySQLConnectionResource();
            //$dbconnection ->testQuery($link);
            $dbconnection ->closeMySQLConnection($link);
            require('navigation.html');
        ?>
        
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
