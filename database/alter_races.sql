ALTER TABLE Racing.Race
ADD COLUMN isPublished tinyint(1) NOT NULL AFTER laps;
ALTER TABLE Racing.Race
ADD KEY (isPublished);

ALTER TABLE Racing.Race
MODIFY COLUMN isPublished tinyint(1) NOT NULL DEFAULT 0 AFTER laps;
