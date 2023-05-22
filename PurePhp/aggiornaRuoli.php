<?php
$id = $_POST['id'];
$ruolo = $_POST['ruolo'];
$area = $_POST['area'];
$portafoglio_di = $_POST['portafoglio_di'];


$mysqli = require __DIR__ . "/configDB.php";
if ($mysqli->connect_error) {
    die("Connessione fallita: " . $mysqli->connect_error);
}

if ($ruolo) {
    $sql = "UPDATE utenti SET ruolo = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        die("Errore SQL: " . $mysqli->error);
    }
    $stmt->bind_param("si", $ruolo, $id);
} elseif ($area) {
    $sql = "UPDATE utenti SET area = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        die("Errore SQL: " . $mysqli->error);
    }
    $stmt->bind_param("si", $area, $id);
}
elseif ($portafoglio_di) {
    $sql = "UPDATE utenti SET portafoglio_di = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        die("Errore SQL: " . $mysqli->error);
    }
    $stmt->bind_param("si", $portafoglio_di, $id);
}

if ($stmt->execute()) {
    header("Location: ../Html/D-clienti.php");
} else {
    echo "Errore durante l'aggiornamento del ruolo o dell'area.";
    exit;
}

$stmt->close();
$mysqli->close();
?>
<!--
     ^...^
    / o,o \
    |):::(|
   ===w=w===
-->