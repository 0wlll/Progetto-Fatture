<?php
session_start();

if (isset($_POST["cliente_id"]) && isset($_SESSION["user_id"])) {
  $mysqli = require __DIR__ . "/../PurePhp/configDB.php";
  $user = $_POST["cliente_id"];
  $sql = "SELECT * FROM utenti WHERE id = {$_POST["cliente_id"]}";
  $result = $mysqli->query($sql);
  $user = $result->fetch_assoc();
  $sql = "SELECT * FROM fatture WHERE utente = {$_POST["cliente_id"]}";
  $fatture = $mysqli->query($sql);

  // Imposta il valore di cliente_id in $_SESSION solo se non è già presente
  if (!isset($_SESSION["cliente_id"])) {
    $_SESSION["cliente_id"] = $_POST["cliente_id"];
  }
} else {
  // Elimina il valore di cliente_id se viene ricliccato il link "Fatture Cliente"
  unset($_SESSION["cliente_id"]);

  if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/../PurePhp/configDB.php";
    $sql = "SELECT * FROM utenti WHERE id = {$_SESSION["user_id"]}";
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();
    $sql = "SELECT * FROM fatture WHERE utente = {$user["id"]}";
    $fatture = $mysqli->query($sql);

    switch ($user["ruolo"]) {
      case "Admin":
        $sql = "SELECT id, cognome, nome, ruolo, area FROM utenti";
        break;
      case "Capo_area":
        $sql = "SELECT id, cognome, nome, ruolo, area FROM utenti WHERE area='{$user["area"]}'";
        break;
      case "Commerciale":
        $sql = "SELECT id, cognome, nome, ruolo, area FROM utenti WHERE portafoglio_di={$user["id"]}";
        break;
      case "Cliente":
        $sql = "SELECT id, cognome, nome, ruolo, area FROM utenti WHERE id={$user["id"]}";
        break;
      default:
        echo "errore nella scelta dei permessi";
    }

    $utenti = $mysqli->query($sql);
  } else {
    echo "ERRORE caricamento sessione";
  }
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
<html>
<head>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel=”stylesheet” href=”https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css” />
    
    <title>Dashboard</title>
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
    </style>
</head>
<body>
  <!--colonna a sinistra info account-->
  <div class="sidebar container-flex" id="sidebar" style="height: 100vh; overflow-y: auto;">
  <?php if(isset($user)) : ?>
    <div class="card bg-dark text-white" style="width: 18rem;">
      <div class="card-body">
        <?php if(isset($_SESSION["cliente_id"])) : ?>
          <h5 class="card-subtitle mb-2 text-muted"><?php echo $translations["pow"]; ?></h5>
          <?php endif; ?>
          <h5 class="card-title" id="nominativo"><?= htmlspecialchars($user["nome"]) ?> <?= htmlspecialchars($user["cognome"]) ?></h5>
          <h6 class="card-subtitle mb-2 text-muted" id="ruolo"> <?= htmlspecialchars($user["ruolo"]) ?> </h6>
        <a href="../PurePhp/logout.php" class="card-link" style="color: red">Log-out</a>
      </div>
    </div>
  <?php endif; ?>
  <!--colonna a sinistra selezione clienti e fatture-->
      <nav class="nav nav-pills nav flex-column vh-100">
        <a id="fatture" class="nav-item nav-link active" href="D-fatture.php"><?php echo $translations["fa"]; ?></a>
        <a id="c_fattura" class="nav-item nav-link" href="#" onclick="addFattura()"><?php echo $translations["cfa"]; ?>
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-square" viewBox="-3 -4 20 20">
            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
          </svg>
        </a>
        <?php if($user["ruolo"]!= "Cliente") : ?>
        <a id="clienti" class=" nav-item nav-link" href="D-clienti.php"><?php echo $translations["c"]; ?></a>
        <!--<a class="nav-item nav-link disabled" href="#" onclick="hey()">Disabled</a>-->
        <?php endif; ?>
      </nav>
  </div>
  <!-- Stampa tutte le fatture attribuite a quell'utente-->
    <?php
      if(isset($fatture) && $fatture-> num_rows > 0){
      while($row = $fatture-> fetch_assoc()){?>
          <div style='display: inline-block'><div class='container-flex' style='float:left'>
        <form action='../PurePhp/cancellaFattura.php' method='post' onsubmit="return confirm('Sei sicuro di voler ELIMINARE la fattura con codice: <?=$row['id']?>')">
          <div class='card border-dark mb-3' style='max-width: 18rem; margin: 10px'>
          <div class='card-header bg-transparent border-dark'>
          <p>Codice: <?=$row["id"]?><p>
          <input type="hidden" name="fattura-id" value="<?=$row["id"]?>" >
          </div>
          <div class='card-body text-dark'>
              <h5><?php echo $translations["cdate"]; ?></h5>
              <p class='card-text'><?=$row["data"]?></p>
              <h5><?php echo $translations["cf"]; ?></h5>
              <p class='card-title'><?=$user["nome"]?> <?=$user["cognome"]?></p>
              <h5><?php echo $translations["it"]; ?></h5>
              <p class='card-title'><?=$row["indirizzata"]?></p>
              <h5><?php echo $translations["m"]; ?></h5>
              <p class='card-text'><?=$row["motivazioni"]?></p>
            </div>
            <div class='card-footer bg-transparent border-dar'>
              <h5><?php echo $translations["sp"]; ?></h5>
              <h5 style='display:flex'>€ <?=$row["somma"]?></h5>
              <div class='btn-group' style='float: right' role='group' aria-label='Basic example'>
                <button type='button' id='btn-modifica' data-fattura-id='<?=$row['id']?>' value='<?=$row["id"]?>' class="btn-modifica btn btn-primary" data-toggle='modal' data-target='#modifica'>Modifica</button>
                <button type='submit' class='btn btn-secondary'><?php echo $translations["del"]; ?></button>
              </div>
            </div>
          </div>
          </div>
      </form>
        <?php }
        }
    ?>

    <!--Modale modifica fattura-->
    <div class="modal fade" id="modifica" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><?php echo $translations["mi"]; ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <form action="../PurePhp/modificaFattura.php" method="post" onsubmit="return confirm('Sei sicuro di voler MODIFICARE la fattura?')">
          <div class="modal-body">
              <div class="form-group">
                <input type="hidden" id='f_id' name="f_id">
                <label><?php echo $translations["cfa"]; ?></label>
                <input type="date" class="form-control" id="f_data" name="f_data" required>
                <label><?php echo $translations["cf"]; ?></label>
                  <div class="alert alert-warning alert-dismissible fade show" role="alert" style="display:none">
                  <?php echo $translations["su"]; ?>
                  </div>
                  <?php if($user["ruolo"] != "Cliente"){?>
                    <select name ='f_utente' class='form-select form-select-lg mb-3 form-control' aria-label='.form-select-lg example' required>
                    <option selected value ='nessuno'><?php echo $translations["sui"]; ?></option>  
                    <?php if ($utenti->num_rows > 0) {
                      foreach ($utenti as $row) {?>
                        <option value='<?=$row["id"]?>'><?=$row["nome"]?> <?=$row["cognome"]?>, <?php echo $translations["r"]; ?> <?=$row["ruolo"]?></option>
                    <?php }
                      }?>
                    </select>
                <?php } ?>
                <?php if($user["ruolo"] == "Cliente"){?>
                    <label class="form-control"><?=$user["nome"]?> <?=$user["cognome"]?></label>
                    <input type="hidden" name="f_utente" value="<?=$user["id"]?>" >
                <?php } ?>

                <label><?php echo $translations["it"]; ?></label>
                <input type="text" class="form-control" id="f_cliente" name="f_cliente" required>
                <label><?php echo $translations["m"]; ?></label>
                <input type="text" class="form-control" id="f_ragioni" name="f_ragioni" required>
                <label><?php echo $translations["sp"]; ?>:</label>
                <input type="number" class="form-control" id="f_somma" name="f_somma" required>
              </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><?php echo $translations["conf"]; ?></button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $translations["cl"]; ?></button>
          </div>
        </form>
        </div>
      </div>
    </div>

    <!--Modale aggiungi fattura-->
    <div class="modal fade" id="aggiungi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><?php echo $translations["cfa"]; ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="cFattura" action="../PurePhp/creaFattura.php" onsubmit="return validateForm()" method="post">
          <div class="modal-body">
              <div class="form-group">
                <label><?php echo $translations["date"]; ?>: (gg-mm-aaaa)</label>
                <input type="date" class="form-control" id="f-data" name="f-data" required>
                <label><?php echo $translations["cf"]; ?></label>
                  <div class="alert alert-warning alert-dismissible fade show" role="alert" style="display:none">
                    <?php echo $translations["su"]; ?>
                  </div>
                  <?php if($user["ruolo"] != "Cliente"){?>
                    <select name ='f-utente' class='form-select form-select-lg mb-3 form-control' aria-label='.form-select-lg example'required>
                    <option selected value ='nessuno'><?php echo $translations["sui"]; ?></option>  
                    <?php if ($utenti->num_rows > 0) {
                      foreach ($utenti as $row) {?>
                        <option value='<?=$row["id"]?>'><?=$row["nome"]?> <?=$row["cognome"]?>, <?php echo $translations["r"]; ?> <?=$row["ruolo"]?></option>
                    <?php }
                      }?>
                    </select>

                <?php } ?>
                <?php if($user["ruolo"] == "Cliente"){?>
                    <label class="form-control"><?=$user["nome"]?> <?=$user["cognome"]?></label>
                    <input type="hidden" name="f-utente" value="<?=$user["id"]?>" >
                <?php } ?>
                <label><?php echo $translations["it"]; ?></label>
                <input type="text" class="form-control" id="f-indirizzata" name="f-indirizzata" required>
                <label><?php echo $translations["m"]; ?></label>
                <input type="text" class="form-control" id="f-motivazioni" name="f-motivazioni" required>
                <label><?php echo $translations["sp"]; ?></label>
                <input type="number" class="form-control" id="f-somma" name="f-somma" required>
              </div>
            </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><?php echo $translations["conf"]; ?>/button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $translations["cl"]; ?></button>
          </div>
          </form>
        </div>
      </div>
    </div>
    <!--pulsante con il quale puoi aggiungere altre fatture
    <button type="button" class="aggiungi btn btn-dark" style="border-radius: 45px; padding: 60px; margin: 20px;" onclick="addFattura()"> 
    </button>-->
    
  </div>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  <script type="text/javascript"> 

  //default è la data di oggi
  document.getElementById('f-data').valueAsDate = new Date();

  //recupera il valore del pulsante e mette il suo valore dentro
 // ottiene il bottone modifica
  const btnModifica = document.querySelectorAll('.btn-modifica');
  
  // itera sui bottoni e aggiunge un event listener per il click
  btnModifica.forEach(function (btn) {
    btn.addEventListener('click', function () {
      // ottiene il valore dell'attributo id del bottone
      const fatturaId = this.getAttribute('data-fattura-id');
      
      // assegna il valore del data-id al campo input f_id del modale modifica
      document.getElementById('f_id').value = fatturaId;
    });
  });

  function validateForm()
  {
    var utente = document.getElementsByName("f-utente")[0].value;
    if (utente == "nessuno") {
      document.querySelector('.alert-warning').style.display = 'block';
      return false;
    }
  return true;
  }

  function addFattura()
  {  
    $('#aggiungi').modal('show');
  }
  function modFattura()
  {  
    $('#modifica').modal('show');
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