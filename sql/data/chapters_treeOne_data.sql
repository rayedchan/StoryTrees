USE heroku_1b0f41c846188ed;

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(1, 1, NULL, 'Chapter 1', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 0);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(1, 2, 1, 'Chapter 2a', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(1, 3, 1, 'Chapter 2b', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(1, 4, 1, 'Chapter 2c', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(1, 5, 1, 'Chapter 2d', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(1, 6, 3, 'Chapter 3a', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 2);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(1, 7, 3, 'Chapter 3b', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 2);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(1, 8, 5, 'Chapter 3c', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 2);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(1, 9, 5, 'Chapter 3d', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 2);