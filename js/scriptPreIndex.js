$("document").ready(function(){
    // Vérifie si le navigateur prend en charge les transformations 3D
    var supports3DTransforms = document.body.style['webkitPerspective'] !== undefined || document.body.style['MozPerspective'] !== undefined;

    /**
     * Cette fonction crée une animation de chute des lettres pour les éléments spécifiés.
     * @param {jQuery} selector - Sélecteur jQuery pour les éléments à animer.
     * @param {number} [char_crossfade=150] - Durée en millisecondes entre chaque lettre.
     */
    function linkify(selector,char_crossfade) {

        // Initialise les valeurs par défaut
        var cc = (char_crossfade!=null)?char_crossfade:"150";
        var ad=0;

        // Vérifie si le navigateur prend en charge les transformations 3D
        if (supports3DTransforms) {
            // Pour chaque élément spécifié
            $.each(selector, function() {
                var nodes = $(this);
                var char_count=$.trim( nodes.text()).length;
                var char_at=$.trim( nodes.text());
                nodes.empty();
                for(var i=0;i<char_count;i++ ){
                    var node = char_at[i];
                    if (node!=" "){
                        // Crée les éléments de lettre avec des délais d'animation
                        nodes.append('<span  class="letter"  data-letter="' + node + '">' +
                            '<span class="char2" style="-webkit-animation-delay:' + ((i*cc)+ad) +
                            'ms;-moz-animation-delay:' + ((i*cc)+ad) + 'ms;'+
                            'ms;-ms-animation-delay:' + ((i*cc)+ad) + 'ms;'+
                            'ms;-o-animation-delay:' + ((i*cc)+ad) + 'ms;'+
                            'ms;animation-delay:' + ((i*cc)+ad) + 'ms;" >'+
                            node+'</span>'+
                            node +
                            '<span class="char1" style="-webkit-animation-delay:' + ((i*cc)+ad) +
                            'ms;-moz-animation-delay:' + ((i*cc)+ad) + 'ms;'+
                            'ms;-ms-animation-delay:' + ((i*cc)+ad) + 'ms;'+
                            'ms;-o-animation-delay:' + ((i*cc)+ad) + 'ms;'+
                            'ms;animation-delay:' + ((i*cc)+ad) + 'ms;" >'+
                            node+'</span>'+
                            '</span>');
                    }else{
                        // Si c'est un espace, ajoute simplement un espace
                        nodes.append('<span class="letter"> &nbsp </span>');
                    }
                }
                ad+=(char_count*char_crossfade);
            });
        }else{
            // Si le navigateur ne prend pas en charge les transformations 3D, ajoute simplement la classe "letter"
            selector.addClass("letter");
        }
    }

    // Appel de la fonction linkify avec les paramètres appropriés
    linkify($(".foo"),150);

    // Délai avant la redirection
    var delayBeforeRedirect = 13100; // Durée de la transition en millisecondes

    // Délai après lequel l'opacité commence à diminuer
    var delayBeforeFadeOut = 11000; // Délai en millisecondes

    // Réduire progressivement l'opacité avant la redirection
    setTimeout(function(){
        $(".foo").addClass("fadeOut");
    }, delayBeforeFadeOut);

    // Redirection vers index.php après que l'opacité de l'élément .foo ait atteint 0
    $(".foo").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
        setTimeout(function(){
            window.location.href = "../pages/index.php";
        }, delayBeforeRedirect);
    });
});