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
class mainControllerAJAX_Navigation {
	private static $retourJSON;

    /*
	** Vérifie que la connexion à la BDD est opérationnelle et que l'utilisateur est connecté
	** entrée: context: lien vers l'instance du contexte.
	** sortie: boolean: true si tout est bon, false sinon.
    */
	private static function clientConnecteEtPret($context) {
		$lnk_BDD = mainControllerAJAX::get_connexionBDD($context);
		
		// Si l'instance de la classe BDD n'a pas pu être récupérée
		if ($lnk_BDD != null) {
			$identifiant = $context->get_sessionInformation("identifiant");

			// Si l'utilisateur est bien connecté
			if (isset($identifiant)) {
				return true;
			}
			else {
				self::$retourJSON['etat'] = "erreur";
				self::$retourJSON['erreur'] = "Vous devez &ecirc;tre connect&eacute; avant d'acc&eacute;der &agrave; cette page !";
			}
			
		}
		else {
			self::$retourJSON['etat'] = "erreur";
			self::$retourJSON['erreur'] = "Impossible d'ex&eacute;cuter le script de configuration !";
		}

		return false;
	}

    /*
	** Action d'affichage de la vue d'ajout des utilisateurs
	** entrée: context: lien vers l'instance du contexte.
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function afficherAjouterUtilisateur($context) {
		if (mainControllerAJAX_Navigation::clientConnecteEtPret($context)) {
			$retourJSON['etat'] = "ok";
			$retourJSON['fichier'] = "mvc_view/ajouterUtilisateur.php";
			return $retourJSON;
		}
		return self::$retourJSON;
	}

    /*
	** Action d'affichage de la vue de gestion des utilisateurs
	** entrée: context: lien vers l'instance du contexte.
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function afficherGererUtilisateurs($context) {
		if (mainControllerAJAX_Navigation::clientConnecteEtPret($context)) {
			$retourJSON['etat'] = "ok";
			$retourJSON['fichier'] = "mvc_view/gererUtilisateurs.php";
			return $retourJSON;
		}
		return self::$retourJSON;
	}

    /*
	** Action d'affichage de la vue d'ajout des configurations
	** entrée: context: lien vers l'instance du contexte.
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function afficherAjouterConfiguration($context) {
		if (mainControllerAJAX_Navigation::clientConnecteEtPret($context)) {
			$retourJSON['etat'] = "ok";
			$retourJSON['fichier'] = "mvc_view/ajouterConfiguration.php";
			return $retourJSON;
		}
		return self::$retourJSON;
	}

    /*
	** Action d'affichage de la vue de gestion des configuration
	** entrée: context: lien vers l'instance du contexte.
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function afficherGererConfigurations($context) {
		if (mainControllerAJAX_Navigation::clientConnecteEtPret($context)) {
			$retourJSON['etat'] = "ok";
			$retourJSON['fichier'] = "mvc_view/gererConfigurations.php";
			return $retourJSON;
		}
		return self::$retourJSON;
	}

    /*
	** Action d'affichage de la vue de gestion des catégories
	** entrée: context: lien vers l'instance du contexte.
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function afficherGererCategories($context) {
		if (mainControllerAJAX_Navigation::clientConnecteEtPret($context)) {
			$retourJSON['etat'] = "ok";
			$retourJSON['fichier'] = "mvc_view/gererCategories.php";
			return $retourJSON;
		}
		return self::$retourJSON;
	}

    /*
	** Action d'affichage de la vue de gestion des noeuds
	** entrée: context: lien vers l'instance du contexte.
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function afficherGererNoeuds($context) {
		if (mainControllerAJAX_Navigation::clientConnecteEtPret($context)) {
			$retourJSON['etat'] = "ok";
			$retourJSON['fichier'] = "mvc_view/gererNoeuds.php";
			return $retourJSON;
		}
		return self::$retourJSON;
	}

    /*
	** Action d'affichage de la vue de gestion des liens
	** entrée: context: lien vers l'instance du contexte.
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function afficherGererLiens($context) {
		if (mainControllerAJAX_Navigation::clientConnecteEtPret($context)) {
			$retourJSON['etat'] = "ok";
			$retourJSON['fichier'] = "mvc_view/gererLiens.php";
			return $retourJSON;
		}
		return self::$retourJSON;
	}

    /*
	** Action d'affichage de la vue de gestion des templates
	** entrée: context: lien vers l'instance du contexte.
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function afficherGererTemplates($context) {
		if (mainControllerAJAX_Navigation::clientConnecteEtPret($context)) {
			$retourJSON['etat'] = "ok";
			$retourJSON['fichier'] = "mvc_view/gererTemplates.php";
			return $retourJSON;
		}
		return self::$retourJSON;
	}

    /*
	** Action d'affichage de la vue de gestion des erreurs PHP
	** entrée: context: lien vers l'instance du contexte.
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function afficherGererErreursPHP($context) {
		if (mainControllerAJAX_Navigation::clientConnecteEtPret($context)) {
			$retourJSON['etat'] = "ok";
			$retourJSON['fichier'] = "mvc_view/erreursPHP.php";
			return $retourJSON;
		}
		return self::$retourJSON;
	}

    /*
	** Action d'affichage de la vue de gestion des erreurs SQL
	** entrée: context: lien vers l'instance du contexte.
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function afficherGererErreursSQL($context) {
		if (mainControllerAJAX_Navigation::clientConnecteEtPret($context)) {
			$retourJSON['etat'] = "ok";
			$retourJSON['fichier'] = "mvc_view/erreursSQL.php";
			return $retourJSON;
		}
		return self::$retourJSON;
	}
}
?>