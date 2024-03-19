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
        .btn-selected {
            background-color: #17a2b8 !important;
            border-color: #17a2b8 !important;
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
                        <label for="montantLibre" class="form-label">Montant libre</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="montantLibre" placeholder="Montant libre">
                            <button type="button" id="confirmMontantLibre" class="btn btn-primary">Confirmer</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="blue-background mt-5">
                <form>
                    <h3>Réduction fiscale</h3>
                    <p id="infoReduction"></p>
                    <button type="button" id="btnParticulier" class="btn btn-primary">Particulier</button>
                    <button type="button" id="btnOrganisme" class="btn btn-primary">Organisme</button>
                    <p id="infoReductionCategorie"></p>
                </form>
            </div>
        </div>

        <!-- Colonne centrale -->
        <div class="col-md-4">
            <div class="blue-background" id="mesCoordonnees">
                <form>
                    <h3>Mes coordonnées</h3>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="payerOrganisme">
                        <label class="form-check-label" for="payerOrganisme">Payer en tant qu'organisme</label>
                    </div>
                    <!-- Ajout du formulaire pour les organismes -->
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
                    <!-- Fin du formulaire pour les organismes -->
                    <div class="mb-3 row">
                        <div class="col">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                        </div>
                        <div class="col">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
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
                    <div class="mb-3 row">
                        <div class="col">
                            <label for="codePostal" class="form-label">Code postal</label>
                            <input type="text" class="form-control" id="codePostal" name="codePostal" required>
                        </div>
                        <div class="col">
                            <label for="ville" class="form-label">Ville</label>
                            <input type="text" class="form-control" id="ville" name="ville" required>
                        </div>
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
        let typeDon = $('#typeDon');
        let montantTotal = $('#montantTotal');
        let validerPayer = $('#validerPayer');
        let payerOrganismeCheckbox = $('#payerOrganisme');
        let champsOrganisme = $('#champsOrganisme');
        let infoReductionCategorie = $('#infoReductionCategorie');
        let montantChoisi = NaN;

        // Fonction pour ajouter des champs pour les organismes
        function afficherChampsOrganisme() {
            champsOrganisme.show();
        }

        function cacherChampsOrganisme() {
            champsOrganisme.hide();
        }

        // Mise à jour de l'affichage en fonction du type de paiement
        function miseAJourAffichagePaiement() {
            if (payerOrganismeCheckbox.is(':checked')) {
                afficherChampsOrganisme();
            } else {
                cacherChampsOrganisme();
            }
        }

        // Fonction pour calculer la réduction fiscale en fonction du type de paiement
        function calculerReductionFiscale(montant) {
            // Vérifier si un montant valide est saisi
            if (!isNaN(montant)) {
                let reduction;
                if (payerOrganismeCheckbox.is(':checked')) {
                    // Si le paiement est fait en tant qu'organisme
                    reduction = montant * 0.60; // 60% de réduction pour les organismes
                } else {
                    // Si le paiement est fait en tant que particulier
                    reduction = montant * 0.66; // 66% de réduction pour les particuliers
                }
                return (montant - reduction).toFixed(2);
            } else {
                return "0.00"; // Retourner 0 si aucun montant n'est saisi
            }
        }

        // Événement pour la case à cocher "Payer en tant qu'organisme"
        payerOrganismeCheckbox.change(function() {
            let montantChoisi = parseFloat($('input[name="montant"]:checked').val());
            let montantReduit = calculerReductionFiscale(montantChoisi);
            infoReduction.text(`Votre don ne vous coûtera que ${montantReduit} € après réduction fiscale`);
        });


        // Fonction pour mettre à jour le montant choisi
        function mettreAJourMontantChoisi(montant) {
            montantChoisi = montant;
        }

        // Mettez à jour la fonction mettreAJourReductionFiscale pour utiliser le montant choisi
        function mettreAJourReductionFiscale() {
            let montantReduit = calculerReductionFiscale(montantChoisi);
            infoReduction.text(`Votre don ne vous coûtera que ${montantReduit} € après réduction fiscale`);
        }

        // Événements
        // Fonction pour afficher les informations de réduction fiscale en fonction du type de don sélectionné
        function afficherInfosReductionCategorie(categorie) {
            infoReductionCategorie.text(categorie);
            let montantChoisi = parseFloat($('input[name="montant"]:checked').val());
            let montantReduit = calculerReductionFiscale(montantChoisi);
            infoReduction.text(`Votre don ne vous coûtera que ${montantReduit} € après réduction fiscale`);
        }

        // Événements pour les boutons de type de don
        btnParticulier.click(function() {
            $(this).addClass('btn-selected');
            btnOrganisme.removeClass('btn-selected');
            afficherInfosReductionCategorie("Particulier : vous pouvez bénéficier d’une réduction d’impôt égale à 66 % du montant de votre don , dans la limite de 20 % de votre revenu imposable.");
            payerOrganismeCheckbox.prop('checked', false);
            cacherChampsOrganisme();
            // Mettre à jour le montant choisi avec le montant libre si défini
            if (!isNaN(montantLibreChoisi)) {
                montantChoisi = montantLibreChoisi;
            } else {
                montantChoisi = parseFloat($('input[name="montant"]:checked').val());
            }
            mettreAJourMontantChoisi(parseFloat($('input[name="montant"]:checked').val()));
            mettreAJourReductionFiscale();
        });

        btnOrganisme.click(function() {
            $(this).addClass('btn-selected');
            btnParticulier.removeClass('btn-selected');
            afficherInfosReductionCategorie("Entreprise : l’ensemble des versements à notre association permet de bénéficier d’une réduction d’impôt sur les sociétés de 60 % du montant de ces versements, plafonnée à 20 000 € ou 5 ‰ (5 pour mille) du chiffre d'affaires annuel hors taxe de l’entreprise. En cas de dépassement de plafond, l'excédent est reportable sur les 5 exercices suivants.");
            payerOrganismeCheckbox.prop('checked', true);
            afficherChampsOrganisme();
            // Mettre à jour le montant choisi avec le montant libre si défini
            if (!isNaN(montantLibreChoisi)) {
                montantChoisi = montantLibreChoisi;
            } else {
                montantChoisi = parseFloat($('input[name="montant"]:checked').val());
            }
            mettreAJourMontantChoisi(parseFloat($('input[name="montant"]:checked').val()));
            mettreAJourReductionFiscale();
        });

        // Événement pour la case à cocher "Payer en tant qu'organisme"
        payerOrganismeCheckbox.change(function() {
            if ($(this).is(':checked')) {
                btnOrganisme.trigger('click');
                champsOrganisme.show(); // Afficher les champs pour les organismes si la case est cochée
            } else {
                btnParticulier.trigger('click');
                champsOrganisme.hide(); // Sinon, les masquer
            }
            mettreAJourReductionFiscale();
        });

        btnDonMensuel.click(function() {
            $(this).addClass('btn-selected');
            btnDonUneFois.removeClass('btn-selected');
            typeDon.text("Don mensuel");
        });

        btnDonUneFois.click(function() {
            $(this).addClass('btn-selected');
            btnDonMensuel.removeClass('btn-selected');
            typeDon.text("Don ponctuel");
        });

        // Fonction pour mettre à jour le montant total dans le récapitulatif
        function mettreAJourMontantTotal(montant) {
            montantTotal.text(`Montant total : ${montant} €`);
        }

        // Fonction pour gérer le changement de montant choisi
        function gererChangementMontantChoisi() {
            let montantChoisi = parseFloat($('input[name="montant"]:checked').val());
            mettreAJourMontantTotal(montantChoisi);
            let montantReduit = calculerReductionFiscale(montantChoisi);
            infoReduction.text(`Votre don ne vous coûtera que ${montantReduit} € après réduction fiscale`);
        }

        // Gestionnaire d'événement pour le changement de montant choisi
        montants.change(function() {
            gererChangementMontantChoisi();
        });

        montants.change(function() {
            let montantChoisi = parseFloat($(this).val());
            let montantReduit = calculerReductionFiscale(montantChoisi);
            infoReduction.text(`Votre don ne vous coûtera que ${montantReduit} € après réduction fiscale`);
        });


        // Fonction pour réinitialiser le champ de saisie du montant libre
        function reinitialiserMontantLibre() {
            montantLibre.val('');
        }

        // Gestionnaire d'événement pour le bouton de confirmation du montant libre
        $('#confirmMontantLibre').click(function() {
            montantLibreChoisi = parseFloat(montantLibre.val());
            if (!isNaN(montantLibreChoisi)) {
                $('input[name="montant"]').prop('checked', false); // Désélectionner les autres montants
                montantChoisi = montantLibreChoisi; // Mettre à jour le montant choisi avec le montant libre
                mettreAJourMontantTotal(montantLibreChoisi);
                mettreAJourReductionFiscale(); // Mettre à jour la réduction fiscale
            }
        });

        // Gestionnaire d'événement pour les boutons radio des montants prédéfinis
        $('input[name="montant"]').change(function() {
            // Réinitialiser le champ de saisie du montant libre lorsqu'un montant prédéfini est sélectionné
            reinitialiserMontantLibre();
            // Mettre à jour le montant choisi avec le montant prédéfini sélectionné
            montantChoisi = parseFloat($(this).val());
            mettreAJourMontantTotal(montantChoisi);
            mettreAJourReductionFiscale(); // Mettre à jour la réduction fiscale
        });

        $('#acceptConditions').change(function() {
            validerPayer.prop('disabled', !$(this).is(':checked'));
        });
    });
</script>
</body>
</html>