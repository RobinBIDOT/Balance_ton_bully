/**
 * Initialise le script et configure les gestionnaires d'événements.
 */
$(document).ready(function() {
    // Déclaration des variables
    let btnParticulier = $('#btnParticulier'); // Bouton pour les particuliers
    let btnOrganisme = $('#btnOrganisme'); // Bouton pour les organismes
    let btnDonUneFois = $('#donUneFois'); // Bouton pour un don ponctuel
    let btnDonMensuel = $('#donMensuel'); // Bouton pour un don mensuel
    let infoReduction = $('#infoReduction'); // Informations sur la réduction fiscale
    let montants = $('input[name="montant"]'); // Options de montant
    let montantLibre = $('#montantLibre'); // Champ de saisie pour un montant libre
    let typeDon = $('#typeDon'); // Affichage du type de don sélectionné
    let montantTotal = $('#montantTotal'); // Affichage du montant total du don
    let validerPayer = $('#validerPayer'); // Bouton de validation du paiement
    let payerOrganismeCheckbox = $('#payerOrganisme'); // Case à cocher pour payer en tant qu'organisme
    let champsOrganisme = $('#champsOrganisme'); // Champs supplémentaires pour les organismes
    let infoReductionCategorie = $('#infoReductionCategorie'); // Informations sur la réduction fiscale en fonction de la catégorie
    let montantLibreChoisi = NaN; // Montant libre choisi
    // Activer le bouton "Je donne une fois" et "Particulier"
    $('#donUneFois').addClass('btn-selected');
    $('#donMensuel').removeClass('btn-selected');
    $('#btnParticulier').addClass('btn-selected');
    $('#btnOrganisme').removeClass('btn-selected');
    // Sélectionner le montant par défaut (5 €) et déclencher un événement de changement
    $('input[name="montant"][value="5"]').prop('checked', true).trigger('change');
    // Mettre à jour les informations de réduction fiscale et les détails spécifiques aux particuliers
    let montantChoisi = parseFloat($('input[name="montant"]:checked').val());
    mettreAJourMontantTotal(montantChoisi);
    afficherInfosReductionCategorie("Particulier : vous pouvez bénéficier d’une réduction d’impôt égale à 66 % du montant de votre don , dans la limite de 20 % de votre revenu imposable.");
    // Mettre à jour directement le texte de récapitulatif
    updateRecapitulatif('Don ponctuel');


    /**
     * Vérifie si tous les champs requis du formulaire sont remplis.
     *
     * @returns {boolean} Vrai si tous les champs requis sont remplis, sinon faux.
     */
    function sontTousLesChampsRemplis() {
        let sontRemplis = true;
        $('#mesCoordonnees input[required]').each(function() {
            if ($(this).val() === '') {
                sontRemplis = false;
            }
        });
        if (payerOrganismeCheckbox.is(':checked')) {
            let siren = $('#siren').val();
            if (!/^\d{9}$/.test(siren)) {
                sontRemplis = false;
            }
        }
        return sontRemplis;
    }
    /**
     * Vérifie si tous les champs requis sont remplis et si les conditions générales d'utilisation
     * sont acceptées, et active ou désactive le bouton 'Valider et payer' en conséquence.
     */
    function evaluerEtatBoutonPaiement() {
        let estValide = sontTousLesChampsRemplis() && $('#acceptConditions').is(':checked');
        $('#validerPayer').prop('disabled', !estValide);
    }

    // Appeler immédiatement pour évaluer l'état initial du formulaire
    evaluerEtatBoutonPaiement();

    // Gestionnaire d'événement pour la modification des champs requis et la case à cocher 'acceptConditions'
    $('#mesCoordonnees input[required], #acceptConditions').on('change', function() {
        evaluerEtatBoutonPaiement();
    });

    // Désactiver le bouton "Valider et payer" par défaut jusqu'à la validation du formulaire.
    $('#validerPayer').prop('disabled', true);


    /**
     * Gestionnaire d'événements pour la modification des champs requis.
     * Appelle validerFormulaireCoordonnees à chaque modification pour vérifier
     * la validité de tous les champs requis.
     */
    $('#mesCoordonnees input, #acceptConditions, #payerOrganisme').on('change', function() {
        evaluerEtatBoutonPaiement();
    });

    // Désactiver le bouton 'Valider et payer' par défaut.
    $('#validerPayer').prop('disabled', true);


    /**
     * Met à jour le récapitulatif avec le type de don spécifié.
     *
     * Cette fonction modifie le contenu textuel de l'élément HTML identifié
     * par l'ID 'typeDon' pour afficher le type de don sélectionné.
     * Elle est utilisée pour refléter le choix de l'utilisateur sur le type
     * de don (par exemple, "Don ponctuel" ou "Don mensuel") dans la section
     * du récapitulatif de la page.
     *
     * @param {string} typeDon - Le type de don à afficher dans le récapitulatif.
     *                           Doit être une chaîne de caractères telle que
     *                           "Don ponctuel" ou "Don mensuel".
     */
    function updateRecapitulatif(typeDon) {
        $('#typeDon').text(typeDon);
    }

    /**
     * Affiche les champs supplémentaires pour les organismes.
     */
    function afficherChampsOrganisme() {
        champsOrganisme.show();
    }

    /**
     * Cache les champs supplémentaires pour les organismes.
     */
    function cacherChampsOrganisme() {
        champsOrganisme.hide();
    }

    /**
     * Calcule la réduction fiscale en fonction du montant du don et du type de paiement.
     * @param {number} montant - Le montant du don.
     * @returns {string} - Le montant après réduction fiscale formaté en chaîne de caractères.
     */
    function calculerReductionFiscale(montant) {
        if (!isNaN(montant)) {
            let reduction;
            if (payerOrganismeCheckbox.is(':checked')) {
                reduction = montant * 0.60; // 60% de réduction pour les organismes
            } else {
                reduction = montant * 0.66; // 66% de réduction pour les particuliers
            }
            return (montant - reduction).toFixed(2);
        } else {
            return "0.00";
        }
    }

    // Événement pour la case à cocher "Payer en tant qu'organisme"
    payerOrganismeCheckbox.change(function() {
        let montantChoisi = parseFloat($('input[name="montant"]:checked').val());
        let montantReduit = calculerReductionFiscale(montantChoisi);
        infoReduction.text(`Votre don ne vous coûtera que ${montantReduit} € après réduction fiscale`);
    });


    /**
     * Met à jour le montant choisi pour le don.
     * @param {number} montant - Le montant du don choisi.
     */
    function mettreAJourMontantChoisi(montant) {
        montantChoisi = montant;
    }

    /**
     * Met à jour les informations sur la réduction fiscale en fonction du montant choisi.
     */
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
        // Sélectionne le mode particulier
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
        // Sélectionne le mode organisme
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

    // Gestionnaire d'événement pour le bouton de don mensuel
    btnDonMensuel.click(function() {
        $(this).addClass('btn-selected');
        btnDonUneFois.removeClass('btn-selected');
        typeDon.text("Don mensuel");
        // Mettre à jour la variable $typeDon
        $typeDon = "Don mensuel";
    });

    // Gestionnaire d'événement pour le bouton de don ponctuel
    btnDonUneFois.click(function() {
        $(this).addClass('btn-selected');
        btnDonMensuel.removeClass('btn-selected');
        typeDon.text("Don ponctuel");
        // Mettre à jour la variable $typeDon
        $typeDon = "Don ponctuel";
    });

    // Fonction pour mettre à jour le montant total dans le récapitulatif
    function mettreAJourMontantTotal(montant) {
        montantTotal.text(`Montant total : ${montant} €`);
    }

    /**
     * Gère le changement du montant choisi et met à jour les informations de réduction fiscale.
     */
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


    /**
     * Réinitialise le champ de saisie du montant libre.
     */
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

    // Gestionnaire d'événement pour la case à cocher d'acceptation des conditions
    $('#acceptConditions').change(function() {
        validerPayer.prop('disabled', !$(this).is(':checked'));
    });
});