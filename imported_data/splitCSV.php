<?php
/**
 * Splits the combined currentlist.csv into 5 separate, clean CSV files:
 *   - training_materials.csv
 *   - events.csv
 *   - event_signups.csv
 *   - messages.csv
 *   - volunteers.csv (with data cleaning applied)
 */

$inputFile = __DIR__ . '/currentlist.csv';
$outputDir = __DIR__;

// Define sections by their header signatures (first few fields)
$sections = [
    'training'  => ['detect' => ['id', 'eventID', 'url'],           'file' => 'training_materials.csv', 'cols' => 6],
    'events'    => ['detect' => ['id', 'name', 'abbrevName'],       'file' => 'events.csv',             'cols' => 9],
    'signups'   => ['detect' => ['eventID', 'userID'],              'file' => 'event_signups.csv',      'cols' => 2],
    'messages'  => ['detect' => ['id', 'senderID', 'recipientID'],  'file' => 'messages.csv',           'cols' => 7],
    'volunteers'=> ['detect' => ['id', 'start_date', 'venue'],      'file' => 'volunteers.csv',         'cols' => null], // keep all columns
];

// Open input
$input = fopen($inputFile, 'r');
if (!$input) {
    die("Error: Cannot open $inputFile\n");
}

// State
$currentSection = null;
$outputHandle = null;
$headerCols = 0;
$counts = [];
$volunteerHeader = [];

foreach ($sections as $key => $sec) {
    $counts[$key] = 0;
}

/**
 * Detect which section a row belongs to by checking if it matches a header signature.
 */
function detectHeader(array $row, array $sections): ?string {
    foreach ($sections as $key => $sec) {
        $detect = $sec['detect'];
        $match = true;
        foreach ($detect as $i => $val) {
            if (!isset($row[$i]) || trim($row[$i]) !== $val) {
                $match = false;
                break;
            }
        }
        if ($match) {
            return $key;
        }
    }
    return null;
}

/**
 * Clean a volunteer data row.
 * - Convert dates (start_date idx 1, birthday idx 13) from M/D/YYYY to YYYY-MM-DD
 * - Convert computer(16), camera(17), transportation(18) from 1/blank to yes/no
 * - Strip phone numbers (phone1 idx 9, contact_num idx 20) to digits only
 * - Decode HTML entities in all text fields
 */
function cleanVolunteerRow(array $row, array $header): array {
    // Build index map from header
    $idx = array_flip($header);

    // Date conversion helper
    $convertDate = function($val) {
        $val = trim($val);
        if (empty($val)) return '';
        $parts = explode('/', $val);
        if (count($parts) === 3) {
            return sprintf('%04d-%02d-%02d', (int)$parts[2], (int)$parts[0], (int)$parts[1]);
        }
        return $val; // already in correct format or unknown
    };

    // Convert dates
    if (isset($idx['start_date'])) {
        $row[$idx['start_date']] = $convertDate($row[$idx['start_date']]);
    }
    if (isset($idx['birthday'])) {
        $row[$idx['birthday']] = $convertDate($row[$idx['birthday']]);
    }

    // Convert access fields: 1 -> yes, blank -> no
    foreach (['computer', 'camera', 'transportation'] as $field) {
        if (isset($idx[$field])) {
            $val = trim($row[$idx[$field]]);
            $row[$idx[$field]] = ($val === '1') ? 'yes' : 'no';
        }
    }

    // Strip phone numbers to digits only
    foreach (['phone1', 'contact_num'] as $field) {
        if (isset($idx[$field])) {
            $row[$idx[$field]] = preg_replace('/[^0-9]/', '', $row[$idx[$field]]);
        }
    }

    // Decode HTML entities in all fields
    foreach ($row as $i => $val) {
        $row[$i] = html_entity_decode($val, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    return $row;
}

/**
 * Trim trailing empty columns from a row, keeping only $numCols columns.
 */
function trimRow(array $row, int $numCols): array {
    return array_slice($row, 0, $numCols);
}

// Process the CSV line by line
while (($row = fgetcsv($input)) !== false) {
    // Check if this row is a section header
    $detected = detectHeader($row, $sections);

    if ($detected !== null) {
        // Close previous output file
        if ($outputHandle) {
            fclose($outputHandle);
        }

        $currentSection = $detected;
        $sec = $sections[$currentSection];
        $outputPath = $outputDir . '/' . $sec['file'];
        $outputHandle = fopen($outputPath, 'w');

        if (!$outputHandle) {
            die("Error: Cannot open $outputPath for writing\n");
        }

        // Determine how many columns this section has
        if ($sec['cols'] !== null) {
            $headerCols = $sec['cols'];
            $cleanHeader = trimRow($row, $headerCols);
        } else {
            // For volunteers, count non-empty trailing columns in header
            $cleanHeader = array_map('trim', $row);
            // Remove trailing empty columns
            while (count($cleanHeader) > 0 && $cleanHeader[count($cleanHeader) - 1] === '') {
                array_pop($cleanHeader);
            }
            $headerCols = count($cleanHeader);
            $volunteerHeader = $cleanHeader;
        }

        // Write the clean header
        fputcsv($outputHandle, $cleanHeader);
        continue;
    }

    // Write data row to current section
    if ($currentSection && $outputHandle) {
        if ($currentSection === 'volunteers') {
            // Pad row to header length if needed
            while (count($row) < $headerCols) {
                $row[] = '';
            }
            $dataRow = trimRow($row, $headerCols);
            $dataRow = cleanVolunteerRow($dataRow, $volunteerHeader);
        } else {
            $dataRow = trimRow($row, $headerCols);
        }

        fputcsv($outputHandle, $dataRow);
        $counts[$currentSection]++;
    }
}

// Close handles
if ($outputHandle) {
    fclose($outputHandle);
}
fclose($input);

// Print summary
echo "=== CSV Split Complete ===\n\n";
foreach ($sections as $key => $sec) {
    echo sprintf("  %-20s -> %-30s (%d data rows)\n", $key, $sec['file'], $counts[$key]);
}
echo "\nTotal data rows: " . array_sum($counts) . "\n";
