<?php
session_start();
require_once __DIR__ . "/../PurePhp/Lingua/imposta-lingua.php";

// Imposta la lingua predefinita se non è stata ancora selezionata
if (!isset($_SESSION["language"])) {
    $_SESSION["language"] = "italian";
}

// Includi il file delle traduzioni corrispondente alla lingua selezionata
$translations = [];
if ($_SESSION["language"] === "italian") {
    require_once __DIR__ . "/../PurePhp/Lingua/traduzione-ita.php";
} else if ($_SESSION["language"] === "english") {
    require_once __DIR__ . "/../PurePhp/Lingua/traduzione-ing.php";
}
?>
<head>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Pagina Login</title>
    <style type="text/css">
      body{
      /* fallback for old browsers */
      background: #6a11cb;
      
      /* Chrome 10-25, Safari 5.1-6 */
      background: -webkit-linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));
      
      /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
      background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1))
      }
      .sidebar{
          float: left;
          background-color: #343A40;
          height: 100%;
      }
      </style>
</head>
<body>
<section class="vh-100 gradient-custom">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
          <div class="card bg-dark text-white" style="border-radius: 1rem;">
            <div class="card-body p-5 text-center">
              <h1 class="fw-bold mb-2 text-uppercase">Tok-Tik</h1>
              <div class="mb-md-5 mt-md-4 pb-5">
                <h2 class="text-white-50 mb-5" style="margin:30%"><?php echo $translations["ac"]; ?></h2>
              </div>
              <div>
                <p class="mb-0"><a href="Log-in.php" class="text-white-50 fw-bold"><?php echo $translations["login"]; ?></a>
                </p>
              </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script type="text/javascript">
    function checkForm()
    {
      var errori = "";
        var controlloErrori= false;
        if(!document.getElementById("i-email").value){
          errori= errori + "Il campo 'Email' è vuoto!\n";
          controlloErrori = true;
        }
        if(!document.getElementById("i-pass").value){
          errori= errori + "Il campo 'Password' è vuoto!\n";
          controlloErrori = true;
        }
        if(controlloErrori==true){
          alert(errori);
        }
    }
  </script>
</body>
<!--
     ^...^
    / o,o \
    |):::(|
   ===w=w===
-->