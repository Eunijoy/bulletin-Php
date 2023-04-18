<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_DATABASE', 'entries_bulletin');


    $con = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATABASE);
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }