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
    <title>SignUp</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
    <script src="../JS/validation.js" defer></script>
    <link rel="stylesheet" href="../stile.css">
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
    .nav-item{
        margin: 20px;
        margin-top: 20px;
    }
    .flex-column{
        background-color: #343A40
    }
    .language-selector {
        display: flex;
        justify-content: center;
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
              <h1 class="fw-bold mb-2 text-uppercase">Tok-Tik</h2>
              <div class="mb-md-5 mt-md-4 pb-5">
                <!--form-->
                <form id="signup" action="../PurePhp/registrazioni.php" method="post">
                  <h3 class="fw-bold mb-2 text-uppercase"><?php echo $translations["signup"]; ?></h3>
                  <p class="text-white-50 mb-5"><?php echo $translations["enter_email_password"]; ?></p>
                  <div class="form-outline form-white mb-4">
                    <label class="form-label"><?php echo $translations["name"]; ?></label>
                    <input type="text" id="i-nome" name="i-nome" class="form-control form-control-lg"/>
                  </div>

                  <div class="form-outline form-white mb-4">
                  <label class="form-label"><?php echo $translations["surname"]; ?></label>
                  <input type="text" id="i-cognome" name="i-cognome" class="form-control form-control-lg"/>
                  </div>

                  <div class="form-outline form-white mb-4">
                  <label class="form-label" for="typeEmailX"><?php echo $translations["email"]; ?></label>
                  <input type="email" id="i-email" name="i-email" class="form-control form-control-lg"/>
                  </div>
    
                  <div class="form-outline form-white mb-4">
                    <label class="form-label" for="typePasswordX"><?php echo $translations["password"]; ?></label>
                    <input type="password" id="i-pass" name="i-pass"class="form-control form-control-lg" />
                  </div>

                  <div class="form-outline form-white mb-4">
                    <label class="form-label" for="typePasswordX"><?php echo $translations["confirm_password"]; ?></label>
                    <input type="password" id="i-pass2" class="form-control form-control-lg" />    
                  </div>

                  <button class="btn btn-outline-light btn-lg px-5" name="form-submit" type="submit"><?php echo $translations["signup"]; ?></button>
                </form>
                <div style="margin-top: 20px;">
                  <p class="mb-0"><?php echo $translations["already_account"]; ?> <a href="Log-in.php" class="text-white-50 fw-bold"><?php echo $translations["login"]; ?></a>
                  </p>
                </div>
                <div class="language-selector">
                    <img src="../PurePhp/Lingua/italian-flag.png" alt="Italian Flag" onclick="selectLanguage('italian')">
                    <img src="../PurePhp/Lingua/english-flag.png" alt="English Flag" onclick="selectLanguage('english')">
                </div>
              </div>
  
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script>
    function selectLanguage(language) {
        $.post("../PurePhp/Lingua/imposta-lingua.php", { language: language }, function(data) {
            if (data === "success") {
                window.location.reload(); // Ricarica la pagina dopo aver impostato la lingua
            }
        });
    }
</script>
</body>
<!--
     ^...^
    / o,o \
    |):::(|
   ===w=w===
-->
