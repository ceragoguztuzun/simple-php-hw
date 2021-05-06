<?php

$server = 'dijkstra.ug.bcc.bilkent.edu.tr';
$dbname = 'cerag_oguztuzun';
$username = 'cerag.oguztuzun';
$password = '7exYR0yV';

$conn = mysqli_connect($server, $username, $password, $dbname);

if (!$conn) {
    die("uh oh" . mysqli_connect_error());
}
