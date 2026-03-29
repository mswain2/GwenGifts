<?php
include_once('dbinfo.php');
include_once(dirname(__FILE__) . '/../domain/Training.php');

function get_training_statuses_for_users(array $userIds): array
{
    $statuses = [];
    $userIds = array_values(array_unique(array_filter($userIds)));

    if (empty($userIds)) {
        return $statuses;
    }

    foreach ($userIds as $id) {
        $statuses[$id] = 'Unknown';
    }

    $connection = connect();

    $table_check = mysqli_query($connection, "SHOW TABLES LIKE 'dbtrainingpersons'");
    if (!$table_check || mysqli_num_rows($table_check) === 0) {
        mysqli_close($connection);
        return $statuses;
    }

    $escaped_ids = array_map(function ($id) use ($connection) {
        return "'" . mysqli_real_escape_string($connection, (string)$id) . "'";
    }, $userIds);

    $query = "
        SELECT userID, COUNT(*) AS training_count
        FROM dbtrainingpersons
        WHERE userID IN (" . implode(',', $escaped_ids) . ")
        GROUP BY userID
    ";

    $result = mysqli_query($connection, $query);

    if ($result) {
        foreach ($userIds as $id) {
            $statuses[$id] = 'Incomplete';
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $statuses[$row['userID']] = ((int)$row['training_count'] > 0)
                ? 'Complete'
                : 'Incomplete';
        }
    }

    mysqli_close($connection);
    return $statuses;
}
?>