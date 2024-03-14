<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de dons</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        .blue-background {
            background-color: #0854C7;
            padding: 20px;
            color: white;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<?php include('../includes/headerNav.php') ?>
<div class="container-fluid mt-5 custom-container">
    <div class="row">
        <!-- Colonne de gauche -->
        <div class="col-md-4">
            <div class="blue-background">
            <form>
                <h3>Mon don</h3>
                <div class="mb-3">
                    <button type="button" class="btn btn-primary">Je donne une fois</button>
                    <button type="button" class="btn btn-primary">Je donne tous les mois</button>
                </div>
                <div class="mb-3">
                    <label for="montant">Montant :</label><br>
                    <input type="radio" name="montant" value="5"> 5 €
                    <input type="radio" name="montant" value="20"> 20 €
                    <input type="radio" name="montant" value="50"> 50 €
                    <input type="radio" name="montant" value="150"> 150 €<br>
                    <input type="text" class="form-control mt-2" id="montantLibre" placeholder="Montant libre">
                </div>
            </form>
            </div>
                <div class="blue-background">
            <form>
                <h3>Réduction fiscale</h3>
                <p>Le don à Balance ton Bully ouvre le droit à une réduction fiscale car il remplit les conditions générales prévues aux articles 200 et 238 bis du code général des impôts.</p>
                <button type="button" class="btn btn-primary">Particulier</button>
                <button type="button" class="btn btn-primary">Organisme</button>
                <p id="infoReduction"></p>
            </form>
                </div>
        </div>

        <!-- Colonne centrale -->
        <div class="col-md-4">
            <div class="blue-background">
            <form>
                <h3>Mes coordonnées</h3>
                <div class="mb-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                </div>
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="dateNaissance" class="form-label">Date de naissance</label>
                    <input type="date" class="form-control" id="dateNaissance" name="dateNaissance" required>
                </div>
                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <input type="text" class="form-control" id="adresse" name="adresse" required>
                </div>
                <div class="mb-3">
                    <label for="codePostal" class="form-label">Code postal</label>
                    <input type="text" class="form-control" id="codePostal" name="codePostal" required>
                </div>
                <div class="mb-3">
                    <label for="ville" class="form-label">Ville</label>
                    <input type="text" class="form-control" id="ville" name="ville" required>
                </div>
                <div class="mb-3">
                    <label for="pays" class="form-label">Pays</label>
                    <input type="text" class="form-control" id="pays" name="pays" required>
                </div>
            </form>
            </div>
        </div>

        <!-- Colonne de droite -->
        <div class="col-md-4">
            <div class="blue-background">
            <form>
                <h3>Mon récapitulatif</h3>
                <!-- Informations sur le montant du don et le total -->
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="acceptConditions">
                    <label class="form-check-label" for="acceptConditions">J’accepte les Conditions Générales d’Utilisation du service et j’ai lu la charte de confidentialité *</label>
                </div>
            </div>
            </form>
                <button type="submit" class="btn btn-primary">Valider et payer</button>
                <div class="blue-background">
                <form>
                <p class="mt-3">Plateforme de paiement 100 % sécurisée</p>
                <p>Toutes les informations bancaires pour traiter ce paiement sont totalement sécurisées. Grâce au cryptage SSL de vos données bancaires, vous êtes assurés de la fiabilité de vos transactions sur notre site.</p>
            </form>
                </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
