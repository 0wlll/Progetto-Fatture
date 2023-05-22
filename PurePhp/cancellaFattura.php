<?php
if (isset($_POST["fattura-id"])) {

    $fatturaid = $_POST["fattura-id"];
    $mysqli= require __DIR__ . "/configDB.php";
    $stmt = $mysqli->prepare("DELETE FROM fatture WHERE id=?");
    $stmt->bind_param("s", $fatturaid);
    $stmt->execute();
    if($stmt->execute()){
        header("Location: ../Html/D-fatture.php");
    }else{
        echo "non cancellata";
        exit;
    };
    $stmt->close();
    $mysqli->close();

} else {
  echo "Errore: id non specificato";
}
?>
<!--
     ^...^
    / o,o \
    |):::(|
   ===w=w===
-->