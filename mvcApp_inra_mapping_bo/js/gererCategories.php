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
$(document).ready(function() {
	function resetMessageAJAX() {
		setTimeout(function(){
			$('#message_ajax').slideUp('normal');
		}, 5000);
		setTimeout(function(){
			$('#message_ajax').css('background-color', '#FF0000');
		}, 6000);
	}

	// Pour chaque balise input contenu dans un <td>
	$('td input').each(function() {
		// Qui a la classe CSS "couleur"
		if ($(this).hasClass('couleur')) {
			// On lui attribut la valeur de ce champ en couleur de fond
			$(this).css('background-color', $(this).val());
			// On lui attribut la couleur blanche pour écrire
			$(this).css('color', 'white');
		}
	});
	
	// Pour chaque touches appuyés dans un input contenu dans un <td>
	$('td input').keyup(function() {
		// Qui a la classe CSS "couleur"
		if ($(this).hasClass('couleur')) {
			// On lui attribut la valeur de ce champ en couleur de fond
			$(this).css('background-color', $(this).val());
			// On lui attribut la couleur blanche pour écrire
			$(this).css('color', 'white');
		}
	});

	// A l'envoi du formuaire ayant la classe CSS "form_categorie_ajout"
	$('form.form_categorie_ajout').on('submit', function() {
		// Appel Ajax
		$.ajax({
			// Le fichier qui gère AJAX côté serveur (PHP)
			url: $(this).attr('action'),
			// La méthode indiquée d'envoi des données (POST ou GET)
			type: $(this).attr('method'),
			// On transmet le formulaire demandé et ses données sérialisées
			data: "validationFormulaire=ajouterCategorie&"+$(this).serialize(),
			// Format de retour attendu
			dataType: "json",
			success: function(json) {
				if (json.etat == 'ok') {
					$('#message_ajax').slideDown('normal');
					$('#message_ajax').css('background-color', '#0BA00B');
					$('#message_ajax').html('La cat&eacute;gorie a bien &eacute;t&eacute; ins&eacute;r&eacute;e !');
					$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/'+json.fichier);
				}
				else {
					$('#message_ajax').slideDown('normal');
					$('#message_ajax').html('L\'ajout de la cat&eacute;gorie n\'a pas pu &eacute;t&eacute; trait&eacute;e !<br><u>Raison:</u> '+json.erreur);
				}
			}
		})
		.fail(function(q, e, r) {
			$('#message_ajax').slideDown('normal');
			$('#message_ajax').html('L\'ajout de la cat&eacute;gorie n\'a pas pu &eacute;t&eacute; trait&eacute; !<br><u>Raison:</u> '+r);
		})
		.always(function() {
			resetMessageAJAX();
		});

		// On empêche le navigateur de soumettre lui-même le formulaire
		return false;
	});

	// A l'envoi du formuaire ayant la classe CSS "form_categorie_gerer"
	$('form.form_categorie_gerer').on('submit', function() {
		// Appel Ajax
		$.ajax({
			// Le fichier qui gère AJAX côté serveur (PHP)
			url: $(this).attr('action'),
			// La méthode indiquée d'envoi des données (POST ou GET)
			type: $(this).attr('method'),
			// On transmet le formulaire demandé et ses données sérialisées
			data: "validationFormulaire=gererCategories&"+$(this).serialize(),
			// Format de retour attendu
			dataType: "json",
			success: function(json) {
				if (json.etat == 'ok') {
					$('#message_ajax').slideDown('normal');
					$('#message_ajax').css('background-color', '#0BA00B');
					$('#message_ajax').html('La cat&eacute;gorie a bien &eacute;t&eacute; mise &agrave; jour !');
					$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/'+json.fichier);
				}
				else {
					$('#message_ajax').slideDown('normal');
					$('#message_ajax').html('La mise &agrave; jour de la cat&eacute;gorie n\'a pas pu &eacute;t&eacute; trait&eacute;e !<br><u>Raison:</u> '+json.erreur);
				}
			}
		})
		.fail(function(q, e, r) {
			$('#message_ajax').slideDown('normal');
			$('#message_ajax').html('La mise &agrave; jour n\'a pas pu &eacute;t&eacute; trait&eacute;e !<br><u>Raison:</u> '+r);
		})
		.always(function() {
			resetMessageAJAX();
		});

		// On empêche le navigateur de soumettre lui-même le formulaire
		return false;
	});

	// Au clic de chaque balise <a> contenu dans un <td>
	$('td a').on('click', function() {
		// Si c'est un lien menant aux noeuds
		if ($(this).attr('value') == "noeud") {
			$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/mvc_view/gererNoeuds.php?id_categorie='+$(this).attr('class'));
		}
		// Si c'est un lien menant aux liens
		else if ($(this).attr('value') == "lien") {
			$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/mvc_view/gererLiens.php?id_categorie='+$(this).attr('class'));
		}
		// Si c'est un lien menant à la suppression
		else if ($(this).attr('value') == "suppression") {
			var nbLiens = $(this).attr('nbLiens');
			var nbNoeuds = $(this).attr('nbNoeuds');
			var nomCategorie = $(this).attr('nomCategorie');
			var messageAvertissement = "Êtes-vous sûr(e) ?\n\n";
			messageAvertissement	+= "ATTENTION ! Vous allez supprimer la catégorie '"+nomCategorie+"' comportant TOUS ses nœuds ("+nbNoeuds+") et TOUS ses liens ("+nbLiens+").\n\n";
			messageAvertissement	+= "Cette action ne pourra pas être annulé une fois effectuée.";

			if (confirm(messageAvertissement)) {
				// Appel Ajax
				$.ajax({
					// Le fichier qui gère AJAX côté serveur (PHP)
					url: "<?php echo constant("NOM_APPLICATION"); ?>/dispatcherAJAX.php",
					// La méthode indiquée d'envoi des données (POST ou GET)
					type: "POST",
					// On transmet le formulaire demandé et ses données sérialisées
					data: "validationFormulaire=supprimerCategorie&id_categorie="+$(this).attr('class'),
					// Format de retour attendu
					dataType: "json",
					success: function(json) {
						if (json.etat == 'ok') {
							$('#message_ajax').slideDown('normal');
							$('#message_ajax').css('background-color', '#0BA00B');
							$('#message_ajax').html('La catégorie, ses n&oelig;uds et ses liens associ&eacute;s ont bien &eacute;t&eacute; supprim&eacute;s !');
							$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/'+json.fichier);
						}
						else {
							$('#message_ajax').slideDown('normal');
							$('#message_ajax').html('La catégorie, ses n&oelig;uds et ses liens associ&eacute;s n\'ont pas pu &ecirc;tre supprim&eacute;s !<br><u>Raison:</u> '+json.erreur);
						}
					}
				})
				.fail(function(q, e, r) {
					$('#message_ajax').slideDown('normal');
					$('#message_ajax').html('La catégorie, ses n&oelig;uds et ses liens associ&eacute;s n\'ont pas pu &ecirc;tre supprim&eacute;s !<br><u>Raison:</u> '+r);
				})
				.always(function() {
					resetMessageAJAX();
				});
			}
		}
	});

	// Lorsque l'on change l'ordre d'affichage
	$('td span.colonne_organisable').on('click', function() {
		if ("<?php echo $ordreSensActuel; ?>" == "DESC") {
			$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/mvc_view/gererCategories.php?ordreColonne='+$(this).attr('id')+'&ordreSens=ASC');
		}
		else {
			$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/mvc_view/gererCategories.php?ordreColonne='+$(this).attr('id')+'&ordreSens=DESC');
		}
	});
});
</script>