USE heroku_1b0f41c846188ed;

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(3, 11, NULL, 'Chapter 1', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 0);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(3, 12, 11, 'Chapter 2a', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(3, 13, 11, 'Chapter 2b', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(3, 14, 11, 'Chapter 2c', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(3, 15, 11, 'Chapter 2d', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(3, 20, 11, 'Chapter 2e', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(3, 16, 13, 'Chapter 3a', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 2);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(3, 17, 13, 'Chapter 3b', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 2);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(3, 18, 15, 'Chapter 3c', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 2);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(3, 19, 15, 'Chapter 3d', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 2);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(3, 21, 20, 'Chapter 3e', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 2);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(3, 22, 15, 'Chapter 3f', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 2);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(3, 23, 16, 'Chapter 4a', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 3);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(3, 24, 16, 'Chapter 4b', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 3);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(3, 25, 16, 'Chapter 4c', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 3);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(3, 27, 19, 'Chapter 4d', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 3);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(3, 26, 21, 'Chapter 4e', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 3);

INSERT INTO chapters (book_id, chapter_id, parent_id, 
title, author, description, create_date, last_modified, height) VALUES
(3, 28, 19, 'Chapter 4f', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 3);