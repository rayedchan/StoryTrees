<?php

/**
 * Description of ChapterNode
 * Represents node in a tree. Each node
 * contains data on a single chapter.
 * @author rchan
 */
class ChapterNode 
{
    //Variables for chapter information
    private $chapter_id;
    private $parent_id;
    private $height;
    private $title;
    private $author;
    private $create_date;
    private $last_modified;
    
    //constructor
    //@param Array Record of a chapter passed as an array
    public function __construct($record)
    {
        
    }
}

?>
