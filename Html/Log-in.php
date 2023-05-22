  <?php
  session_start();
  require_once __DIR__ . "/../PurePhp/Lingua/imposta-lingua.php";

  $is_invalid = false;
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $mysqli = require __DIR__ . "/../PurePhp/configDB.php";

      $sql = sprintf("SELECT * FROM utenti WHERE email = '%s'", $mysqli->real_escape_string($_POST["i-email"]));
      $result = $mysqli->query($sql);
      $user = $result->fetch_assoc();
      if ($user) {
          if (password_verify($_POST["i-pass"], $user["password"])) {
              session_regenerate_id();
              $_SESSION["user_id"] = $user["id"];
              header("Location: D-fatture.php");
              exit;
          }
      }
      $is_invalid = true;
  }

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

  <!DOCTYPE html>
  <head>
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
      <title>Pagina Login</title>
      <link rel="stylesheet" href="stile.css">
      <style type="text/css">
          body {
              /* fallback for old browsers */
              background: #6a11cb;

              /* Chrome 10-25, Safari 5.1-6 */
              background: -webkit-linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));

              /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
              background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));
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
                          <h1 class="fw-bold mb-2 text-uppercase">Tok-Tik</h1>
                          <div class="mb-md-5 mt-md-4 pb-5">
                              <h4 class="fw-bold mb-2 text-uppercase"><?php echo $translations["login"]; ?></h4>
                              <p class="text-white-50 mb-5"><?php echo $translations["email"]; ?> <?php echo $translations["and"]; ?> <?php echo $translations["password"]; ?></p>
                              <?php if ($is_invalid) : ?>
                                  <em style="color: red"><?php echo $translations["invalid_login"]; ?></em>
                              <?php endif; ?>
                              <form method="post">
                                  <div class="form-outline form-white mb-4">
                                      <input type="email" id="i-email" name="i-email" class="form-control form-control-lg"/>
                                      <label class="form-label" for="typeEmailX"><?php echo $translations["email"]; ?></label>
                                  </div>
                                  <div class="form-outline form-white mb-4">
                                      <input type="password" id="i-pass" name="i-pass" class="form-control form-control-lg" />
                                      <label class="form-label" for="typePasswordX"><?php echo $translations["password"]; ?></label>
                                  </div>
                                  <button id="login" class="btn btn-outline-light btn-lg px-5" type="submit" onclick="checkForm()"><?php echo $translations["login"]; ?></button>
                              </form>
                          </div>
                          <div>
                              <p class="mb-0"><?php echo $translations["no_account"]; ?> <a href="Register.php" class="text-white-50 fw-bold"><?php echo $translations["signup"]; ?></a></p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="language-selector">
          <img src="../PurePhp/Lingua/italian-flag.png" alt="Italian Flag" onclick="selectLanguage('italian')">
          <img src="../PurePhp/Lingua/english-flag.png" alt="English Flag" onclick="selectLanguage('english')">
      </div>
  </section>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
  </html>
<!--
     ^...^
    / o,o \
    |):::(|
   ===w=w===
-->