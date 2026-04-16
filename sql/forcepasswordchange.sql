-- Adds force_password_change column to dbpersons table
-- Run this if database already exists and you need the new column
-- Safe to run multiple times (IF NOT EXISTS)

ALTER TABLE dbpersons ADD COLUMN IF NOT EXISTS force_password_change TINYINT(1) DEFAULT 0;