<?php
session_start();
if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/../PurePhp/configDB.php";
    $sql = "SELECT * FROM utenti WHERE id = {$_SESSION["user_id"]}";
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();
    switch ($user["ruolo"]) {
        case "Admin":
            $sql = "SELECT id, cognome, nome, ruolo, area, portafoglio_di FROM utenti WHERE id!={$user["id"]}";
            break;
        case "Capo_area":
            $sql = "SELECT id, cognome, nome, ruolo, area, portafoglio_di FROM utenti WHERE id!={$user["id"]} AND area='".$user["area"]."' ";
            break;
        case "Commerciale":
            $sql = "SELECT id, cognome, nome, ruolo, area, portafoglio_di FROM utenti WHERE portafoglio_di = {$user["id"]}";
            break;
        default:
            echo "Errore nella scelta dei permessi";
    }
    $clienti = $mysqli->query($sql);
} else {
    echo "ERRORE caricamento sessione";
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <title>Dashboard</title>
    <style type="text/css">
        body {
            /* fallback for old browsers */
            background: #6a11cb;
        
            /* Chrome 10-25, Safari 5.1-6 */
            background: -webkit-linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));
        
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1))
        }

        .sidebar {
            float: left;
            background-color: #343A40;
            height: 100%;
        }

        .nav-item {
            margin: 20px;
            margin-top: 20px;
        }

        .flex-column {
            background-color: #343A40
        }
    </style>
</head>
<body>
    <!--colonna a sinistra info account-->
    <div class="sidebar container-flex" id="sidebar" style="height: 100vh; overflow-y: auto;">
        <div class="card bg-dark text-white" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title" id="nominativo"><?= htmlspecialchars($user["nome"]) ?> <?= htmlspecialchars($user["cognome"]) ?></h5>
                <h6 class="card-subtitle mb-2 text-muted" id="ruolo"><?= htmlspecialchars($user["ruolo"]) ?></h6>
                <a href="../PurePhp/logout.php" class="card-link" style="color: red">Log-out</a>
            </div>
        </div>
        <!--colonna a sinistra selezione clienti e fatture-->
        <nav class="nav nav-pills nav flex-column vh-100">
            <a id="fatture" class="nav-item nav-link" href="D-fatture.php" onclick="hey()">Fatture</a>
            <a id="c_fattura" class="nav-item nav-link disabled" href="#" onclick="addFattura()">Creazione Fattura
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-square" viewBox="-3 -4 20 20">
                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </a>
            <a id="clienti" class="nav-item nav-link active" href="#">Clienti</a>
        </nav>
    </div>
    <!--sezione clienti-->
    <div class="container-flex col-10 col-md-8" style="float:left; margin-top: 10px;">
        <!--elenco clienti-->
        <table class="table table-striped table-bordered table-dark flex-column" bg>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col"><?php echo $translations["surname"]; ?></th>
                    <th scope="col"><?php echo $translations["name"]; ?></th>
                    <th scope="col"><?php echo $translations["r"]; ?></th>
                    <th scope="col"><?php echo $translations["area"]; ?></th>
                    <th scope="col"><?php echo $translations["vsf"]; ?></th>
                    <th scope="col"><?php echo $translations["opf"]; ?></th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($clienti->num_rows > 0) {
            foreach ($clienti as $row) {
                ?>
            <tr>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo $row["cognome"]; ?></td>
                <td><?php echo $row["nome"]; ?></td>
                <td>
                    <?php if ($user["ruolo"] == "Admin") { ?>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton<?php echo $row["id"]; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php echo $row["ruolo"]; ?>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton<?php echo $row["id"]; ?>">
                                <form method="post" action="../PurePhp/aggiornaRuoli.php">
                                    <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
                                    <button class="dropdown-item" type="submit" name="ruolo" value="Admin"><?php echo $translations["admin"]; ?></button>
                                    <button class="dropdown-item" type="submit" name="ruolo" value="Capo_area"><?php echo $translations["ca"]; ?></button>
                                    <button class="dropdown-item" type="submit" name="ruolo" value="Cliente"><?php echo $translations["cs"]; ?></button>
                                    <button class="dropdown-item" type="submit" name="ruolo" value="Commerciale"><?php echo $translations["comm"]; ?></button>
                                </form>
                            </div>
                        </div>
                    <?php } else { ?>
                        <?php echo $row["ruolo"]; ?>
                    <?php } ?>
                </td>
                <td>
                  <?php if ($user["ruolo"] === "Admin") { ?>
                    <div class="dropdown">
                      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton<?php echo $row["id"]; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $row["area"]; ?>
                      </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton<?php echo $row["id"]; ?>">
                    <form method="post" action="../PurePhp/aggiornaRuoli.php">
                      <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
                        <button class="dropdown-item" type="submit" name="area" value="null"><?php echo $translations["none"]; ?></button>
                        <button class="dropdown-item" type="submit" name="area" value="nord">nord</button>
                        <button class="dropdown-item" type="submit" name="area" value="sud">sud</button>
                        <button class="dropdown-item" type="submit" name="area" value="est">est</button>
                        <button class="dropdown-item" type="submit" name="area" value="ovest">ovest</button>
                    </form>
                    </div>
                  </div>
                  <?php } else {
                      echo $row["area"];
                  } ?>
                </td>
                <td>
                <!-- Form per il trasferimento alla pagina D-fatture.php -->
                <form action="D-fatture.php" method="POST">
                    <!-- Campo nascosto per passare l'ID del cliente -->
                    <input type="hidden" name="cliente_id" value="<?php echo $row["id"]; ?>">
                    <!-- Pulsante "Fatture" -->
                    <button type="submit" class="btn btn-info"><?php echo $translations["fas"]; ?></button>
                </form>
                </td>
              <td>
              <?php if ($user["ruolo"] === "Admin") { ?>
                <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton<?php echo $row["id"]; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php
                    if ($row["portafoglio_di"] === "null") {
                        echo "nessuno";
                    } else {
                        $id = intval($row["portafoglio_di"]);
                        $utenteSql = "SELECT nome, cognome FROM utenti WHERE id = $id";
                        $utenteResult = $mysqli->query($utenteSql);
                        if ($utenteResult->num_rows > 0) {
                            $utenteRow = $utenteResult->fetch_assoc();
                            echo $utenteRow["nome"] . " " . $utenteRow["cognome"];
                        }
                    }
                    ?>
                </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton<?php echo $row["id"]; ?>">
              <form method="post" action="../PurePhp/aggiornaRuoli.php">
                  <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
                  <button class="dropdown-item" type="submit" name="portafoglio_di" value="null"><?php echo $translations["none"]; ?>
                  </button>
                  <?php
                  $commercialeSql = "SELECT id, nome, cognome FROM utenti WHERE ruolo = 'Commerciale'";
                  $commercialeResult = $mysqli->query($commercialeSql);
                  if ($commercialeResult->num_rows > 0) {
                      foreach ($commercialeResult as $commercialeRow) {
                          ?>
                          <button class="dropdown-item" type="submit" name="portafoglio_di" value="<?php echo $commercialeRow["id"]; ?>"><?php echo $commercialeRow["nome"] . " " . $commercialeRow["cognome"]; ?></button>
                          <?php
                      }
                  }
                  ?>
              </form>
              </div>
              </div>
              <?php } else {
                  echo isset($row["portafoglio_di"]) ? $row["portafoglio_di"] : "nessuno";
              } ?>
            </td>
            </tr>
            <?php }} ?>
        </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $(".dropdown-toggle").click(function() {
                $(this).siblings(".dropdown-menu").toggle();
            });

            $(document).click(function(event) {
                var target = $(event.target);
                if (!target.closest(".dropdown").length) {
                    $(".dropdown-menu").hide();
                }
            });
        });
    </script>

</body>
</html>
<!--
     ^...^
    / o,o \
    |):::(|
   ===w=w===
-->