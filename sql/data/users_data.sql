USE heroku_1b0f41c846188ed;

INSERT INTO users (usr_key, username,email, password,
    firstname, lastname, birthdate, gender, create_date,
    last_modified) VALUES (NULL, 'rayedchan', 'rayedchan@gmail.com',
    'mypassword', 'Ray', 'Chan', '1990-01-01', 'M', CURRENT_TIMESTAMP, 
    CURRENT_TIMESTAMP);