-- =============================================================
-- Test data for report generation
-- Run this AFTER the base schema is in place.
-- Covers: dbpersons, dbevents, dbeventpersons, dbpersonhours
-- =============================================================

-- ----- VOLUNTEERS (10 active, 3 inactive) -----

INSERT INTO `dbpersons`
  (`id`, `start_date`, `first_name`, `last_name`, `email`, `phone1`, `type`, `status`, `archived`, `password`, `state`, `zip_code`, `gender`)
VALUES
  ('vol_alice',   '2025-10-05', 'Alice',   'Johnson',  'alice@test.com',   '5551110001', 'volunteer', 'Active',   0, '$2y$10$DUMMY', 'VA', '22401', 'Female'),
  ('vol_bob',     '2025-10-12', 'Bob',     'Martinez', 'bob@test.com',     '5551110002', 'volunteer', 'Active',   0, '$2y$10$DUMMY', 'VA', '22401', 'Male'),
  ('vol_carol',   '2025-11-01', 'Carol',   'Davis',    'carol@test.com',   '5551110003', 'volunteer', 'Active',   0, '$2y$10$DUMMY', 'VA', '22402', 'Female'),
  ('vol_dan',     '2025-11-20', 'Dan',     'Wilson',   'dan@test.com',     '5551110004', 'volunteer', 'Active',   0, '$2y$10$DUMMY', 'VA', '22402', 'Male'),
  ('vol_emma',    '2025-12-10', 'Emma',    'Brown',    'emma@test.com',    '5551110005', 'volunteer', 'Active',   0, '$2y$10$DUMMY', 'VA', '22401', 'Female'),
  ('vol_frank',   '2026-01-08', 'Frank',   'Taylor',   'frank@test.com',   '5551110006', 'volunteer', 'Active',   0, '$2y$10$DUMMY', 'VA', '22403', 'Male'),
  ('vol_grace',   '2026-01-22', 'Grace',   'Anderson', 'grace@test.com',   '5551110007', 'volunteer', 'Active',   0, '$2y$10$DUMMY', 'VA', '22401', 'Female'),
  ('vol_hank',    '2026-02-14', 'Hank',    'Thomas',   'hank@test.com',    '5551110008', 'volunteer', 'Active',   0, '$2y$10$DUMMY', 'VA', '22402', 'Male'),
  ('vol_iris',    '2026-03-01', 'Iris',    'Jackson',  'iris@test.com',    '5551110009', 'volunteer', 'Active',   0, '$2y$10$DUMMY', 'VA', '22403', 'Female'),
  ('vol_jack',    '2026-03-20', 'Jack',    'White',    'jack@test.com',    '5551110010', 'volunteer', 'Active',   0, '$2y$10$DUMMY', 'VA', '22401', 'Male'),
  ('vol_karen',   '2025-10-15', 'Karen',   'Lee',      'karen@test.com',   '5551110011', 'volunteer', 'Inactive', 0, '$2y$10$DUMMY', 'VA', '22401', 'Female'),
  ('vol_leo',     '2025-11-05', 'Leo',     'Harris',   'leo@test.com',     '5551110012', 'volunteer', 'Inactive', 0, '$2y$10$DUMMY', 'VA', '22402', 'Male'),
  ('vol_mia',     '2025-12-20', 'Mia',     'Clark',    'mia@test.com',     '5551110013', 'volunteer', 'Inactive', 0, '$2y$10$DUMMY', 'VA', '22403', 'Female')
ON DUPLICATE KEY UPDATE id=id;


-- ----- EVENTS (6 events spanning Oct 2025 – Mar 2026) -----

INSERT INTO `dbevents`
  (`id`, `name`, `abbr_name`, `type`, `startDate`, `startTime`, `endTime`, `endDate`, `timezone`, `description`, `capacity`, `location`, `access`, `completed`, `board_event`, `series_id`, `recurrence_interval_days`)
VALUES
  (9001, 'Fall Cleanup Day',        'Fall Cleanup',  'Normal', '2025-10-18', '09:00', '13:00', '2025-10-18', 'America/New_York', 'Community park cleanup',          20, 'City Park',        'Public', 'Y', 0, NULL, 0),
  (9002, 'Holiday Gift Wrapping',   'Gift Wrap',     'Normal', '2025-12-06', '10:00', '15:00', '2025-12-06', 'America/New_York', 'Wrap gifts for families',         15, 'Community Center',  'Public', 'Y', 0, NULL, 0),
  (9003, 'Winter Food Drive',       'Food Drive',    'Normal', '2026-01-17', '08:00', '12:00', '2026-01-17', 'America/New_York', 'Collect and sort food donations',  25, 'Food Bank',         'Public', 'Y', 0, NULL, 0),
  (9004, 'Valentine Card Making',   'Cards',         'Normal', '2026-02-08', '13:00', '16:00', '2026-02-08', 'America/New_York', 'Make cards for nursing homes',     12, 'Library',           'Public', 'Y', 0, NULL, 0),
  (9005, 'Spring Trail Restoration','Trail Work',    'Normal', '2026-03-14', '08:00', '14:00', '2026-03-14', 'America/New_York', 'Repair and mark hiking trails',    18, 'State Park',        'Public', 'N', 0, NULL, 0),
  (9006, 'Literacy Tutoring',       'Tutoring',      'Normal', '2026-03-28', '15:00', '18:00', '2026-03-28', 'America/New_York', 'After-school reading tutoring',    10, 'Elementary School', 'Public', 'N', 0, NULL, 0)
ON DUPLICATE KEY UPDATE id=id;


-- ----- EVENT SIGNUPS & ATTENDANCE (dbeventpersons) -----
-- Mix of attended (1) and no-shows (0) across events

INSERT INTO `dbeventpersons` (`eventID`, `userID`, `notes`, `attended`) VALUES
-- Event 9001: Fall Cleanup (Oct) — 8 signups, 6 attended
(9001, 'vol_alice',  '', 1),
(9001, 'vol_bob',    '', 1),
(9001, 'vol_carol',  '', 1),
(9001, 'vol_dan',    '', 0),
(9001, 'vol_emma',   '', 1),
(9001, 'vol_karen',  '', 1),
(9001, 'vol_leo',    '', 0),
(9001, 'vol_mia',    '', 1),

-- Event 9002: Gift Wrapping (Dec) — 7 signups, 5 attended
(9002, 'vol_alice',  '', 1),
(9002, 'vol_bob',    '', 1),
(9002, 'vol_carol',  '', 0),
(9002, 'vol_emma',   '', 1),
(9002, 'vol_frank',  '', 1),
(9002, 'vol_karen',  '', 0),
(9002, 'vol_mia',    '', 1),

-- Event 9003: Food Drive (Jan) — 9 signups, 7 attended
(9003, 'vol_alice',  '', 1),
(9003, 'vol_bob',    '', 1),
(9003, 'vol_carol',  '', 1),
(9003, 'vol_dan',    '', 1),
(9003, 'vol_emma',   '', 1),
(9003, 'vol_frank',  '', 1),
(9003, 'vol_grace',  '', 1),
(9003, 'vol_karen',  '', 0),
(9003, 'vol_leo',    '', 0),

-- Event 9004: Valentine Cards (Feb) — 6 signups, 5 attended
(9004, 'vol_alice',  '', 1),
(9004, 'vol_carol',  '', 1),
(9004, 'vol_emma',   '', 1),
(9004, 'vol_grace',  '', 1),
(9004, 'vol_hank',   '', 1),
(9004, 'vol_mia',    '', 0),

-- Event 9005: Trail Restoration (Mar) — 10 signups, 8 attended
(9005, 'vol_alice',  '', 1),
(9005, 'vol_bob',    '', 1),
(9005, 'vol_carol',  '', 1),
(9005, 'vol_dan',    '', 1),
(9005, 'vol_frank',  '', 1),
(9005, 'vol_grace',  '', 1),
(9005, 'vol_hank',   '', 0),
(9005, 'vol_iris',   '', 1),
(9005, 'vol_jack',   '', 1),
(9005, 'vol_leo',    '', 0),

-- Event 9006: Tutoring (Mar) — 5 signups, 4 attended
(9006, 'vol_alice',  '', 1),
(9006, 'vol_emma',   '', 1),
(9006, 'vol_grace',  '', 1),
(9006, 'vol_iris',   '', 1),
(9006, 'vol_jack',   '', 0);


-- ----- VOLUNTEER HOURS (dbpersonhours) -----
-- Realistic check-in/check-out timestamps matching event dates/times
-- Hours vary per volunteer to create a meaningful leaderboard

INSERT INTO `dbpersonhours` (`personID`, `eventID`, `start_time`, `end_time`, `status`) VALUES

-- Event 9001: Fall Cleanup — Oct 18, 2025, 09:00-13:00 ET (UTC-4)
('vol_alice', 9001, '2025-10-18 13:00:00', '2025-10-18 17:00:00', 'approved'),   -- 4h
('vol_bob',   9001, '2025-10-18 13:00:00', '2025-10-18 16:30:00', 'approved'),   -- 3.5h
('vol_carol', 9001, '2025-10-18 13:30:00', '2025-10-18 17:00:00', 'approved'),   -- 3.5h
('vol_emma',  9001, '2025-10-18 13:00:00', '2025-10-18 15:00:00', 'approved'),   -- 2h
('vol_karen', 9001, '2025-10-18 13:00:00', '2025-10-18 17:00:00', 'approved'),   -- 4h
('vol_mia',   9001, '2025-10-18 14:00:00', '2025-10-18 17:00:00', 'approved'),   -- 3h

-- Event 9002: Gift Wrapping — Dec 6, 2025, 10:00-15:00 ET (UTC-5)
('vol_alice', 9002, '2025-12-06 15:00:00', '2025-12-06 20:00:00', 'approved'),   -- 5h
('vol_bob',   9002, '2025-12-06 15:00:00', '2025-12-06 18:00:00', 'approved'),   -- 3h
('vol_emma',  9002, '2025-12-06 15:00:00', '2025-12-06 19:00:00', 'approved'),   -- 4h
('vol_frank', 9002, '2025-12-06 16:00:00', '2025-12-06 20:00:00', 'approved'),   -- 4h
('vol_mia',   9002, '2025-12-06 15:00:00', '2025-12-06 17:30:00', 'approved'),   -- 2.5h

-- Event 9003: Food Drive — Jan 17, 2026, 08:00-12:00 ET (UTC-5)
('vol_alice', 9003, '2026-01-17 13:00:00', '2026-01-17 17:00:00', 'approved'),   -- 4h
('vol_bob',   9003, '2026-01-17 13:00:00', '2026-01-17 17:00:00', 'approved'),   -- 4h
('vol_carol', 9003, '2026-01-17 13:00:00', '2026-01-17 16:00:00', 'approved'),   -- 3h
('vol_dan',   9003, '2026-01-17 13:00:00', '2026-01-17 15:30:00', 'approved'),   -- 2.5h
('vol_emma',  9003, '2026-01-17 13:00:00', '2026-01-17 17:00:00', 'approved'),   -- 4h
('vol_frank', 9003, '2026-01-17 13:30:00', '2026-01-17 17:00:00', 'approved'),   -- 3.5h
('vol_grace', 9003, '2026-01-17 14:00:00', '2026-01-17 17:00:00', 'approved'),   -- 3h

-- Event 9004: Valentine Cards — Feb 8, 2026, 13:00-16:00 ET (UTC-5)
('vol_alice', 9004, '2026-02-08 18:00:00', '2026-02-08 21:00:00', 'approved'),   -- 3h
('vol_carol', 9004, '2026-02-08 18:00:00', '2026-02-08 20:30:00', 'approved'),   -- 2.5h
('vol_emma',  9004, '2026-02-08 18:00:00', '2026-02-08 21:00:00', 'approved'),   -- 3h
('vol_grace', 9004, '2026-02-08 18:30:00', '2026-02-08 21:00:00', 'approved'),   -- 2.5h
('vol_hank',  9004, '2026-02-08 18:00:00', '2026-02-08 20:00:00', 'approved'),   -- 2h

-- Event 9005: Trail Restoration — Mar 14, 2026, 08:00-14:00 ET (UTC-4)
('vol_alice', 9005, '2026-03-14 12:00:00', '2026-03-14 18:00:00', 'approved'),   -- 6h
('vol_bob',   9005, '2026-03-14 12:00:00', '2026-03-14 17:00:00', 'approved'),   -- 5h
('vol_carol', 9005, '2026-03-14 12:00:00', '2026-03-14 16:00:00', 'approved'),   -- 4h
('vol_dan',   9005, '2026-03-14 12:00:00', '2026-03-14 15:00:00', 'approved'),   -- 3h
('vol_frank', 9005, '2026-03-14 13:00:00', '2026-03-14 18:00:00', 'approved'),   -- 5h
('vol_grace', 9005, '2026-03-14 12:00:00', '2026-03-14 17:00:00', 'approved'),   -- 5h
('vol_iris',  9005, '2026-03-14 12:00:00', '2026-03-14 18:00:00', 'approved'),   -- 6h
('vol_jack',  9005, '2026-03-14 12:00:00', '2026-03-14 16:30:00', 'approved'),   -- 4.5h

-- Event 9006: Tutoring — Mar 28, 2026, 15:00-18:00 ET (UTC-4)
('vol_alice', 9006, '2026-03-28 19:00:00', '2026-03-28 22:00:00', 'approved'),   -- 3h
('vol_emma',  9006, '2026-03-28 19:00:00', '2026-03-28 21:30:00', 'approved'),   -- 2.5h
('vol_grace', 9006, '2026-03-28 19:00:00', '2026-03-28 22:00:00', 'approved'),   -- 3h
('vol_iris',  9006, '2026-03-28 19:00:00', '2026-03-28 21:00:00', 'approved');   -- 2h


-- ----- Sync total_hours_volunteered on dbpersons -----
-- Alice:  4+5+4+3+6+3       = 25
-- Bob:    3.5+3+4+5          = 15.5
-- Carol:  3.5+3+2.5+4        = 13
-- Dan:    2.5+3              = 5.5
-- Emma:   2+4+4+3+2.5        = 15.5
-- Frank:  4+3.5+5            = 12.5
-- Grace:  3+2.5+5+3          = 13.5
-- Hank:   2                  = 2
-- Iris:   6+2                = 8
-- Jack:   4.5                = 4.5
-- Karen:  4                  = 4
-- Leo:    0                  = 0
-- Mia:    3+2.5              = 5.5

UPDATE `dbpersons` SET `total_hours_volunteered` = 25.00  WHERE `id` = 'vol_alice';
UPDATE `dbpersons` SET `total_hours_volunteered` = 15.50  WHERE `id` = 'vol_bob';
UPDATE `dbpersons` SET `total_hours_volunteered` = 13.00  WHERE `id` = 'vol_carol';
UPDATE `dbpersons` SET `total_hours_volunteered` = 5.50   WHERE `id` = 'vol_dan';
UPDATE `dbpersons` SET `total_hours_volunteered` = 15.50  WHERE `id` = 'vol_emma';
UPDATE `dbpersons` SET `total_hours_volunteered` = 12.50  WHERE `id` = 'vol_frank';
UPDATE `dbpersons` SET `total_hours_volunteered` = 13.50  WHERE `id` = 'vol_grace';
UPDATE `dbpersons` SET `total_hours_volunteered` = 2.00   WHERE `id` = 'vol_hank';
UPDATE `dbpersons` SET `total_hours_volunteered` = 8.00   WHERE `id` = 'vol_iris';
UPDATE `dbpersons` SET `total_hours_volunteered` = 4.50   WHERE `id` = 'vol_jack';
UPDATE `dbpersons` SET `total_hours_volunteered` = 4.00   WHERE `id` = 'vol_karen';
UPDATE `dbpersons` SET `total_hours_volunteered` = 0.00   WHERE `id` = 'vol_leo';
UPDATE `dbpersons` SET `total_hours_volunteered` = 5.50   WHERE `id` = 'vol_mia';


-- ----- ATTENDANCE (dbattendance) -----
-- Mirrors dbeventpersons attended values; loggedById = 'bokchoyy' (existing event manager)

INSERT INTO `dbattendance` (`eventId`, `userId`, `loggedById`, `attended`, `attendanceNote`) VALUES
-- Event 9001: Fall Cleanup
(9001, 'vol_alice', 'bokchoyy', 1, NULL),
(9001, 'vol_bob',   'bokchoyy', 1, NULL),
(9001, 'vol_carol', 'bokchoyy', 1, NULL),
(9001, 'vol_dan',   'bokchoyy', 0, 'No-show, no notice'),
(9001, 'vol_emma',  'bokchoyy', 1, NULL),
(9001, 'vol_karen', 'bokchoyy', 1, NULL),
(9001, 'vol_leo',   'bokchoyy', 0, 'No-show'),
(9001, 'vol_mia',   'bokchoyy', 1, NULL),
-- Event 9002: Gift Wrapping
(9002, 'vol_alice', 'bokchoyy', 1, NULL),
(9002, 'vol_bob',   'bokchoyy', 1, NULL),
(9002, 'vol_carol', 'bokchoyy', 0, 'Called in sick'),
(9002, 'vol_emma',  'bokchoyy', 1, NULL),
(9002, 'vol_frank', 'bokchoyy', 1, NULL),
(9002, 'vol_karen', 'bokchoyy', 0, 'No-show'),
(9002, 'vol_mia',   'bokchoyy', 1, NULL),
-- Event 9003: Food Drive
(9003, 'vol_alice', 'bokchoyy', 1, NULL),
(9003, 'vol_bob',   'bokchoyy', 1, NULL),
(9003, 'vol_carol', 'bokchoyy', 1, NULL),
(9003, 'vol_dan',   'bokchoyy', 1, NULL),
(9003, 'vol_emma',  'bokchoyy', 1, NULL),
(9003, 'vol_frank', 'bokchoyy', 1, NULL),
(9003, 'vol_grace', 'bokchoyy', 1, NULL),
(9003, 'vol_karen', 'bokchoyy', 0, 'No-show'),
(9003, 'vol_leo',   'bokchoyy', 0, 'No-show'),
-- Event 9004: Valentine Cards
(9004, 'vol_alice', 'bokchoyy', 1, NULL),
(9004, 'vol_carol', 'bokchoyy', 1, NULL),
(9004, 'vol_emma',  'bokchoyy', 1, NULL),
(9004, 'vol_grace', 'bokchoyy', 1, NULL),
(9004, 'vol_hank',  'bokchoyy', 1, NULL),
(9004, 'vol_mia',   'bokchoyy', 0, 'Cancelled last minute'),
-- Event 9005: Trail Restoration
(9005, 'vol_alice', 'bokchoyy', 1, NULL),
(9005, 'vol_bob',   'bokchoyy', 1, NULL),
(9005, 'vol_carol', 'bokchoyy', 1, NULL),
(9005, 'vol_dan',   'bokchoyy', 1, NULL),
(9005, 'vol_frank', 'bokchoyy', 1, NULL),
(9005, 'vol_grace', 'bokchoyy', 1, NULL),
(9005, 'vol_hank',  'bokchoyy', 0, 'Injury'),
(9005, 'vol_iris',  'bokchoyy', 1, NULL),
(9005, 'vol_jack',  'bokchoyy', 1, NULL),
(9005, 'vol_leo',   'bokchoyy', 0, 'No-show'),
-- Event 9006: Tutoring
(9006, 'vol_alice', 'bokchoyy', 1, NULL),
(9006, 'vol_emma',  'bokchoyy', 1, NULL),
(9006, 'vol_grace', 'bokchoyy', 1, NULL),
(9006, 'vol_iris',  'bokchoyy', 1, NULL),
(9006, 'vol_jack',  'bokchoyy', 0, 'Schedule conflict');


-- ----- SHIFTS (dbshifts) -----
-- One row per volunteer per event, with date/times matching dbpersonhours

INSERT INTO `dbshifts` (`person_id`, `date`, `startTime`, `endTime`, `totalHours`, `description`) VALUES
-- Event 9001: Fall Cleanup — Oct 18, 2025
('vol_alice', '2025-10-18', '09:00:00', '13:00:00', 4.00,  'Fall Cleanup Day'),
('vol_bob',   '2025-10-18', '09:00:00', '12:30:00', 3.50,  'Fall Cleanup Day'),
('vol_carol', '2025-10-18', '09:30:00', '13:00:00', 3.50,  'Fall Cleanup Day'),
('vol_emma',  '2025-10-18', '09:00:00', '11:00:00', 2.00,  'Fall Cleanup Day'),
('vol_karen', '2025-10-18', '09:00:00', '13:00:00', 4.00,  'Fall Cleanup Day'),
('vol_mia',   '2025-10-18', '10:00:00', '13:00:00', 3.00,  'Fall Cleanup Day'),
-- Event 9002: Gift Wrapping — Dec 6, 2025
('vol_alice', '2025-12-06', '10:00:00', '15:00:00', 5.00,  'Holiday Gift Wrapping'),
('vol_bob',   '2025-12-06', '10:00:00', '13:00:00', 3.00,  'Holiday Gift Wrapping'),
('vol_emma',  '2025-12-06', '10:00:00', '14:00:00', 4.00,  'Holiday Gift Wrapping'),
('vol_frank', '2025-12-06', '11:00:00', '15:00:00', 4.00,  'Holiday Gift Wrapping'),
('vol_mia',   '2025-12-06', '10:00:00', '12:30:00', 2.50,  'Holiday Gift Wrapping'),
-- Event 9003: Food Drive — Jan 17, 2026
('vol_alice', '2026-01-17', '08:00:00', '12:00:00', 4.00,  'Winter Food Drive'),
('vol_bob',   '2026-01-17', '08:00:00', '12:00:00', 4.00,  'Winter Food Drive'),
('vol_carol', '2026-01-17', '08:00:00', '11:00:00', 3.00,  'Winter Food Drive'),
('vol_dan',   '2026-01-17', '08:00:00', '10:30:00', 2.50,  'Winter Food Drive'),
('vol_emma',  '2026-01-17', '08:00:00', '12:00:00', 4.00,  'Winter Food Drive'),
('vol_frank', '2026-01-17', '08:30:00', '12:00:00', 3.50,  'Winter Food Drive'),
('vol_grace', '2026-01-17', '09:00:00', '12:00:00', 3.00,  'Winter Food Drive'),
-- Event 9004: Valentine Cards — Feb 8, 2026
('vol_alice', '2026-02-08', '13:00:00', '16:00:00', 3.00,  'Valentine Card Making'),
('vol_carol', '2026-02-08', '13:00:00', '15:30:00', 2.50,  'Valentine Card Making'),
('vol_emma',  '2026-02-08', '13:00:00', '16:00:00', 3.00,  'Valentine Card Making'),
('vol_grace', '2026-02-08', '13:30:00', '16:00:00', 2.50,  'Valentine Card Making'),
('vol_hank',  '2026-02-08', '13:00:00', '15:00:00', 2.00,  'Valentine Card Making'),
-- Event 9005: Trail Restoration — Mar 14, 2026
('vol_alice', '2026-03-14', '08:00:00', '14:00:00', 6.00,  'Spring Trail Restoration'),
('vol_bob',   '2026-03-14', '08:00:00', '13:00:00', 5.00,  'Spring Trail Restoration'),
('vol_carol', '2026-03-14', '08:00:00', '12:00:00', 4.00,  'Spring Trail Restoration'),
('vol_dan',   '2026-03-14', '08:00:00', '11:00:00', 3.00,  'Spring Trail Restoration'),
('vol_frank', '2026-03-14', '09:00:00', '14:00:00', 5.00,  'Spring Trail Restoration'),
('vol_grace', '2026-03-14', '08:00:00', '13:00:00', 5.00,  'Spring Trail Restoration'),
('vol_iris',  '2026-03-14', '08:00:00', '14:00:00', 6.00,  'Spring Trail Restoration'),
('vol_jack',  '2026-03-14', '08:00:00', '12:30:00', 4.50,  'Spring Trail Restoration'),
-- Event 9006: Tutoring — Mar 28, 2026
('vol_alice', '2026-03-28', '15:00:00', '18:00:00', 3.00,  'Literacy Tutoring'),
('vol_emma',  '2026-03-28', '15:00:00', '17:30:00', 2.50,  'Literacy Tutoring'),
('vol_grace', '2026-03-28', '15:00:00', '18:00:00', 3.00,  'Literacy Tutoring'),
('vol_iris',  '2026-03-28', '15:00:00', '17:00:00', 2.00,  'Literacy Tutoring');
