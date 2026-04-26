<?php
require_once(__DIR__ . '/dbinfo.php');

function ensure_password_resets_table() {
    $con = connect();
    $sql = "CREATE TABLE IF NOT EXISTS dbpasswordresets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        token VARCHAR(64) NOT NULL UNIQUE,
        user_id VARCHAR(255) NOT NULL,
        expires_at DATETIME NOT NULL,
        used TINYINT(1) NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    )";
    mysqli_query($con, $sql);
    mysqli_close($con);
}

function retrieve_person_id_by_email($email) {
    $con = connect();
    $safe_email = mysqli_real_escape_string($con, strtolower($email));
    $result = mysqli_query($con, "SELECT id FROM dbpersons WHERE LOWER(email)='$safe_email' LIMIT 1");
    if (!$result || mysqli_num_rows($result) === 0) {
        mysqli_close($con);
        return false;
    }
    $row = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $row['id'];
}

function create_password_reset_token($user_id) {
    ensure_password_resets_table();
    $con = connect();
    $token      = bin2hex(random_bytes(32));
    $safe_user  = mysqli_real_escape_string($con, $user_id);
    $safe_token = mysqli_real_escape_string($con, $token);
    $result = mysqli_query($con,
        "INSERT INTO dbpasswordresets (token, user_id, expires_at, used)
         VALUES ('$safe_token', '$safe_user', NOW() + INTERVAL 15 MINUTE, 0)"
    );
    mysqli_close($con);
    return $result ? $token : false;
}

function validate_password_reset_token($token) {
    ensure_password_resets_table();
    $con = connect();
    $safe_token = mysqli_real_escape_string($con, $token);
    $result = mysqli_query($con,
        "SELECT user_id FROM dbpasswordresets
         WHERE token='$safe_token' AND used=0 AND expires_at > NOW()
         LIMIT 1"
    );
    if (!$result || mysqli_num_rows($result) !== 1) {
        mysqli_close($con);
        return false;
    }
    $row = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $row['user_id'];
}

function consume_password_reset_token($token) {
    ensure_password_resets_table();
    $con = connect();
    $safe_token = mysqli_real_escape_string($con, $token);
    $result = mysqli_query($con,
        "UPDATE dbpasswordresets SET used=1 WHERE token='$safe_token'"
    );
    mysqli_close($con);
    return $result;
}
