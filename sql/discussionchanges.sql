
ALTER TABLE dbdiscussions ADD COLUMN category VARCHAR(50) DEFAULT 'general';

UPDATE dbdiscussions SET category = 'general' WHERE category IS NULL;

ALTER TABLE dbdiscussions DROP PRIMARY KEY;

ALTER TABLE dbdiscussions ADD PRIMARY KEY (author_id, title, category);

ALTER TABLE discussion_replies ADD COLUMN category VARCHAR(50) DEFAULT 'general';
