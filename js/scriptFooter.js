/**
 * Fonction auto-invoquée pour encapsuler le code
 */
(function() {
    "use strict";

    // Déclaration de variables
    var cvs, ctx;
    var nodes = 6;
    var waves = [];
    var waveHeight = 60;
    var colours = ["#0854C7", "#0A6DD9", "#0B77EB"]; // Couleurs des vagues

    /**
     * Initialise le canvas et commence le processus de mise à jour des vagues.
     */
    function init() {
        cvs = document.getElementById("canvas"); // Récupération du canvas
        ctx = cvs.getContext("2d"); // Obtention du contexte 2D
        resizeCanvas(cvs); // Réglage de la taille du canvas

        // Création des vagues avec différentes couleurs
        for (var i = 0; i < 3; i++) {
            waves.push(new wave(colours[i], 1, nodes));
        }

        update(); // Démarrage du processus de mise à jour
    }

    /**
     * Mise à jour et redessine les vagues sur le canvas.
     */
    function update() {
        var fill = "#A42AF0"; // Couleur de fond pour la vague
        ctx.fillStyle = fill;
        ctx.globalCompositeOperation = "source-over";
        ctx.fillRect(0, 0, cvs.width, cvs.height);
        ctx.globalCompositeOperation = "screen";

        // Mise à jour et dessin de chaque vague
        for (var i = 0; i < waves.length; i++) {
            for (var j = 0; j < waves[i].nodes.length; j++) {
                bounce(waves[i].nodes[j]);
            }
            drawWave(waves[i]);
        }

        requestAnimationFrame(update); // Appel récursif pour la prochaine mise à jour
    }

    /**
     * Représente une vague.
     * @param {string} colour - Couleur de la vague
     * @param {number} lambda - Inutilisé dans cette version
     * @param {number} nodes - Nombre de nœuds dans la vague
     */
    function wave(colour, lambda, nodes) {
        this.colour = colour;
        this.lambda = lambda;
        this.nodes = [];

        // Création des nœuds de la vague
        for (var i = 0; i <= nodes + 2; i++) {
            var temp = [(i - 1) * cvs.width / nodes, 0, Math.random() * 200, .3];
            this.nodes.push(temp);
        }
    }

    /**
     * Fait rebondir un nœud de la vague.
     * @param {Array} nodeArr - Tableau représentant un nœud de la vague
     */
    function bounce(nodeArr) {
        nodeArr[1] = waveHeight / 2 * Math.sin(nodeArr[2] / 20) + cvs.height / 2;
        nodeArr[2] = nodeArr[2] + nodeArr[3];
    }

    /**
     * Dessine une vague sur le canvas.
     * @param {Object} obj - Objet représentant une vague
     */
    function drawWave(obj) {
        var diff = function(a, b) {
            return (b - a) / 2 + a;
        }

        ctx.fillStyle = obj.colour;
        ctx.beginPath();
        ctx.moveTo(0, cvs.height);
        ctx.lineTo(obj.nodes[0][0], obj.nodes[0][1]);

        for (var i = 0; i < obj.nodes.length; i++) {
            if (obj.nodes[i + 1]) {
                ctx.quadraticCurveTo(
                    obj.nodes[i][0], obj.nodes[i][1],
                    diff(obj.nodes[i][0], obj.nodes[i + 1][0]), diff(obj.nodes[i][1], obj.nodes[i + 1][1])
                );
            } else {
                ctx.lineTo(obj.nodes[i][0], obj.nodes[i][1]);
                ctx.lineTo(cvs.width, cvs.height);
            }
        }
        ctx.closePath();
        ctx.fill();
    }

    /**
     * Ajuste la taille du canvas en fonction de la largeur de la fenêtre.
     * @param {HTMLElement} canvas - L'élément canvas à redimensionner
     */
    function resizeCanvas(canvas) {
        if (window.innerWidth > 1920) {
            canvas.width = window.innerWidth;
        } else {
            canvas.width = 1920;
        }
        canvas.height = waveHeight;
    }

    // Initialisation lorsque le DOM est prêt
    document.addEventListener("DOMContentLoaded", function() {
        if (document.getElementById("canvas")) {
            init();
        }
    });
})();