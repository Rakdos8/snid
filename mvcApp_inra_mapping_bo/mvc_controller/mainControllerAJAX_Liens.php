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
class mainControllerAJAX_Liens {
    /*
	** Action d'ajout d'un lien
	** entrée: request: équivalent à $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function ajouterLien($request, $context) {
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
				if (isset($request['noeud_ajout_1']))	$idNoeud1 = $request['noeud_ajout_1'];
				else								$idNoeud1 = "";
				if (isset($request['noeud_ajout_2']))	$idNoeud2 = $request['noeud_ajout_2'];
				else								$idNoeud2 = "";
				
				// Si les informations données sont exploitables
				if ((is_numeric($idNoeud1) && $idNoeud1 > 0) &&
					 is_numeric($idNoeud2) && $idNoeud2 > 0) {
					if ($idNoeud1 != $idNoeud2) {
						if (!lienTable::get_lienExisteDeja($idNoeud1, $idNoeud2)) {
							// Sauvegarde des informations à transmettre dans un tableau (Array)
							$informationsLien = array(
													  "id_noeud_1" => $idNoeud1,
													  "id_noeud_2" => $idNoeud2
													 );
							// Instanciation du nouvel lien
							$nouveauLien = new fo_liens($informationsLien);
							// Sauvegarde de ce nouveau lien dans la BDD
							$nombreDeLigneAjoutee = $nouveauLien->save();

							if ($nombreDeLigneAjoutee > 0) {
								$retourJSON['etat'] = "ok";
								$retourJSON['retour'] = $nombreDeLigneAjoutee;
								$retourJSON['fichier'] = "mvc_view/gererLiens.php";
							}
							else {
								$retourJSON['etat'] = "erreur";
								$retourJSON['erreur'] = "Impossible d'ajouter la nouveau lien !";
							}
						}
						else {
							$retourJSON['etat'] = "erreur";
							$retourJSON['erreur'] = "Cette liaison existe d&eacute;j&agrave; !";
						}
					}
					else {
						$retourJSON['etat'] = "erreur";
						$retourJSON['erreur'] = "Un n&oelig;ud ne peut pas &ecirc;tre li&eacute; &agrave; lui m&ecirc;me !";
					}
				}
				else {
					$retourJSON['etat'] = "erreur";
					$retourJSON['erreur'] = "Impossible de r&eacute;cup&eacute;rer les identifiants des n&oelig;uds s&eacute;lection&eacute;s !";
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
	** Action de gestion des liens
	** entrée: request: équivalent à $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function gererLiens($request, $context) {
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
				if (isset($request['id_lien']))						$idLien = $request['id_lien'];
				else												$idLien = "";
				if (isset($request['noeud_gerer_'.$idLien.'_1']))	$idNoeud1 = $request['noeud_gerer_'.$idLien.'_1'];
				else												$idNoeud1 = "";
				if (isset($request['noeud_gerer_'.$idLien.'_2']))	$idNoeud2 = $request['noeud_gerer_'.$idLien.'_2'];
				else												$idNoeud2 = "";
				
				// Si les informations données sont exploitables
				if (is_numeric($idLien) && $idLien > 0 &&
					is_numeric($idNoeud1) && $idNoeud1 > 0 &&
					is_numeric($idNoeud2) && $idNoeud2 > 0) {
					if ($idNoeud1 != $idNoeud2) {
						if (!lienTable::get_lienExisteDeja($idNoeud1, $idNoeud2)) {
							// Sauvegarde des informations à transmettre dans un tableau (Array)
							$informationsLien = array(
													  "id" => "id_lien",
													  "id_lien" => $idLien, 
													  "id_noeud_1" => $idNoeud1,
													  "id_noeud_2" => $idNoeud2
													 );
							// Instanciation du nouvel lien
							$MaJLien = new fo_liens($informationsLien);
							// Sauvegarde de ce nouveau lien dans la BDD
							$nombreDeLigneMaJ = $MaJLien->save();

							if ($nombreDeLigneMaJ > 0) {
								$retourJSON['etat'] = "ok";
								$retourJSON['retour'] = $nombreDeLigneMaJ;
								$retourJSON['fichier'] = "mvc_view/gererLiens.php";
							}
							else if ($nombreDeLigneMaJ == 0) {
								$retourJSON['etat'] = "erreur";
								$retourJSON['erreur'] = "Rien &agrave; mettre &agrave; jour !";
							}
							else {
								$retourJSON['etat'] = "erreur";
								$retourJSON['erreur'] = "Impossible de mettre &agrave; jour le lien !";
							}
						}
						else {
							$retourJSON['etat'] = "erreur";
							$retourJSON['erreur'] = "Cette liaison existe d&eacute;j&agrave; !";
						}
					}
					else {
						$retourJSON['etat'] = "erreur";
						$retourJSON['erreur'] = "Un n&oelig;ud ne peut pas &ecirc;tre li&eacute; &agrave; lui m&ecirc;me !";
					}
				}
				else {
					$retourJSON['etat'] = "erreur";
					$retourJSON['erreur'] = "Impossible de r&eacute;cup&eacute;rer les identifiants des n&oelig;uds s&eacute;lection&eacute;s !";
				}
			}
		}
		return $retourJSON;
	}

    /*
	** Action de suppression d'un lien
	** entrée: request: équivalent à $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function supprimerLien($request, $context) {
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
				if (isset($request['id_lien']))	$id_lien = $request['id_lien'];
				else							$id_lien = "";
				
				// Si les informations données ne sont pas vides
				if (is_numeric($id_lien) && $id_lien > 0) {
					// Suppression de l'lien indiqué
					$lnk_BDD->infosDebug(__FILE__, __LINE__);
					$nombreLigneSupprimee = $lnk_BDD->query_Delete("fo_liens", "id_lien = ".$id_lien);

					if ($nombreLigneSupprimee > 0) {
						$retourJSON['etat'] = "ok";
						$retourJSON['retour'] = $nombreLigneSupprimee;
						$retourJSON['fichier'] = "mvc_view/gererLiens.php";
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