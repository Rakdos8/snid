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

	// A l'envoi du formuaire ayant la classe CSS "form_noeud_ajout"
	$('form.form_noeud_ajout').on('submit', function() {
		// Appel Ajax
		$.ajax({
			// Le fichier qui gère AJAX côté serveur (PHP)
			url: $(this).attr('action'),
			// La méthode indiquée d'envoi des données (POST ou GET)
			type: $(this).attr('method'),
			// On transmet le formulaire demandé et ses données sérialisées
			data: "validationFormulaire=ajouterNoeud&"+$(this).serialize(),
			// Format de retour attendu
			dataType: "json",
			success: function(json) {
				if (json.etat == 'ok') {
					$('#message_ajax').slideDown('normal');
					$('#message_ajax').css('background-color', '#0BA00B');
					$('#message_ajax').html('Le n&oelig;ud a bien &eacute;t&eacute; ins&eacute;r&eacute; !');
					$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/'+json.fichier);
				}
				else {
					$('#message_ajax').slideDown('normal');
					$('#message_ajax').html('L\'ajout du n&oelig;ud n\'a pas pu &eacute;t&eacute; trait&eacute; !<br><u>Raison:</u> '+json.erreur);
				}
			}
		})
		.fail(function(q, e, r) {
			$('#message_ajax').slideDown('normal');
			$('#message_ajax').html('L\'ajout du n&oelig;ud n\'a pas pu &eacute;t&eacute; trait&eacute; !<br><u>Raison:</u> '+r);
		})
		.always(function() {
			resetMessageAJAX();
		});

		// On empêche le navigateur de soumettre lui-même le formulaire
		return false;
	});

	// A l'envoi du formuaire ayant la classe CSS "form_noeud_gerer"
	$('form.form_noeud_gerer').on('submit', function() {
		// Appel Ajax
		$.ajax({
			// Le fichier qui gère AJAX côté serveur (PHP)
			url: $(this).attr('action'),
			// La méthode indiquée d'envoi des données (POST ou GET)
			type: $(this).attr('method'),
			// On transmet le formulaire demandé et ses données sérialisées
			data: "validationFormulaire=gererNoeuds&"+$(this).serialize(),
			// Format de retour attendu
			dataType: "json",
			success: function(json) {
				if (json.etat == 'ok') {
					$('#message_ajax').slideDown('normal');
					$('#message_ajax').css('background-color', '#0BA00B');
					$('#message_ajax').html('Le n&oelig;ud a bien &eacute;t&eacute; mise &agrave; jour !');
					$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/'+json.fichier);
				}
				else {
					$('#message_ajax').slideDown('normal');
					$('#message_ajax').html('La mise &agrave; jour du n&oelig;ud n\'a pas pu &eacute;t&eacute; trait&eacute;e !<br><u>Raison:</u> '+json.erreur);
				}
			}
		})
		.fail(function(q, e, r) {
			$('#message_ajax').slideDown('normal');
			$('#message_ajax').html('La mise &agrave; jour du n&oelig;ud n\'a pas pu &eacute;t&eacute; trait&eacute;e !<br><u>Raison:</u> '+r);
		})
		.always(function() {
			resetMessageAJAX();
		});

		// On empêche le navigateur de soumettre lui-même le formulaire
		return false;
	});

	// Au clic de chaque balise <a> contenu dans un <td>
	$('td a').on('click', function() {
		// Si c'est un lien menant aux liens
		if ($(this).attr('value') == "lien") {
			$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/mvc_view/gererLiens.php?id_noeud='+$(this).attr('class'));
		}
		// Si c'est un lien menant au template
		if ($(this).attr('value') == "template") {
			$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/mvc_view/gererTemplates.php?id_noeud='+$(this).attr('class'));
		}
		// Si c'est un lien menant à la suppression
		else if ($(this).attr('value') == "suppression") {
			var nbLiens = $(this).attr('nbLiens');
			var nomNoeud = $(this).attr('nomNoeud');
			var messageAvertissement = "Êtes-vous sûr(e) ?\n\n";
			messageAvertissement	+= "ATTENTION ! Vous allez supprimer le nœud '"+nomNoeud+"' comportant TOUS ses liens ("+nbLiens+").\n\n";
			messageAvertissement	+= "Cette action ne pourra pas être annulé une fois effectuée.";

			if (confirm(messageAvertissement)) {
				// Appel Ajax
				$.ajax({
					// Le fichier qui gère AJAX côté serveur (PHP)
					url: "<?php echo constant("NOM_APPLICATION"); ?>/dispatcherAJAX.php",
					// La méthode indiquée d'envoi des données (POST ou GET)
					type: "POST",
					// On transmet le formulaire demandé et ses données sérialisées
					data: "validationFormulaire=supprimerNoeud&id_noeud="+$(this).attr('class'),
					// Format de retour attendu
					dataType: "json",
					success: function(json) {
						if (json.etat == 'ok') {
							$('#message_ajax').slideDown('normal');
							$('#message_ajax').css('background-color', '#0BA00B');
							$('#message_ajax').html('Le n&oelig;ud et ses liens associ&eacute;s ont bien &eacute;t&eacute; supprim&eacute;s !');
							$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/'+json.fichier);
						}
						else {
							$('#message_ajax').slideDown('normal');
							$('#message_ajax').html('Le n&oelig;ud et ses liens associ&eacute;s n\'ont pas pu &ecirc;tre supprim&eacute;s !<br><u>Raison:</u> '+json.erreur);
						}
					}
				})
				.fail(function(q, e, r) {
					$('#message_ajax').slideDown('normal');
					$('#message_ajax').html('Le n&oelig;ud et ses liens associ&eacute;s n\'ont pas pu &ecirc;tre supprim&eacute;s !<br><u>Raison:</u> '+r);
				})
				.always(function() {
					resetMessageAJAX();
				});
			}
		}
	});

	// Lorsque l'on change la catégorie
	$('select.changerCategorie').on('change', function() {
		if ($(this).val() > 0) {
			$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/mvc_view/gererNoeuds.php?id_categorie='+$(this).val());
		}
	});

	// Lorsque l'on change l'ordre d'affichage
	$('td span.colonne_organisable').on('click', function() {
		var idCategorie = <?php echo $idCategorieDemandee; ?>;

		if (idCategorie > 0) {
			idCategorie = '&id_categorie=<?php echo $idCategorieDemandee; ?>';
		}
		else {
			idCategorie = '';
		}

		if ("<?php echo $ordreSensActuel; ?>" == "DESC") {
			$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/mvc_view/gererNoeuds.php?ordreColonne='+$(this).attr('id')+'&ordreSens=ASC'+idCategorie);
		}
		else {
			$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/mvc_view/gererNoeuds.php?ordreColonne='+$(this).attr('id')+'&ordreSens=DESC'+idCategorie);
		}
	});
});
</script>