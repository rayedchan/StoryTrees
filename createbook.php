<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Create a Book</title>
    </head>
    <body>
        <?php require('navigation.html'); ?>
        <h2>Create a Book</h2>
        <form method="post" action="post_scripts/post_createbook.php">
            <table>
                <tr>
                    <td>Book Title:</td> 
                    <td><input type="text" id="title" name="title" maxlength="100" value="" /></td>
                </tr>
                <tr>
                    <td>Description:</td> 
                    <td><textarea id="description" name="description" maxlength="200" value="" rows="10" cols="40"></textarea></td>
                </tr>
                <tr> 
                    <td>Genre:</td> 
                    <td><input type="text" id="genre" name="genre" maxlength="100" value="" /></td>
                </tr>
                <tr>
                    <td colspan="2" align="right"><input type="submit" name="submit" value="Create Book" style="height:25px; width:100px" /></td>
                </tr>
            </table>
        </form>
    </body>
</html>
