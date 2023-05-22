<?php

$mysqli = require __DIR__ . "/configDB.php";

$sql = sprintf("SELECT * FROM utenti
                WHERE email = '%s'",
                $mysqli->real_escape_string($_GET["email"]));
$result = $mysqli->query($sql);
$is_available = $result->num_rows === 0; //è true se non ci sono altre email uguali
header("Content-Type: application/json");
echo json_encode(["aviable" => $is_available]); //il json dice se è vero o falso
?>
<!--
     ^...^
    / o,o \
    |):::(|
   ===w=w===
-->