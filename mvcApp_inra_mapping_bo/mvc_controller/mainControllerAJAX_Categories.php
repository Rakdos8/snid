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
class mainControllerAJAX_Categories {
    /*
	** Action d'ajout d'une catégorie
	** entrée: request: équivalent à $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function ajouterCategorie($request, $context) {
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
				if (isset($request['nomEntier']))		$nomEntier = $request['nomEntier'];
				else									$nomEntier = "";
				if (isset($request['nomPartiel']))		$nomPartiel = $request['nomPartiel'];
				else									$nomPartiel = "";
				if (isset($request['couleurNeutre']))	$couleurNeutre = $request['couleurNeutre'];
				else									$couleurNeutre = "";
				if (isset($request['couleurSelect']))	$couleurSelect = $request['couleurSelect'];
				else									$couleurSelect = "";
				
				// Si les informations données ne sont pas vides
				if (strlen($nomEntier) > 0 && strlen($nomPartiel) > 0 && strlen($couleurNeutre) >= 4 && strlen($couleurSelect) >= 4) {
					// Première étape: vérifier que cet identifiant n'existe pas déjà
					if (!categorieTable::get_categorieExisteDeja($nomEntier, $nomPartiel)) {
						// Sécurisation des données à transmettre à la BDD
						$nomEntier = $lnk_BDD->encode($nomEntier);
						$nomPartiel = $lnk_BDD->encode($nomPartiel, false);
						$couleurNeutre = $lnk_BDD->encode($couleurNeutre);
						$couleurSelect = $lnk_BDD->encode($couleurSelect);

						// Sauvegarde des informations à transmettre dans un tableau (Array)
						$informationsCategorie = array(
							"nom_entier" => $nomEntier,
							"nom_partiel" => $nomPartiel,
							"couleur_liaisons" => $couleurNeutre,
							"couleur_liaisons_select" => $couleurSelect
						);
						// Instanciation de la nouvelle catégorie
						$nouvelleCategorie = new fo_categories($informationsCategorie);
						// Sauvegarde de cette nouvelle catégorie dans la BDD
						$nombreDeLigneAjoutee = $nouvelleCategorie->save();

						if ($nombreDeLigneAjoutee > 0) {
							$retourJSON['etat'] = "ok";
							$retourJSON['retour'] = $nombreDeLigneAjoutee;
							$retourJSON['fichier'] = "mvc_view/gererCategories.php";
						}
						else {
							$retourJSON['etat'] = "erreur";
							$retourJSON['erreur'] = "Impossible d'ajouter la nouvelle cat&eacute;gorie !";
						}
					}
					else {
						$retourJSON['etat'] = "erreur";
						$retourJSON['erreur'] = "Ce nom de cat&eacute;gorie est d&eacute;j&agrave; utilis&eacute; !";
					}
				}
				else {
					$retourJSON['etat'] = "erreur";
					$retourJSON['erreur'] = "Les champs ne doivent pas &ecirc;tre vides et les champs des couleurs doivent &ecirc;tre compos&eacute; d'au moins 4 caract&egrave;res !";
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
	** Action de gestion des catégories
	** entrée: request: équivalent à $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function gererCategories($request, $context) {
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
				if (isset($request['idCategorie']))		$idCategorie = $request['idCategorie'];
				else									$idCategorie = "";
				if (isset($request['nomEntier']))		$nomEntier = $request['nomEntier'];
				else									$nomEntier = "";
				if (isset($request['nomPartiel']))		$nomPartiel = $request['nomPartiel'];
				else									$nomPartiel = "";
				if (isset($request['couleurNeutre']))	$couleurNeutre = $request['couleurNeutre'];
				else									$couleurNeutre = "";
				if (isset($request['couleurSelect']))	$couleurSelect = $request['couleurSelect'];
				else									$couleurSelect = "";
				
				// Si les informations données ne sont pas vides
				if (strlen($nomEntier) > 0 && strlen($nomPartiel) > 0 && strlen($couleurNeutre) >= 4 && strlen($couleurSelect) >= 4) {
					// Sécurisation des données à transmettre à la BDD
					$nomEntier = $lnk_BDD->encode($nomEntier);
					$nomPartiel = $lnk_BDD->encode($nomPartiel, false);
					$couleurNeutre = $lnk_BDD->encode($couleurNeutre);
					$couleurSelect = $lnk_BDD->encode($couleurSelect);

					// Sauvegarde des informations à transmettre dans un tableau (Array)
					$informationsCategorie = array(
						"id" => "id_categorie",
						"id_categorie" => $idCategorie, 
						"nom_entier" => $nomEntier,
						"nom_partiel" => $nomPartiel,
						"couleur_liaisons" => $couleurNeutre,
						"couleur_liaisons_select" => $couleurSelect
					);
					// Instanciation de la nouvelle catégorie
					$MaJCategorie = new fo_categories($informationsCategorie);
					// Sauvegarde de cette nouvelle catégorie dans la BDD
					$nombreDeLigneMaJ = $MaJCategorie->save();

					if ($nombreDeLigneMaJ > 0) {
						$retourJSON['etat'] = "ok";
						$retourJSON['retour'] = $nombreDeLigneMaJ;
						$retourJSON['fichier'] = "mvc_view/gererCategories.php";
					}
					else if ($nombreDeLigneMaJ == 0) {
						$retourJSON['etat'] = "erreur";
						$retourJSON['erreur'] = "Rien &agrave; mettre &agrave; jour !";
					}
					else {
						$retourJSON['etat'] = "erreur";
						$retourJSON['erreur'] = "Impossible de mettre &agrave; jour la cat&eacute;gorie !";
					}
				}
				else {
					$retourJSON['etat'] = "erreur";
					$retourJSON['erreur'] = "Les champs ne doivent pas &ecirc;tre vides et les champs des couleurs doivent &ecirc;tre compos&eacute; d'au moins 4 caract&egrave;res !";
				}
			}
		}
		return $retourJSON;
	}

    /*
	** Action de suppression d'une catégorie
	** entrée: request: équivalent à $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function supprimerCategorie($request, $context) {
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
				if (isset($request['id_categorie']))	$idCategorie = $request['id_categorie'];
				else									$idCategorie = "";
				
				// Si les informations données ne sont pas vides
				if (is_numeric($idCategorie)) {
					// Suppression de la catégorie indiquée
					$lnk_BDD->infosDebug(__FILE__, __LINE__);
					$nombreLigneSupprimee = $lnk_BDD->query_Delete("fo_categories", "id_categorie = ".$idCategorie);

					if ($nombreLigneSupprimee > 0) {
						$retourJSON['etat'] = "ok";
						$retourJSON['retour'] = $nombreLigneSupprimee;
						$retourJSON['fichier'] = "mvc_view/gererCategories.php";
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
					$retourJSON['erreur'] = "Impossible de r&eacute;cup&eacute;rer la cat&eacute;gorie &agrave; supprimer !";
				}
			}
		}
		return $retourJSON;
	}
}
?>