<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include './db.php';

$offres_par_page = 10;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $offres_par_page;

// Initialisation des filtres
$filters = [];

// Traitement des filtres
if (isset($_GET['metier'])) {
  $metier = implode(',', array_map('intval', $_GET['metier']));
  $filters[] = "o.type_metier_id IN ($metier)";
}

if (isset($_GET['contrat'])) {
  $contrat = implode(',', array_map('intval', $_GET['contrat']));
  $filters[] = "o.type_contrat_id IN ($contrat)";
}

if (isset($_GET['ville'])) {
  $ville = implode(',', array_map('intval', $_GET['ville']));
  $filters[] = "o.ville_id IN ($ville)";
}

// Construction de la requête SQL avec les filtres
$sql = "SELECT o.date_publication, e.nom as entreprise, o.intitule, o.reference, m.type as metier, c.type as contrat, v.nom as ville 
        FROM offres o 
        JOIN entreprises e ON o.entreprise_id = e.id
        JOIN types_metier m ON o.type_metier_id = m.id
        JOIN types_contrat c ON o.type_contrat_id = c.id
        JOIN villes v ON o.ville_id = v.id";

if (!empty($filters)) {
  $sql .= ' WHERE ' . implode(' AND ', $filters);
}

// Traitement du tri
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
switch ($sort) {
  case 'date_asc':
    $sql .= " ORDER BY o.date_publication ASC";
    break;
  case 'date_desc':
    $sql .= " ORDER BY o.date_publication DESC";
    break;
  case 'alpha_asc':
    $sql .= " ORDER BY o.intitule ASC";
    break;
  case 'alpha_desc':
    $sql .= " ORDER BY o.intitule DESC";
    break;
}

$sql .= " LIMIT $offres_par_page OFFSET $offset";

$result = $mysqli->query($sql);

// Comptez le nombre total d'offres après filtrage
$sql_count = "SELECT COUNT(*) as total FROM offres o";

if (!empty($filters)) {
  $sql_count .= ' WHERE ' . implode(' AND ', $filters);
}

$total_offres = $mysqli->query($sql_count)->fetch_assoc()['total'];
$total_pages = ceil($total_offres / $offres_par_page);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Job Board</title>
  <link rel="stylesheet" href="./style.css">
</head>

<body>
  <div class="container">
    <form action="index.php" method="get" id="filterForm">
      <!-- Filtre pour Métier -->
      <fieldset>
        <legend>Métier:</legend>
        <?php
        $resultMetier = $mysqli->query("SELECT * FROM types_metier");
        while ($row = $resultMetier->fetch_assoc()) {
          $checked = isset($_GET['metier']) && in_array($row['id'], $_GET['metier']) ? 'checked' : '';
          echo '<label><input type="checkbox" name="metier[]" value="' . $row['id'] . '" ' . $checked . '>' . $row['type'] . '</label>';
        }
        ?>
      </fieldset>

      <!-- Filtre pour Contrat -->
      <fieldset>
        <legend>Contrat:</legend>
        <?php
        $resultContrat = $mysqli->query("SELECT * FROM types_contrat");
        while ($row = $resultContrat->fetch_assoc()) {
          $checked = isset($_GET['contrat']) && in_array($row['id'], $_GET['contrat']) ? 'checked' : '';
          echo '<label><input type="checkbox" name="contrat[]" value="' . $row['id'] . '" ' . $checked . '>' . $row['type'] . '</label>';
        }
        ?>
      </fieldset>

      <!-- Filtre pour Ville -->
      <fieldset>
        <legend>Ville:</legend>
        <?php
        $resultVille = $mysqli->query("SELECT * FROM villes");
        while ($row = $resultVille->fetch_assoc()) {
          $checked = isset($_GET['ville']) && in_array($row['id'], $_GET['ville']) ? 'checked' : '';
          echo '<label><input type="checkbox" name="ville[]" value="' . $row['id'] . '" ' . $checked . '>' . $row['nom'] . '</label>';
        }
        ?>
      </fieldset>

      <input type="submit" value="Filtrer">
    </form>

    <form action="index.php" method="get" id="sortForm">
      <!-- Inclusion des paramètres de filtre actuels comme champs cachés -->
      <?php
      if (isset($_GET['metier'])) {
        foreach ($_GET['metier'] as $value) {
          echo '<input type="hidden" name="metier[]" value="' . htmlspecialchars($value) . '">';
        }
      }
      if (isset($_GET['contrat'])) {
        foreach ($_GET['contrat'] as $value) {
          echo '<input type="hidden" name="contrat[]" value="' . htmlspecialchars($value) . '">';
        }
      }
      if (isset($_GET['ville'])) {
        foreach ($_GET['ville'] as $value) {
          echo '<input type="hidden" name="ville[]" value="' . htmlspecialchars($value) . '">';
        }
      }
      ?>

      <label for="sort">Trier par :</label>
      <select name="sort" id="sort" onchange="this.form.submit()">
        <option value="date_asc" <?= $sort === 'date_asc' ? 'selected' : '' ?>>Date de publication (ascendant)</option>
        <option value="date_desc" <?= $sort === 'date_desc' ? 'selected' : '' ?>>Date de publication (descendant)</option>
        <option value="alpha_asc" <?= $sort === 'alpha_asc' ? 'selected' : '' ?>>Ordre alphabétique (ascendant)</option>
        <option value="alpha_desc" <?= $sort === 'alpha_desc' ? 'selected' : '' ?>>Ordre alphabétique (descendant)</option>
      </select>
    </form>

    <?php
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


    <?php
    // Comptez le nombre total d'offres après filtrage
    $sql_count = "SELECT COUNT(*) as total FROM offres o";

    if (!empty($filters)) {
      $sql_count .= ' WHERE ' . implode(' AND ', $filters);
    }

    $total_offres = $mysqli->query($sql_count)->fetch_assoc()['total'];

    // Calculez le nombre total de pages en fonction du nombre d'offres résultantes
    $total_pages = ceil($total_offres / $offres_par_page);
    ?>

    <div class="pagination">
      <?php
      $queryParams = $_GET; // Copiez les paramètres d'URL actuels

      // Vérifiez si les paramètres d'URL existent et sont des tableaux, sinon initialisez-les comme des tableaux vides
      $queryParams['metier'] = isset($_GET['metier']) && is_array($_GET['metier']) ? $_GET['metier'] : [];
      $queryParams['contrat'] = isset($_GET['contrat']) && is_array($_GET['contrat']) ? $_GET['contrat'] : [];
      $queryParams['ville'] = isset($_GET['ville']) && is_array($_GET['ville']) ? $_GET['ville'] : [];

      for ($page = 1; $page <= $total_pages; $page++) :
        $queryParams['page'] = $page; // Ajoutez le numéro de page aux paramètres d'URL
      ?>
        <a href="index.php?<?= http_build_query($queryParams) ?>"><?= $page ?></a>
      <?php endfor; ?>
    </div>

    <?php $mysqli->close(); ?>
  </div>
</body>

</html>