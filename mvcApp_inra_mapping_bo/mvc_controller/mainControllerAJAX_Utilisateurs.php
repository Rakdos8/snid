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
class mainControllerAJAX_Utilisateurs {
    /*
	** Action d'ajout d'utilisateur
	** entrée: request: équivalent à $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function ajouterUtilisateur($request, $context) {
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
				if (isset($request['identifiant']))	$identifiant = $request['identifiant'];
				else								$identifiant = "";
				if (isset($request['mdp1']))		$mdp1 = $request['mdp1'];
				else								$mdp1 = "";
				if (isset($request['mdp2']))		$mdp2 = $request['mdp2'];
				else								$mdp2 = "";
				
				// Si les informations données ne sont pas vides
				if (strlen($identifiant) > 0 && strlen($mdp1) > 0 && strlen($mdp2) > 0) {
					// Si le mot de passe a bien été confirmé (ils sont donc identiques)
					if (strcmp($mdp1, $mdp2) == 0) {
						// Première étape: vérifier que cet identifiant n'existe pas déjà
						if (!utilisateurTable::get_identifiantDejaUtilise($identifiant)) {
							// Sécurisation des données à transmettre à la BDD
							$identifiant = $lnk_BDD->encode($identifiant);
							$motDePasse = $lnk_BDD->encode(sha1(sha1(SALT_MOT_DE_PASSE).sha1($mdp1)));

							// Sauvegarde des informations à transmettre dans un tableau (Array)
							$informationsUtilisateur = array("identifiant" => $identifiant, "mot_de_passe" => $motDePasse);
							// Instanciation du nouvel utilisateur
							$lnk_BDD->infosDebug(__FILE__, __LINE__);
							$nouveauUtilisateur = new bo_utilisateurs($informationsUtilisateur);
							// Sauvegarde de ce nouvel utilisateur dans la BDD
							$nombreDeLigneAjoutee = $nouveauUtilisateur->save();

							if ($nombreDeLigneAjoutee > 0) {
								$retourJSON['etat'] = "ok";
								$retourJSON['retour'] = $nombreDeLigneAjoutee;
								$retourJSON['fichier'] = "mvc_view/ajouterUtilisateur.php";
							}
							else {
								$retourJSON['etat'] = "erreur";
								$retourJSON['erreur'] = "Impossible d'ajouter le nouvel utilisateur !";
							}
						}
						else {
							$retourJSON['etat'] = "erreur";
							$retourJSON['erreur'] = "Cet identifiant est d&eacute;j&agrave; utilis&eacute; !";
						}
					}
					else {
						$retourJSON['etat'] = "erreur";
						$retourJSON['erreur'] = "Le mot de passe a mal &eacute;t&eacute; confirm&eacute; !";
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
	** Action de gestion des utilisateurs
	** entrée: request: équivalent à $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function gererUtilisateurs($request, $context) {
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
				if (isset($request['id_utilisateur']))	$id_utilisateur = $request['id_utilisateur'];
				else									$id_utilisateur = "";
				if (isset($request['identifiant']))		$identifiant = $request['identifiant'];
				else									$identifiant = "";
				if (isset($request['mdp']))				$mdp = $request['mdp'];
				else									$mdp = "";
				
				// Si les informations données ne sont pas vides
				if (is_numeric($id_utilisateur) && strlen($identifiant) > 0 && strlen($mdp)) {
					// Sécurisation des données à transmettre à la BDD
					$identifiant = $lnk_BDD->encode($identifiant);
					$motDePasse = $lnk_BDD->encode(sha1(sha1(SALT_MOT_DE_PASSE).sha1($mdp)));

					// Sauvegarde des informations à transmettre dans un tableau (Array)
					$informationsUtilisateur = array(
													"id" => "id_utilisateur",
													"id_utilisateur" => $id_utilisateur,
													"identifiant" => $identifiant,
													"mot_de_passe" => $motDePasse
													);
					// Instanciation du nouvel utilisateur
					$MaJUtilisateur = new bo_utilisateurs($informationsUtilisateur);
					// Sauvegarde de ce nouvel utilisateur dans la BDD
					$nombreDeLigneMaJ = $MaJUtilisateur->save();

					if ($nombreDeLigneMaJ > 0) {
						$retourJSON['etat'] = "ok";
						$retourJSON['retour'] = $nombreDeLigneMaJ;
						$retourJSON['fichier'] = "mvc_view/gererUtilisateurs.php";
					}
					else if ($nombreDeLigneMaJ == 0) {
						$retourJSON['etat'] = "erreur";
						$retourJSON['erreur'] = "Rien &agrave; mettre &agrave; jour !";
					}
					else {
						$retourJSON['etat'] = "erreur";
						$retourJSON['erreur'] = "Impossible de mettre &agrave; jour l'utilisateur !";
					}
				}
				else {
					$retourJSON['etat'] = "erreur";
					$retourJSON['erreur'] = "Le champ ne doit pas &ecirc;tre vide !";
				}
			}
		}
		return $retourJSON;
	}

    /*
	** Action de suppression d'un utilisateur
	** entrée: request: équivalent à $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function supprimerUtilisateur($request, $context) {
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
				if (isset($request['identifiant']))	$id_utilisateur = $request['identifiant'];
				else								$id_utilisateur = "";
				
				// Si les informations données ne sont pas vides
				if (is_numeric($id_utilisateur) && $id_utilisateur > 10) {
					// Suppression de l'utilisateur indiqué
					$lnk_BDD->infosDebug(__FILE__, __LINE__);
					$nombreLigneSupprimee = $lnk_BDD->query_Delete("bo_utilisateurs", "id_utilisateur = ".$id_utilisateur);

					if ($nombreLigneSupprimee > 0) {
						$retourJSON['etat'] = "ok";
						$retourJSON['retour'] = $nombreLigneSupprimee;
						$retourJSON['fichier'] = "mvc_view/gererUtilisateurs.php";
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
					$retourJSON['erreur'] = "Impossible de r&eacute;cup&eacute;rer l'identifiant &agrave; supprimer !";
				}
			}
		}
		return $retourJSON;
	}
}
?>