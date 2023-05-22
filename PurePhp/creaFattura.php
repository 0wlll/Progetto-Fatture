<?php
$fdata = $_POST['f-data'];
$findirizzata = $_POST['f-indirizzata'];
$fmotivazioni = $_POST['f-motivazioni'];
$fsomma = $_POST['f-somma'];
//$futente = $_POST['f-utente'];
if (isset($_POST['f-utente'])) {
    // The 'f-utente' input field was submitted with a value
    $futente = $_POST['f-utente'];
    // Do something with the value...
} else {
    echo $futente;
}

    $mysqli= require __DIR__ . "/configDB.php";
    $sql = "INSERT INTO fatture (data, indirizzata, motivazioni, somma, utente)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->stmt_init();
    if(!$stmt->prepare($sql)){
        die("errore sql");
    }

    $stmt->bind_param("sssss",
                    $fdata,
                    $findirizzata,
                    $fmotivazioni,
                    $fsomma,
                    $futente);

    if($stmt->execute()){
        header("Location: ../Html/D-fatture.php");
    }else{

        echo "fattura non registrata";
        exit;
    };
?>
<!--
     ^...^
    / o,o \
    |):::(|
   ===w=w===
-->