USE heroku_1b0f41c846188ed;

INSERT INTO books (book_id, title, description, create_date, last_modified)
VALUES (1, 'Ray ''s Adventures', 'A story about my adventures.', 
CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

INSERT INTO books (book_id, title, description, create_date, last_modified)
VALUES (2, 'Time Machine', 'Travel back to the past.', 
CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

INSERT INTO books (book_id, title, description, create_date, last_modified)
VALUES (3, 'Infinite Loop', 'Stuck in a loop.', 
CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);