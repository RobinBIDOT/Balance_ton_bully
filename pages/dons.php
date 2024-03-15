<?php
    // Inclusion du fichier de connexion à la base de données
    include('../php/tools/functions.php');
    $dbh = dbConnexion();
    session_start();

    // Afficher les informations de session
    //    echo "<pre>";
    //    var_dump($_SESSION);
    //    echo "</pre>";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de dons</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                        <button type="button" id="donUneFois" class="btn btn-primary">Je donne une fois</button>
                        <button type="button" id="donMensuel" class="btn btn-primary">Je donne tous les mois</button>
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
            <div class="blue-background mt-5">
                <form>
                    <h3>Réduction fiscale</h3>
                    <p>Le don à Balance ton Bully ouvre le droit à une réduction fiscale car il remplit les conditions générales prévues aux articles 200 et 238 bis du code général des impôts.</p>
                    <button type="button" id="btnParticulier" class="btn btn-primary">Particulier</button>
                    <button type="button" id="btnOrganisme" class="btn btn-primary">Organisme</button>
                    <p id="infoReduction"></p>
                </form>
            </div>
        </div>

        <!-- Colonne centrale -->
        <div class="col-md-4">
            <div class="blue-background" id="mesCoordonnees">
                <form>
                    <h3>Mes coordonnées</h3>
                    <div id="champsOrganisme" style="display: none;">
                        <div class="mb-3">
                            <label for="raisonSociale" class="form-label">Raison sociale</label>
                            <input type="text" class="form-control mb-2" placeholder="Raison sociale">
                        </div>
                        <div class="mb-3">
                            <label for="siren" class="form-label">SIREN</label>
                            <input type="text" class="form-control mb-2" placeholder="SIREN">
                        </div>
                        <div class="mb-3">
                            <label for="formeJuridique" class="form-label">Forme juridique</label>
                            <input type="text" class="form-control mb-2" placeholder="Forme juridique">
                        </div>
                    </div>
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
                    <div class="mb-3 form-check">
                        <p id="typeDon"></p>
                        <p id="montantTotal"></p>
                        <input type="checkbox" class="form-check-input" id="acceptConditions">
                        <label class="form-check-label" for="acceptConditions">J’accepte les Conditions Générales d’Utilisation du service et j’ai lu la charte de confidentialité *</label>
                    </div>
                </form>
            </div>
            <div class="text-center mt-5">
                <button type="submit" class="btn btn-primary" disabled id="validerPayer">Valider et payer</button>
            </div>
            <div class="blue-background mt-5">
                <form>
                    <p>Plateforme de paiement 100 % sécurisée</p>
                    <p>Toutes les informations bancaires pour traiter ce paiement sont totalement sécurisées. Grâce au cryptage SSL de vos données bancaires, vous êtes assurés de la fiabilité de vos transactions sur notre site.</p>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('../includes/footer.php') ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Récupération des éléments du DOM
        let btnParticulier = $('#btnParticulier');
        let btnOrganisme = $('#btnOrganisme');
        let btnDonUneFois = $('#donUneFois');
        let btnDonMensuel = $('#donMensuel');
        let infoReduction = $('#infoReduction');
        let montants = $('input[name="montant"]');
        let montantLibre = $('#montantLibre');
        let divCoordonnees = $('#mesCoordonnees');
        let recapDon = $('#recapDon');
        let typeDon = $('#typeDon');
        let montantTotal = $('#montantTotal');
        let validerPayer = $('#validerPayer');

        // Fonction pour ajouter des champs pour les organismes
        function ajouterChampsOrganisme() {
            if ($('#champsOrganisme').children().length === 0) {
                divCoordonnees.append(`
                <div id="champsOrganisme">
                    <div class="mb-3">
                        <label for="raisonSociale" class="form-label">Raison sociale</label>
                        <input type="text" class="form-control mb-2" placeholder="Raison sociale">
                    </div>
                    <div class="mb-3">
                        <label for="siren" class="form-label">SIREN</label>
                        <input type="text" class="form-control mb-2" placeholder="SIREN">
                    </div>
                    <div class="mb-3">
                        <label for="formeJuridique" class="form-label">Forme juridique</label>
                        <input type="text" class="form-control mb-2" placeholder="Forme juridique">
                    </div>
                </div>
            `);
            }
        }

        // Fonction pour changer les montants des dons
        function changerMontants(montantsDon) {
            montants.each(function() {
                let montantInput = $(this);
                let valeur = montantsDon[montantInput.val()];
                if (valeur) {
                    montantInput.val(valeur).next().text(' ' + valeur + ' €');
                }
            });
        }

        // Mise à jour de l'information fiscale et du montant après réduction
        function miseAJourInfoFiscale(montant) {
            let montantReduit = btnParticulier.is(':checked') ? montant * 0.66 : montant * 0.60;
            infoReduction.text(`Votre don ne vous coûtera que ${montantReduit.toFixed(2)} € après réduction fiscale`);
        }

        // Événements
        btnParticulier.click(function() {
            $('#champsOrganisme').remove();
            infoReduction.text("Particulier : vous pouvez bénéficier d’une réduction d’impôt égale à 66 % du montant de votre don , dans la limite de 20 % de votre revenu imposable.");
        });

        btnOrganisme.click(function() {
            ajouterChampsOrganisme();
            infoReduction.text("Entreprise : l’ensemble des versements à notre association permet de bénéficier d’une réduction d’impôt sur les sociétés de 60 % du montant de ces versements, plafonnée à 20 000 € ou 5 ‰ (5 pour mille) du chiffre d'affaires annuel hors taxe de l’entreprise. En cas de dépassement de plafond, l'excédent est reportable sur les 5 exercices suivants.");
        });

        btnDonMensuel.click(function() {
            changerMontants({'5': '9', '20': '12', '50': '30', '150': '70'});
            typeDon.text("Don mensuel");
        });

        btnDonUneFois.click(function() {
            changerMontants({'9': '5', '12': '20', '30': '50', '70': '150'});
            typeDon.text("Don ponctuel");
        });

        montants.change(function() {
            let montantChoisi = parseFloat($(this).val());
            miseAJourInfoFiscale(montantChoisi);
            montantTotal.text(`Montant total: ${montantChoisi} €`);
        });

        montantLibre.change(function() {
            let montantLibreChoisi = parseFloat(montantLibre.val());
            miseAJourInfoFiscale(montantLibreChoisi);
            montantTotal.text(`Montant total: ${montantLibreChoisi} €`);
        });

        $('#acceptConditions').change(function() {
            validerPayer.prop('disabled', !$(this).is(':checked'));
        });
    });

</script>
</html>
