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
<?php
	$identifiant = $context->get_sessionInformation("identifiant");
	if (isset($identifiant)) {
		$login = true;
	}
	else {
		$login = false;
	}
	
	$idAccordion = $context->get_ongletNavigationActif();
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>INRA Mapping &bull; Prototype backoffice</title>
			<!-- Les css -->
		<link rel="stylesheet" type="text/css" href="<?php echo constant("NOM_APPLICATION"); ?>/css/id.css">
		<link rel="stylesheet" type="text/css" href="<?php echo constant("NOM_APPLICATION"); ?>/css/type.css">
		<link rel="stylesheet" type="text/css" href="<?php echo constant("NOM_APPLICATION"); ?>/css/class.css">
		<link rel="stylesheet" type="text/css" href="<?php echo constant("NOM_APPLICATION"); ?>/css/datepicker.css">
		
		<link rel="stylesheet" type="text/css" href="<?php echo constant("NOM_APPLICATION"); ?>/css/smoothness/jquery-ui-1.10.3.custom.min.css">
			<!-- Les meta -->
		<meta charset="UTF-8">
		<meta name="author" content="BOURELLY Christophe">
		<meta name="description" content="Prototype backoffice INRA Mapping">
		<meta name="keywords" content="">
			<!-- CKeditor
		<script src="config/ckeditor/ckeditor.js"></script> -->
			<!-- JQuery -->
		<script src="js/jquery-1.9.1.js" charset="UTF-8"></script>
		<script src="js/jquery-ui-1.10.3.custom.min.js" charset="UTF-8"></script>
			<!-- CKeditor -->
		<script src="<?php echo constant("NOM_APPLICATION"); ?>/js/ckeditor/ckeditor.js"></script>

		<script>
		$(document).ready(function() {
			$('#accordion').accordion({active: <?php echo $idAccordion; ?>});
			
			// Si l'utilisateur a cliqué sur une image pour redimensionner les cadres
			$('div img').on('click', function() {
				// Si la demande vient du cadre du haut
				if ($(this).hasClass('haut')) {
					// Si on veut agrandir le cadre du haut
					if ($(this).hasClass('expand')) {
						$('#menu_bas').animate({top: '155%', height: '0%'});
						$('#menu_haut').animate({height: '98%'});
						$('.menu_bas_redimensionner').hide();
					}
					// Sinon on veut réduire le cadre du haut
					else {
						$('#menu_bas').animate({top: '55%', height: '43%'});
						$('#menu_haut').animate({height: '50%'});
						$('.menu_bas_redimensionner').show();
					}
				}
				// Sinon la demande vient du cadre du bas
				else {
					// Si on veut agrandir le cadre du bas
					if ($(this).hasClass('expand')) {
						$('#menu_haut').slideUp('normal');
						$('#menu_bas').animate({top: '10px', height: '98%'});
						$('.menu_haut_redimensionner').hide();
					}
					// Sinon on veut réduire le cadre du bas
					else {
						$('#menu_haut').slideDown('normal');
						$('#menu_bas').animate({top: '55%', height: '43%'});
						$('.menu_haut_redimensionner').show();
					}
				}
			});
			
			$('p.menu_navigation a').on('click', function() {
				if ($(this).attr('class') != null) {
					// Appel Ajax
					$.ajax({
						// Le fichier qui gère AJAX côté serveur (PHP)
						url: "<?php echo constant("NOM_APPLICATION"); ?>/dispatcherAJAX.php",
						// La méthode indiquée d'envoi des données
						type: "GET",
						// On transmet le menu de navigation demandé
						data: "navigationMenu="+$(this).attr('class'),
						// Format de retour attendu
						dataType: "json",
						success: function(json) {
							if (json.etat == "ok") {
								$('#message_ajax').slideUp("normal");
								$('#cadre_menu_bas').load("<?php echo constant("NOM_APPLICATION"); ?>/"+json.fichier);
							}
							else {
								$('#message_ajax').slideDown("normal");
								$('#message_ajax').html("Votre demande n'a pas pu &eacute;t&eacute; trait&eacute;e !<br><u>Raison:</u> "+json.erreur);
							}
						}
					})
					.fail(function(q, e, r) {
						$('#message_ajax').slideDown("normal");
						$('#message_ajax').html("Votre demande n'a pas pu &eacute;t&eacute; trait&eacute;e !<br><br><u>Raison:</u> "+r);
					});
				}
			});
		});
		</script>
	</head>

	<body id="page">
<div id="message_ajax"></div>

<?php
	if ($login) {
?>
<aside id="menu_gauche">
	Bienvenue, <i><?php echo $identifiant; ?></i> !
	<br><br>
	<div id="accordion">
		<h3>G&eacute;rer les utilisateurs</h3>
		<p class="menu_navigation">
			&bull; <a class="ajouterUtilisateur">Ajouter un utilisateur</a><br>
			&bull; <a class="gererUtilisateurs">G&eacute;rer les utilisateurs</a><br>
		</p>

		<h3>Configuration g&eacute;n&eacute;rale</h3>
		<p class="menu_navigation">
			&bull; <a class="ajouterConfiguration">Ajouter une configuration</a><br>
			&bull; <a class="gererConfigurations">G&eacute;rer les configurations</a><br>
		</p>

		<h3>Configurer le graphe</h3>
		<p class="menu_navigation">
			&bull; <a class="gererCategories">G&eacute;rer les cat&eacute;gories</a><br>
			&bull; <a class="gererNoeuds">G&eacute;rer les n&oelig;uds</a><br>
			&bull; <a class="gererLiens">G&eacute;rer les liens</a><br>
			&bull; <a class="gererTemplates">G&eacute;rer les templates</a><br>
			<br>
			&bull; <a href="http://www.w3schools.com/tags/ref_colorpicker.asp" target="_blank">Choisir une couleur</a>
		</p>

		<h3>Gestion des erreurs (PHP &amp; SQL)</h3>
		<p class="menu_navigation">
			&bull; <a class="erreursPHP">Voir les erreurs PHP</a><br/>
			&bull; <a class="erreursSQL">Voir les erreurs SQL</a>
		</p>
	</div>
	<br>
	<button onClick="window.location.replace('index.php?unlog');">D&eacute;connexion</button>
</aside>

<nav id="menu_haut">
	<iframe name="previsualisation_graphe" src="http://inra.bourelly.net/inra_mapping/" class="previsualisation_graphe"></iframe>
	<div class="menu_haut_redimensionner">
		<img src="<?php echo constant("NOM_APPLICATION"); ?>/images/expand.png" alt="&Eacute;tendre" title="&Eacute;tendre" class="haut expand">
		<img src="<?php echo constant("NOM_APPLICATION"); ?>/images/collapse.png" alt="R&eacute;duire" title="R&eacute;duire" class="haut collapse">
	</div>
</nav>

<section id="menu_bas">
	<div class="menu_bas_redimensionner">
		<img src="<?php echo constant("NOM_APPLICATION"); ?>/images/expand.png" alt="R&eacute;duire" title="R&eacute;duire" class="bas collapse">
		<img src="<?php echo constant("NOM_APPLICATION"); ?>/images/collapse.png" alt="&Eacute;tendre" title="&Eacute;tendre" class="bas expand">
	</div>
	<div id="cadre_menu_bas">
		<?php require constant("CHEMIN_APPLICATION")."/mvc_view/".$template_view.".php"; ?>
	</div>
</section>
<?php
	}
	else {
		require constant("CHEMIN_APPLICATION")."/mvc_view/".$template_view.".php";
	}
?>
	</body>
</html>