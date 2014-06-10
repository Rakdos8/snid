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

	// Au clic de chaque balise <a> contenu dans un <td>
	$('td a').on('click', function() {
		var messageAvertissement = "Êtes-vous sûr(e) ?\n\n";
		messageAvertissement	+= "ATTENTION ! Vous allez supprimer l'utilisateur.\n\n";
		messageAvertissement	+= "Cette action ne pourra pas être annulé une fois effectuée.";

		if (confirm(messageAvertissement)) {
			// Appel Ajax
			$.ajax({
				// Le fichier qui gère AJAX côté serveur (PHP)
				url: "<?php echo constant("NOM_APPLICATION"); ?>/dispatcherAJAX.php",
				// La méthode indiquée d'envoi des données (POST ou GET)
				type: "POST",
				// On transmet le formulaire demandé et ses données sérialisées
				data: "validationFormulaire=supprimerUtilisateur&identifiant="+$(this).attr('class'),
				// Format de retour attendu
				dataType: "json",
				success: function(json) {
					if (json.etat == 'ok') {
						$('#message_ajax').slideDown('normal');
						$('#message_ajax').css('background-color', '#0BA00B');
						$('#message_ajax').html('L\'utilisateur a bien &eacute;t&eacute; supprim&eacute; !');
						$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/'+json.fichier);
					}
					else {
						$('#message_ajax').slideDown('normal');
						$('#message_ajax').html('L\'utilisateur n\'a pas pu &ecirc;tre supprim&eacute; !<br><u>Raison:</u> '+json.erreur);
					}
				}
			})
			.fail(function(q, e, r) {
				$('#message_ajax').slideDown('normal');
				$('#message_ajax').html('L\'utilisateur n\'a pas pu &ecirc;tre supprim&eacute; !<br><u>Raison:</u> '+r);
			})
			.always(function() {
				resetMessageAJAX();
			});
		}
	});

	// A l'envoi du formuaire ayant la classe CSS "form_utilisateur_gerer"
	$('form.form_utilisateur_gerer').on('submit', function() {
		// Appel Ajax
		$.ajax({
			// Le fichier qui gère AJAX côté serveur (PHP)
			url: $(this).attr('action'),
			// La méthode indiquée d'envoi des données (POST ou GET)
			type: $(this).attr('method'),
			// On transmet le formulaire demandé et ses données sérialisées
			data: "validationFormulaire=gererUtilisateurs&"+$(this).serialize(),
			// Format de retour attendu
			dataType: "json",
			success: function(json) {
				if (json.etat == 'ok') {
					$('#message_ajax').slideDown('normal');
					$('#message_ajax').css('background-color', '#0BA00B');
					$('#message_ajax').html('Les informations de l\'utilisateur ont bien &eacute;t&eacute; mises &agrave; jour !');
					$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/'+json.fichier);
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
			resetMessageAJAX();
		});

		// On empêche le navigateur de soumettre lui-même le formulaire
		return false;
	});
});
</script>