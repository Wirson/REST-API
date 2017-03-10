<?php

include_once('book.php');

//DANE DOSTĘPOWE DO BAZY DANYCH
//STRUKTURA DO IMPORTU W PLIKU dump.sql

$servername = "localhost";
$username = "root";
$password = "coderslab";
$baseName = "books";

$conn = new mysqli($servername, $username, $password, $baseName);

if ($conn->connect_error) {
    echo "Connection failed. Error: " . $conn->connect_error;
    die;
}

$setEncodingSql = "SET CHARSET utf8";
$conn->query($setEncodingSql);

Book::$conn = $conn;