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
class mainControllerAJAX_Templates {
    /*
	** Action d'ajout d'un template
	** entrée: request: équivalent à $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function ajouterTemplate($request, $context) {
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
				if (isset($request['id_noeud']))	$idNoeud = $request['id_noeud'];
				else								$idNoeud = "";	
				if (isset($request['id_template']))	$idTemplate = $request['id_template'];
				else								$idTemplate = "";	
				if (isset($request['contenu']))		$contenuTemplate = $request['contenu'];
				else								$contenuTemplate = "";
				
				// Si les informations données ne sont pas vides
				if (is_numeric($idNoeud) && is_numeric($idTemplate) && strlen($contenuTemplate) > 0) {
					// Sécurisation des données à transmettre à la BDD
					$contenuTemplate = $lnk_BDD->encode($contenuTemplate);

					// Si on veut ajouter un template
					if ($idTemplate < 0) {
						// Sauvegarde des informations à transmettre dans un tableau (Array)
						$informationsTemplate = array(
							"id_noeud" => $idNoeud,
							"contenu" => $contenuTemplate
						);
					}
					// Sinon on veut modifier un template
					else {
						// Sauvegarde des informations à transmettre dans un tableau (Array)
						$informationsTemplate = array(
							"id" => "id_template",
							"id_template" => $idTemplate,
							"id_noeud" => $idNoeud,
							"contenu" => $contenuTemplate
						);
					}
					// Instanciation du nouveau noeud
					$nouveauTemplate = new fo_templates($informationsTemplate);
					// Sauvegarde de ce nouveau noeud dans la BDD
					$lnk_BDD->infosDebug(__FILE__, __LINE__);
					$nombreDeLigneAffectee = $nouveauTemplate->save();

					if ($nombreDeLigneAffectee > 0) {
						$retourJSON['etat'] = "ok";
						$retourJSON['retour'] = $nombreDeLigneAffectee;
						$retourJSON['fichier'] = "mvc_view/gererTemplates.php";
					}
					else {
						$retourJSON['etat'] = "erreur";
						$retourJSON['erreur'] = "Impossible d'ajouter le nouveau template !";
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
	** Action de gestion des templates
	** entrée: request: équivalent à $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function gererTemplates($request, $context) {
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
				$retourJSON['fichier'] = "mvc_view/gererTemplates.php";
			}
		}
		return $retourJSON;
	}

    /*
	** Action de suppression d'un template
	** entrée: request: équivalent à $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function supprimerTemplate($request, $context) {
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
				if (isset($request['id_template']))	$idTemplate = $request['id_template'];
				else								$idTemplate = "";
				
				// Si les informations données ne sont pas vides
				if (is_numeric($idTemplate)) {
					// Suppression du template indiqué
					$lnk_BDD->infosDebug(__FILE__, __LINE__);
					$nombreLigneSupprimee = $lnk_BDD->query_Delete("fo_templates", "id_template = ".$idTemplate);

					if ($nombreLigneSupprimee > 0) {
						$retourJSON['etat'] = "ok";
						$retourJSON['retour'] = $nombreLigneSupprimee;
						$retourJSON['fichier'] = "mvc_view/gererTemplates.php";
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
					$retourJSON['erreur'] = "Impossible de r&eacute;cup&eacute;rer le template &agrave; supprimer !";
				}
			}
		}
		return $retourJSON;
	}
}
?>