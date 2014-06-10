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
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>INRA Mapping &bull; Prototype frontoffice</title>
			<!-- Les css -->
		<link rel="stylesheet" type="text/css" href="<?php echo constant("NOM_APPLICATION"); ?>/css/id.css">
		<link rel="stylesheet" type="text/css" href="<?php echo constant("NOM_APPLICATION"); ?>/css/type.css">
		<link rel="stylesheet" type="text/css" href="<?php echo constant("NOM_APPLICATION"); ?>/css/class.css">
			<!-- Les meta -->
		<meta charset="UTF-8">
		<meta name="author" content="BOURELLY Christophe">
		<meta name="description" content="Prototype frontoffice INRA Mapping">
		<meta name="keywords" content="">
		<script src="js/jquery-1.9.1.js" charset="UTF-8"></script>
		<script src="js/jquery-ui-1.10.3.custom.min.js" charset="UTF-8"></script>
		<script src="<?php echo constant("NOM_APPLICATION"); ?>/js/d3.v3.js" charset="UTF-8"></script>
        <script src="<?php echo constant("NOM_APPLICATION"); ?>/js/utilitaires.js" charset="UTF-8"></script>
		<script charset="UTF-8">
		$(document).ready(function() {
			$("input[type='text']").on("click", function () {
			   $(this).select();
			});
		});
		</script>
	</head>

	<body>
<?php
	require_once constant("NOM_APPLICATION")."/js/graphes.php";

	if (isset($_GET['idGraphe']) && $_GET['idGraphe']) {
		$idGraphe = $_GET['idGraphe'];
	}
	else {
		$idGraphe = 0;
	}

	if (isset($_GET['typeDeGraphe']) && $_GET['typeDeGraphe']) {
		$typeDeGraphe = $_GET['typeDeGraphe'];
	}
	else {
		$typeDeGraphe = "accueil";
	}
?>
		<div id="message_ajax"></div>

		<div id="conteneur"></div>
		<br>
<?php
	if (!array_key_exists("HTTP_REFERER", $_SERVER) ||
		(array_key_exists("HTTP_REFERER", $_SERVER) && !strstr($_SERVER['HTTP_REFERER'], "backoffice"))) {
?>
		<div id="historiqueDeNavigation">
			<input class="url" type="text">
		</div>
<?php
	}
?>
        <script>
            // Initialisation de l'historique de navigation
            initialiserHistorique(50);
			
            // Génération du graphe d'accueil
            genererGraphe('#conteneur', '<?php echo $typeDeGraphe; ?>', <?php echo $idGraphe; ?>);
        </script>
	</body>
</html>