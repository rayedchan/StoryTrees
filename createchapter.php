<!-- Add Chapter Form -->
<form method="post" action="post_scripts/post_createchapter.php">
    <table>
        <tr>
            <td>Book Id:</td> 
            <td><input type="text" name="bookid" id="bookid" value="<?php echo $book_id; ?>" /></td>
        </tr>
        <tr>
            <td>Title:</td> 
            <td><input type="text" name="title" id="title" maxlength="100" /></td>
        </tr>
        
        <!--Case if a chapter exist -->
        <?php if($chapters_num_rows != 0){ ?>
            <tr>
                <td>Selected Chapter Id:</td> 
                <td><input type="text" name="chapterid" id="chapterid" /></td>
            </tr>
            <tr>
                <td><input type="radio" name="createtype" id="createtype" value="1" />Extend</td>
                <td><input type="radio" name="createtype" id="createtype" value="2" />Parallel</td>
            </tr>
        <?php } ?>
        
        <tr>
            <td colspan="2" align="right"><input type="submit" value="Create Chapter"></td>
        </tr>
    </table>
</form>
