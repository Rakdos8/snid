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

	// A la sélection d'une catégorie dans la liste (de gauche)
	$('td select.select_texte').on('change', function() {
		// Si le select est un <select> des catégories
		if ($(this).hasClass('categorie')) {
			// On récupère le nom de la catégorie
			var idCategorie = $(this).val();
			// On récupère l'id du <select> des noeuds à éditer
			var selectNoeud = "#"+$(this).attr('id').replace("categorie", "noeud");
	 
			// Si la catégorie n'est pas vide
			if (idCategorie != '' && idCategorie > 0) {
				// On vide la liste des noeuds
				$(selectNoeud).empty();
				// On rend possible le choix du noeud
				$(selectNoeud).removeAttr('disabled');

				$.ajax({
					// Le fichier qui gère AJAX côté serveur (PHP)
					url: "mvcApp_inra_mapping_bo/dispatcherAJAX.php",
					// La méthode indiquée d'envoi des données (POST ou GET)
					type: "POST",
					// On transmet la catégorie demandée
					data: "rafraichirChamps=afficherNoeuds&id_categorie="+idCategorie,
					// Format de retour attendu
					dataType: 'json',
					success: function(json) {
						if (json.etat == 'ok') {
							$.each(json.retour, function(index, valeur) {
								$(selectNoeud).append('<option value="'+valeur.id_noeud+'">'+valeur.nom_entier+'</option>');
							});
						}
						else {
							$('#message_ajax').slideDown('normal');
							$('#message_ajax').html('La r&eacute;cup&eacute;ration des n&oelig;uds associ&eacute;s &agrave; la cat&eacute;gorie "'+$($(this).attr('id')+' option:selected').text()+'" n\'a pas pu &ecirc;tre trait&eacute;e !<br><u>Raison:</u> '+json.erreur);
						}
					}
				})
				.fail(function(q, e, r) {
					$('#message_ajax').slideDown('normal');
					$('#message_ajax').html('La r&eacute;cup&eacute;ration des n&oelig;uds associ&eacute;s &agrave; la cat&eacute;gorie "'+$(this).text()+'" n\'a pas pu &ecirc;tre trait&eacute;e !<br><u>Raison:</u> '+r);
				})
				.always(function() {
					resetMessageAJAX();
				});
			}
			else {
				// On vide la liste des noeuds
				$(selectNoeud).empty();
				// On rend impossible le choix du noeud
				$(selectNoeud).attr('disabled', 'disabled');
				// On averti qu'il faut choisir une catégorie
				$(selectNoeud).append('<option value="">- - - - - - - - </option>');
			}
		}
	});

	// A l'envoi du formuaire ayant la classe CSS "form_noeud_ajout"
	$('form.form_lien_ajout').on('submit', function() {
		// Appel Ajax
		$.ajax({
			// Le fichier qui gère AJAX côté serveur (PHP)
			url: $(this).attr('action'),
			// La méthode indiquée d'envoi des données (POST ou GET)
			type: $(this).attr('method'),
			// On transmet le formulaire demandé et ses données sérialisées
			data: "validationFormulaire=ajouterLien&"+$(this).serialize(),
			// Format de retour attendu
			dataType: "json",
			success: function(json) {
				if (json.etat == 'ok') {
					$('#message_ajax').slideDown('normal');
					$('#message_ajax').css('background-color', '#0BA00B');
					$('#message_ajax').html('Le lien a bien &eacute;t&eacute; ins&eacute;r&eacute; !');
					$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/'+json.fichier);
				}
				else {
					$('#message_ajax').slideDown('normal');
					$('#message_ajax').html('L\'ajout du lien n\'a pas pu &eacute;t&eacute; trait&eacute; !<br><u>Raison:</u> '+json.erreur);
				}
			}
		})
		.fail(function(q, e, r) {
			$('#message_ajax').slideDown('normal');
			$('#message_ajax').html('L\'ajout du lien n\'a pas pu &eacute;t&eacute; trait&eacute; !<br><u>Raison:</u> '+r);
		})
		.always(function() {
			resetMessageAJAX();
		});

		// On empêche le navigateur de soumettre lui-même le formulaire
		return false;
	});

	// A l'envoi du formuaire ayant la classe CSS "form_noeud_ajout"
	$('form.form_lien_gerer').on('submit', function() {
		// Appel Ajax
		$.ajax({
			// Le fichier qui gère AJAX côté serveur (PHP)
			url: $(this).attr('action'),
			// La méthode indiquée d'envoi des données (POST ou GET)
			type: $(this).attr('method'),
			// On transmet le formulaire demandé et ses données sérialisées
			data: "validationFormulaire=gererLiens&"+$(this).serialize(),
			// Format de retour attendu
			dataType: "json",
			success: function(json) {
				if (json.etat == 'ok') {
					$('#message_ajax').slideDown('normal');
					$('#message_ajax').css('background-color', '#0BA00B');
					$('#message_ajax').html('Le lien a bien &eacute;t&eacute; modifi&eacute; !');
					$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/'+json.fichier);
				}
				else {
					$('#message_ajax').slideDown('normal');
					$('#message_ajax').html('La modification du lien n\'a pas pu &eacute;t&eacute; trait&eacute;e !<br><u>Raison:</u> '+json.erreur);
				}
			}
		})
		.fail(function(q, e, r) {
			$('#message_ajax').slideDown('normal');
			$('#message_ajax').html('La modification du lien n\'a pas pu &eacute;t&eacute; trait&eacute;e !<br><u>Raison:</u> '+r);
		})
		.always(function() {
			resetMessageAJAX();
		});

		// On empêche le navigateur de soumettre lui-même le formulaire
		return false;
	});

	// Au clic de chaque balise <a> contenu dans un <td>
	$('td a').on('click', function() {
		var messageAvertissement = "Êtes-vous sûr(e) ?\n\n";
		messageAvertissement	+= "ATTENTION ! Cette action supprimera entièrement le lien !\n\n";
		messageAvertissement	+= "Cette action ne pourra pas être annulé une fois effectuée.";
		if (confirm(messageAvertissement)) {
			// Appel Ajax
			$.ajax({
				// Le fichier qui gère AJAX côté serveur (PHP)
				url: "<?php echo constant("NOM_APPLICATION"); ?>/dispatcherAJAX.php",
				// La méthode indiquée d'envoi des données (POST ou GET)
				type: "POST",
				// On transmet le formulaire demandé et ses données sérialisées
				data: "validationFormulaire=supprimerLien&id_lien="+$(this).attr('class'),
				// Format de retour attendu
				dataType: "json",
				success: function(json) {
					if (json.etat == 'ok') {
						$('#message_ajax').slideDown('normal');
						$('#message_ajax').css('background-color', '#0BA00B');
						$('#message_ajax').html('Le lien a bien &eacute;t&eacute; supprim&eacute; !');
						$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/'+json.fichier);
					}
					else {
						$('#message_ajax').slideDown('normal');
						$('#message_ajax').html('Le lien n\'a pas pu &eacute;t&eacute; supprim&eacute; !<br><u>Raison:</u> '+json.erreur);
					}
				}
			})
			.fail(function(q, e, r) {
				$('#message_ajax').slideDown('normal');
				$('#message_ajax').html('Le lien n\'a pas pu &eacute;t&eacute; supprim&eacute; !<br><u>Raison:</u> '+r);
			})
			.always(function() {
				resetMessageAJAX();
			});
		}
	});

	// Lorsque l'on change le noeud
	$('select.changerNoeud').on('change', function() {
		if ($(this).val() == 0) {
			$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/mvc_view/gererLiens.php');
		}
		else {
			$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/mvc_view/gererLiens.php?id_noeud='+$(this).val());
		}
	});

	// Lorsque l'on change la catégorie
	$('select.changerCategorie').on('change', function() {
		if ($(this).val() == 0) {
			$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/mvc_view/gererLiens.php');
		}
		else {
			$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/mvc_view/gererLiens.php?id_categorie='+$(this).val());
		}
	});

	// Lorsque l'on change l'ordre d'affichage
	$('td span.colonne_organisable').on('click', function() {
		var idNoeud = <?php echo $idNoeudDemande; ?>;
		var idCategorie = <?php echo $idCategorieDemandee; ?>;

		if (idCategorie > 0) {
			idCategorie = '&id_categorie=<?php echo $idCategorieDemandee; ?>';
		}
		else {
			idCategorie = '';
		}

		if (idNoeud > 0) {
			idNoeud = '&id_noeud=<?php echo $idNoeudDemande; ?>';
		}
		else {
			idNoeud = '';
		}

		if ("<?php echo $ordreSensActuel; ?>" == "DESC") {
			$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/mvc_view/gererLiens.php?ordreColonne='+$(this).attr('id')+'&ordreSens=ASC'+idCategorie+idNoeud);
		}
		else {
			$('#cadre_menu_bas').load('<?php echo constant("NOM_APPLICATION"); ?>/mvc_view/gererLiens.php?ordreColonne='+$(this).attr('id')+'&ordreSens=DESC'+idCategorie+idNoeud);
		}
	});
});
</script>