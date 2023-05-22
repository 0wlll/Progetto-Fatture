<?php
//if (isset($_POST['form-submit'])){

        $inome = $_POST['i-nome'];
        $icognome = $_POST['i-cognome'];
        $iemail = $_POST['i-email'];
        $ipass = password_hash($_POST['i-pass'], PASSWORD_DEFAULT);

        
            $mysqli= require __DIR__ . "/configDB.php";
            $sql = "INSERT INTO utenti (nome, cognome, email, password)
                    VALUES (?, ?, ?, ?)";
            $stmt = $mysqli->stmt_init();
            if(!$stmt->prepare($sql)){
                die("errore sql");
            }

            $stmt->bind_param("ssss",
                            $inome,
                            $icognome,
                            $iemail,
                            $ipass);

            if($stmt->execute()){
                header("Location: ../Html/Account-creato.php");
            }else{
        
                echo "non registrato";
                exit;
            };
?>
<!--
     ^...^
    / o,o \
    |):::(|
   ===w=w===
-->