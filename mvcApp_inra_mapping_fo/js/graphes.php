<?php
/*
	This file is part of Système de Navigation Interactif et Dynamique (SNID).

    SNID is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    SNID is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with SNID. If not, see http://www.gnu.org/licenses/.
*/
?>
<script charset="UTF-8">
var PI = Math.PI;
var conteneur;
var nombreApercu = new Array();
var historique = 0;
var tailleMaxHistorique;
var historiqueEstInitialise = false;
var categorie_gauche;
var categorie_droite;

//-------------------------------------------------------------------

function graphePrecedent() {
    this.type = "";
    this.id = "";
}

function caracteristiquesNoeuds() {
    this.id = 0;
    this.x = 0;
    this.y = 0;
    this.r = 0;
    this.tailleBordure = 0;
    this.couleur = "";
    this.couleurBordure = "";
    this.couleurSelection = "";
    this.texte = "";
    this.url = "aucune";
	this.noeud = "";
	this.tag = "";
}

function caracteristiquesLiaisons() {
    this.x1 = 0;
    this.y1 = 0;
    this.x2 = 0;
    this.y2 = 0;
}

//-------------------------------------------------------------------

function genererCercle(pos, color, texte, svg, posX, posY, rayon, rayonNoeuds) {
    var positionAngleNoeud = 0;
    var angleEntreNoeud = 4;

	if ( pos == 0 ) {
		var gcircle = svg.selectAll(gcircle).data(texte[2]).enter().append('g')
			.attr('id', texte[2][0].id_categorie)
			.on('mouseover', function (d) {
				d3.select(this).select('circle').style('fill', color.couleur_liaisons_select);
				d3.select(this).select('text').attr('fill', color.couleur_liaisons_select);
			})
			.on('mouseout', function (d) {
				d3.select(this).select('circle').style('fill', color.couleur_liaisons);
				d3.select(this).select('text').attr('fill', color.couleur_liaisons);
			})
			.on('click', function (d, i) {
				// Suppression du cadre
				d3.select('svg').remove();

				// Génération du nouveau graphe
				genererGraphe(conteneur, 'noeud', texte[2][i].id_noeud);
			});

		var facteurAngleNoeud;
		gcircle.append('circle')
			.attr('cx', function (d, i) {
				if (i % 2 == 0) {
					facteurAngleNoeud = positionAngleNoeud;
					positionAngleNoeud = positionAngleNoeud + 1;
				}
				else {
					facteurAngleNoeud = -positionAngleNoeud;
				}
				return posX + rayon * Math.cos(((pos + angleEntreNoeud * facteurAngleNoeud) * PI) / 180);
			})
			.attr('cy', function (d, i) {
				if (i % 2 == 0) {
					if ( texte[2].length % 2 == 0 ) {
						facteurAngleNoeud = positionAngleNoeud - texte[2].length / 2;
					}
					else {
						facteurAngleNoeud = positionAngleNoeud - ( texte[2].length + 1 ) / 2;
					}
					positionAngleNoeud = positionAngleNoeud + 1;
				}
				else {
					if ( texte[2].length % 2 == 0 ) {
						facteurAngleNoeud = -positionAngleNoeud + texte[2].length / 2;
					}
					else {
						facteurAngleNoeud = -positionAngleNoeud + ( texte[2].length + 1 ) / 2;
					}
				}
				 return posY + rayon * Math.sin(((pos + angleEntreNoeud * facteurAngleNoeud) * PI) / 180);
			})
			.attr('r', rayonNoeuds)
			.attr('id', 'droite')
			.style('fill', color.couleur_liaisons)
			.style('stroke', 'black')
			.style('stroke-width', '0.2');

		var tag2 = new Array();

		for (var i = 0; i < texte[2].length; i++ ) {
			tag2[i] = texte[2][i].nom_partiel;
		}

		gcircle.append('text').text(function (d) { return d.nom_partiel; })
			.attr('class', 'droite')
			.attr('id', 'droite')
			.attr('x', function (d, i) {return parseInt(d3.selectAll('circle#droite')[0][i].getAttribute('cx')) + 15; })
			.attr('y', function (d, i) { return parseInt(d3.selectAll('circle#droite')[0][i].getAttribute('cy')) + 5; })
			.attr('fill', color.couleur_liaisons)
			.append("title")
			.text(function(d,i) { return d.nom_entier; });
	}
	else {
		var gcircle = svg.selectAll(gcircle).data(texte[1]).enter().append('g')
			.attr('id', texte[1][0].id_categorie)
			.on('mouseover', function (d) {
				d3.select(this).select('circle').style('fill', color.couleur_liaisons_select);
				d3.select(this).select('text').attr('fill', color.couleur_liaisons_select);
			})
			.on('mouseout', function (d) {
				d3.select(this).select('circle').style('fill', color.couleur_liaisons);
				d3.select(this).select('text').attr('fill', color.couleur_liaisons);
			})
			.on('click', function (d, i) {
				// Suppression du cadre
				d3.select('svg').remove();

				// Génération du nouveau graphe
				genererGraphe(conteneur, 'noeud', texte[1][i].id_noeud);
			});

		var facteurAngleNoeud
		gcircle.append('circle')
			.attr('cx', function (d, i) {
				if (i % 2 == 0) {
					facteurAngleNoeud = positionAngleNoeud;
					positionAngleNoeud = positionAngleNoeud + 1;
				}
				else {
					facteurAngleNoeud = -positionAngleNoeud;
				}
				return posX + rayon * Math.cos(((pos + angleEntreNoeud * facteurAngleNoeud) * PI) / 180);
			})
			.attr('cy', function (d, i) {
				if (i % 2 == 0) {
					if ( texte[1].length % 2 == 0 ) {
						facteurAngleNoeud = positionAngleNoeud - texte[1].length / 2;
					}
					else {
						facteurAngleNoeud = positionAngleNoeud - ( texte[1].length + 1 ) / 2;
					}
					positionAngleNoeud = positionAngleNoeud + 1;
				}
				else {
					if ( texte[1].length % 2 == 0 ) {
						facteurAngleNoeud = -positionAngleNoeud + texte[1].length / 2;
					}
					else {
						facteurAngleNoeud = -positionAngleNoeud + ( texte[1].length + 1 ) / 2;
					}
				}
				 return posY + rayon * Math.sin(((pos + angleEntreNoeud * facteurAngleNoeud) * PI) / 180);
			})
			.attr('r', rayonNoeuds)
			.attr('id', 'gauche')
			.style('fill', color.couleur_liaisons)
			.style('stroke', 'black')
			.style('stroke-width', '0.2');

		var tag0 = new Array();

		for (var i = 0; i < texte[1].length; i++ ) {
			tag0[i] = texte[1][i].nom_partiel;
		}

		gcircle.append('text').text(function (d,i) { return d.nom_partiel; })
			.attr('class', 'gauche')
			.attr('id', 'gauche')
			.attr('x', function (d, i) { return parseInt(d3.selectAll('circle#gauche')[0][i].getAttribute('cx')) - 15; })
			.attr('y', function (d, i) { return parseInt(d3.selectAll('circle#gauche')[0][i].getAttribute('cy')) + 5; })
			.attr('fill', color.couleur_liaisons)
			.append("title")
			.text(function(d,i) { return d.nom_entier; });
	}
}

function ajouterCercle(donnees, cadre, largeurCadre, hauteurCadre, rayonCercleImaginaire){
	if ( donnees.categories.length >= 2 ) {
		var cercleGauche = genererCercle(180, donnees.categories[1], donnees.noeuds, cadre, largeurCadre / 2, hauteurCadre / 2, rayonCercleImaginaire, 5);
		if ( donnees.categories.length >= 3 ) {
			var cercleDroite = genererCercle(0, donnees.categories[2], donnees.noeuds, cadre, largeurCadre / 2, hauteurCadre / 2, rayonCercleImaginaire, 5);
		}
	}
}

//Permet de définir la position de "nbElement" noeuds sur un cercle de centre "posX","posY" ayant un rayon de "rayon"
//le retour ce fait dans un tableau, pour accéder au x (y) du premier élément on y accède : nomDuTableau[0].x (nomDuTableau[0].y)
function calculerPositionsNoeuds(posX, posY, rayon, nbElement, debutArc, finArc) {
    // Pour éviter la division par 0
    if (nbElement <= 1) {
        var angle = (debutArc - finArc) / 1;
    }

    else {
        // angle maximal utilisable (entre début de l'arc et sa fin pour éviter le noeud de navigation)
        var angle = (debutArc - finArc) / (nbElement - 1);
    }
    var angleDepart = debutArc;
    var listeCercle = new Array;

    for (var i = 0; i < nbElement; i++) {
        listeCercle[i] = {"x" : posX + rayon * Math.cos((angleDepart - angle * (i)) * PI / 180),
            "y" : posY + rayon * Math.sin((angleDepart - angle * (i)) * PI / 180)};
    }

    return listeCercle;
}

function preparerNoeuds(donnees, largeurCadre, hauteurCadre, typeDeGraphe) {
    var positionsNoeuds;

    // Mémorisation du centre du graphe
    var centreGrapheX = largeurCadre / 2;
    var centreGrapheY = hauteurCadre / 2;

    // S'il s'agit du graphe d'accueil
    if (typeDeGraphe == 'accueil') {
        // Création d'un tableau de noeuds
        var noeuds = new Array();
        for (var i = 0; i < donnees.noeuds.length; i++) {
            noeuds[i] = new caracteristiquesNoeuds();
        }

        // Préparation des noeuds
        var positionsNoeudsPrincipaux = calculerPositionsNoeuds(centreGrapheX, centreGrapheY - 100, 150, (donnees.categories.length), -30, 210);
        var positionsNoeudsSecondaires = calculerPositionsNoeuds(centreGrapheX, centreGrapheY - 100, 285, (donnees.categories.length), -30, 210);
        var indexPrincipal = 0, indexSecondaire = 0;

        for (var i = 0; i < noeuds.length; i++) {
            noeuds[i].id = donnees.noeuds[i].id_noeud;
            if (donnees.noeuds[i].type == 'principal') {
                noeuds[i].x = positionsNoeudsPrincipaux[indexPrincipal].x;
                noeuds[i].y = positionsNoeudsPrincipaux[indexPrincipal].y
                noeuds[i].r = 60;
                noeuds[i].type = 'principal';
                indexPrincipal++;
            }

            else {
                noeuds[i].x = positionsNoeudsSecondaires[indexSecondaire].x;
                noeuds[i].y = positionsNoeudsSecondaires[indexSecondaire].y
                noeuds[i].r = 25;
                noeuds[i].type = 'secondaire';
                indexSecondaire++;

				// Préparation des tableaux pour les aperçus
				nombreApercu[noeuds[i].id.toString()] = 0;
				noeuds[i].noeud = donnees.noeuds[i].noeud;
				noeuds[i].tag = donnees.noeuds[i].tag;
            }
            
            noeuds[i].tailleBordure = 0;

            for (var k = 0; k < donnees.categories.length; k++) {
                if (donnees.categories[k].id_categorie == donnees.noeuds[i].id_categorie) {
                    noeuds[i].couleur = donnees.categories[k].couleur_liaisons;
                    noeuds[i].couleurBordure = donnees.categories[k].couleur_liaisons;
                    noeuds[i].couleurSelection = donnees.categories[k].couleur_liaisons_select;
                }
            }
            noeuds[i].texte = donnees.noeuds[i].nom;
            noeuds[i].url = donnees.noeuds[i].url_redirection;
        }
    }

    // S'il s'agit d'un graphe de catégorie
    else if (typeDeGraphe == 'categorie') {
        // Création d'un tableau de noeuds
        var noeuds = new caracteristiquesNoeuds();
        
        // Préparation du noeud de navigation
        noeuds.x = centreGrapheX;
        noeuds.y = d3.selectAll('rect')[0][0].getAttribute('y') - 100;
        noeuds.r = 67;
        noeuds.tailleBordure = 15;
        noeuds.couleur = "#000000";
        noeuds.couleurBordure = donnees.noeudNavigation.couleurBordure;
        noeuds.texte = ["Navigation"];
    }

    // S'il s'agit d'un graphe de noeud
    else if (typeDeGraphe == 'noeud') {
        // Création d'un tableau de noeuds
        var noeuds = new Array();
        // +1 pour le noeud de navigation
        for (var i = 0; i < donnees.noeuds.length + 1; i++) {
            noeuds[i] = new caracteristiquesNoeuds();
        }

        // Préparation du noeud de navigation
        // Calcul de la position du noeud de navigation
        positionsNoeuds = calculerPositionsNoeuds(centreGrapheX, centreGrapheY, 250, 1, -90, 270);
        noeuds[0].x = positionsNoeuds[0].x;
        noeuds[0].y = positionsNoeuds[0].y;
        noeuds[0].r = 67;
        noeuds[0].tailleBordure = 15;
        noeuds[0].couleur = "#000000";
        noeuds[0].couleurBordure = donnees.noeudNavigation.couleurBordure;
        noeuds[0].categorieNoeudCentral = donnees.noeudNavigation.categorieNoeudCentral;
        noeuds[0].texte = ["Navigation"];

        // Préparation des autres noeuds
        // donnees.noeuds.length -1 car le noeud central n'est pas compté
        positionsNoeuds = calculerPositionsNoeuds(centreGrapheX, centreGrapheY, 236, (donnees.noeuds.length - 1), -30, 210);
        for (var i = 1, j = -1; i < noeuds.length; i++, j++) {
            noeuds[i].id = donnees.noeuds[i - 1].id_noeud;
            // Pour le noeud central
            if (i == 1) {
                noeuds[i].id = donnees.noeuds[i - 1].id_noeud;
                noeuds[i].x = centreGrapheX;
                noeuds[i].y = centreGrapheY;
                noeuds[i].r = 60;
                noeuds[i].tailleBordure = 0;
            }

                // Pour les autres noeuds
            else {
                noeuds[i].id = donnees.noeuds[i - 1].id_noeud;
                noeuds[i].x = positionsNoeuds[j].x;
                noeuds[i].y = positionsNoeuds[j].y
                noeuds[i].r = 60;
                noeuds[i].tailleBordure = 0;
            }

            for (var k = 0; k < donnees.categories.length; k++) {
                if (donnees.categories[k].id_categorie == donnees.noeuds[i - 1].id_categorie) {
                    noeuds[i].couleur = donnees.categories[k].couleur_liaisons;
                    noeuds[i].couleurBordure = donnees.categories[k].couleur_liaisons;
                    noeuds[i].couleurSelection = donnees.categories[k].couleur_liaisons_select;
                }
            }
            noeuds[i].texte = donnees.noeuds[i - 1].nom;
            noeuds[i].url = donnees.noeuds[i - 1].url_redirection;
        }
    }

    return noeuds;
}

function preparerLiaisons(donnees, caracteristiquesNoeuds) {
    // Création d'un tableau de liaisons
    var liaisons = new Array();
    for (var i = 0; i < donnees.liens.length; i++) {
        liaisons[i] = new caracteristiquesLiaisons();
    }

    // Préparation des liaisons
    for (var i = 0; i < donnees.liens.length; i++) {
        for (var j = 0; j < caracteristiquesNoeuds.length; j++) {
            if (caracteristiquesNoeuds[j].id == donnees.liens[i].id_noeud_1) {
                liaisons[i].x1 = caracteristiquesNoeuds[j].x;
                liaisons[i].y1 = caracteristiquesNoeuds[j].y;
            }

            if (caracteristiquesNoeuds[j].id == donnees.liens[i].id_noeud_2) {
                liaisons[i].x2 = caracteristiquesNoeuds[j].x;
                liaisons[i].y2 = caracteristiquesNoeuds[j].y;
            }
        }
    }

    return liaisons;
}

function ajouterRectangle(categories, donnees, svg, posX, posY, rayon) {
    var largeurRect=200;
	for ( var i = 0; i < donnees.noeuds[0].length; i++ ) {
		if ( donnees.noeuds[0][i].nom_entier.length * 7 > largeurRect ) {
			largeurRect = donnees.noeuds[0][i].nom_entier.length * 7;
		}
	}
	if ( largeurRect > 350 ) {
		largeurRect = 350;
	}
    var hauteurRect=20;
    var angleEntreNoeud = 4;

    var groupeRectText = svg.selectAll('g#rect').data(donnees.noeuds[0]).enter().append('g')
		.attr('id', 'rect')
        .on('mouseover', function (d) {
            d3.select(this).select('rect').attr('fill', categories[0].couleur_liaisons_select);
            d3.select(this).select('text').attr('fill', 'black');
            var lien = new Array();
            var nbLien = 0;
			if ( d.nom_entier.length * 6.5 > largeurRect ) {
				d3.select(this).select('text').text(d.nom_entier);
			}

			//id du noeud surligné
			//console.log(d.id_noeud);
			var id_noeud_depart = d.id_noeud;
			var noeud_arrive = null;

			if ( null != donnees.liens ) {
				for (var l = 0; l < donnees.liens.length; l++ ) {
					if (donnees.liens[l].id_noeud_1 == id_noeud_depart || donnees.liens[l].id_noeud_2 == id_noeud_depart) {
						lien[nbLien] = donnees.liens[l];
						nbLien++;
					}
				}
				var rectX = parseInt(d3.select(this).select('rect')[0][0].getAttribute('x'));
				var liaison = svg.selectAll(liaison).data(lien).enter().append('line')
					.attr('x1', function (d, i) {
						if ( id_noeud_depart == d.id_noeud_1 ) {
							noeud_arrive = d.id_noeud_2;
						}
						else {
							noeud_arrive = d.id_noeud_1;
						}
						for (var nb = 0; nb < donnees.noeuds[1].length; nb++ ) {
							if (noeud_arrive == donnees.noeuds[1][nb].id_noeud) {
								return rectX;
							}
						}
						return rectX + largeurRect;
					})
					.attr('y1', parseInt(d3.select(this).select('rect')[0][0].getAttribute('y')) + hauteurRect / 2)
					.attr('x2', function (d, i) {
						if ( id_noeud_depart == d.id_noeud_1 ) {
							noeud_arrive = d.id_noeud_2;
						}
						else {
							noeud_arrive = d.id_noeud_1;
						}
						for (var nb = 0; nb < donnees.noeuds[1].length; nb++ ) {
							if (noeud_arrive == donnees.noeuds[1][nb].id_noeud) {
								return d3.selectAll('circle#gauche')[0][nb].getAttribute('cx');
							}
						}
						for (var nb = 0; nb < donnees.noeuds[2].length; nb++ ) {
							if (noeud_arrive == donnees.noeuds[2][nb].id_noeud) {
								return d3.selectAll('circle#droite')[0][nb].getAttribute('cx');
							}
						}
					})
					.attr('y2', function (d, i) {
						if ( id_noeud_depart == d.id_noeud_1 ) {
							noeud_arrive = d.id_noeud_2;
						}
						else {
							noeud_arrive = d.id_noeud_1;
						}
						for (var nb = 0; nb < donnees.noeuds[1].length; nb++ ) {
							if (noeud_arrive == donnees.noeuds[1][nb].id_noeud) {
								return d3.selectAll('circle#gauche')[0][nb].getAttribute('cy');
							}
						}
						for (var nb = 0; nb < donnees.noeuds[2].length; nb++ ) {
							if (noeud_arrive == donnees.noeuds[2][nb].id_noeud) {
								return d3.selectAll('circle#droite')[0][nb].getAttribute('cy');
							}
						}
					})
					.attr('stroke-width', '1')
					.attr('stroke', function(d, i) {
						color = "black";
						if ( id_noeud_depart == d.id_noeud_1 ) {
							noeud_arrive = d.id_noeud_2;
						}
						else {
							noeud_arrive = d.id_noeud_1;
						}
						for (var nb = 0; nb < donnees.noeuds[1].length; nb++ ) {
							if (noeud_arrive == donnees.noeuds[1][nb].id_noeud) {
								color = donnees.categories[1].couleur_liaisons;
							}
						}
						for (var nb = 0; nb < donnees.noeuds[2].length; nb++ ) {
							if (noeud_arrive == donnees.noeuds[2][nb].id_noeud) {
								color = donnees.categories[2].couleur_liaisons;
							}
						}
						return color;
					});
			}

        })
        .on('mouseout', function (d) {
            d3.select(this).select('rect').attr('fill', categories[0].couleur_liaisons);
            d3.select(this).select('text').attr('fill', 'white');
			if ( d.nom_entier.length * 6.5 > largeurRect ) {
				d3.select(this).select('text').text(d.nom_partiel);
			}
            d3.selectAll('line').remove();
        })
        .on('click', function (d, i) { gererClic(d, i, 'categorie'); });

    groupeRectText.append('rect')
        .attr('x', posX-largeurRect/2)
        .attr('y', function (d, i) { return (posY - (donnees.noeuds[0].length - i - donnees.noeuds[0].length / 2) * hauteurRect); })
		.attr('id_n', function(d, i) { return donnees.noeuds[0][i].id_noeud; })
        .attr('width', largeurRect)
        .attr('height', hauteurRect)
        .attr('fill',categories[0].couleur_liaisons)
        .attr('stroke', 'white')
        .attr('stroke-width', '1');

    groupeRectText.append('text').text(function (d) {
			if ( d.nom_entier.length * 6.5 > largeurRect ) {
				return d.nom_partiel;
			}
			return d.nom_entier;
		})
       .attr('x', function (d, i) { return parseInt(svg.selectAll('rect')[0][i].getAttribute('x')) + largeurRect / 2; })
       .attr('y', function (d, i) { return parseInt(svg.selectAll('rect')[0][i].getAttribute('y')) + hauteurRect - 5; })
       .attr('fill', 'white')
       .style('text-anchor', 'middle');
}

function ajouterDegrade(nomId, couleurDepart, couleurArrivee) {
    var definition = d3.select('svg').append('defs').append('linearGradient')
    .attr('id', nomId);
    definition.append('stop').attr('offset', '0%').attr('style', 'stop-color:' + couleurDepart);
    definition.append('stop').attr('offset', '100%').attr('style', 'stop-color:' + couleurArrivee);
}

function ajouterLiaisons(caracteristiquesLiaisons, cadre, dureeTransition) {
    var nouvellesLiaisons = cadre.selectAll('line').data(caracteristiquesLiaisons).enter().append('line');
    nouvellesLiaisons.attr('x1', function (d) { return d.x2; })
    .attr('y1', function (d) { return d.y2; })
    .attr('x2', function (d) { return d.x2; })
    .attr('y2', function (d) { return d.y2; })
    .attr('stroke-width', 2)
    .attr('stroke', 'black')
    .transition().duration(dureeTransition).attr('x1', function (d) { return d.x1; }).attr('y1', function (d) { return d.y1; });
}

function ajouterMenuCirculaire(elementSVG, idCategorieNoeudCentral) {
    // Création de l'arc sur lequel les boutons Accueil et retour sont placés
    var arc = d3.svg.arc()
    .innerRadius(60)
    .outerRadius(74)
    .startAngle(PI * (-180) / 180)
    .endAngle(PI * (180) / 180);

    // Création d'un chemin de la forme de l'arc précédent
    elementSVG.append('path')
    .attr('d', arc)
    .attr('id', 'cheminHaut')
    .attr('fill', 'green')
    .attr('fill-opacity', '0');

    // Création d'un chemin de la forme d'un demi cercle utilisé pour le bouton exporter
    elementSVG.append('path')
    .attr('d', 'M -60 0 A 60 60 0 0 0 60 0')
    .attr('id', 'cheminBas')
    .attr('fill', 'black')
    .attr('fill-opacity', '0');

    // S'il s'agit d'un graphe de noeud, idCategorieNoeudCentral n'est pas nul car les ids des catégories ne peuvent pas être nuls
    if(idCategorieNoeudCentral > 0) {
        // Ajout du bouton catégorie
        var categorie = elementSVG.append('text')
        .attr('dy', 11);

        // Assignation du chemin au texte du bouton accueil
        pathCategorie = categorie.append('textPath')
        .attr('fill', 'white')
        .attr('xlink:href', '#cheminHaut')
        .attr('startOffset', '10%')
        .text('Categorie');

        // Gestion des évènements du bouton catégorie
        categorie.on('mouseover', function (d) { pathCategorie.attr('fill', 'black'); })
        .on('mouseout', function (d) { pathCategorie.attr('fill', 'white'); })
        .on('click', function (d) {
            // Suppression du graphe
            d3.select('svg').remove();

            // Génération du graphe de catégorie 
            genererGraphe(conteneur, 'categorie', idCategorieNoeudCentral);
        });
    }
    
    // Ajout du bouton accueil
    var accueil = elementSVG.append('text')
    .attr('dy', 12);

    // Assignation du chemin au texte du bouton accueil
    pathAccueil = accueil.append('textPath')
    .attr('fill', 'white')
    .attr('xlink:href', '#cheminHaut')
    .attr('startOffset', '25%')
    .text('Accueil');

    // Gestion des évènements du bouton accueil
    accueil.on('mouseover', function (d) { pathAccueil.attr('fill', 'black'); })
    .on('mouseout', function (d) { pathAccueil.attr('fill', 'white'); })
    .on('click', function (d) {
        // Réinitialisation de l'historique
        while(historique.length > 0) {
            historique.pop();
        }

        // Suppression du graphe
        d3.select('svg').remove();

        // Génération du graphe d'accueil
        genererGraphe(conteneur, 'accueil', 0);
    });

    // Si l'historique est activé, le bouton retour est ajouté
    if (historiqueEstInitialise == true) {
        // Ajout du bouton retour
        var retour = elementSVG.append('text')
        .attr('dy', 12);

        // Assignation du chemin au texte du bouton retour
        pathRetour = retour.append('textPath')
        .attr('fill', 'white')
        .attr('xlink:href', '#cheminHaut')
        .attr('startOffset', '39%')
        .text('Retour');

        // Gestion des évènements du bouton retour
        retour.on('mouseover', function (d) { pathRetour.attr('fill', 'black'); })
        .on('mouseout', function (d) { pathRetour.attr('fill', 'white'); })
        .on('click', function (d) {
            // Si l'historique est activé
            if (historiqueEstInitialise == true) {
                // Suppression de l'élément actuel
                historique.pop();

                // Récupération de l'élément précédent
                var typeAGenerer = historique[historique.length - 1].type;
                var idAGenerer = historique[historique.length - 1].id;

                // Effacement de l'élément qui va être rechargé
                historique.pop();

                // Suppression du graphe
                d3.select('svg').remove();

                // Génération du graphe précédent
                genererGraphe(conteneur, typeAGenerer, idAGenerer);
            }
        });
    }

    // Ajout du bouton exporter
    var exporter = elementSVG.append('text')
    .attr('dy', 12);

    // Assignation du chemin au texte du bouton accueil exporter
    pathExport = exporter.append('textPath')
    .attr('fill', 'white')
    .attr('xlink:href', '#cheminBas')
    .attr('startOffset', '35%')
    .text('Exporter');

    // Gestion des évènements du bouton exporter
    exporter.on('mouseover', function (d) { pathExport.attr('fill', 'black'); })
    .on('mouseout', function (d) { pathExport.attr('fill', 'white'); })
    .on('click', genererLienTelechargement);
}

function ajouterNoeuds(caracteristiquesNoeuds, cadre, typeDeGraphe, dureeTransition) {

    if (typeDeGraphe == 'categorie') {
        // Création du groupe
        var nouveauxNoeuds = cadre.append('g');
        nouveauxNoeuds.attr('transform', function (d) { return 'translate(' + parseInt(cadre.style('width')) / 2 + ',' + parseInt(cadre.style('height')) / 2 + ')'; })
        .transition().duration(dureeTransition).attr('transform', function (d) { return 'translate(' + caracteristiquesNoeuds.x + ',' + caracteristiquesNoeuds.y + ')'; }).ease('elastic');

        // Ajout du cercle 
        nouveauxNoeuds.append('circle')
        .attr('r', caracteristiquesNoeuds.r)
        .attr('stroke-width', caracteristiquesNoeuds.tailleBordure)
        .attr('fill', caracteristiquesNoeuds.couleur)
        .attr('stroke', caracteristiquesNoeuds.couleurBordure);

        // Ajout du texte
        nouveauxNoeuds.append('text')
        .attr('font-size', 14)
        .attr('font-family', 'serif')
        .attr('fill', 'white')
        .style('text-anchor', 'middle')
        .text(caracteristiquesNoeuds.texte);

        // Ajout du menu circulaire
        ajouterMenuCirculaire(nouveauxNoeuds);

        // Ajout des flèches
        var flecheGauche = nouveauxNoeuds.append('image')
        .attr('xlink:href', '<?php echo constant("NOM_APPLICATION"); ?>/images/flecheGauche.png')
        .attr('width', 31)
        .attr('height', 27)
        .attr('transform', 'translate(-35, 10)')
        .style('opacity', '0.5')
        .on('mouseover', function (d) { flecheGauche.style('opacity', '1.0'); })
        .on('mouseout', function (d) { flecheGauche.style('opacity', '0.5'); })
		.on('click', function () {
			// Suppression du graphe
			d3.select('svg').remove();

			// Génération du graphe précédent
			genererGraphe(conteneur, typeDeGraphe, categorie_gauche);
		});

        var flecheDroite = nouveauxNoeuds.append('image')
        .attr('xlink:href', '<?php echo constant("NOM_APPLICATION"); ?>/images/flecheDroite.png')
        .attr('width', 31)
        .attr('height', 27)
        .attr('transform', 'translate(5, 10)')
        .style('opacity', '0.5')
        .on('mouseover', function (d) { flecheDroite.style('opacity', '1.0'); })
        .on('mouseout', function (d) { flecheDroite.style('opacity', '0.5'); })
		.on('click', function () {
			// Suppression du graphe
			d3.select('svg').remove();

			// Génération du graphe précédent
			genererGraphe(conteneur, typeDeGraphe, categorie_droite);
		});
    }

    else {
        // Création du groupe
        var nouveauxNoeuds = cadre.selectAll('g').data(caracteristiquesNoeuds).enter().append('g');
        nouveauxNoeuds.attr('transform', function (d) { return 'translate(' + parseInt(cadre.style('width')) / 2 + ',' + parseInt(cadre.style('height')) / 2 + ')'; })
        .on('mouseover', function (d) {
            // S'il ne s'agit pas du noeud de navigation
            if (d.texte != 'Navigation') {
                d3.select(this).select('circle').style('fill', d.couleurSelection);
            }
        })
        .on('mouseout', function (d) { d3.select(this).select('circle').style('fill', d.couleur); })
        .on('click', function (d, i) { gererClic(d, i, typeDeGraphe); })
        .transition().duration(dureeTransition).attr('transform', function (d) { return 'translate(' + d.x + ',' + d.y + ')'; }).ease('elastic');

        // Ajout du cercle 
        nouveauxNoeuds.append('circle')
        .attr('r', function (d) { return d.r; })
        .attr('stroke-width', function (d) { return d.tailleBordure; })
        .attr('fill', function (d) { return d.couleur; })
        .attr('stroke', function (d) { return d.couleurBordure; });

        // Ajout du texte
        var texte = nouveauxNoeuds.append('text')
        .attr('font-size', 14)
        .attr('font-family', 'serif')
        .attr('fill', 'white')
        .style('text-anchor', 'middle');

        // Pour chaque bloc de texte
        texte.each(function (d, i) {
            var decalageYTextePrecedent = 0;
            decalageYTextePrecedent = decalageYTextePrecedent - ((d.texte.length - 1) * (1.5 / 2));
            // Parcours du tableau contenant les lignes de texte
            for (var index = 0; index < d.texte.length; index++) {
                // Ajout d'un tspan pour chaque ligne
                d3.select(this).append('tspan')
                .attr('x', 0)
                .attr('y', 0)
                .attr('dy', decalageYTextePrecedent + 'em')
                .text(convertirAccents(d.texte[index]));
                decalageYTextePrecedent = decalageYTextePrecedent + 1.5;
            }
        });

        // Ajout du menu circulaire pour le noeud de navigation
        nouveauxNoeuds.each(function (d, i) {
            if (d.texte == 'Navigation') {
                // Ajout du menu circulaire
                ajouterMenuCirculaire(d3.select(this), caracteristiquesNoeuds[0].categorieNoeudCentral);
            }
        });
    }
}

function genererLienTelechargement() {
    var html = d3.select('svg')
        .attr('title', 'test2')
        .attr('version', 1.1)
        .attr('xmlns', 'http://www.w3.org/2000/svg')
        .node().parentNode.innerHTML;

    d3.select('body').append('div')
        .attr('id', 'telechargement')
        .style('top', event.clientY + 20 + 'px')
        .style('left', event.clientX + 'px')
        .html('Clic-droit sur cet aper&ccedil;u puis Enregistrer sous<br />Clic-gauche pour effacer<br />')
        .append('img')
        .attr('src', 'data:image/svg+xml;base64,' + btoa(html));

    d3.select('#telechargement')
        .on('click', function () {
            if (event.button == 0) {
                d3.select(this).transition()
                    .style('opacity', 0)
                    .remove();
            }
        })
        .transition()
        .duration(500)
        .style('opacity', 1);
}

function gererClic(donnees, index, typeDeGraphe) {
    if (typeDeGraphe == 'accueil') {
        // S'il s'agit d'un noeud principal
        if (donnees.type == 'principal') {
            // Suppression du graphe
            d3.select('svg').remove();

            // Génération du nouveau graphe
            genererGraphe(conteneur, 'categorie', donnees.url);
        }
        else {
            // Sinon affichage de l'aperçu
            svg = d3.select(conteneur).select('svg');
            cercleAccueil(donnees,svg);
        }
    }

    else if (typeDeGraphe == 'categorie') {
        // S'il ne s'agit pas du noeud de navigation
        if (donnees.nom != 'Navigation') {
            // Suppression du cadre
            d3.select('svg').remove();

            // Génération du nouveau graphe
            genererGraphe(conteneur, 'noeud', donnees.id_noeud);
        }
    }

    else if (typeDeGraphe == 'noeud') {
        // S'il ne s'agit pas du noeud de navigation
        if (donnees.texte != 'Navigation') {
            // Pour le noeud central
            if (index == 1) {
                window.open(donnees.url);
            }

            // Pour tous les autres noeuds
            else {
                // Suppression du graphe
                d3.select('svg').remove();

                // Génération du nouveau graphe
                genererGraphe(conteneur, 'noeud', donnees.url);
            }
        }
    }

    else if (typeDeGraphe == 'apercu') {
        // Suppression du cadre
        d3.select('svg').remove();

        // Génération du nouveau graphe
        genererGraphe(conteneur, 'noeud', parseChaine(true, donnees));
    }
}

function cercleAccueil(donnees, svg){
    var rayon = 50;
    var cat = donnees.id.toString();

	// Localiser le centre du graphe pour savoir de quel côté mettre les cercles
    xCentre = svg[0][0].getAttribute('width') / 2;
	// Si le noeud sur lequel on clic se situent sur la droite du graphe
    if (donnees.x >= xCentre) {
        var debutArc = 45;
        var finArc = -45;
        var posX = donnees.x + donnees.r;
    }
    else {
        var debutArc = -135;
        var finArc = -225;
        var posX = donnees.x - donnees.r;
    }

	// Si tous les aperçus ont déjà été affichés
    if (donnees.texte[0] - nombreApercu[cat] * 5 <= 0) {
        svg.selectAll('circle#id'+cat).remove();
        svg.selectAll('line#id'+cat).remove();
        svg.selectAll('text#id'+cat).remove();

        nombreApercu[cat] = 0;
    }
	
	// S'il reste au moins 5 aperçus à afficher dans le reste de la liste on met nbElem à 5 (le nombre d'aperçus que l'on souhaite afficher)
    if (parseInt(donnees.texte[0]) - nombreApercu[cat] * 5 >= 5) {
        var nbElem = 5;
    }
    else {
		// Sinon nbElem contient le nombre d'aperçus restants à afficher
        var nbElem = parseInt(donnees.texte[0]);
    }

	// Si des aperçus de la catégorie on déjà été affichés on supprime les aperçus précédement affichés
    if (nombreApercu[cat] > 0) {
        svg.selectAll('circle#id'+cat).remove();
        svg.selectAll('line#id'+cat).remove();
        svg.selectAll('text#id'+cat).remove();
    }

	// On calcule la position des futurs noeuds pour les aperçus
    var listeNoeud = calculerPositionsNoeuds(donnees.x, donnees.y, rayon, nbElem, debutArc, finArc);

	// Pour tous les aperçus à afficher  on dessine les cercles / liens et textes
    for (var i = 0; i < nbElem; i++) {
        var gapp = svg.append('g')
			.on('click', function () { gererClic(d3.select(this).select('circle')[0][0].getAttribute('info'), i, 'apercu'); });
			
        gapp.append('circle')
			.attr('cx', listeNoeud[i].x)
			.attr('cy', listeNoeud[i].y)
			.attr('r', 5)
			.attr('id', 'id'+cat)
			.attr('info', donnees.noeud[i + (nombreApercu[cat] * 5)])
			.style('fill', donnees.couleur)
			.style('stroke', 'black')
			.style('stroke-width', '0.2');

        svg.append('line')
			.attr('x1', posX)
			.attr('y1', donnees.y)
			.attr('x2', listeNoeud[i].x)
			.attr('y2', listeNoeud[i].y)
			.attr('id', 'id'+cat)
			.attr('stroke-width', '1')
			.attr('stroke', donnees.couleur);

        if (debutArc > 0) {
            gapp.append('text')
				.attr('class', 'gauche')
				.text(donnees.tag[i + (nombreApercu[cat] * 5)])
				.attr('id', 'id'+cat)
				.attr('x', listeNoeud[i].x + 10 )
				.attr('y', listeNoeud[i].y + 5 )
				.attr('fill', donnees.couleur)
				.append('title')
				.text(function() { return donnees.noeud[i + (nombreApercu[cat] * 5)]; });
        }
        else {
            gapp.append('text')
				.attr('class', 'droite')
				.text(donnees.tag[i + (nombreApercu[cat] * 5)])
				.attr('id', 'id'+cat)
				.attr('x', listeNoeud[i].x - 10 )
				.attr('y', listeNoeud[i].y + 5 )
				.attr('fill', donnees.couleur)
				.append('title')
				.text(function() { return donnees.noeud[i + (nombreApercu[cat] * 5)]; });
        }
    }

	// On enregistre le nombre de fois qu'on a affiché les aperçus pour cette catégorie
    nombreApercu[cat]++;
}

function initialiserHistorique(tailleMaximale) {
    // Vérification de la taille maximale
    if (tailleMaximale < 0) {
        // La taille est fixée à 20 par défaut
        tailleMaxHistorique = 20;
    }

    else {
        tailleMaxHistorique = tailleMaximale;
    }

    // Initialisation du tableau d'historique de navigation
    historique = new Array();
    
    // Prise en compte de l'initialisation
    historiqueEstInitialise = true;
}

function genererGraphe(conteneurGraphe, typeDeGraphe, idGraphe) {
	// Mémorisation du conteneur
    conteneur = conteneurGraphe;
	
    // Création du cadre du graphe
	var largeurCadre = parseInt(d3.select(conteneur).style('width')), hauteurCadre = parseInt(d3.select(conteneur).style('height'));
    var cadre = d3.select(conteneur).append('svg').attr('width', largeurCadre).attr('height', hauteurCadre);

	$.ajax({
		// Le fichier qui gère AJAX côté serveur (PHP)
		url: '<?php echo constant("NOM_APPLICATION"); ?>/dispatcherAJAX.php',
		// Les données sont transmises en GET
		type: 'GET',
		// On transmet le type de graphe et l'id du noeud ou de la catégorie central(e). Pour le graphe d'accueil l'id est 0.
		data: 'typeDeGraphe=' + typeDeGraphe + '&idGraphe=' + idGraphe,
		// Format de retour attendu
		dataType: "json",
		success: function(json) {
			if (json.etat == 'ok') {
			    // Ajout des éléments contenus dans le fichier JSON
			    var donnees = JSON.parse(JSON.stringify(json.graphe));

				// S'il s'agit du graphe d'accueil
				if (typeDeGraphe == 'accueil') {
					// Configuration du cadre aux bonnes proportions
					cadre.attr('viewBox', 0 + ' ' + 0 + ' ' + 800 + ' ' + 800)
					.attr('preserveAspectRatio', 'xMidYMid');

					// Préparation des noeuds
					caracteristiquesNoeuds = preparerNoeuds(donnees, largeurCadre, hauteurCadre, typeDeGraphe);

					// Préparation des liaisons
					caracteristiquesLiaisons = preparerLiaisons(donnees, caracteristiquesNoeuds);

					// Ajout des liaisons
					ajouterLiaisons(caracteristiquesLiaisons, cadre, 150);

					// Ajout des noeuds dans le cadre
					ajouterNoeuds(caracteristiquesNoeuds, cadre, 'accueil', 1000);

				    // Ajout du lien d'export de graphe
				    cadre.append('text')
                    .text('Exporter')
                    .attr('font-size', 14)
                    .attr('font-family', 'serif')
                    .attr('fill', 'blue')
                    .attr('transform', function (d) { return 'translate(' + largeurCadre / 2 + ',' + (hauteurCadre - 100) + ')'; })
                    .style('text-anchor', 'middle')
                    .style('cursor', 'pointer')
                    .on("click", genererLienTelechargement);
				    
				    // Initialisation pour les aperçus de chaque catégorie
					for (var i = 1; i < caracteristiquesNoeuds.length; i = i + 2){
						nombreApercu[caracteristiquesNoeuds[i].categorie] = 0;
					}
				}

				// S'il s'agit d'un graphe de catégorie
				else if (typeDeGraphe == 'categorie') {
					var rayonCercleImaginaire = 300;

					// Configuration du cadre aux bonnes proportions
					cadre.attr('viewBox', 0 + ' ' + 0 + ' ' + largeurCadre + ' ' + hauteurCadre)
					.attr('preserveAspectRatio', 'xMidYMid');            

					ajouterCercle(donnees, cadre, largeurCadre, hauteurCadre, rayonCercleImaginaire);
					ajouterRectangle(donnees.categories, donnees, cadre, largeurCadre / 2, hauteurCadre / 2, rayonCercleImaginaire);

					// Préparation du noeud de navigation
					caracteristiquesNoeuds = preparerNoeuds(donnees, largeurCadre, hauteurCadre, typeDeGraphe);

					// Ajout du noeud de navigation dans le cadre
					ajouterNoeuds(caracteristiquesNoeuds, cadre, 'categorie', 1000);

					//Initialisation des variables categorie_gauche et categorie_droite
					categorie_gauche = donnees.categories[1].id_categorie;
					categorie_droite = donnees.categories[2].id_categorie;
				}

				// S'il s'agit d'un graphe de noeud
				else if (typeDeGraphe == 'noeud') {
					var caracteristiquesNoeuds;
					var caracteristiquesLiaisons;
					
					// Configuration du cadre aux bonnes proportions
					cadre.attr('viewBox', 0 + ' ' + 0 + ' ' + 800 + ' ' + 800)
					.attr('preserveAspectRatio', 'xMidYMid');

					// Préparation des noeuds
					caracteristiquesNoeuds = preparerNoeuds(donnees, largeurCadre, hauteurCadre, typeDeGraphe);

					// Préparation des liaisons
					caracteristiquesLiaisons = preparerLiaisons(donnees, caracteristiquesNoeuds);

					// Ajout des liaisons
					ajouterLiaisons(caracteristiquesLiaisons, cadre, 150);

					// Ajout des noeuds dans le cadre
					ajouterNoeuds(caracteristiquesNoeuds, cadre, 'noeud', 1000);
				}
			}
			else {
				$('#message_ajax').slideDown('normal');
				$('#message_ajax').html('Votre demande n\'a pas pu &eacute;t&eacute; trait&eacute;e !<br><u>Raison:</u> '+json.erreur);
			}
		}
	})
	.fail(function(q, e, r) {
		$('#message_ajax').slideDown('normal');
		$('#message_ajax').html('Votre demande n\'a pas pu &eacute;t&eacute; trait&eacute;e !<br><u>Raison:</u> '+r);
	})
	.always(function() {
		setTimeout(function(){
			$('#message_ajax').slideUp('normal');
		}, 5000);
		setTimeout(function(){
			$('#message_ajax').css('background-color', '#FF0000');
		}, 6000);
	});

    // Si l'historique est activé, l'historique est mis à jour
    if (historiqueEstInitialise == true) {
        // Si la taille maximale du tableau d'historique est atteinte
        if (historique.length == tailleMaxHistorique) {
            // L'élément le plus ancien est effacé
            historique.shift();
        }

        // Préparation de l'élément à mémoriser
        var nouvelElement = new graphePrecedent();
        nouvelElement.type = typeDeGraphe;
        nouvelElement.id = idGraphe;

        // Ajout du nouvel élément dans l'historique
        historique.push(nouvelElement);
    }

    // Indication de l'URL en cours de visualisation, cette URL peut-être copiée afin de retourner directement à ce graphe
    $('.url').val('http://<?php echo $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']; ?>?typeDeGraphe=' + typeDeGraphe + '&idGraphe=' + idGraphe);
}
</script>