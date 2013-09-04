USE heroku_1b0f41c846188ed;

/*
 * Adjacency List Tree Model
 * Each entry knows its immediate parent
 */

CREATE TABLE chapters
(
    book_id int unsigned not null,
    chapter_id int unsigned auto_increment primary key not null,
    parent_id int unsigned, -- null means root node
    height int,
    content blob,
    title varchar(100),
    author varchar(30),
    description varchar(100),
    create_date timestamp,
    last_modified timestamp,

    FOREIGN KEY (book_id) REFERENCES books (book_id), -- book_id must exist in books table
    CHECK (chapter_id <> parent_id) -- prohibit single node self cycle
);