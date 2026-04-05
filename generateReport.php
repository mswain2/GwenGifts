<?php
session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);
date_default_timezone_set("America/New_York");

// check RBAC
if (isset($_SESSION['access_level']) && $_SESSION['access_level'] >= 2) {
    $isEventManager = true;
} else {
    header('Location: index.php');
    die();  
}

// Get current fiscal year
$currentMonth = date("m");
$currentYear = date("Y");
$fiscalYearStart = ($currentMonth >= 10) ? $currentYear : $currentYear - 1;
$fiscalYearEnd = $fiscalYearStart + 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gwyneth's Gift | Generate Report</title>
    <!--<script src="js/data-filters.js" defer></script>-->
    <link href="css/normal_tw.css" rel="stylesheet">
    <?php
    $tailwind_mode = true;
    require_once('header.php');
    ?>
</head>

<body>
    <?php require_once('database/dbEvents.php');?>
    <?php require_once('database/dbPersons.php');?>

    <!-- Hero Section with Title -->
    <h1 style="color:white;">Generate Report</h1>

    <main>
        <?php $events = get_all_events_sorted_by_date_not_archived();?>

        <div class="main-content-box w-[80%] p-8">
            
            <!-- Fiscal Year Label -->
            <!--<div class="text-center">
                <p style="font-size: 18px; color: #c2c2c2ff; margin-top: 0.5rem; margin-bottom: 0.5rem;">Fiscal Year: <?= $fiscalYearStart ?> - <?= $fiscalYearEnd ?></p>
            </div>-->

            <!-- Form Title -->
            <div class="text-center mb-8">
                <h2>Generate Report</h2>
                <p class="sub-text">Use this tool to generate monthly or annual reports on volunteer activity. Reports are available in CSV or PDF format.</p>
            </div>

            <!-- Form -->
            <form method="POST" action="processReport.php">
                <!-- Event ID -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="eventID" style="font-weight: 600;">Select Report Type</label>
                    <select name="eventID" id="eventID">
                        <?php foreach ($events as $event) {
                            $eventID = $event->getID();
                            $eventName = $event->getName();
                            echo "<option value='$eventID'>$eventName (ID: $eventID)</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Month (conditionally hidden)
                <div id="monthField">
                    <label for="month" class="font-semibold">Select Month:</label>
                    <select name="month" id="month">
                        <?php
                        $months = [
                            '10' => 'October', '11' => 'November', '12' => 'December', '01' => 'January',
                            '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May',
                            '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September'
                        ];
                        foreach ($months as $num => $name) {
                            echo "<option value='$num'>$name</option>";
                        }
                        ?>
                    </select>
                </div> -->

                <!-- Content Select -->

                    <h4 style="margin-top: 1rem; margin-bottom: 0.5rem; font-weight: 600;">Field Selector</h4>
                    <p style="font-size: 16px; color: #c2c2c2ff; margin-top: 0.5rem; margin-bottom: 0.5rem;">If any fields are selected, the report will include all users who signed up and whether they attended.</p>
                    <div id="field-picker">
                            <div class="checkbox-grouping">
                                <label class="checkbox-label">
                                    <input type="checkbox" value="user" name="user" id="user" checked> Username</label>
                                <label class="checkbox-label">
                                    <input type="checkbox" value="name" name="name" id="name" checked> Full Name</label>
                                <label class="checkbox-label">
                                    <input type="checkbox" value="branch" name="branch" id="branch"> Branch</label>
                                <label class="checkbox-label">
                                    <input type="checkbox" value="affiliation" name="affiliation" id="affiliation"> Affiliation</label>
                        </div>
                    </div>
                </section>

                <!-- Format -->
                <div style="margin-bottom: 1.5rem; margin-top: 1.5rem;">
                    <label for="format" style="font-weight: 600;">File Format</label>
                    <select name="format" id="format">
                        <option value="excel">Excel (.xls)</option>
                        <option value="csv">CSV (.csv)</option>
                    </select>
                </div>

                <!-- Submit -->
                <div class="text-center pt-4">
                    <input type="submit" value="Search" class="submit-button">
                </div>

            </form>
        </div>

        <!-- Return to Dashboard -->
        <div class="text-center mt-6">
            <a href="index.php" class="return-button">Return to Dashboard</a>
        </div>

    </main>

    <script>
        function toggleDateFields() {
            const eventID = document.getElementById("eventID").value;
            // const monthField = document.getElementById("monthField");
            // monthField.style.display = reportType === "annually" ? "none" : "block";
        }
        document.addEventListener("DOMContentLoaded", toggleDateFields);
    </script>

</body>
</html>

