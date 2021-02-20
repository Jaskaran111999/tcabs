<?php

        mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_INDEX);

        //main server
        //$conn = new mysqli("139.99.166.3", "tcabs_web", "Database1", "tcabs");

        //local server
        //$DATABASE_HOST = 'localhost';
        //$DATABASE_USER = 'root';
        //$DATABASE_PASS = '';

        $DATABASE_HOST = '159.65.9.180';
        $DATABASE_USER = 'tcabs_web';
        $DATABASE_PASS = 'password123';
        $DATABASE_NAME = 'tcabs';

        $conn = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

        if ($conn->connect_error) {
                die("ERROR: Unable to connect: " . $conn->connect_error);
        }
?>
