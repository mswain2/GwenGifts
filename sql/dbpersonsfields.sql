ALTER TABLE dbpersons
ADD COLUMN profile_pic VARCHAR(512) DEFAULT 'images/usaicon.png',
ADD COLUMN force_password_change TINYINT(1) DEFAULT 0,
ADD COLUMN cpr_training_completion ENUM('yes','no') DEFAULT 'no',
ADD COLUMN aed_training_completion ENUM('yes','no') DEFAULT 'no',
ADD COLUMN has_disability ENUM('yes','no') DEFAULT 'no',
ADD COLUMN disability_specifications VARCHAR(255) DEFAULT NULL;