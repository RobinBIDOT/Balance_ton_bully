<?php
/**
 * Gestion des demandes de formation.
 *
 * Ce script traite les demandes de formation envoyées via un formulaire.
 * Il enregistre les données dans une base de données et affiche un message
 * de succès après l'envoi du formulaire.
 */

// Activation de l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des fonctions et connexion à la base de données
include('../php/tools/functions.php');
$dbh = dbConnexion();
session_start();

// Vérification si le formulaire a été soumis.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collecte et nettoyage des données du formulaire.
    $prenom = $_POST['prenom'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $demande = $_POST['demande'] ?? '';

    /**
     * Préparation et exécution de la requête SQL pour insérer les données
     * du formulaire dans la base de données.
     */
    $query = $dbh->prepare("INSERT INTO demandes_formation (prenom, nom, mail, telephone, message) VALUES (?, ?, ?, ?, ?)");
    $query->execute([$prenom, $nom, $email, $telephone, $demande]);

    // Mise à jour de la variable pour le message de succès.
    $successMessage = "<div class='alert alert-success'>Votre demande a bien été envoyée.</div>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de formation</title>
    <?php include('../includes/headLink.php') ?>
    <link rel="stylesheet" href="../css/style-formations.css">
</head>
<body>
  <?php include('../includes/headerNav.php')?>
  <div class="container mt-4">
      <div class="row">
        <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="../ressources/discrimination.png" alt="Image gauche" class="img-fluid" style="max-width: 100px;">
                </div>
                <h1 class="flex-grow-1 text-center titre-formations">Formations de sensibilisation à la lutte contre le harcèlement scolaire</h1>
                <div class="d-flex align-items-center">
                    <img src="../ressources/stop-bullying-2.png" alt="Image droite" class="img-fluid" style="max-width: 100px;">
                </div>
            </div>
        </div>
      <p class="formation-contenu">La lutte contre le harcèlement scolaire est un enjeu crucial dans le système éducatif. Ce phénomène, qui affecte de nombreux élèves, peut avoir des répercussions profondes et durables sur la santé mentale, le bien-être et la réussite scolaire des jeunes. Se former à la lutte contre le harcèlement scolaire n'est donc pas seulement une responsabilité, mais une nécessité impérative pour tous les acteurs impliqués dans le milieu éducatif, qu'ils soient enseignants, administrateurs scolaires, conseillers, parents ou même élèves.<br><br>
        D'abord, la formation permet de mieux comprendre la nature et les manifestations du harcèlement scolaire. Elle aide à identifier les différents types de harcèlement – physique, verbal, psychologique ou en ligne – et à reconnaître les signes parfois subtils chez les victimes. Cette compréhension approfondie est la première étape pour intervenir efficacement et prévenir les situations de harcèlement.<br><br>
        Ensuite, se former offre des outils et des stratégies pour agir. Cela inclut des techniques de communication, des approches de résolution de conflits et des méthodes pour créer un environnement scolaire sécurisant et inclusif. Les éducateurs formés sont mieux équipés pour gérer les incidents de harcèlement de manière sensible et efficace, en soutenant la victime et en adressant le comportement du harceleur de manière constructive.<br><br>
        La formation joue également un rôle crucial dans la prévention. En intégrant les principes de respect, d'empathie et de bienveillance dans le curriculum et la culture scolaire, on crée un environnement où le harcèlement est moins susceptible de se produire. Les élèves eux-mêmes, lorsqu'ils sont formés, peuvent devenir des acteurs de changement, en s'opposant au harcèlement et en soutenant leurs pairs.<br><br>
        De plus, la formation à la lutte contre le harcèlement scolaire contribue à une prise de conscience collective. Elle sensibilise toute la communauté éducative aux effets dévastateurs du harcèlement, renforçant ainsi l'engagement de chacun à créer un espace d'apprentissage sûr et accueillant pour tous.<br><br>
        Enfin, face à l'évolution constante des modes de communication, notamment avec les réseaux sociaux et les technologies numériques, se former permet de rester à jour sur les nouvelles formes de harcèlement et les meilleures pratiques pour les contrer.<br><br>
        En somme, se former à la lutte contre le harcèlement scolaire est essentiel pour protéger les élèves, favoriser leur épanouissement et garantir un environnement d'apprentissage sain et respectueux. C'est une démarche qui bénéficie à l'ensemble de la société, en contribuant à la formation de citoyens responsables et empathiques, aptes à construire un avenir plus bienveillant.</p>   
      <p>Veuillez remplir le formulaire ci-dessous pour demander plus d'informations ou un devis pour une formation :</p>
      <div class="Formulaire-formations p-3 rounded">
          <form action="formations.php" method="post" class="mt-4">
              <!-- Affiche le message de succès si défini -->
              <?php if (!empty($successMessage)) echo $successMessage; ?>
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label for="prenom" class="form-label">Prénom :</label>
                      <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Votre prénom" required>
                  </div>
                  <div class="col-md-6 mb-3">
                      <label for="nom" class="form-label">Nom :</label>
                      <input type="text" class="form-control" id="nom" name="nom" placeholder="Votre nom" required>
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label for="telephone" class="form-label">Téléphone :</label>
                      <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="Votre téléphone" required>
                  </div>
                  <div class="col-md-6 mb-3">
                      <label for="email" class="form-label">Votre e-mail :</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Votre e-mail" required>
                  </div>
              </div>
              <div class="mb-3">
                  <label for="demande" class="form-label">Expliquer votre demande de formation :</label>
                  <textarea class="form-control" id="demande" name="demande" rows="5" placeholder="Votre demande..." required></textarea>
              </div>
              <button type="submit" class="btn btn-primary">Envoyer</button>
          </form>
      </div>
      <div class="p-3">
        <p class="mt-3">* Nous vous recontacterons dans les meilleurs délais pour vous fournir un devis.</p>
      </div>
    </div>
  <?php include('../includes/footer.php') ?>
  <?php include('../includes/scriptLink.php') ?>
</body>
</html>