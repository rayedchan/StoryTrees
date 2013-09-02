USE heroku_1b0f41c846188ed;

/*
* Retrieve all node's children
*/
SELECT * FROM chapters AS c1
LEFT JOIN chapters AS c2 ON
c1.chapter_id = c2. parent_id;

/*
*  Retrieve a single node's children
*/
SELECT * FROM chapters AS c1
LEFT JOIN chapters AS c2 ON
c1.chapter_id = c2.parent_id
WHERE c1.chapter_id = 1;

/*
* Retrieve node's parent
*/
SELECT * FROM chapters AS c1
JOIN chapters AS c2 ON
c1.parent_id = c2.chapter_id;

/*
* Retrieve all the leaf nodes in given tree
*/
SELECT * FROM chapters AS c1
LEFT JOIN chapters AS c2 ON
c1.chapter_id = c2.parent_id
WHERE c2.parent_id IS NULL;