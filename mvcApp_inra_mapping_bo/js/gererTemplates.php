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

	// Au clic de la balise <a> ayant comme classe "retour"
	$('a.retour').on('click', function() {
		$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/mvc_view/gererTemplates.php');
	});
	
	// A l'envoi du formuaire ayant la classe CSS "ajouter_modifier_template"
	$('form.ajouter_modifier_template').on('submit', function() {
		// CKEditor requiert de mettre à jour avant de sérialiser
		CKEDITOR.instances['contenu'].updateElement();

		// Appel Ajax
		$.ajax({
			// Le fichier qui gère AJAX côté serveur (PHP)
			url: $(this).attr('action'),
			// La méthode indiquée d'envoi des données (POST ou GET)
			type: $(this).attr('method'),
			// On transmet le formulaire demandé et ses données sérialisées
			data: "validationFormulaire=ajouterTemplate&"+$(this).serialize(),
			// Format de retour attendu
			dataType: "json",
			success: function(json) {
				if (json.etat == 'ok') {
					$('#message_ajax').slideDown('normal');
					$('#message_ajax').css('background-color', '#0BA00B');
					$('#message_ajax').html('Le nouveau template a bien &eacute;t&eacute; ins&eacute;r&eacute; !');
					$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/'+json.fichier);
				}
				else {
					$('#message_ajax').slideDown('normal');
					$('#message_ajax').html('Le nouveau template n\'a pas pu &eacute;t&eacute; trait&eacute; !<br><u>Raison:</u> '+json.erreur);
				}
			}
		})
		.fail(function(q, e, r) {
			$('#message_ajax').slideDown('normal');
			$('#message_ajax').html('Le nouveau template n\'a pas pu &eacute;t&eacute; trait&eacute; !<br><u>Raison:</u> '+r);
		})
		.always(function() {
			resetMessageAJAX();
		});

		// On empêche le navigateur de soumettre lui-même le formulaire
		return false;
	});

	// Au clic de chaque balise <a> contenu dans un <td>
	$('td a').on('click', function() {
		if ($(this).attr('value') == "edition") {
			$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/mvc_view/gererTemplates.php?id_noeud='+$(this).attr('class'));
		}
		else if ($(this).attr('value') == "suppression") {
			var nomNoeud = $(this).attr('nomNoeud');
			var messageAvertissement = "Êtes-vous sûr(e) ?\n\n";
			messageAvertissement	+= "ATTENTION ! Vous allez supprimer le template du nœud '"+nomNoeud+"'.\n\n";
			messageAvertissement	+= "Cette action ne pourra pas être annulé une fois effectuée.";

			if (confirm(messageAvertissement)) {
				// Appel Ajax
				$.ajax({
					// Le fichier qui gère AJAX côté serveur (PHP)
					url: "<?php echo constant("NOM_APPLICATION"); ?>/dispatcherAJAX.php",
					// La méthode indiquée d'envoi des données (POST ou GET)
					type: "POST",
					// On transmet le formulaire demandé et ses données sérialisées
					data: "validationFormulaire=supprimerTemplate&id_template="+$(this).attr('class'),
					// Format de retour attendu
					dataType: "json",
					success: function(json) {
						if (json.etat == 'ok') {
							$('#message_ajax').slideDown('normal');
							$('#message_ajax').css('background-color', '#0BA00B');
							$('#message_ajax').html('Le template bien &eacute;t&eacute; supprim&eacute; !');
							$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/'+json.fichier);
						}
						else {
							$('#message_ajax').slideDown('normal');
							$('#message_ajax').html('Le template n\'a pas pu &ecirc;tre supprim&eacute; !<br><u>Raison:</u> '+json.erreur);
						}
					}
				})
				.fail(function(q, e, r) {
					$('#message_ajax').slideDown('normal');
					$('#message_ajax').html('Le template n\'a pas pu &ecirc;tre supprim&eacute; !<br><u>Raison:</u> '+r);
				})
				.always(function() {
					resetMessageAJAX();
				});
			}
		}
	});

	// Lorsque l'on change le noeud
	$('select.changerNoeud').on('change', function() {
		if ($(this).val() > 0) {
			$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/mvc_view/gererTemplates.php?id_noeud='+$(this).val());
		}
	});

	// Lorsque l'on change l'ordre d'affichage
	$('td span.colonne_organisable').on('click', function() {
		if ("<?php echo $ordreSensActuel; ?>" == "DESC") {
			$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/mvc_view/gererTemplates.php?ordreColonne='+$(this).attr('id')+'&ordreSens=ASC');
		}
		else {
			$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/mvc_view/gererTemplates.php?ordreColonne='+$(this).attr('id')+'&ordreSens=DESC');
		}
	});
});
</script>