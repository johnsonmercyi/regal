use regal_tulip;

# TEST START
# These are all for test
SELECT * FROM section_heads;
SELECT * FROM termly_comments;
SELECT * FROM staff;
SELECT * FROM form_teachers;
SELECT * FROM school_classes;
SELECT * FROM school_sections;
DELETE FROM school_sections WHERE id=4;

INSERT INTO form_teachers (global_id, academic_session_id, school_id, class_id, staff_id)
VALUES (NULL, 10, 1, 7, 1009);

INSERT INTO section_heads (global_id, section_id, school_id, name, signature)
VALUES (NULL, 1, 1, 'Johnson Mercy Ibenye', '');
INSERT INTO section_heads (global_id, section_id, school_id, name, signature)
VALUES (NULL, 2, 1, 'John Doe', '');
INSERT INTO section_heads (global_id, section_id, school_id, name, signature)
VALUES (NULL, 3, 1, 'Mary Jane', '');
# TEST END

# In the Laravel project
# Run create_section_heads_table migration file
# command: php artisan migrate --path=database/migrations/2023_11_02_124126_create_section_heads_table.
# php
# Run add_section_head_id_to_termly_comments migration file
# command: php artisan migrate --path=database/migrations/2023_11_02_131848_add_section_head_id_to_termly_comments.
# php

ALTER TABLE school_sections MODIFY sectionHead VARCHAR(255) NULL;
ALTER TABLE school_sections MODIFY sectionHeadSign VARCHAR(255) NULL;

SELECT * FROM schools;
UPDATE schools SET name='REGAL TULIP SCHOOL HILLVIEW ESTATE NKWELLE EZUNAKA';
