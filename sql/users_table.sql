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

    primary key(usr_key),
    unique(username),
    unique(email)
);

/*Password column needs to increase in length in order to
store hashed password. Password Encryption - Hashing alogorithm
may change*/
ALTER TABLE users MODIFY password varchar(255);