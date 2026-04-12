<?php
/**
 * Reads volunteers.csv and generates import_volunteers.sql
 * Run once locally: php generateSQL.php
 * Then import the .sql file into phpMyAdmin (local or SiteGround)
 */

$csvFile = __DIR__ . '/volunteers.csv';
$sqlFile = __DIR__ . '/import_volunteers.sql';

// Bcrypt hash of "Welcome1"
$welcomeHash = '$2y$10$mvXCeyP7ZUEx7uRoEU4hSuuLP/rk/UEKGWeiMt9zLQ2ARRx6gSmxa';

$handle = fopen($csvFile, 'r');
if (!$handle) {
    die("Cannot open $csvFile\n");
}

// Read header row
$header = fgetcsv($handle);
if (!$header) {
    die("Cannot read CSV header\n");
}

// Build column index map
$colIndex = [];
foreach ($header as $i => $col) {
    $colIndex[trim($col)] = $i;
}

function csvVal($row, $colIndex, $key) {
    if (!isset($colIndex[$key])) return '';
    return trim($row[$colIndex[$key]] ?? '');
}

function sqlEscape($val) {
    // Escape single quotes for SQL
    return str_replace("'", "''", $val);
}

/**
 * Split "First Last" into [first, last].
 * If only one word, first = that word, last = ''.
 * Splits on the LAST space so "Mary Jane Watson" => "Mary Jane" + "Watson".
 */
function splitName($fullName) {
    $fullName = trim($fullName);
    if ($fullName === '') return ['', ''];
    $lastSpace = strrpos($fullName, ' ');
    if ($lastSpace === false) return [$fullName, ''];
    return [substr($fullName, 0, $lastSpace), substr($fullName, $lastSpace + 1)];
}

$rows = [];
$count = 0;

while (($row = fgetcsv($handle)) !== false) {
    // Skip empty rows
    if (count($row) < 5) continue;

    $id           = csvVal($row, $colIndex, 'id');
    if (empty($id)) continue; // skip rows with no ID

    // Skip placeholder/template rows and existing system accounts
    if (strpos($id, '[value') === 0) continue;
    if ($id === 'vmsroot') continue;

    $start_date   = csvVal($row, $colIndex, 'start_date');
    $first_name   = csvVal($row, $colIndex, 'first_name');
    $last_name    = csvVal($row, $colIndex, 'last_name');
    $address      = csvVal($row, $colIndex, 'address');
    $city         = csvVal($row, $colIndex, 'city');
    $state        = csvVal($row, $colIndex, 'state');
    $zip          = csvVal($row, $colIndex, 'zip');
    $phone1       = csvVal($row, $colIndex, 'phone1');
    $phone1type   = csvVal($row, $colIndex, 'phone1type');
    $birthday     = csvVal($row, $colIndex, 'birthday');
    $email        = csvVal($row, $colIndex, 'email');
    $shirt_size   = csvVal($row, $colIndex, 'shirt_size');
    $computer     = csvVal($row, $colIndex, 'computer');
    $camera       = csvVal($row, $colIndex, 'camera');
    $transport    = csvVal($row, $colIndex, 'transportation');
    $contactName  = csvVal($row, $colIndex, 'contact_name');
    $contactNum   = csvVal($row, $colIndex, 'contact_num');
    $relation     = csvVal($row, $colIndex, 'relation');
    $specialties  = csvVal($row, $colIndex, 'specialties');
    $type         = csvVal($row, $colIndex, 'type');
    $status       = csvVal($row, $colIndex, 'status');
    $notes        = csvVal($row, $colIndex, 'notes');
    $gender       = csvVal($row, $colIndex, 'gender');

    // Split emergency contact name into first + last
    list($ecFirst, $ecLast) = splitName($contactName);

    // Build SQL
    $sql = "INSERT IGNORE INTO dbpersons ("
         . "id, start_date, first_name, last_name, street_address, city, state, zip_code, "
         . "phone1, phone1type, birthday, email, t_shirt_size, "
         . "computer_access, camera_access, transportation_access, "
         . "emergency_contact_first_name, emergency_contact_last_name, "
         . "contact_num, emergency_contact_relation, "
         . "skills, type, status, notes, gender, password, force_password_change"
         . ") VALUES ("
         . "'" . sqlEscape($id) . "', "
         . ($start_date ? "'" . sqlEscape($start_date) . "'" : "NULL") . ", "
         . "'" . sqlEscape($first_name) . "', "
         . "'" . sqlEscape($last_name) . "', "
         . "'" . sqlEscape($address) . "', "
         . "'" . sqlEscape($city) . "', "
         . "'" . sqlEscape($state) . "', "
         . "'" . sqlEscape($zip) . "', "
         . "'" . sqlEscape($phone1) . "', "
         . "'" . sqlEscape($phone1type) . "', "
         . "'" . sqlEscape($birthday) . "', "
         . "'" . sqlEscape($email) . "', "
         . "'" . sqlEscape($shirt_size) . "', "
         . "'" . sqlEscape($computer) . "', "
         . "'" . sqlEscape($camera) . "', "
         . "'" . sqlEscape($transport) . "', "
         . "'" . sqlEscape($ecFirst) . "', "
         . "'" . sqlEscape($ecLast) . "', "
         . "'" . sqlEscape($contactNum) . "', "
         . "'" . sqlEscape($relation) . "', "
         . "'" . sqlEscape($specialties) . "', "
         . "'" . sqlEscape($type) . "', "
         . "'" . sqlEscape($status) . "', "
         . "'" . sqlEscape($notes) . "', "
         . "'" . sqlEscape($gender) . "', "
         . "'" . $welcomeHash . "', "
         . "1"
         . ");";

    $rows[] = $sql;
    $count++;
}

fclose($handle);

// Write SQL file
$out = fopen($sqlFile, 'w');

fwrite($out, "-- ============================================================\n");
fwrite($out, "-- import_volunteers.sql\n");
fwrite($out, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
fwrite($out, "-- Imports $count returning volunteers into dbpersons\n");
fwrite($out, "-- \n");
fwrite($out, "-- HOW TO USE:\n");
fwrite($out, "--   1. Open phpMyAdmin (local or SiteGround)\n");
fwrite($out, "--   2. Select the gwengiftsdb database\n");
fwrite($out, "--   3. Go to the SQL tab\n");
fwrite($out, "--   4. Import this file (or paste contents)\n");
fwrite($out, "-- \n");
fwrite($out, "-- NOTES:\n");
fwrite($out, "--   - Uses INSERT IGNORE so duplicates are safely skipped\n");
fwrite($out, "--   - All accounts have password 'Welcome1' (bcrypt hashed)\n");
fwrite($out, "--   - All accounts have force_password_change = 1\n");
fwrite($out, "--   - Volunteers must go through Returning Volunteer flow\n");
fwrite($out, "--     to set a username + new password before using the system\n");
fwrite($out, "--   - Make sure force_password_change column exists first:\n");
fwrite($out, "--     ALTER TABLE dbpersons ADD COLUMN force_password_change TINYINT(1) DEFAULT 0;\n");
fwrite($out, "-- ============================================================\n\n");

foreach ($rows as $sql) {
    fwrite($out, $sql . "\n");
}

fwrite($out, "\n-- Done. $count volunteers imported.\n");

fclose($out);

echo "Generated $sqlFile with $count INSERT statements.\n";
