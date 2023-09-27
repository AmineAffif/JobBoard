<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  include './db.php'; // Incluez le fichier de connexion à la base de données

  $result = $mysqli->query("SELECT * FROM offres");
  while ($row = $result->fetch_assoc()) {
    echo $row['intitule'] . '<br>';
  }

  $mysqli->close(); // Fermez la connexion à la base de données
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Job Board</title>
  <style>
    body {
      font-family: Montserrat, Helvetica, sans-serif;
      font-size: .9em;
      color: #000000;
      background-color: #FFFFFF;
      margin: 0;
      padding: 10px 20px 20px 20px;
    }
  </style>
</head>

<body>
  <p class="text"><strong>Welcome to MAMP</strong></p>
</body>

</html>