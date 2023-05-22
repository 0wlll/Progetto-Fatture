<?php
    $fid = $_POST['f_id'];
    $fdata= $_POST["f_data"];
    $futente= $_POST["f_utente"];
    $fcliente= $_POST["f_cliente"];
    $fragioni=$_POST["f_ragioni"];
    $fsomma=$_POST["f_somma"];

    $mysqli= require __DIR__ . "/configDB.php";
    if ($mysqli->connect_error) {
        die("Connessione fallita: " . $mysqli->connect_error);
    }
    
    $sql = "UPDATE fatture SET data=?, utente=?, indirizzata=?, motivazioni=?, somma=? WHERE id=?";
    $stmt = $mysqli->prepare($sql);
    if(!$stmt){
        die("errore sql: " . $mysqli->error);
    }
    $stmt->bind_param("sssssi", $fdata, $futente, $fcliente, $fragioni, $fsomma, $fid);
    
    if($stmt->execute()){
        header("Location: ../Html/D-fatture.php");
    } else {
        echo "non modificata";
        exit;
    };

    $stmt->close();
    $mysqli->close();
?>
<!--
     ^...^
    / o,o \
    |):::(|
   ===w=w===
-->