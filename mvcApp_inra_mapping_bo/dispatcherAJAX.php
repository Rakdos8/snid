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
	session_start();
	$retourJSON = array();

	// On vérifie que la personne est bien authentifiée et bien connectée
	if (isset($_SESSION['identifiant']) && strlen($_SESSION['identifiant']) > 0) {
		define("NOM_APPLICATION", "mvcApp_inra_mapping_bo", false);
		require_once "../config.php";
		require_once "mvc_controller/mainControllerAJAX.php";

		if (constant("EST_CONFIGURE") == null || constant("EST_CONFIGURE") != true) {
			$retourJSON['etat'] = "erreur";
			$retourJSON['erreur'] = "Impossible d'ex&eacute;cuter le script de configuration !";
		}
		else {
			if (count($_GET) > 0) {
				// Si un changement de page a été demandé
				if (isset($_GET['navigationMenu'])) {
					$retourJSON = mainControllerAJAX::gestionDuMenuDeNavigation($_GET['navigationMenu'], context::get_Instance());
				}
				// Si aucune valeur n'a été envoyé en GET ou en POST
				else {
					$retourJSON['etat'] = "erreur";
					$retourJSON['erreur'] = "Erreur d'interpr&eacute;tation du dispatcher AJAX Navigation! (1)";
				}
			}
			else if (count($_POST) > 0) {
				// Si c'est une validation d'un formulaire
				if (isset($_POST['validationFormulaire'])) {
					$retourJSON = mainControllerAJAX::gestionDesFormulaires($_POST['validationFormulaire'], $_POST, context::get_Instance());
				}
				// Si c'est une validation d'un formulaire
				else if (isset($_POST['rafraichirChamps'])) {
					$retourJSON = mainControllerAJAX::gestionDuRafraichissement($_POST['rafraichirChamps'], $_POST, context::get_Instance());
				}
				// Sinon
				else {
					$retourJSON['etat'] = "erreur";
					$retourJSON['erreur'] = "Erreur d'interpr&eacute;tation du dispatcher AJAX ! (1)";
				}
			}
			// Si aucune valeur n'a été envoyé en GET ou en POST
			else {
				$retourJSON['etat'] = "erreur";
				$retourJSON['erreur'] = "Erreur d'interpr&eacute;tation du dispatcher AJAX ! (1)";

			}
		}
		echo json_encode($retourJSON);
	}
	else {
		session_destroy();

		$retourJSON['etat'] = "erreur";
		$retourJSON['erreur'] = "Il est n&eacute;cessaire de se connecter :-)";

		echo json_encode($retourJSON);
	}
?>