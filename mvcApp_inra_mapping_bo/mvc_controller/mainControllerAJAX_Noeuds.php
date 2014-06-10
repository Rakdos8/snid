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
class mainControllerAJAX_Noeuds {
    /*
	** Action d'ajout d'un noeud
	** entrée: request: équivalent à $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function ajouterNoeud($request, $context) {
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
				if (isset($request['idCategorie']))		$idCategorie = $request['idCategorie'];
				else									$idCategorie = "";
				if (isset($request['urlNoeud']))		$urlNoeud = $request['urlNoeud'];
				else									$urlNoeud = "";
				
				// Si les informations données ne sont pas vides
				if (strlen($nomEntier) > 0 && strlen($nomPartiel) > 0 && is_numeric($idCategorie) && strlen($urlNoeud) > 11) {
					// Première étape: vérifier que cet identifiant n'existe pas déjà
					if (!noeudTable::get_noeudExisteDeja($nomEntier, $nomPartiel)) {
						// Sécurisation des données à transmettre à la BDD
						$nomEntier = $lnk_BDD->encode($nomEntier);
						$nomPartiel = $lnk_BDD->encode($nomPartiel, false);
						$idCategorie = $lnk_BDD->encode($idCategorie);
						$urlNoeud = $lnk_BDD->encode($urlNoeud);

						// Sauvegarde des informations à transmettre dans un tableau (Array)
						$informationsNoeud = array(
							"id_categorie" => $idCategorie,
							"nom_entier" => $nomEntier,
							"nom_partiel" => $nomPartiel,
							"url_redirection" => $urlNoeud
						);
						// Instanciation du nouveau noeud
						$nouveauNoeud = new fo_noeuds($informationsNoeud);
						// Sauvegarde de ce nouveau noeud dans la BDD
						$nombreDeLigneAjoutee = $nouveauNoeud->save();

						if ($nombreDeLigneAjoutee > 0) {
							$retourJSON['etat'] = "ok";
							$retourJSON['retour'] = $nombreDeLigneAjoutee;
							$retourJSON['fichier'] = "mvc_view/gererNoeuds.php";
						}
						else {
							$retourJSON['etat'] = "erreur";
							$retourJSON['erreur'] = "Impossible d'ajouter la nouvelle cat&eacute;gorie !";
						}
					}
					else {
						$retourJSON['etat'] = "erreur";
						$retourJSON['erreur'] = "Ce nom de n&oelig;ud est d&eacute;j&agrave; utilis&eacute; !";
					}
				}
				else {
					$retourJSON['etat'] = "erreur";
					$retourJSON['erreur'] = "Les champs ne doivent pas &ecirc;tre vides et le champ URL doit contenir l'adresse du site internet !";
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
	** Action de gestion des noeuds
	** entrée: request: équivalent à $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function gererNoeuds($request, $context) {
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
				if (isset($request['idNoeud']))			$idNoeud = $request['idNoeud'];
				else									$idNoeud = "";
				if (isset($request['idCategorie']))		$idCategorie = $request['idCategorie'];
				else									$idCategorie = "";
				if (isset($request['nomEntier']))		$nomEntier = $request['nomEntier'];
				else									$nomEntier = "";
				if (isset($request['nomPartiel']))		$nomPartiel = $request['nomPartiel'];
				else									$nomPartiel = "";
				if (isset($request['urlRedirection']))	$urlNoeud = $request['urlRedirection'];
				else									$urlNoeud = "";
				
				// Si les informations données ne sont pas vides
				if (is_numeric($idNoeud) && is_numeric($idCategorie) && strlen($nomEntier) > 0 && strlen($nomPartiel) > 0 && strlen($urlNoeud) > 11) {
					// Sécurisation des données à transmettre à la BDD
					$nomEntier = $lnk_BDD->encode($nomEntier);
					$nomPartiel = $lnk_BDD->encode($nomPartiel, false);
					$urlNoeud = $lnk_BDD->encode($urlNoeud);

					// Sauvegarde des informations à transmettre dans un tableau (Array)
					$informationsNoeud = array(
						"id" => "id_noeud",
						"id_noeud" => $idNoeud, 
						"id_categorie" => $idCategorie, 
						"nom_entier" => $nomEntier,
						"nom_partiel" => $nomPartiel,
						"url_redirection" => $urlNoeud
					);
					// Instanciation du nouveau noeud
					$lnk_BDD->infosDebug(__FILE__, __LINE__);
					$MaJNoeud = new fo_noeuds($informationsNoeud);
					// Sauvegarde de ce nouveau noeud dans la BDD
					$nombreDeLigneMaJ = $MaJNoeud->save();

					if ($nombreDeLigneMaJ > 0) {
						$retourJSON['etat'] = "ok";
						$retourJSON['retour'] = $nombreDeLigneMaJ;
						$retourJSON['fichier'] = "mvc_view/gererNoeuds.php";
					}
					else if ($nombreDeLigneMaJ == 0) {
						$retourJSON['etat'] = "erreur";
						$retourJSON['erreur'] = "Rien &agrave; mettre &agrave; jour !";
					}
					else {
						$retourJSON['etat'] = "erreur";
						$retourJSON['erreur'] = "Impossible de mettre &agrave; jour le n&oelig;ud !";
					}
				}
				else {
					$retourJSON['etat'] = "erreur";
					$retourJSON['erreur'] = "Les champs ne doivent pas &ecirc;tre vides et le champ URL doit contenir l'adresse du site internet !";
				}
			}
		}
		return $retourJSON;
	}

    /*
	** Action de suppression d'un noeud
	** entrée: request: équivalent à $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function supprimerNoeud($request, $context) {
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
				
				// Si les informations données ne sont pas vides
				if (is_numeric($idNoeud)) {
					// Suppression du noeud indiqué
					$lnk_BDD->infosDebug(__FILE__, __LINE__);
					$nombreLigneSupprimee = $lnk_BDD->query_Delete("fo_noeuds", "id_noeud = ".$idNoeud);

					if ($nombreLigneSupprimee > 0) {
						$retourJSON['etat'] = "ok";
						$retourJSON['retour'] = $nombreLigneSupprimee;
						$retourJSON['fichier'] = "mvc_view/gererNoeuds.php";
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
					$retourJSON['erreur'] = "Impossible de r&eacute;cup&eacute;rer la n&oelig;ud &agrave; supprimer !";
				}
			}
		}
		return $retourJSON;
	}
}
?>