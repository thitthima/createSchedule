<?php
    $servername = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'stay-at-home-family-organizer';

    $conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);

    if(!$conn) {
        die("Connection: failed".mysqli_connect_error());
    }