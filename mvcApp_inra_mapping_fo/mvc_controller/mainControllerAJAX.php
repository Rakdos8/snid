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
	** entrée: void.
	** sortie: BDD: objet BDD si réussi, null sinon.
    */
	private static function get_connexionBDD() {
		// Si le nom de l'application n'est pas défini
		define("NOM_APPLICATION", "mvcApp_inra_mapping_bo", false);
		// On récupère les informations essentielles de l'application
		require_once "../config.php";

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
	** Action de génération du graphe d'accueil
	** entrée: request: équivalent à $_REQUEST
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function genererGrapheAccueil($request) {
		$lnk_BDD = mainControllerAJAX::get_connexionBDD();
		
		// Si l'instance de la classe BDD n'a pas pu être récupérée
		if ($lnk_BDD == null) {
			$retourJSON['etat'] = "erreur";
			$retourJSON['erreur'] = "Impossible d'ex&eacute;cuter le script de configuration !";
		}
		else {
			// On définit le retour en JSON
			$retourJSON = array();
			
			// On récupère toutes les catégories
			$categories = categorieTable::get_toutesLesCategories();
			// Pour chaque catégorie...
			$tabCategorie;
			$nbCategories = 0;
			foreach ($categories as $categorie) {
				// On décompose le nom affiché en 2: son nom et son nombre de noeud associé
				$nomTemporaire = array(
									0 => decode($categorie->nom_entier),
									1 => $categorie->get_nombreNoeud()
								);

				// On détermine la liste des catégories pour chaque catégorie
				$listeCategorie[] = array(
										'id_noeud' => $categorie->id_categorie,
										'id_categorie' => $categorie->id_categorie,
										'nom_entier' => decode($categorie->nom_entier),
										'nom_partiel' => decode($categorie->nom_partiel),
										'type' => "principal",
										'nom' => $nomTemporaire,
										'couleur_liaisons' => decode($categorie->couleur_liaisons),
										'couleur_liaisons_select' => decode($categorie->couleur_liaisons_select),
										'url_redirection' => decode($categorie->id_categorie)
									);
				$nbCategories++;
				$tabCategorie[] = $categorie->id_categorie;
			}
			
			// On récupère toutes les liaisions des catégories
			$liensCategories = array();
			// Pour chaque catégories récupérées
			for ($i = 0; $i < $nbCategories; $i++) {
				$idCategorie1 = $tabCategorie[$i];
				if ($i != 0) {
					$idCategorie2 = $tabCategorie[$i-1];
				}
				else {
					$idCategorie2 = $tabCategorie[($nbCategories-1)];
				}

				// On vérifie que la catégorie 1 est liée à la catégorie 2
				$categoriesLiee = categorieTable::get_categoriesLieesEntreElles($idCategorie1, $idCategorie2);
				if ($categoriesLiee) {
					// Si elles sont liées, on indique dans le tableau liensCategories %CATEGORIE1%.%CATEGORIE2%
					$liensCategories[] = $idCategorie1.".".$idCategorie2;
				}
			}

			// On liste les catégories devant être liés
			$listeLiens = array();
			foreach ($liensCategories as $lienCategories) {
				// On récupère les liaisons entre les catégories
				$idCategorie = explode('.', $lienCategories);
				// On crée la nouvelle liaison pour l'afficher
				$lienEntreCategories = array('id_noeud_1' => $idCategorie[0], 'id_noeud_2' => $idCategorie[1]);

				// On l'ajoute dans le tableau des liaisons existantes
				array_push($listeLiens, $lienEntreCategories);
			}

			// On indique que la génération est OK
			$retourJSON['etat'] = "ok";
			// On indique le graphe
			$retourJSON['graphe'] = array(
										"categories" => $listeCategorie,
										"noeuds" => $listeCategorie,
										"liens" => $listeLiens
									);
		}
		return $retourJSON;
	}

    /*
	** Action de génération du graphe de catégorie
	** entrée: request: équivalent à $_REQUEST
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function genererGrapheCategorie($request) {
		$lnk_BDD = mainControllerAJAX::get_connexionBDD();
		
		// Si l'instance de la classe BDD n'a pas pu être récupérée
		if ($lnk_BDD == null) {
			$retourJSON['etat'] = "erreur";
			$retourJSON['erreur'] = "Impossible d'ex&eacute;cuter le script de configuration !";
		}
		else {
			// On vérifie que la communication est exacte
			if (isset($request['idGraphe']) && is_numeric($request['idGraphe'])) {
				$i = 0;
				$retourJSON = array();
				$listeNoeuds = array();
				$idCategorie = $request['idGraphe'];

				// On récupère la catégorie demandée
				$categories = categorieTable::get_categorieParId($idCategorie);

				// Pour la catégorie centrée
				foreach ($categories as $categorie) {
					// On détermine la liste des catégories pour chaque catégorie
					$listeCategorie[] = array(
											'id_categorie' => $categorie->id_categorie,
											'couleur_liaisons' => decode($categorie->couleur_liaisons),
											'couleur_liaisons_select' => decode($categorie->couleur_liaisons_select)
										);

					// On récupère les noeuds de la catégorie en cours
					$noeuds = noeudTable::get_tousLesNoeudsDeLaCategorie($categorie->id_categorie);
					// Pour chaque noeud...
					foreach ($noeuds as $noeud) {
						// On détermine la liste des noeuds pour chaque catégorie
						$listeNoeuds[$i][] = array(
												'id_noeud' => $noeud->id_noeud,
												'id_categorie' => $noeud->id_categorie,
												'nom_entier' => decode($noeud->nom_entier_noeud),
												'nom_partiel' => decode($noeud->nom_partiel),
												'url_redirection' => decode($noeud->url_redirection)
											);
					}
					$i++;
				}

				// Pour chaque catégorie...
				$categories = categorieTable::get_toutesLesCategories();
				foreach ($categories as $categorie) {
					// Si l'id catégorie est différente de celle demandée
					if ($idCategorie != $categorie->id_categorie) {
						// On détermine la liste des catégories pour chaque catégorie
						$listeCategorie[] = array(
												'id_categorie' => $categorie->id_categorie,
												'couleur_liaisons' => decode($categorie->couleur_liaisons),
												'couleur_liaisons_select' => decode($categorie->couleur_liaisons_select)
											);

						// On récupère les noeuds de la catégorie en cours
						$noeuds = noeudTable::get_tousLesNoeudsDeLaCategorie($categorie->id_categorie);
						// Pour chaque noeud...
						foreach ($noeuds as $noeud) {
							// On détermine la liste des noeuds pour chaque catégorie
							$listeNoeuds[$i][] = array(
													'id_noeud' => $noeud->id_noeud,
													'id_categorie' => $noeud->id_categorie,
													'nom_entier' => decode($noeud->nom_entier_noeud),
													'nom_partiel' => decode($noeud->nom_partiel),
													'url_redirection' => decode($noeud->url_redirection)
												);
						}
						$i++;
					}
				}
				
				$listeNoeudNavigation = array('couleurBordure' => "#828282");

				// On récupère les liens de la catégorie demandée
				$liens = lienTable::get_tousLesLiensDeLaCategorie($idCategorie);
				
				// Si des liens existent
				if ($liens != null) {
					// Pour chaque lien de la catégorie demandée
					foreach ($liens as $lien) {
						$listeLiens[] = array(
											'id_noeud_1' => $lien->id_noeud_1,
											'id_noeud_2' => $lien->id_noeud_2
										);
					}
				}
				// Sinon on met la liste à vide
				else {
					$listeLiens = array();
				}

				// On indique que la génération est OK
				$retourJSON['etat'] = "ok";
				// On indique le graphe
				$retourJSON['graphe'] = array(
											"categories" => $listeCategorie,
											"noeudNavigation" => $listeNoeudNavigation,
											"noeuds" => $listeNoeuds,
											"liens" => $listeLiens
										);
			}
			else {
				$retourJSON['etat'] = "erreur";
				$retourJSON['erreur'] = "Impossible de r&eacute;cup&eacute;rer la cat&eacute;gorie demand&eacute;e !";
			}
		}
		return $retourJSON;
	}

    /*
	** Action de génération du graphe de noeud
	** entrée: request: équivalent à $_REQUEST
	** sortie: array: tableau JSON à encoder avec toutes les informations en son sein.
    */
	public static function genererGrapheNoeud($request) {
		$lnk_BDD = mainControllerAJAX::get_connexionBDD();
		
		// Si l'instance de la classe BDD n'a pas pu être récupérée
		if ($lnk_BDD == null) {
			$retourJSON['etat'] = "erreur";
			$retourJSON['erreur'] = "Impossible d'ex&eacute;cuter le script de configuration !";
		}
		else {
			// On vérifie que la communication est exacte
			if (isset($request['idGraphe']) && is_numeric($request['idGraphe'])) {
				// On définit le retour en JSON
				$retourJSON = array();
				$idNoeud = $request['idGraphe'];
				
				// On récupère toutes les catégories
				$categories = categorieTable::get_toutesLesCategories("id_categorie", "ASC");
				// Pour chaque catégorie...
				foreach ($categories as $categorie) {
					// On détermine la liste des catégories pour chaque catégorie
					$listeCategorie[] = array(
											'id_categorie' => $categorie->id_categorie,
											'couleur_liaisons' => decode($categorie->couleur_liaisons),
											'couleur_liaisons_select' => decode($categorie->couleur_liaisons_select)
										);
				}
				

				// On récupère tous les noeuds de la catégorie en cours
				$noeuds = noeudTable::get_noeudParId($idNoeud);
				foreach ($noeuds as $noeud) {
					$idCategorieNoeudCentral = $noeud->id_categorie;

					// On décompose le nom affiché selon les espaces					
					$nomTemporaire = explode(' ', decode($noeud->nom_entier));

					// On détermine la liste des noeuds pour chaque catégorie
					$listeNoeuds[] = array(
											'id_noeud' => $noeud->id_noeud,
											'id_categorie' => $noeud->id_categorie,
											'nom' => $nomTemporaire,
											'nom_entier' => decode($noeud->nom_entier),
											'nom_partiel' => decode($noeud->nom_partiel),
											'url_redirection' => decode($noeud->url_redirection)
										);
				}

				$listeNoeudNavigation = array('couleurBordure' => "#828282", 'categorieNoeudCentral' => $idCategorieNoeudCentral);

				// Pour chaque lien du noeud demandé
				$liens = lienTable::get_tousLesLiensDuNoeud($idNoeud);

				// Si des liens existent
				if ($liens != null) {
					// Pour chaque lien de la catégorie demandée
					foreach ($liens as $lien) {
						if ($idNoeud == $lien->id_noeud_2) {
							$idNoeudInformation = $lien->id_noeud_1;
						}
						else {
							$idNoeudInformation = $lien->id_noeud_2;
						}
						
						// On récupère tous les noeuds liés au noeud central
						$noeuds = noeudTable::get_noeudParId($idNoeudInformation);
						// Pour chaque noeud...
						foreach ($noeuds as $noeud) {
							// On décompose le nom affiché selon les espaces					
							$nomTemporaire = explode(' ', decode($noeud->nom_entier));

							// On détermine la liste des noeuds pour chaque catégorie
							$listeNoeuds[] = array(
													'id_noeud' => $noeud->id_noeud,
													'id_categorie' => $noeud->id_categorie,
													'nom' => $nomTemporaire,
													'nom_entier' => decode($noeud->nom_entier),
													'nom_partiel' => decode($noeud->nom_partiel),
													'url_redirection' => $noeud->id_noeud
												);
						}
						
						$listeLiens[] = array(
											'id_noeud_1' => $lien->id_noeud_1,
											'id_noeud_2' => $lien->id_noeud_2
										);
					}
				}
				// Sinon on met la liste à vide
				else {
					$listeLiens = array();
				}

				// On indique que la génération est OK
				$retourJSON['etat'] = "ok";
				// On indique le graphe
				$retourJSON['graphe'] = array(
											"categories" => $listeCategorie,
											"noeudNavigation" => $listeNoeudNavigation,
											"noeuds" => $listeNoeuds,
											"liens" => $listeLiens
										);
			}
			else {
				$retourJSON['etat'] = "erreur";
				$retourJSON['erreur'] = "Impossible de r&eacute;cup&eacute;rer le n&oelig; demand&eacute; !";
			}
		}
		return $retourJSON;
	}
}
?>