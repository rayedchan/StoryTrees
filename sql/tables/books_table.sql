USE heroku_1b0f41c846188ed;

CREATE TABLE books
(
    book_id int unsigned auto_increment primary key not null,
    title varchar(100),
    description varchar(200),
    create_date timestamp,
    last_modified timestamp
);