<?php

$DSN = 'mysql:host = localhost; dbname=cms4.4.1';
$ConnectingDB = new PDO($DSN, 'injam', 'injam2015jsr');

if (!$ConnectingDB) {
    echo 'Connection error: ' . mysqli_connect_error();
}
