<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include './db.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Job Board</title>
  <link rel="stylesheet" href="./style.css">
  <style>
  </style>
</head>

<body>
  <div class="container">
    <?php
    $result = $mysqli->query("SELECT o.date_publication, e.nom as entreprise, o.intitule, o.reference, m.type as metier, c.type as contrat, v.nom as ville 
                                  FROM offres o 
                                  JOIN entreprises e ON o.entreprise_id = e.id
                                  JOIN types_metier m ON o.type_metier_id = m.id
                                  JOIN types_contrat c ON o.type_contrat_id = c.id
                                  JOIN villes v ON o.ville_id = v.id");

    while ($row = $result->fetch_assoc()) :
    ?>
      <div class="offre">
        <div class="image"><img src="https://via.placeholder.com/150" alt="Image"></div>
        <div class="infos">
          <p>Date de publication: <?= $row['date_publication'] ?></p>
          <p>Entreprise/Intitulé: <?= $row['entreprise'] ?>/<?= $row['intitule'] ?></p>
          <p>Référence: <?= $row['reference'] ?></p>
          <p>Métier: <?= $row['metier'] ?></p>
          <p>Contrat: <?= $row['contrat'] ?></p>
          <p>Ville: <?= $row['ville'] ?></p>
        </div>
      </div>
    <?php endwhile; ?>

    <?php $mysqli->close(); ?>
  </div>
</body>

</html>