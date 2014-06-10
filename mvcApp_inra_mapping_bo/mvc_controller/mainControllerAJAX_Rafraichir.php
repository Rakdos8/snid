<?php
/*
	This file is part of Syst�me de Navigation Interactif et Dynamique (SNID).

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
class mainControllerAJAX_Rafraichir {
    /*
	** Action d'affichage des noeuds
	** entr�e: request: �quivalent � $_REQUEST
	**         context: lien vers l'instance du contexte
	** sortie: array: tableau JSON � encoder avec toutes les informations en son sein.
    */
	public static function afficherNoeuds($request, $context) {
		$lnk_BDD = mainControllerAJAX::get_connexionBDD();
		
		// Si l'instance de la classe BDD n'a pas pu �tre r�cup�r�e
		if ($lnk_BDD == null) {
			$retourJSON['etat'] = "erreur";
			$retourJSON['erreur'] = "Impossible d'ex&eacute;cuter le script de configuration !";
		}
		else {
			$identifiant = $context->get_sessionInformation("identifiant");
			
			// Si l'utilisateur est bien connect�
			if (isset($identifiant)) {
				// R�cup�ration des informations issues du formulaire
				if (isset($request['id_categorie']))	$id_categorie = $request['id_categorie'];
				else									$id_categorie = "";
				
				// Si les informations donn�es ne sont pas vides
				if (is_numeric($id_categorie) && $id_categorie > 0) {
					// R�cup�ration des noeuds appartenant � la cat�gorie demand�e
					$noeuds = noeudTable::get_tousLesNoeudsDeLaCategorie($id_categorie);
					
					$nbNoeud = 0;
					$noeudsDeLaCategorie = array();
					if ($noeuds != null) {
						foreach ($noeuds as $noeud) {
							$noeudsDeLaCategorie[$nbNoeud]['id_noeud'] = $noeud->id_noeud;
							$noeudsDeLaCategorie[$nbNoeud]['nom_entier'] = $noeud->nom_entier_noeud;
							
							$nbNoeud++;
						}
					}
					else {
						$noeudsDeLaCategorie[$nbNoeud]['id_noeud'] = "";
						$noeudsDeLaCategorie[$nbNoeud]['nom_entier'] = "Aucun n&oelig;ud dans cette cat&eacute;gorie";
					}

					$retourJSON['etat'] = "ok";
					$retourJSON['retour'] = $noeudsDeLaCategorie;
					$retourJSON['fichier'] = "mvc_view/ajouterUtilisateur.php";
				}
				else {
					$retourJSON['etat'] = "erreur";
					$retourJSON['erreur'] = "Les champs ne doivent pas &ecirc;tre vides !";
				}
			}
			// Page par d�faut quand on est connect�
			else {
				$retourJSON['etat'] = "erreur";
				$retourJSON['erreur'] = "Vous devez &ecirc;tre connect&eacute; avant d'acc&eacute;der &agrave; cette page !";
			}
		}
		return $retourJSON;
	}
}
?>