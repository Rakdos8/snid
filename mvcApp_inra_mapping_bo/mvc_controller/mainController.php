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
class mainController {
    /*
	** Action par défaut si aucune page n'est appelée
	** entrée: request: équivalent à $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: Retourne SUCCESS si tout s'est bien passé, sinon ERROR
    */
	public static function index($request, $context) {
		$identifiant = $context->get_sessionInformation("identifiant");

		// Page par défaut quand on n'est pas connecté
		if (!isset($identifiant)) {
			$context->message = "Vous devez &ecirc;tre connect&eacute; avant d'acc&eacute;der &agrave; cette page !";
			return context::ERROR;
		}
		// Page par défaut quand on est connecté
		else {
			return context::SUCCESS;
		}
	}

    /*
	** Action de connexion
	** entrée: request: équivalent à $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: Retourne SUCCESS si tout s'est bien passé, sinon ERROR
    */
	public static function login($request, $context) {
		// Si le formulaire de connexion a été rempli
		if (isset($request['identifiant']) && isset($request['password'])) {
			require_once constant("NOM_APPLICATION")."/mvc_model/utilisateurTable.php";
			
			$motDePasse = $request['password'];
			$identifiant = $request['identifiant'];
			$userExist = utilisateurTable::get_utilisateur($identifiant, $motDePasse);

			if ($userExist != null) {
				$context->message = "Authentification r&eacute;ussie.";

				$context->set_sessionInformation("identifiant", $identifiant);

				$context->redirect("index.php");
				return context::SUCCESS;
			}
			else {
				$context->message = "Mauvais identifiant ou mot de passe. &Ecirc;tes-vous bien enregistr&eacute; ?";
				$context->identifiant = $identifiant;
				return context::ERROR;
			}			
		}
		// Si le formulaire de connexion n'a pas été rempli
		else {
			$context->message = "Vous devez &ecirc;tre connect&eacute; avant d'acc&eacute;der &agrave; cette page !";
			return context::SUCCESS;
		}
	}
}
?>