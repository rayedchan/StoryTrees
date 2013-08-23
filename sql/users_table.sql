USE heroku_1b0f41c846188ed;

CREATE TABLE users 
(
    usr_key int unsigned auto_increment not null,
    username varchar(30) not null,
    email varchar(254) not null,
    password varchar(128) not null,
    firstname varchar(30),
    lastname varchar(30),
    birthdate date,
    gender enum('M', 'F'),
    create_date timestamp,
    last_modified timestamp,

    primary key(usr_key, username, email)
);