-- SCRUM-129: Fix clearance levels in boarddocuments
-- Remove 'public', change 'manager' to 'event_manager', set default to 'volunteer'

-- Step 1: Migrate any existing 'public' documents to 'volunteer'
UPDATE boarddocuments SET clearance_level = 'volunteer' WHERE clearance_level = 'public';

-- Step 2: Migrate any existing 'manager' documents to 'event_manager'
UPDATE boarddocuments SET clearance_level = 'event_manager' WHERE clearance_level = 'manager';

-- Step 3: Update the ENUM column to remove 'public' and rename 'manager' to 'event_manager'
ALTER TABLE `boarddocuments`
  MODIFY COLUMN `clearance_level`
  ENUM('volunteer','event_manager','board_member','admin','superadmin')
  NOT NULL DEFAULT 'volunteer';
