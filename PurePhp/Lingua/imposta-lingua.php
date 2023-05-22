<?php
if (!isset($_SESSION)) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if(isset($_POST["language"]))
    {$language = $_POST["language"];

    // Puoi personalizzare questa parte per adattarla alle tue esigenze di traduzione
    if ($language === "italian" || $language === "english") {
        $_SESSION["language"] = $language;
        echo "success";
    }
}
}
?>
<!--
     ^...^
    / o,o \
    |):::(|
   ===w=w===
-->
