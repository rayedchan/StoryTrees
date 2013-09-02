USE heroku_1b0f41c846188ed;

INSERT INTO books (book_id, title, description, create_date, last_modified)
VALUES (NULL, 'Ray ''s Adventures', 'A story about my adventures.', 
CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);