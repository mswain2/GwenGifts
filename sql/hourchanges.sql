ALTER TABLE dbpersonhours ADD COLUMN status ENUM('pending', 'approved') DEFAULT 'pending';
ALTER TABLE dbpersons ADD COLUMN total_hours_volunteered DECIMAL(8,2) DEFAULT 0;