/**
 * Cette fonction est exécutée lorsque le document HTML est complètement chargé et prêt à être manipulé.
 * Elle affiche le menu déroulant au survol de l'élément avec la classe 'dropdown'.
 * Elle utilise la fonction hover de jQuery pour détecter le survol de la souris et fadeIn/fadeOut pour afficher/cacher le menu déroulant.
 */
$(document).ready(function() {
    $('.dropdown').hover(function() {
        // Affiche le menu déroulant au survol
        $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(500);
    }, function() {
        // Masque le menu déroulant lorsque la souris quitte l'élément
        $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(500);
    });
});

/**
 * Cette fonction est exécutée lorsque le document HTML est complètement chargé et prêt à être manipulé.
 * Elle gère l'affichage de la barre de recherche en fonction des clics sur l'élément avec la classe 'search-bar__submit' ou sur le champ d'entrée.
 * Elle utilise la fonction click de jQuery pour détecter les clics et toggleClass pour ajouter ou supprimer la classe 'show-search'.
 */
$(document).ready(function() {
    // Empêche la fermeture de la barre de recherche lorsqu'on clique sur la zone de saisie ou sur le bouton
    $(".search-bar__input, .search-bar__submit").click(function(event) {
        event.stopPropagation();
    });

    // Bascule l'affichage de la barre de recherche lors du clic sur le bouton ou sur le champ d'entrée
    $(".search-bar__submit, .search-bar__input").click(function() {
        $(".search-bar__input").toggleClass("show-search");
    });
});


/**
 * Initialise les interactions avec le logo pour un effet 3D et de surbrillance au survol.
 * Utilise la bibliothèque GSAP pour animer le logo.
 *
 * Le logo s'agrandit légèrement et devient plus lumineux lorsqu'il est survolé.
 * Il subit également une rotation en 3D en fonction de la position de la souris.
 */
$(document).ready(function() {
    /**
     * Gère les événements de survol (mouseenter et mouseleave) sur le logo.
     *
     * @listens {MouseEvent} mouseenter - Lorsque la souris entre sur le logo.
     * @listens {MouseEvent} mouseleave - Lorsque la souris quitte le logo.
     */
    $(".perso_logoSize").on("mouseenter mouseleave", function(e) {
        if (e.type === "mouseenter") {
            // Applique un effet d'agrandissement et de surbrillance au logo
            gsap.to(this, { duration: 0.3, scale: 1.05, filter: "brightness(1.2)" });

            // Ajoute l'écouteur d'événements pour la rotation 3D
            this.addEventListener("mousemove", onMove);
        } else {
            // Retire l'écouteur d'événements et réinitialise les styles du logo
            this.removeEventListener("mousemove", onMove);
            gsap.to(this, { duration: 0.5, rotationY: 0, rotationX: 0, transformPerspective: 1000, scale: 1, filter: "brightness(1)" });
        }
    });

    /**
     * Applique une rotation 3D au logo en fonction de la position de la souris.
     *
     * @param {MouseEvent} e - L'événement de mouvement de la souris.
     */
    function onMove(e) {
        var rect = this.getBoundingClientRect();
        var x = e.clientX - rect.left;
        var y = e.clientY - rect.top;
        var px = x / rect.width;
        var py = y / rect.height;
        var rotateY = 30 * (px - 0.5);
        var rotateX = -30 * (py - 0.5);

        // Applique la rotation 3D au logo
        gsap.to(this, { duration: 0.5, rotationY: rotateY, rotationX: rotateX, transformPerspective: 1000 });
    }
});