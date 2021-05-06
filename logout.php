<?php

session_start();

// end sesh and navigate to login.php
$_SESSION = array();
session_destroy();
header("location: index.php");

exit;