-- Sprint 4: Add board_event column to dbevents
-- Run this in phpMyAdmin on gwengiftsdb if you haven't already
ALTER TABLE `dbevents`
  ADD COLUMN IF NOT EXISTS `board_event` TINYINT(1) NOT NULL DEFAULT 0 AFTER `completed`;
