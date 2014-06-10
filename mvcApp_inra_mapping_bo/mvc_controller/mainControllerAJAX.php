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
class mainControllerAJAX {
    /*
	** Action permettant de récupérer la connexion à la BDD.
	** entrée: request: équivalent à $_REQUEST.
	**         context: lien vers l'instance du contexte.
	** sortie: BDD: objet BDD si réussi, null sinon.
    */
	public static function get_connexionBDD() {
		// Si le nom de l'application n'est pas défini
		if (constant("NOM_APPLICATION") == null) {
			define("NOM_APPLICATION", "mvcApp_inra_mapping_bo", false);
			// On récupère les informations essentielles de l'application
			require_once "config.php";
		}

		// Si le script de configuration n'a pas bien été exécuté
		if (constant("EST_CONFIGURE") == null || constant("EST_CONFIGURE") != true) {
			return null;
		}
		else {
			// On récupère l'instance à la classe BDD
			$lnk_BDD = BDD::get_Instance();
			return $lnk_BDD;
		}
	}
	
    /*
	** Action permettant de gérer les actions de navigation.
	** entrée: action: étant le menu demandé.
	**         context: lien vers l'instance du contexte.
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function gestionDuMenuDeNavigation($action, $context) {
		require_once constant("CHEMIN_APPLICATION")."/mvc_controller/mainControllerAJAX_Navigation.php";

		// Menu pour ajouter un utilisateur
		if ($action == "ajouterUtilisateur") {
			$retourJSON = mainControllerAJAX_Navigation::afficherAjouterUtilisateur($context);
		}
		// Menu pour gérer les utilisateurs
		else if ($action == "gererUtilisateurs") {
			$retourJSON = mainControllerAJAX_Navigation::afficherGererUtilisateurs($context);
		}
		// Menu pour ajouter une configuration d'affichage des graphes
		else if ($action == "ajouterConfiguration") {
			$retourJSON = mainControllerAJAX_Navigation::afficherAjouterConfiguration($context);
		}
		// Menu pour gérer les configuration d'affichage des graphes
		else if ($action == "gererConfigurations") {
			$retourJSON = mainControllerAJAX_Navigation::afficherGererConfigurations($context);
		}
		// Menu pour gérer les catégories
		else if ($action == "gererCategories") {
			$retourJSON = mainControllerAJAX_Navigation::afficherGererCategories($context);
		}
		// Menu pour gérer les noeuds
		else if ($action == "gererNoeuds") {
			$retourJSON = mainControllerAJAX_Navigation::afficherGererNoeuds($context);
		}
		// Menu pour gérer les liens
		else if ($action == "gererLiens") {
			$retourJSON = mainControllerAJAX_Navigation::afficherGererLiens($context);
		}
		// Menu pour gérer les templates
		else if ($action == "gererTemplates") {
			$retourJSON = mainControllerAJAX_Navigation::afficherGererTemplates($context);
		}
		// Menu pour visualiser les erreurs PHP dans le fichier "log_error_php.txt"
		else if ($action == "erreursPHP") {
			$retourJSON = mainControllerAJAX_Navigation::afficherGererErreursPHP($context);
		}
		// Menu pour visualiser les erreurs SQL dans le fichier "log_error_sql.txt"
		else if ($action == "erreursSQL") {
			$retourJSON = mainControllerAJAX_Navigation::afficherGererErreursSQL($context);
		}
		// Sinon la page n'a pas été encore crée (ou n'est pas encore prêt)
		else {
			$retourJSON['etat'] = "erreur";
			$retourJSON['erreur'] = "Ce menu de navigation n'est pas encore pr&ecirc;t !";
		}

		return $retourJSON;
	}
	
    /*
	** Action permettant de gérer les formulaires.
	** entrée: action: étant le menu demandé.
	** 		   request: équivalent à $_REQUEST.
	**         context: lien vers l'instance du contexte.
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function gestionDesFormulaires($action, $request, $context) {
		// Formulaire sur les utilisateurs
		if ($action == "ajouterUtilisateur" || $action == "gererUtilisateurs" || $action == "supprimerUtilisateur") {
			require_once constant("CHEMIN_APPLICATION")."/mvc_controller/mainControllerAJAX_Utilisateurs.php";
			
			// Formulaire pour ajouter un utilisateur
			if ($action == "ajouterUtilisateur") {
				$retourJSON = mainControllerAJAX_Utilisateurs::ajouterUtilisateur($request, $context);
			}
			// Formulaire pour gérer les utilisateurs
			else if ($action == "gererUtilisateurs") {
				$retourJSON = mainControllerAJAX_Utilisateurs::gererUtilisateurs($request, $context);
			}
			// Formulaire pour supprimer un utilisateur
			else if ($action == "supprimerUtilisateur") {
				$retourJSON = mainControllerAJAX_Utilisateurs::supprimerUtilisateur($request, $context);
			}
			// Si rien n'a été "demandé"
			else {
				$retourJSON['etat'] = "erreur";
				$retourJSON['erreur'] = "Erreur d'interpr&eacute;tation du dispatcher AJAX Formulaire utilisateur ! (1)";
			}
		}
		// Formulaire sur les configurations
		else if ($action == "ajouterConfiguration" || $action == "gererConfigurations" || $action == "supprimerConfiguration") {
			require_once constant("CHEMIN_APPLICATION")."/mvc_controller/mainControllerAJAX_Configurations.php";
			
			// Formulaire pour ajouter une configuration
			if ($action == "ajouterConfiguration") {
				$retourJSON = mainControllerAJAX_Configurations::ajouterConfiguration($request, $context);
			}
			// Formulaire pour gérer les configurations
			else if ($action == "gererConfigurations") {
				$retourJSON = mainControllerAJAX_Configurations::gererConfigurations($request, $context);
			}
			// Formulaire pour supprimer un configuration
			else if ($action == "supprimerConfiguration") {
				$retourJSON = mainControllerAJAX_Configurations::supprimerConfiguration($request, $context);
			}
			// Si rien n'a été "demandé"
			else {
				$retourJSON['etat'] = "erreur";
				$retourJSON['erreur'] = "Erreur d'interpr&eacute;tation du dispatcher AJAX Formulaire configuration ! (1)";
			}
		}
		// Formulaire sur les catégories
		else if ($action == "ajouterCategorie" || $action == "gererCategories" || $action == "supprimerCategorie") {
			require_once constant("CHEMIN_APPLICATION")."/mvc_controller/mainControllerAJAX_Categories.php";
			
			// Formulaire pour ajouter une catégorie
			if ($action == "ajouterCategorie") {
				$retourJSON = mainControllerAJAX_Categories::ajouterCategorie($request, $context);
			}
			// Formulaire pour gérer les catégories
			else if ($action == "gererCategories") {
				$retourJSON = mainControllerAJAX_Categories::gererCategories($request, $context);
			}
			// Formulaire pour supprimer une catégorie
			else if ($action == "supprimerCategorie") {
				$retourJSON = mainControllerAJAX_Categories::supprimerCategorie($request, $context);
			}
			// Si rien n'a été "demandé"
			else {
				$retourJSON['etat'] = "erreur";
				$retourJSON['erreur'] = "Erreur d'interpr&eacute;tation du dispatcher AJAX Formulaire cat&eacute;gorie ! (1)";
			}
		}
		// Formulaire sur les noeuds
		else if ($action == "ajouterNoeud" || $action == "gererNoeuds" || $action == "supprimerNoeud") {
			require_once constant("CHEMIN_APPLICATION")."/mvc_controller/mainControllerAJAX_Noeuds.php";
			
			// Formulaire pour ajouter un noeud
			if ($action == "ajouterNoeud") {
				$retourJSON = mainControllerAJAX_Noeuds::ajouterNoeud($request, $context);
			}
			// Formulaire pour gérer les noeuds
			else if ($action == "gererNoeuds") {
				$retourJSON = mainControllerAJAX_Noeuds::gererNoeuds($request, $context);
			}
			// Formulaire pour supprimer un noeud
			else if ($action == "supprimerNoeud") {
				$retourJSON = mainControllerAJAX_Noeuds::supprimerNoeud($request, $context);
			}
			// Si rien n'a été "demandé"
			else {
				$retourJSON['etat'] = "erreur";
				$retourJSON['erreur'] = "Erreur d'interpr&eacute;tation du dispatcher AJAX Formulaire noeud ! (1)";
			}
		}
		// Formulaire sur les liens
		else if ($action == "ajouterLien" || $action == "gererLiens" || $action == "supprimerLien") {
			require_once constant("CHEMIN_APPLICATION")."/mvc_controller/mainControllerAJAX_Liens.php";
			
			// Formulaire pour ajouter un lien
			if ($action == "ajouterLien") {
				$retourJSON = mainControllerAJAX_Liens::ajouterLien($request, $context);
			}
			// Formulaire pour gérer les liens
			else if ($action == "gererLiens") {
				$retourJSON = mainControllerAJAX_Liens::gererLiens($request, $context);
			}
			// Formulaire pour supprimer un lien
			else if ($action == "supprimerLien") {
				$retourJSON = mainControllerAJAX_Liens::supprimerLien($request, $context);
			}
			// Si rien n'a été "demandé"
			else {
				$retourJSON['etat'] = "erreur";
				$retourJSON['erreur'] = "Erreur d'interpr&eacute;tation du dispatcher AJAX Formulaire lien ! (1)";
			}
		}
		// Formulaire sur les templates
		else if ($action == "ajouterTemplate" || $action == "gererTemplates" || $action == "supprimerTemplate") {
			require_once constant("CHEMIN_APPLICATION")."/mvc_controller/mainControllerAJAX_Templates.php";
			
			// Formulaire pour ajouter un template
			if ($action == "ajouterTemplate") {
				$retourJSON = mainControllerAJAX_Templates::ajouterTemplate($request, $context);
			}
			// Formulaire pour gérer les noeuds
			else if ($action == "gererTemplates") {
				$retourJSON = mainControllerAJAX_Templates::gererTemplates($request, $context);
			}
			// Formulaire pour supprimer un template
			else if ($action == "supprimerTemplate") {
				$retourJSON = mainControllerAJAX_Templates::supprimerTemplate($request, $context);
			}
			// Si rien n'a été "demandé"
			else {
				$retourJSON['etat'] = "erreur";
				$retourJSON['erreur'] = "Erreur d'interpr&eacute;tation du dispatcher AJAX Formulaire template ! (1)";
			}
		}
		// Sinon le formulaire n'a pas été encore crée (ou n'est pas encore prêt)
		else {
			$retourJSON['etat'] = "erreur";
			$retourJSON['erreur'] = "Ce formulaire n'est pas encore pr&ecirc;t !";
		}
		
		return $retourJSON;
	}
	
    /*
	** Action permettant de gérer l'affichage des noeuds.
	** entrée: action: étant le menu demandé.
	** 		   request: équivalent à $_REQUEST.
	**         context: lien vers l'instance du contexte.
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function gestionDuRafraichissement($action, $request, $context) {
		require_once constant("CHEMIN_APPLICATION")."/mvc_controller/mainControllerAJAX_Rafraichir.php";

		// Action de rafraichissement des noeuds
		if ($action == "afficherNoeuds") {
			$retourJSON = mainControllerAJAX_Rafraichir::afficherNoeuds($request, $context);
		}
		// Sinon l'action n'a pas été encore crée
		else {
			$retourJSON['etat'] = "erreur";
			$retourJSON['erreur'] = "Cette action n'est pas encore pr&ecirc;te !";
		}

		return $retourJSON;
	}
}
?>