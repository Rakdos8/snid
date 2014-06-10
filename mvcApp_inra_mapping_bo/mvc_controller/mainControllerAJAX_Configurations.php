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
class mainControllerAJAX_Configurations {
    /*
	** Action d'ajout dune configuration
	** entrée: request: équivalent à $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function ajouterConfiguration($request, $context) {
		$lnk_BDD = mainControllerAJAX::get_connexionBDD();
		
		// Si l'instance de la classe BDD n'a pas pu être récupérée
		if ($lnk_BDD == null) {
			$retourJSON['etat'] = "erreur";
			$retourJSON['erreur'] = "Impossible d'ex&eacute;cuter le script de configuration !";
		}
		else {
			$identifiant = $context->get_sessionInformation("identifiant");
			
			// Si l'utilisateur est bien connecté
			if (isset($identifiant)) {
				// Récupération des informations issues du formulaire
				if (isset($request['is_active']))				$is_active = $request['is_active'];
				else											$is_active = "";
				if (isset($request['liens_visible']))			$liens_visible = $request['liens_visible'];
				else											$liens_visible = "";
				if (isset($request['mode_navigation']))			$mode_navigation = $request['mode_navigation'];
				else											$mode_navigation = "";
				if (isset($request['couleur_noeud_interne']))	$couleur_noeud_interne = $request['couleur_noeud_interne'];
				else											$couleur_noeud_interne = "";
				if (isset($request['couleur_noeud_externe']))	$couleur_noeud_externe = $request['couleur_noeud_externe'];
				else											$couleur_noeud_externe = "";
				
				// Si les informations données ne sont pas vides et comportent bien le strict minimum
				if (strlen($is_active) >= 4 && strlen($liens_visible) >= 4 && strlen($mode_navigation) == 6 &&
					strlen($couleur_noeud_interne) > 4 && strlen($couleur_noeud_externe) > 4) {
					// Si on fait de cette configuration la configuration active pour l'application, on doit désactiver les autres
					if ($is_active == "true") {
						configurationTable::set_desactiverToutesLesConfigurations();
					}

					// Sécurisation des données à transmettre à la BDD
					$is_active = $lnk_BDD->encode($is_active);
					$liens_visible = $lnk_BDD->encode($liens_visible);
					$mode_navigation = $lnk_BDD->encode($mode_navigation);
					$couleur_noeud_interne = $lnk_BDD->encode($couleur_noeud_interne);
					$couleur_noeud_externe = $lnk_BDD->encode($couleur_noeud_externe);

					// Sauvegarde des informations à transmettre dans un tableau (Array)
					$informationsConfiguration = array(
													"is_active" => $is_active,
													"liens_visible" => $liens_visible,
													"mode_navigation" => $mode_navigation,
													"couleur_noeud_interne" => $couleur_noeud_interne,
													"couleur_noeud_externe" => $couleur_noeud_externe
													);
					// Instanciation du nouvel configuration
					$lnk_BDD->infosDebug(__FILE__, __LINE__);
					$nouvelleConfiguration = new fo_configurations($informationsConfiguration);
					// Sauvegarde de ce nouvel configuration dans la BDD
					$nombreDeLigneAjoutee = $nouvelleConfiguration->save();

					if ($nombreDeLigneAjoutee > 0) {
						$retourJSON['etat'] = "ok";
						$retourJSON['retour'] = $nombreDeLigneAjoutee;
						$retourJSON['fichier'] = "mvc_view/ajouterConfiguration.php";
					}
					else {
						$retourJSON['etat'] = "erreur";
						$retourJSON['erreur'] = "Impossible d'ajouter la nouvelle configuration !";
					}
				}
				else {
					$retourJSON['etat'] = "erreur";
					$retourJSON['erreur'] = "Les champs ne doivent pas &ecirc;tre vides !";
				}
			}
			// Page par défaut quand on est connecté
			else {
				$retourJSON['etat'] = "erreur";
				$retourJSON['erreur'] = "Vous devez &ecirc;tre connect&eacute; avant d'acc&eacute;der &agrave; cette page !";
			}
		}
		return $retourJSON;
	}

    /*
	** Action de gestion des configurations
	** entrée: request: équivalent à $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function gererConfigurations($request, $context) {
		$lnk_BDD = mainControllerAJAX::get_connexionBDD();
		
		// Si l'instance de la classe BDD n'a pas pu être récupérée
		if ($lnk_BDD == null) {
			$retourJSON['etat'] = "erreur";
			$retourJSON['erreur'] = "Impossible d'ex&eacute;cuter le script de configuration !";
		}
		else {
			$identifiant = $context->get_sessionInformation("identifiant");
			
			// Si l'utilisateur est bien connecté
			if (isset($identifiant)) {
				// Récupération des informations issues du formulaire
				if (isset($request['id_configuration']))		$id_configuration = $request['id_configuration'];
				else											$id_configuration = "";
				if (isset($request['is_active']))				$is_active = $request['is_active'];
				else											$is_active = "";
				if (isset($request['liens_visible']))			$liens_visible = $request['liens_visible'];
				else											$liens_visible = "";
				if (isset($request['mode_navigation']))			$mode_navigation = $request['mode_navigation'];
				else											$mode_navigation = "";
				if (isset($request['couleur_noeud_interne']))	$couleur_noeud_interne = $request['couleur_noeud_interne'];
				else											$couleur_noeud_interne = "";
				if (isset($request['couleur_noeud_externe']))	$couleur_noeud_externe = $request['couleur_noeud_externe'];
				else											$couleur_noeud_externe = "";
				
				// Si les informations données ne sont pas vides et comportent bien le strict minimum
				if (is_numeric($id_configuration) && strlen($is_active) >= 4 && strlen($liens_visible) >= 4 &&
					strlen($mode_navigation) == 6 && strlen($couleur_noeud_interne) > 4 && strlen($couleur_noeud_externe) > 4) {
					// Si on fait de cette configuration la configuration active pour l'application, on doit désactiver les autres
					if ($is_active == "true") {
						configurationTable::set_desactiverToutesLesConfigurations();
					}

					// Sécurisation des données à transmettre à la BDD
					$id_configuration = $lnk_BDD->encode($id_configuration);
					$is_active = $lnk_BDD->encode($is_active);
					$liens_visible = $lnk_BDD->encode($liens_visible);
					$mode_navigation = $lnk_BDD->encode($mode_navigation);
					$couleur_noeud_interne = $lnk_BDD->encode($couleur_noeud_interne);
					$couleur_noeud_externe = $lnk_BDD->encode($couleur_noeud_externe);

					// Sauvegarde des informations à transmettre dans un tableau (Array)
					$informationsConfiguration = array(
													"id" => "id_configuration",
													"id_configuration" => $id_configuration,
													"is_active" => $is_active,
													"liens_visible" => $liens_visible,
													"mode_navigation" => $mode_navigation,
													"couleur_noeud_interne" => $couleur_noeud_interne,
													"couleur_noeud_externe" => $couleur_noeud_externe
													);
					// Instanciation du nouvel configuration
					$MaJconfiguration = new fo_configurations($informationsConfiguration);
					// Sauvegarde de ce nouvel configuration dans la BDD
					$nombreDeLigneMaJ = $MaJconfiguration->save();

					if ($nombreDeLigneMaJ > 0) {
						$retourJSON['etat'] = "ok";
						$retourJSON['retour'] = $nombreDeLigneMaJ;
						$retourJSON['fichier'] = "mvc_view/gererConfigurations.php";
					}
					else if ($nombreDeLigneMaJ == 0) {
						$retourJSON['etat'] = "erreur";
						$retourJSON['erreur'] = "Rien &agrave; mettre &agrave; jour !";
					}
					else {
						$retourJSON['etat'] = "erreur";
						$retourJSON['erreur'] = "Impossible de mettre &agrave; jour la configuration !".$nombreDeLigneMaJ;
					}
				}
				else {
					$retourJSON['etat'] = "erreur";
					$retourJSON['erreur'] = "Le champ ne doit pas &ecirc;tre vide !".print_r($_POST, true);
				}
			}
		}
		return $retourJSON;
	}

    /*
	** Action de suppression d'une configuration
	** entrée: request: équivalent à $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function supprimerConfiguration($request, $context) {
		$lnk_BDD = mainControllerAJAX::get_connexionBDD();
		
		// Si l'instance de la classe BDD n'a pas pu être récupérée
		if ($lnk_BDD == null) {
			$retourJSON['etat'] = "erreur";
			$retourJSON['erreur'] = "Impossible d'ex&eacute;cuter le script de configuration !";
		}
		else {
			$identifiant = $context->get_sessionInformation("identifiant");
			
			// Si l'utilisateur est bien connecté
			if (isset($identifiant)) {
				// Récupération des informations issues du formulaire
				if (isset($request['id_configuration']))	$id_configuration = $request['id_configuration'];
				else										$id_configuration = "";
				
				// Si les informations données ne sont pas vides
				if (is_numeric($id_configuration)) {
					// Suppression de l'configuration indiqué
					$lnk_BDD->infosDebug(__FILE__, __LINE__);
					$nombreLigneSupprimee = $lnk_BDD->query_Delete("fo_configurations", "id_configuration = ".$id_configuration);

					if ($nombreLigneSupprimee > 0) {
						$retourJSON['etat'] = "ok";
						$retourJSON['retour'] = $nombreLigneSupprimee;
						$retourJSON['fichier'] = "mvc_view/gererConfigurations.php";
					}
					else if ($nombreLigneSupprimee == 0) {
						$retourJSON['etat'] = "erreur";
						$retourJSON['erreur'] = "Rien &agrave; supprimer !";
					}
					else {
						$retourJSON['etat'] = "erreur";
						$retourJSON['erreur'] = "Une demande de suppression a &eacute;t&eacute; demand&eacute;e mais aucune valeur n'a &eacute;t&eacute; supprim&eacute; !";
					}
				}
				else {
					$retourJSON['etat'] = "erreur";
					$retourJSON['erreur'] = "Impossible de r&eacute;cup&eacute;rer la configuration &agrave; supprimer !";
				}
			}
		}
		return $retourJSON;
	}
}
?>