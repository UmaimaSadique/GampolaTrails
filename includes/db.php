<?php
require_once __DIR__ . '/../config.php';

mysqli_report(MYSQLI_REPORT_OFF);
$db = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($db->connect_errno) {
    http_response_code(500);
    die('Could not connect to the database. Is MySQL running in XAMPP and has database.sql been imported?');
}
$db->set_charset('utf8mb4');
