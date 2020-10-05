<?php
error_reporting(E_ALL | E_STRICT);

$connection = new PDO("sqlite:./data/mydb.sdb");
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
$connection->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);

//~ $software->debug = true;
?>
