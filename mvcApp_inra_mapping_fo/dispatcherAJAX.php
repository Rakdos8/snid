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
	$retourJSON = array();

	if (count($_GET) > 0) {
		require_once "mvc_controller/mainControllerAJAX.php";

		// Si le graphe demandé est celui de l'accueil
		if (isset($_GET['typeDeGraphe']) && strtolower($_GET['typeDeGraphe']) == "accueil") {
			$retourJSON = mainControllerAJAX::genererGrapheAccueil($_GET);
		}
		// Si le graphe demandé est celui d'une catégorie
		else if (isset($_GET['typeDeGraphe']) && strtolower($_GET['typeDeGraphe']) == "categorie") {
			$retourJSON = mainControllerAJAX::genererGrapheCategorie($_GET);
		}
		// Si le graphe demandé est celui des noeuds
		else if (isset($_GET['typeDeGraphe']) && strtolower($_GET['typeDeGraphe']) == "noeud") {
			$retourJSON = mainControllerAJAX::genererGrapheNoeud($_GET);
		}
		else {
			$retourJSON['etat'] = "erreur";
			$retourJSON['erreur'] = "Erreur d'interpr&eacute;tation du dispatcher AJAX ! (2)";
		}
		echo json_encode($retourJSON);
	}
	// Si aucune valeur n'a été envoyé en GET ou en POST
	else {
		$retourJSON['etat'] = "erreur";
		$retourJSON['erreur'] = "Erreur d'interpr&eacute;tation du dispatcher AJAX ! (1)";

		echo json_encode($retourJSON);
	}
?>